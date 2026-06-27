/**
 * Global JavaScript Utilities for Medicinal Plants and Herbs Database System
 */

document.addEventListener('DOMContentLoaded', () => {
    // Initialization code can go here if needed.
    initTabs();
});

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
    initPlantForm();
});

function initPlantForm() {
    const plantForm = document.getElementById('plant-form');
    if (plantForm) {
        plantForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showToast('Plant saved successfully!');
            const modal = this.closest('.modal-overlay');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
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
        e.preventDefault();
        
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
            // Trigger shake animation
            const loginCard = loginForm.closest('.login-card') || loginForm;
            loginCard.classList.remove('shake');
            void loginCard.offsetWidth; // trigger reflow
            loginCard.classList.add('shake');
        } else {
            // Proceed with login (simulate success for frontend phase)
            window.location.href = 'admin-dashboard.html';
        }
    });

    // Remove error on input
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
            const targetModal = document.getElementById(targetId);
            if (targetModal) {
                targetModal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
                
                if (trigger.classList.contains('edit')) {
                    const row = trigger.closest('tr');
                    if (row) {
                        const cells = row.querySelectorAll('td');
                        if (cells.length >= 5) {
                            const commonName = cells[1].textContent.trim();
                            const botanicalName = cells[2].textContent.trim();
                            const family = cells[3].textContent.trim();
                            const status = cells[4].textContent.trim().toLowerCase();
                            
                            const form = document.getElementById('plant-form');
                            if (form) {
                                const cnInput = form.querySelector('#common-name');
                                const bnInput = form.querySelector('#botanical-name');
                                const fInput = form.querySelector('#family');
                                const sInput = form.querySelector('#status');
                                
                                if (cnInput) cnInput.value = commonName;
                                if (bnInput) bnInput.value = botanicalName;
                                if (fInput) fInput.value = family;
                                if (sInput) {
                                    for (let i = 0; i < sInput.options.length; i++) {
                                        if (sInput.options[i].value === status) {
                                            sInput.selectedIndex = i;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else if (targetId === 'add-plant-modal') {
                    const form = document.getElementById('plant-form');
                    if (form) form.reset();
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

    // Close on click outside modal content
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
