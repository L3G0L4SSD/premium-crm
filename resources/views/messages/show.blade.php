<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Konuşma Detayı
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-100">
                <!-- Chat Header -->
                <div class="bg-slate-50 border-b border-slate-200 p-4 flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($conversation->customer->full_name ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 leading-tight">
                                {{ $conversation->customer->full_name ?? 'Müşteri' }}
                            </h3>
                            <p class="text-xs text-slate-500">{{ $conversation->customer->email }}</p>
                        </div>
                    </div>
                    <div>
                        <span id="customer-status" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center bg-white px-3 py-1 rounded-full border border-slate-100 shadow-sm">
                            <span class="inline-block w-2 h-2 rounded-full bg-slate-300 mr-2 animate-pulse"></span> Offline
                        </span>
                    </div>
                </div>

                <!-- Chat Body -->
                <div id="messages" class="h-[500px] overflow-y-auto p-6 space-y-4 bg-[#f0f2f5] custom-scrollbar">
                    @forelse($conversation->messages as $message)
                        @php $isMe = ($message->sender_type === 'employee'); @endphp
                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] rounded-2xl p-3 shadow-sm {{ $isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-800 rounded-tl-none border border-slate-200' }}">
                                <div class="text-sm leading-relaxed">
                                    {{ $message->message }}
                                </div>
                                <div class="text-[10px] mt-1 flex justify-end {{ $isMe ? 'text-indigo-200' : 'text-slate-400' }}">
                                    {{ $message->created_at?->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-slate-400 space-y-2">
                            <span class="text-4xl">📥</span>
                            <p class="text-sm italic">Henüz mesaj yok. İlk mesajı siz yazın.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Chat Footer / Input -->
                <div class="p-4 bg-white border-t border-slate-100">
                    <form method="POST" action="{{ route('messages.store', $conversation) }}" id="chat-form" class="flex items-end space-x-3">
                        @csrf
                        <div class="flex-1">
                            <textarea
                                name="message"
                                id="message-input"
                                class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm resize-none py-2 px-4 bg-slate-50"
                                rows="1"
                                placeholder="Bir mesaj yazın..."
                                required
                            ></textarea>
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-xl transition shadow-lg shadow-indigo-100 group">
                            <svg class="h-5 w-5 transform transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const conversationId = {{ $conversation->id }};
            const container = document.getElementById('messages');
            const input = document.getElementById('message-input');
            const statusDot = document.getElementById('customer-status');
            
            if (container) container.scrollTop = container.scrollHeight;

            const channel = window.Echo.join('conversation.' + conversationId);

            channel.here((users) => {
                console.log('Admin here users:', users); // Debug: users listesini logla
                if (users.find(u => u.type === 'customer' || u.type === 'guest')) { // Genişletilmiş kontrol
                    statusDot.innerHTML = '<span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-2 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span> Online';
                    statusDot.classList.replace('text-slate-400', 'text-emerald-600');
                }
            })
            .joining((user) => {
                console.log('Admin joining user:', user); // Debug: joining user'ı logla
                if (user.type === 'customer' || user.type === 'guest') { // Genişletilmiş kontrol
                    statusDot.innerHTML = '<span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-2 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span> Online';
                    statusDot.classList.replace('text-slate-400', 'text-emerald-600');
                }
            })
            .leaving((user) => {
                console.log('Admin leaving user:', user); // Debug: leaving user'ı logla
                if (user.type === 'customer' || user.type === 'guest') { // Genişletilmiş kontrol
                    statusDot.innerHTML = '<span class="inline-block w-2 h-2 rounded-full bg-slate-300 mr-2"></span> Offline';
                    statusDot.classList.replace('text-emerald-600', 'text-slate-400');
                }
            });

            input.addEventListener('input', () => {
                channel.whisper('typing', { typing: true });
                input.style.height = 'auto';
                input.style.height = (input.scrollHeight) + 'px';
            });

            channel.listen('.message.sent', (e) => {
                const isMe = (e.sender_type === 'employee');
                const bubbleHtml = `
                    <div class="flex ${isMe ? 'justify-end' : 'justify-start'} animate-fadeIn">
                        <div class="max-w-[75%] rounded-2xl p-3 shadow-sm ${isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-800 rounded-tl-none border border-slate-200'}">
                            <div class="text-sm leading-relaxed">${e.message}</div>
                            <div class="text-[10px] mt-1 flex justify-end ${isMe ? 'text-indigo-200' : 'text-slate-400'}">
                                ${e.created_at.split(' ')[0]}
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', bubbleHtml);
                container.scrollTop = container.scrollHeight;
            })
            .listenForWhisper('typing', (e) => {
                let existing = document.getElementById('typing-display');
                if (!existing) {
                    existing = document.createElement('div');
                    existing.id = 'typing-display';
                    existing.className = 'text-[10px] text-indigo-500 italic px-6 py-1 bg-white/50 backdrop-blur';
                    container.after(existing);
                }
                existing.innerText = 'Müşteri bir mesaj yazıyor...';
                clearTimeout(window.typingTimer);
                window.typingTimer = setTimeout(() => { existing.innerText = ''; }, 3000);
            });
        });
    </script>
</x-app-layout>