import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('ui', {
        darkMode: localStorage.getItem('dark-mode') === 'true',
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('dark-mode', String(this.darkMode));
            document.documentElement.classList.toggle('dark', this.darkMode);
        }
    });

    Alpine.store('sidebar', {
        open: localStorage.getItem('sidebar_open') !== 'false',
        grupos: JSON.parse(localStorage.getItem('sidebar_grupos') || '{"cadastros":false,"consultas":false,"admin":false}'),
        toggle() {
            this.open = !this.open;
            localStorage.setItem('sidebar_open', String(this.open));
        },
        toggleGrupo(nome) {
            this.grupos[nome] = !this.grupos[nome];
            localStorage.setItem('sidebar_grupos', JSON.stringify(this.grupos));
        }
    });
});

Alpine.start();
