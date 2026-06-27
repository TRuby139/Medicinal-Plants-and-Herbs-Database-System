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
