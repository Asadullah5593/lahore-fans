import './bootstrap';

// Font Awesome — matches the icon set used across lahorefan.com (fab / fas / far).
import '@fortawesome/fontawesome-free/css/all.min.css';

import Alpine from 'alpinejs';

// Bootstrap JS drives the header's mobile off-canvas drawer (lf_design).
import * as bootstrap from 'bootstrap';

window.Alpine = Alpine;
window.bootstrap = bootstrap;

Alpine.start();

// Mobile nav drawer: slide between the main menu and the Shop submenu panel.
document.addEventListener('DOMContentLoaded', () => {
    const offcanvas = document.getElementById('mainNavOffcanvas');
    if (!offcanvas) return;

    const panels = offcanvas.querySelector('.lf-drawer-panels');

    offcanvas.querySelectorAll('[data-open-panel]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            panels.classList.add('is-shop-open');
        });
    });

    offcanvas.querySelectorAll('[data-close-panel]').forEach((button) => {
        button.addEventListener('click', () => {
            panels.classList.remove('is-shop-open');
        });
    });

    offcanvas.addEventListener('hidden.bs.offcanvas', () => {
        panels.classList.remove('is-shop-open');
    });
});
