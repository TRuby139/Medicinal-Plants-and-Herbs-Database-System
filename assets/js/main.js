/**
 * Global JavaScript Utilities for Medicinal Plants and Herbs Database System
 */

document.addEventListener('DOMContentLoaded', () => {
    initTabs();
    
    // If we are on the public catalogue page
    if (document.getElementById('plant-grid-container')) {
        fetchTags();
        fetchPlants(1);
        
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', (e) => {
                if (e.key === 'Enter') fetchPlants(1);
            });
            const searchBtn = searchInput.nextElementSibling;
            if (searchBtn && searchBtn.tagName === 'BUTTON') {
                searchBtn.addEventListener('click', () => fetchPlants(1));
            }
        }

        const clearBtn = document.getElementById('clear-filters-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                document.querySelectorAll('.sidebar input[type="checkbox"]').forEach(cb => cb.checked = false);
                if (searchInput) searchInput.value = '';
                const sortOrder = document.getElementById('sortOrder');
                if (sortOrder) sortOrder.value = 'alpha_asc';
                fetchPlants(1);
            });
        }
        
        const sortOrder = document.getElementById('sortOrder');
        if (sortOrder) {
            sortOrder.addEventListener('change', () => fetchPlants(1));
        }
    }
    
    // If we are on the admin dashboard
    const adminSearchInput = document.getElementById('admin-search-input');
    if (adminSearchInput) {
        adminSearchInput.addEventListener('input', () => {
            // Live search as they type
            loadAdminPlants();
        });
        const adminSearchBtn = document.getElementById('admin-search-btn');
        if (adminSearchBtn) {
            adminSearchBtn.addEventListener('click', () => loadAdminPlants());
        }
        
        const plantImageInput = document.getElementById('plant-image');
        if (plantImageInput) {
            plantImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.querySelector('.image-preview');
                const uploadText = document.querySelector('.upload-text');
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (preview) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        }
                        if (uploadText) uploadText.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                } else {
                    if (preview) {
                        preview.style.display = 'none';
                        preview.src = '';
                    }
                    if (uploadText) uploadText.style.display = 'block';
                }
            });
        }
    }
});

let currentPage = 1;

function getCheckedValues(containerId) {
    const checkboxes = document.querySelectorAll(`#${containerId} input[type="checkbox"]:checked`);
    return Array.from(checkboxes).map(cb => cb.value);
}

function fetchPlants(page = 1) {
    currentPage = page;
    const container = document.getElementById('plant-grid-container');
    if (!container) return;
    
    container.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 40px;"><p>Loading catalogue...</p></div>';
    
    let url = `api/plants.php?action=get_plants&page=${page}&limit=6`;
    const search = document.getElementById('searchInput')?.value || '';
    if (search) url += '&search=' + encodeURIComponent(search);
    
    const sort = document.getElementById('sortOrder')?.value || 'alpha_asc';
    if (sort) url += '&sort=' + encodeURIComponent(sort);
    
    // Get tags
    const families = getCheckedValues('filter-family-list');
    const uses = getCheckedValues('filter-uses-list');
    const compounds = getCheckedValues('filter-compounds-list');
    
    const categories = [...families, ...uses];
    categories.forEach(c => url += `&category_id[]=${c}`);
    compounds.forEach(c => url += `&compound_id[]=${c}`);
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                container.innerHTML = '';
                if (data.data.length === 0) {
                    container.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 40px;"><p>No plants found matching your criteria.</p></div>';
                    renderPagination(0, 1);
                    return;
                }
                
                data.data.forEach(plant => {
                    const card = document.createElement('div');
                    card.className = 'plant-card';
                    const imgPath = plant.image_path ? plant.image_path : 'assets/images/Not_uploaded.png';
                    
                    card.innerHTML = `
                        <div class="card-image">
                            <img src="${imgPath}" alt="${plant.common_name}">
                        </div>
                        <div class="card-content">
                            <h4 class="botanical-name">${plant.botanical_name}</h4>
                            <h3 class="common-name">${plant.common_name}</h3>
                            <a href="plant-detail.php?id=${plant.id}" class="btn btn-outline btn-full" style="margin-top: 15px;">View Profile</a>
                        </div>
                    `;
                    container.appendChild(card);
                });
                renderPagination(data.total_pages, data.current_page);
            } else {
                container.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 40px;"><p>Error loading catalogue.</p></div>';
            }
        })
        .catch(err => {
            container.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 40px;"><p>Error loading catalogue.</p></div>';
        });
}

function renderPagination(totalPages, currentPage) {
    const container = document.getElementById('pagination-container');
    if (!container) return;
    
    if (totalPages <= 1) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'flex';
    container.innerHTML = '';
    
    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
        btn.textContent = i;
        btn.onclick = () => fetchPlants(i);
        container.appendChild(btn);
    }
}

function fetchTags() {
    // Fetch families
    fetch('api/tags.php?action=get_categories&type=family')
        .then(res => res.json())
        .then(data => populateFilterList('filter-family-list', data.data, 'category_id'));
        
    // Fetch uses
    fetch('api/tags.php?action=get_categories&type=medicinal_use')
        .then(res => res.json())
        .then(data => populateFilterList('filter-uses-list', data.data, 'category_id'));
        
    // Fetch compounds
    fetch('api/tags.php?action=get_compounds')
        .then(res => res.json())
        .then(data => populateFilterList('filter-compounds-list', data.data, 'compound_id'));
}

function populateFilterList(containerId, items, nameAttr) {
    const list = document.getElementById(containerId);
    if (!list) return;
    list.innerHTML = '';
    
    items.forEach(item => {
        const li = document.createElement('li');
        li.innerHTML = `<label><input type="checkbox" value="${item.id}" name="${nameAttr}[]" onchange="fetchPlants(1)"> ${item.name}</label>`;
        list.appendChild(li);
    });
}

/**
 * Tab functionality for plant details page
 */
function initTabs() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    if (tabBtns.length === 0) return;

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons and panes
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));

            // Add active class to clicked button
            btn.classList.add('active');

            // Find corresponding pane and make it active
            const targetId = btn.getAttribute('data-tab');
            const targetPane = document.getElementById(targetId);
            
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
}

/**
 * Toggles the mobile navigation menu.
 * Requires a button with onclick="toggleMobileNav()" and a nav element with id="mobile-nav"
 */
function toggleMobileNav() {
    const nav = document.getElementById('mobile-nav');
    if (nav) {
        nav.classList.toggle('active');
    } else {
        console.warn('Mobile navigation element not found (id="mobile-nav")');
    }
}

/**
 * Displays a toast notification.
 * @param {string} message - The message to display.
 * @param {string} type - The type of toast ('success' or 'error'). Default is 'success'.
 */
function showToast(message, type = 'success') {
    // Find or create the toast container
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    // Create the toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerText = message;
    
    // Append the toast to the container
    container.appendChild(toast);
    
    // Trigger layout to allow CSS transitions to run
    void toast.offsetWidth;
    
    // Remove the toast after a delay
    setTimeout(() => {
        toast.classList.add('toast-hide');
        
        // Wait for the transition to finish before removing from DOM
        setTimeout(() => {
            if (container.contains(toast)) {
                container.removeChild(toast);
            }
            // Remove container if empty to keep DOM clean
            if (container.children.length === 0) {
                container.remove();
            }
        }, 300); // 300ms matches the CSS transition duration
    }, 3000); // Display for 3 seconds
}

/**
 * --- Admin Module JS ---
 */

document.addEventListener('DOMContentLoaded', () => {
    initLoginForm();
    initModals();
    initImageUploadPreview();
    
    // Admin dashboard specific loads
    if (document.getElementById('admin-plants-tbody')) {
        loadAdminPlants();
        loadAdminCategories();
        loadAdminUses();
        loadAdminCompounds();
    }
});

function loadAdminPlants(page = 1) {
    const tbody = document.getElementById('admin-plants-tbody');
    if (!tbody) return;
    
    tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Loading...</td></tr>';
    
    const searchInput = document.getElementById('admin-search-input');
    const searchVal = searchInput ? encodeURIComponent(searchInput.value) : '';
    let url = `api/plants.php?action=get_plants&limit=15&page=${page}`;
    if (searchVal) url += `&search=${searchVal}`;
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                tbody.innerHTML = '';
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">No plants found</td></tr>';
                    return;
                }
                data.data.forEach(plant => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${plant.id}</td>
                        <td>${plant.common_name}</td>
                        <td>${plant.botanical_name}</td>
                        <td>${plant.family || 'N/A'}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon edit" title="Edit" onclick="editPlant(${plant.id})">✏️</button>
                                <button class="btn-icon delete" title="Delete" onclick="deletePlant(${plant.id})">🗑️</button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
                renderAdminPagination(data.total_pages, data.current_page);
            } else {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Error loading plants</td></tr>';
            }
        });
}

function renderAdminPagination(totalPages, currentPage) {
    let container = document.getElementById('admin-pagination-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'admin-pagination-container';
        container.className = 'pagination flex justify-center items-center gap-2';
        container.style.marginTop = '20px';
        const table = document.querySelector('.data-table');
        if (table) table.parentNode.insertBefore(container, table.nextSibling);
    }
    
    if (totalPages <= 1) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'flex';
    container.innerHTML = '';
    
    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
        btn.textContent = i;
        btn.onclick = () => loadAdminPlants(i);
        container.appendChild(btn);
    }
}

function loadAdminCategories() {
    const tbody = document.getElementById('admin-categories-tbody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">Loading...</td></tr>';
    
    fetch('api/tags.php?action=get_categories&type=family')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                tbody.innerHTML = '';
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">No categories found</td></tr>';
                    return;
                }
                data.data.forEach(cat => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${cat.id}</td>
                        <td>${cat.name}</td>
                        <td>${cat.type}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon delete" title="Delete" onclick="deleteCategory(${cat.id})">🗑️</button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        });
}

function loadAdminCompounds() {
    const tbody = document.getElementById('admin-compounds-tbody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Loading...</td></tr>';
    
    fetch('api/tags.php?action=get_compounds')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                tbody.innerHTML = '';
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No compounds found</td></tr>';
                    return;
                }
                data.data.forEach(comp => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${comp.id}</td>
                        <td>${comp.name}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon delete" title="Delete" onclick="deleteCompound(${comp.id})">🗑️</button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        });
}

/**
 * Tab functionality for admin dashboard
 */
function switchTab(tabId) {
    const tabBtns = document.querySelectorAll('.admin-tabs .tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.classList.remove('active');
        btn.style.borderBottomColor = 'transparent';
    });
    tabContents.forEach(content => {
        content.classList.remove('active');
        content.style.display = 'none';
    });
    
    const activeBtn = Array.from(tabBtns).find(btn => btn.getAttribute('onclick').includes(tabId));
    if (activeBtn) {
        activeBtn.classList.add('active');
        activeBtn.style.borderBottomColor = 'var(--color-primary)';
    }
    
    const activeTab = document.getElementById(tabId + '-tab');
    if (activeTab) {
        activeTab.classList.add('active');
        activeTab.style.display = 'block';
    }
}

function editPlant(id) {
    const formData = new FormData();
    formData.append('action', 'get_plant');
    formData.append('id', id);
    
    fetch('api/plants.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const plant = data.data;
                const form = document.getElementById('plant-form');
                form.reset();
                
                document.getElementById('plant-id').value = plant.id;
                document.getElementById('common-name').value = plant.common_name;
                document.getElementById('botanical-name').value = plant.botanical_name;
                document.getElementById('family').value = plant.family || '';
                document.getElementById('uses').value = plant.uses || '';
                document.getElementById('compounds').value = plant.compounds || '';
                document.getElementById('habitat').value = plant.habitat || '';
                document.getElementById('description').value = plant.description || '';
                document.getElementById('preparation').value = plant.preparation_methods || '';
                const dosagesEl = document.getElementById('dosages');
                if(dosagesEl) dosagesEl.value = plant.dosages || '';
                document.getElementById('precautions').value = plant.precautions || '';
                
                const previewImg = document.querySelector('.image-preview');
                const uploadText = document.querySelector('.upload-text');
                if (plant.image_path) {
                    if (previewImg) {
                        previewImg.src = plant.image_path;
                        previewImg.style.display = 'block';
                    }
                    if (uploadText) uploadText.style.display = 'none';
                } else {
                    if (previewImg) previewImg.style.display = 'none';
                    if (uploadText) uploadText.style.display = 'block';
                }
                
                const targetModal = document.getElementById('add-plant-modal');
                if (targetModal) {
                    targetModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            } else {
                showToast('Failed to load plant data.', 'error');
            }
        });
}

function deletePlant(id) {
    if (confirm('Are you sure you want to delete this plant?')) {
        const formData = new FormData();
        formData.append('action', 'delete_plant');
        formData.append('id', id);
        fetch('api/plants.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Plant deleted successfully');
                    loadAdminPlants();
                } else {
                    showToast(data.message || 'Error deleting plant', 'error');
                }
            });
    }
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category?')) {
        const formData = new FormData();
        formData.append('action', 'delete_category');
        formData.append('id', id);
        fetch('api/tags.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Deleted successfully');
                    loadAdminCategories();
                } else {
                    showToast('Error deleting', 'error');
                }
            });
    }
}

function deleteCompound(id) {
    if (confirm('Are you sure you want to delete this compound?')) {
        const formData = new FormData();
        formData.append('action', 'delete_compound');
        formData.append('id', id);
        fetch('api/tags.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Deleted successfully');
                    loadAdminCompounds();
                } else {
                    showToast('Error deleting', 'error');
                }
            });
    }
}

/**
 * Validates and submits the Plant form
 */
function submitPlantForm(e) {
    e.preventDefault();
    let isValid = true;

    const form = document.getElementById('plant-form');
    
    // Common Name Validation
    const commonName = document.getElementById('common-name');
    const commonNameErr = document.getElementById('common-name-error');
    if (commonName.value.trim().length < 2) {
        commonNameErr.innerText = 'Common name must be at least 2 characters.';
        commonNameErr.style.display = 'block';
        isValid = false;
    } else {
        commonNameErr.style.display = 'none';
    }

    // Botanical Name Validation
    const botanicalName = document.getElementById('botanical-name');
    const botanicalNameErr = document.getElementById('botanical-name-error');
    if (botanicalName.value.trim().length < 2) {
        botanicalNameErr.innerText = 'Botanical name must be at least 2 characters.';
        botanicalNameErr.style.display = 'block';
        isValid = false;
    } else {
        botanicalNameErr.style.display = 'none';
    }

    // Image Validation (Optional, but if present max 2MB)
    const plantImage = document.getElementById('plant-image');
    const plantImageErr = document.getElementById('plant-image-error');
    if (plantImage.files.length > 0) {
        const file = plantImage.files[0];
        if (!file.type.startsWith('image/')) {
            plantImageErr.innerText = 'Please upload a valid image file.';
            plantImageErr.style.display = 'block';
            isValid = false;
        } else if (file.size > 10 * 1024 * 1024) { // 10MB
            plantImageErr.innerText = 'Image size must be less than 10MB.';
            plantImageErr.style.display = 'block';
            isValid = false;
        } else {
            plantImageErr.style.display = 'none';
        }
    } else {
        if (plantImageErr) plantImageErr.style.display = 'none';
    }

    if (isValid) {
        const formData = new FormData(form);
        const pid = document.getElementById('plant-id').value;
        formData.append('action', pid === '0' || pid === '' ? 'add_plant' : 'edit_plant');
        
        fetch('api/plants.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                console.log('Save response:', JSON.stringify(data, null, 2));
                if(data.success) {
                    showToast('Plant saved successfully!');
                    const modal = document.getElementById('add-plant-modal');
                    if (modal) {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                    form.reset();
                    document.getElementById('plant-id').value = '0';
                    const preview = document.querySelector('.image-preview');
                    const uploadText = document.querySelector('.upload-text');
                    if (preview) preview.style.display = 'none';
                    if (uploadText) uploadText.style.display = 'block';
                    loadAdminPlants();
                } else {
                    showToast(data.message || 'Error saving plant', 'error');
                }
            });
    }
}

function submitCategoryForm(e) {
    e.preventDefault();
    const nameInput = document.getElementById('category-name');
    const errorMsg = nameInput.nextElementSibling;
    if (nameInput.value.trim() === '') {
        errorMsg.innerText = 'Family name is required.';
        errorMsg.style.display = 'block';
    } else {
        errorMsg.style.display = 'none';
        
        const formData = new FormData();
        formData.append('action', 'add_category');
        formData.append('name', nameInput.value.trim());
        formData.append('type', 'family');
        
        fetch('api/tags.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Category saved successfully!');
                    const modal = document.getElementById('add-category-modal');
                    if (modal) {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                    document.getElementById('add-category-form').reset();
                    loadAdminCategories();
                } else {
                    showToast(data.message, 'error');
                }
            });
    }
}

function submitCompoundForm(e) {
    e.preventDefault();
    const nameInput = document.getElementById('compound-name');
    const errorMsg = nameInput.nextElementSibling;
    if (nameInput.value.trim() === '') {
        errorMsg.innerText = 'Compound name is required.';
        errorMsg.style.display = 'block';
    } else {
        errorMsg.style.display = 'none';
        
        const formData = new FormData();
        formData.append('action', 'add_compound');
        formData.append('name', nameInput.value.trim());
        
        fetch('api/tags.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Compound saved successfully!');
                    const modal = document.getElementById('add-compound-modal');
                    if (modal) {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                    document.getElementById('add-compound-form').reset();
                    loadAdminCompounds();
                } else {
                    showToast(data.message, 'error');
                }
            });
    }
}

/**
 * Initialize login form validation
 */
function initLoginForm() {
    const loginForm = document.getElementById('login-form');
    if (!loginForm) return;

    loginForm.addEventListener('submit', function(e) {
        let hasError = false;
        const inputs = loginForm.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            const formGroup = input.closest('.form-group');
            if (input.value.trim() === '') {
                hasError = true;
                formGroup.classList.add('has-error');
            } else {
                formGroup.classList.remove('has-error');
            }
        });
        
        if (hasError) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const loginCard = loginForm.closest('.login-card') || loginForm;
            loginCard.classList.remove('shake');
            void loginCard.offsetWidth;
            loginCard.classList.add('shake');
        }
    });

    const inputs = loginForm.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            const formGroup = this.closest('.form-group');
            if (formGroup) {
                formGroup.classList.remove('has-error');
            }
        });
    });
}

/**
 * Initialize Modal Overlay Logic
 */
function initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    const closeButtons = document.querySelectorAll('.close-modal');
    const modals = document.querySelectorAll('.modal-overlay');

    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = trigger.getAttribute('data-modal-target');
            // Allow JS functions to handle edit, only handle simple opens here
            if (!trigger.classList.contains('edit')) {
                const targetModal = document.getElementById(targetId);
                if (targetModal) {
                    if (targetId === 'add-plant-modal') {
                        const form = document.getElementById('plant-form');
                        if (form) form.reset();
                        document.getElementById('plant-id').value = '0';
                        const preview = document.querySelector('.image-preview');
                        const uploadText = document.querySelector('.upload-text');
                        if (preview) preview.style.display = 'none';
                        if (uploadText) uploadText.style.display = 'block';
                    }
                    targetModal.classList.add('active');
                    document.body.style.overflow = 'hidden'; 
                }
            }
        });
    });

    closeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal-overlay');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
}

/**
 * Image Upload Preview Logic
 */
function initImageUploadPreview() {
    const fileInputs = document.querySelectorAll('input[type="file"][accept="image/*"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const container = this.closest('.image-upload-container');
            if (!container) return;
            
            let previewImg = container.querySelector('.image-preview');
            let uploadText = container.querySelector('.upload-text');
            
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (!previewImg) {
                        previewImg = document.createElement('img');
                        previewImg.className = 'image-preview';
                        container.appendChild(previewImg);
                    }
                    previewImg.src = event.target.result;
                    previewImg.style.display = 'block';
                    if (uploadText) uploadText.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                if (previewImg) previewImg.style.display = 'none';
                if (uploadText) uploadText.style.display = 'block';
            }
        });
    });
}

function loadAdminUses() {
    const tbody = document.getElementById('admin-uses-tbody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan=""4"" style=""text-align: center;"">Loading...</td></tr>';
    
    fetch('api/tags.php?action=get_categories&type=medicinal_use')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                tbody.innerHTML = '';
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan=""4"" style=""text-align: center;"">No medicinal uses found</td></tr>';
                    return;
                }
                data.data.forEach(use => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${use.id}</td>
                        <td>${use.name}</td>
                        <td>${use.type}</td>
                        <td>
                            <div class=""action-buttons"">
                                <button class=""btn-icon delete"" title=""Delete"" onclick=""deleteUse(${use.id})"">???</button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        });
}

function deleteUse(id) {
    if (confirm('Are you sure you want to delete this medicinal use?')) {
        const formData = new FormData();
        formData.append('action', 'delete_category');
        formData.append('id', id);
        fetch('api/tags.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Deleted successfully');
                    loadAdminUses();
                } else {
                    showToast('Error deleting', 'error');
                }
            });
    }
}

function submitUseForm(e) {
    e.preventDefault();
    const nameInput = document.getElementById('use-name');
    const errorMsg = nameInput.nextElementSibling;
    if (nameInput.value.trim() === '') {
        errorMsg.innerText = 'Medicinal Use name is required.';
        errorMsg.style.display = 'block';
    } else {
        errorMsg.style.display = 'none';
        
        const formData = new FormData();
        formData.append('action', 'add_category');
        formData.append('name', nameInput.value.trim());
        formData.append('type', 'medicinal_use');
        
        fetch('api/tags.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Medicinal Use saved successfully!');
                    const modal = document.getElementById('add-use-modal');
                    if (modal) {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                    document.getElementById('add-use-form').reset();
                    loadAdminUses();
                } else {
                    showToast(data.message, 'error');
                }
            });
    }
}
