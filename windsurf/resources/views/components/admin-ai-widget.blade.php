@if(auth()->check() && auth()->user()->is_admin)
<script>window.aiChatRoute = '{{ route("admin.ai-chat.message") }}';</script>
<div x-data="floatingAiChat()" class="fixed bottom-6 right-6 z-[100] font-sans">
    
    <!-- Botão Flutuante (Robozinho) -->
    <button 
        @click="toggleChat"
        :class="isOpen ? 'scale-0' : 'scale-100'"
        class="bg-emerald-600 hover:bg-emerald-500 text-white p-4 rounded-full shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-emerald-500/50 flex items-center justify-center relative"
        title="Falar com a Inteligência do Sistema">
        
        <!-- Ponto de Notificação (pulsante) -->
        <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white dark:border-slate-900"></span>
        </span>

        <!-- SVG Icon: Robozinho -->
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 9h.01M15 9h.01"></path>
        </svg>
    </button>

    <!-- Janela de Chat -->
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-95"
        @click.away="isOpen = false"
        class="absolute bottom-0 right-0 w-80 sm:w-96 h-[500px] max-h-[80vh] bg-white dark:bg-slate-900 shadow-2xl rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden transform origin-bottom-right"
        style="display: none;">
        
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-4 text-white flex justify-between items-center shadow-md z-10">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-full relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    <!-- Status Indicator -->
                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-emerald-600"></span>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Assistente IA</h3>
                    <p class="text-[10px] text-emerald-100">Exclusivo Rota do Mar</p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-white hover:text-slate-200 focus:outline-none transition-colors p-1 rounded hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 p-4 overflow-y-auto space-y-4 bg-slate-50 dark:bg-slate-950 scroll-smooth" id="widget-messages-container">
            
            <!-- Msg Inicial -->
            <div class="flex justify-start">
                <div class="max-w-[85%] bg-white dark:bg-slate-800 p-3 rounded-2xl rounded-tl-sm shadow-sm border border-slate-100 dark:border-slate-700 text-sm text-slate-700 dark:text-slate-300">
                    Olá! Sou o robozinho do Rota do Mar. O que você gostaria de analisar ou perguntar sobre o sistema hoje?
                </div>
            </div>

            <!-- Loop Mensagens -->
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="['max-w-[85%] text-sm p-3 shadow-sm border', 
                                  msg.role === 'user' 
                                    ? 'bg-emerald-600 text-white border-emerald-500 rounded-2xl rounded-tr-sm' 
                                    : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-100 dark:border-slate-700 rounded-2xl rounded-tl-sm']"
                         style="min-width: 60px;">
                        
                        <template x-if="msg.isLoading">
                            <div class="flex space-x-1.5 justify-center items-center h-4 py-1">
                                <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
                                <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                                <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce"></div>
                            </div>
                        </template>
                        
                        <template x-if="!msg.isLoading">
                            <div class="whitespace-pre-wrap leading-relaxed" x-html="formatMessage(msg.content)"></div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Chat Input -->
        <div class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 p-3">
            <form @submit.prevent="sendMessage" class="flex gap-2 items-end relative">
                <textarea 
                    x-model="input" 
                    @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                    placeholder="Digite sua dúvida..." 
                    class="w-full text-sm bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-emerald-500 focus:border-emerald-500 rounded-xl resize-none py-2.5 pl-3 pr-10"
                    rows="1"
                    style="min-height: 42px; max-height: 120px;"
                    x-ref="chatInput"
                    :disabled="isLoading"
                    @input="autoResize"
                ></textarea>
                
                <button type="submit" 
                        :disabled="!input.trim() || isLoading"
                        class="absolute right-2 bottom-1.5 p-1.5 text-emerald-600 hover:text-emerald-500 disabled:text-slate-300 dark:disabled:text-slate-600 transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                </button>
            </form>
            <div class="text-center mt-1.5">
                <span class="text-[9px] text-slate-400">Tecle 'Enter' para enviar</span>
            </div>
        </div>

    </div>
</div>
@endif
