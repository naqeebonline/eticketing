import './bootstrap';
import Alpine from 'alpinejs';
import { busStandForm } from './bus-stand-form';
import { routeForm } from './route-form';
import { scheduleForm, scheduleDuplicateModal } from './schedule-form';
import { registerDialogStore, bssAlert, bssConfirm } from './dialog';

window.Alpine = Alpine;
window.bssAlert = bssAlert;
window.bssConfirm = bssConfirm;

Alpine.store('toast', {
    items: [],
    show(message, type = 'success') {
        const id = Date.now();
        this.items.push({ id, message, type });
        setTimeout(() => this.remove(id), 4500);
    },
    remove(id) {
        this.items = this.items.filter((t) => t.id !== id);
    },
});

document.addEventListener('alpine:init', () => {
    registerDialogStore(Alpine);

    Alpine.data('themeToggle', () => ({
        toggle() {
            const isDark = document.documentElement.classList.toggle('dark');
            window.location.href = `/theme/${isDark ? 'dark' : 'light'}`;
        },
    }));

    Alpine.data('busStandForm', (config) => busStandForm(config));

    Alpine.data('routeForm', (config) => routeForm(config));

    Alpine.data('scheduleForm', (config) => scheduleForm(config));

    Alpine.data('scheduleDuplicateModal', () => scheduleDuplicateModal());
});

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            window.location.href = `/theme/${isDark ? 'dark' : 'light'}`;
        });
    });
});
