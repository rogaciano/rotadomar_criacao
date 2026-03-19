/**
 * API Module - Comunicação com o backend Laravel
 */
const API = {
    // Base URL da API (ajustar para produção)
    baseUrl: localStorage.getItem('api_base_url') || window.location.origin,

    getToken() {
        return localStorage.getItem('auth_token');
    },

    setToken(token) {
        localStorage.setItem('auth_token', token);
    },

    clearToken() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_data');
    },

    getUser() {
        const data = localStorage.getItem('user_data');
        return data ? JSON.parse(data) : null;
    },

    setUser(user) {
        localStorage.setItem('user_data', JSON.stringify(user));
    },

    isAuthenticated() {
        return !!this.getToken();
    },

    /**
     * Fetch wrapper com auth header
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}/api${endpoint}`;
        const token = this.getToken();

        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
            ...(options.headers || {}),
        };

        try {
            const response = await fetch(url, {
                ...options,
                headers,
            });

            // Token expirado ou inválido
            if (response.status === 401) {
                this.clearToken();
                window.location.href = '/motorista/index.html';
                return null;
            }

            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                throw new Error('Resposta não é JSON');
            }

            const data = await response.json();

            if (!response.ok) {
                throw { status: response.status, data };
            }

            return data;
        } catch (error) {
            if (error.status) throw error;
            throw { status: 0, data: { message: 'Erro de conexão. Verifique sua internet.' } };
        }
    },

    // ===== Auth =====

    async login(email, password) {
        const data = await this.request('/motorista/login', {
            method: 'POST',
            body: JSON.stringify({ email, password }),
        });
        if (data && data.token) {
            this.setToken(data.token);
            this.setUser(data.user);
        }
        return data;
    },

    async logout() {
        try {
            await this.request('/motorista/logout', { method: 'POST' });
        } catch (e) {
            // ignore
        }
        this.clearToken();
    },

    // ===== Coletas =====

    async getColetas(status = 'ativas') {
        return this.request(`/motorista/coletas?status=${status}`);
    },

    async getColetaDetalhe(id) {
        return this.request(`/motorista/coletas/${id}`);
    },

    async confirmarChegada(id, observacao = '') {
        return this.request(`/motorista/coletas/${id}/confirmar-chegada`, {
            method: 'POST',
            body: JSON.stringify({ observacao }),
        });
    },

    async confirmarEntrega(id, observacao = '') {
        return this.request(`/motorista/coletas/${id}/confirmar-entrega`, {
            method: 'POST',
            body: JSON.stringify({ observacao }),
        });
    },

    // ===== Push =====

    async pushSubscribe(subscription) {
        return this.request('/motorista/push-subscribe', {
            method: 'POST',
            body: JSON.stringify({
                endpoint: subscription.endpoint,
                keys: {
                    p256dh: btoa(String.fromCharCode(...new Uint8Array(subscription.getKey('p256dh')))),
                    auth: btoa(String.fromCharCode(...new Uint8Array(subscription.getKey('auth')))),
                },
            }),
        });
    },

    async pushUnsubscribe(endpoint) {
        return this.request('/motorista/push-unsubscribe', {
            method: 'DELETE',
            body: JSON.stringify({ endpoint }),
        });
    },
};
