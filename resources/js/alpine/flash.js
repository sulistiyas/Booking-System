/**
 * flash.js — Flash notification component
 *
 * Usage di blade (sudah di-include layouts.app):
 *   session('success'), session('error'), session('warning'), session('info')
 *
 * Auto-dismiss setelah 4 detik.
 */

export default function flash() {
    return {
        messages: [],

        init() {
            // Ambil flash dari data attribute yang di-render server
            const el = document.getElementById('flash-data');
            if (!el) return;

            try {
                const data = JSON.parse(el.dataset.flash || '[]');
                data.forEach(msg => this.add(msg.type, msg.text));
            } catch (e) {
                console.warn('Flash parse error:', e);
            }
        },

        add(type, text) {
            const id = Date.now() + Math.random();
            this.messages.push({ id, type, text });

            // Auto-dismiss setelah 4 detik
            setTimeout(() => this.remove(id), 4000);
        },

        remove(id) {
            this.messages = this.messages.filter(m => m.id !== id);
        },

        icon(type) {
            const icons = {
                success: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>`,
                error: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                </svg>`,
                warning: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>`,
                info: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>`,
            };
            return icons[type] || icons.info;
        },
    };
}