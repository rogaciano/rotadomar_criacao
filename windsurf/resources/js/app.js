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

// Registrar componentes Alpine ANTES do Alpine.start()
Alpine.data('floatingAiChat', () => ({
    isOpen: false,
    input: '',
    isLoading: false,
    messages: [],

    init() {
        // Restaurar conversa da sessão
        try {
            const saved = sessionStorage.getItem('aiChatMessages');
            if (saved) {
                this.messages = JSON.parse(saved).filter(m => !m.isLoading);
            }
        } catch (e) {}

        // Salvar conversa sempre que messages mudar
        this.$watch('messages', value => {
            try {
                const toSave = value.filter(m => !m.isLoading);
                sessionStorage.setItem('aiChatMessages', JSON.stringify(toSave));
            } catch (e) {}
        });

        this.$watch('isOpen', value => {
            if (value) {
                setTimeout(() => {
                    if (this.$refs.chatInput) {
                        this.$refs.chatInput.focus();
                    }
                    this.scrollToBottom();
                }, 300);
            }
        });
    },

    toggleChat() {
        this.isOpen = !this.isOpen;
    },

    autoResize() {
        if (!this.$refs.chatInput) return;
        const el = this.$refs.chatInput;
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    },

    formatMessage(text) {
        if (!text) return '';
        let html = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" class="text-emerald-400 hover:underline border-b border-emerald-400 cursor-pointer">$1</a>');
        return html;
    },

    scrollToBottom() {
        setTimeout(() => {
            const container = document.getElementById('widget-messages-container');
            if (container) container.scrollTop = container.scrollHeight;
        }, 50);
    },

    async sendFeedback(historicoId, util) {
        if (!historicoId || !window.aiFeedbackRoute) return;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const url = window.aiFeedbackRoute.replace('__ID__', historicoId);
        try {
            await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ util })
            });
        } catch (e) {
            // silencioso
        }
    },

    async sendMessage() {
        if (!this.input.trim() || this.isLoading) return;

        const userMessage = this.input.trim();
        this.input = '';
        this.autoResize();

        this.messages.push({ role: 'user', content: userMessage, isLoading: false });
        this.scrollToBottom();

        this.isLoading = true;
        this.messages.push({ role: 'ai', content: '', isLoading: true, historicoId: null, feedback: null });
        this.scrollToBottom();

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const response = await fetch(window.aiChatRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: userMessage })
            });

            const data = await response.json();
            const last = this.messages[this.messages.length - 1];
            last.isLoading = false;

            if (response.ok && data.success) {
                last.content = data.reply;
                last.historicoId = data.historico_id || null;
                last.feedback = null;
            } else {
                last.content = data.error || 'Ocorreu um erro no servidor.';
            }

        } catch (error) {
            this.messages[this.messages.length - 1].isLoading = false;
            this.messages[this.messages.length - 1].content = 'Erro de rede. Tente novamente mais tarde.';
        } finally {
            this.isLoading = false;
            this.scrollToBottom();
        }
    }
}));

Alpine.start();
