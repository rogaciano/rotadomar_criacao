<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Assistente IA - Rota do Mar
        </h2>
    </x-slot>

    <div class="py-8" x-data="aiChat()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-xl sm:rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col" style="height: 75vh;">
                
                <!-- Chat Header Area (optional contextual info) -->
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-700 dark:text-slate-300">Chat exclusivo para Administração</h3>
                        <p class="text-xs text-slate-500">A IA possui o contexto geral do sistema integrado (Rota do Mar).</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Online (Gemini 2.5)</span>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 p-6 overflow-y-auto space-y-6 bg-slate-50 dark:bg-slate-950" id="chat-messages-container">
                    
                    <!-- Welcome Message -->
                    <div class="flex justify-start">
                        <div class="max-w-[80%] flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center border border-emerald-200 dark:border-emerald-800">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl rounded-tl-sm shadow-sm border border-slate-100 dark:border-slate-700">
                                <p class="text-slate-700 dark:text-slate-300 text-sm whitespace-pre-wrap">Olá! Sou o Assistente Inteligente do sistema **Rota do Mar**. Como administrador, como posso ajudar você hoje?</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Messages -->
                    <template x-for="(msg, index) in messages" :key="index">
                        <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="['max-w-[85%] flex gap-3', msg.role === 'user' ? 'flex-row-reverse' : 'flex-row']">
                                
                                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center border"
                                     :class="msg.role === 'user' ? 'bg-indigo-100 dark:bg-indigo-900/50 border-indigo-200 dark:border-indigo-800' : 'bg-emerald-100 dark:bg-emerald-900/50 border-emerald-200 dark:border-emerald-800'">
                                    
                                    <svg x-show="msg.role === 'user'" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    
                                    <svg x-show="msg.role === 'ai'" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </div>
                                
                                <div :class="['p-4 rounded-2xl shadow-sm border text-sm', 
                                              msg.role === 'user' 
                                                ? 'bg-indigo-600 text-white border-indigo-500 rounded-tr-sm' 
                                                : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-100 dark:border-slate-700 rounded-tl-sm']"
                                     style="min-width: 80px;">
                                    <template x-if="msg.isLoading">
                                        <div class="flex space-x-2 justify-center items-center h-5">
                                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
                                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce"></div>
                                        </div>
                                    </template>
                                    <template x-if="!msg.isLoading">
                                        <div class="whitespace-pre-wrap" x-html="formatMessage(msg.content)"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Input Area -->
                <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
                    <form @submit.prevent="sendMessage" class="flex gap-3 relative">
                        <textarea 
                            x-model="input" 
                            @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                            placeholder="Digite sua mensagem para a inteligência de negócios..." 
                            class="w-full bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-emerald-500 focus:border-emerald-500 rounded-xl resize-none p-4 pr-16 shadow-inner"
                            rows="2"
                            :disabled="isLoading"
                        ></textarea>
                        
                        <button type="submit" 
                                :disabled="!input.trim() || isLoading"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 p-2 bg-emerald-600 hover:bg-emerald-500 disabled:bg-slate-300 dark:disabled:bg-slate-700 text-white rounded-lg transition-colors flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                    <div class="text-center mt-2">
                        <span class="text-[10px] text-slate-400">Pressione 'Enter' para enviar, 'Shift+Enter' para quebrar linha</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('aiChat', () => ({
                input: '',
                isLoading: false,
                messages: [],

                init() {
                    // Start at bottom
                    this.scrollToBottom();
                },

                formatMessage(text) {
                    if (!text) return '';
                    // Simple Markdown formatting to HTML (bold and lines)
                    let html = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    return html;
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const container = document.getElementById('chat-messages-container');
                        container.scrollTop = container.scrollHeight;
                    }, 50);
                },

                async sendMessage() {
                    if (!this.input.trim() || this.isLoading) return;

                    const userMessage = this.input.trim();
                    this.input = '';
                    
                    // Add user message to UI
                    this.messages.push({ role: 'user', content: userMessage, isLoading: false });
                    this.scrollToBottom();

                    // Add loading AI message
                    this.isLoading = true;
                    this.messages.push({ role: 'ai', content: '', isLoading: true });
                    this.scrollToBottom();

                    try {
                        const response = await fetch('{{ route("admin.ai-chat.message") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ message: userMessage })
                        });

                        const data = await response.json();
                        
                        // Stop loading and show response
                        this.messages[this.messages.length - 1].isLoading = false;
                        
                        if (response.ok && data.success) {
                            this.messages[this.messages.length - 1].content = data.reply;
                        } else {
                            this.messages[this.messages.length - 1].content = data.error || 'Ocorreu um erro no servidor.';
                        }

                    } catch (error) {
                        this.messages[this.messages.length - 1].isLoading = false;
                        this.messages[this.messages.length - 1].content = 'Erro de comunicação. Tente novamente.';
                    } finally {
                        this.isLoading = false;
                        this.scrollToBottom();
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
