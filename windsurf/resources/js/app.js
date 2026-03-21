import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Registrar componentes Alpine ANTES do Alpine.start()
Alpine.data('floatingAiChat', () => ({
    isOpen: false,
    input: '',
    isLoading: false,
    messages: [],

    init() {
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

    async sendMessage() {
        if (!this.input.trim() || this.isLoading) return;

        const userMessage = this.input.trim();
        this.input = '';
        this.autoResize();

        this.messages.push({ role: 'user', content: userMessage, isLoading: false });
        this.scrollToBottom();

        this.isLoading = true;
        this.messages.push({ role: 'ai', content: '', isLoading: true });
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
            this.messages[this.messages.length - 1].isLoading = false;

            if (response.ok && data.success) {
                this.messages[this.messages.length - 1].content = data.reply;
            } else {
                this.messages[this.messages.length - 1].content = data.error || 'Ocorreu um erro no servidor.';
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
