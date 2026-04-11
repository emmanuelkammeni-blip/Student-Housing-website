// ================================================
// StudentHousing - JavaScript Principal
// ================================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('StudentHousing Application Loaded');
    
    // Initialisation des événements globaux
    initializeEventListeners();
});

/**
 * Initialise les écouteurs d'événements
 */
function initializeEventListeners() {
    // Menu mobile (si applicable)
    const menuToggle = document.querySelector('.menu-toggle');
    const menuClose = document.querySelector('.menu-close');
    const navMenu = document.querySelector('.navbar-menu');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navMenu.classList.add('active');
        });
    }
    
    if (menuClose) {
        menuClose.addEventListener('click', function() {
            navMenu.classList.remove('active');
        });
    }
}

/**
 * Affiche un message de confirmation
 */
function showConfirm(message) {
    return confirm(message);
}

/**
 * Affiche une notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/**
 * Valide un formulaire basique
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });
    
    return isValid;
}
