<!-- Chat Container -->
<div id="chat-container" class="bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-100">
    <!-- Chat Header -->
    <div class="bg-slate-50 border-b border-slate-200 p-4 flex justify-between items-center text-sm font-bold">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 bg-indigo-600 rounded-full flex items-center justify-center text-white text-lg">
                🎧
            </div>
            <div>
                <h1 class="text-slate-800 leading-tight">Müşteri Destek Merkezi</h1>
                <p class="text-[10px] text-slate-500 font-normal">Temsilci: {{ $conversation->user->name ?? 'Müsait bir temsilci' }}</p>
            </div>
        </div>
        <span id="representative-status" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center bg-white px-3 py-1 rounded-full border border-slate-100 shadow-sm">
            <span class="inline-block w-2 h-2 rounded-full bg-slate-300 mr-2"></span> Offline
        </span>
    </div>

    <!-- Chat Messages -->
    <div id="messages" class="h-[400px] overflow-y-auto p-6 space-y-4 bg-[#f0f2f5] custom-scrollbar">
        @forelse($conversation->messages as $msg)
            @php $isMe = ($msg->sender_type === 'customer'); @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[85%] rounded-2xl p-3 shadow-sm {{ $isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-800 rounded-tl-none border border-slate-200' }}">
                    <div class="text-sm leading-relaxed">
                        {{ $msg->message }}
                    </div>
                    <div class="text-[10px] mt-1 flex justify-end {{ $isMe ? 'text-indigo-200' : 'text-slate-400' }}">
                        {{ $msg->created_at?->format('H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-slate-400">
                <p class="text-sm italic">Destek ekibimize bir mesaj göndererek sohbete başlayabilirsiniz.</p>
            </div>
        @endforelse
    </div>

    <!-- Chat Footer -->
    <div class="p-4 bg-white border-t border-slate-100">
        <form id="chat-form" method="POST" action="{{ route('customer.messages.store') }}" class="flex items-end space-x-3">
            @csrf
            <div class="flex-1">
                <input 
                    type="text" 
                    name="message" 
                    id="message-input"
                    placeholder="Mesajınızı yazın..." 
                    class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm py-3 px-4 bg-slate-50"
                    required
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-xl transition shadow-lg shadow-indigo-100">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>
        @unless(Route::is('customer.dashboard'))
        <div class="mt-4 flex justify-between items-center text-xs text-slate-400">
            <a href="{{ route('customer.dashboard') }}" class="hover:text-indigo-600 transition">← Panele Dön</a>
        </div>
        @endunless
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const conversationId = {{ $conversation->id }};
        const container = document.getElementById('messages');
        const input = document.getElementById('message-input');
        const statusDot = document.getElementById('representative-status');
        const chatForm = document.getElementById('chat-form');

        // Auto-scroll to bottom
        const scrollToBottom = () => {
            container.scrollTop = container.scrollHeight;
        };
        scrollToBottom();

        // Listen for new messages via Echo
        if (window.Echo) {
            let channel = window.Echo.join('conversation.' + conversationId);

            channel.here((users) => {
                if (users.find(u => u.type === 'employee')) {
                    statusDot.innerHTML = '<span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-2 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span> Online';
                    statusDot.classList.replace('text-slate-400', 'text-emerald-600');
                }
            })
            .joining((user) => {
                if (user.type === 'employee') {
                    statusDot.innerHTML = '<span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-2 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span> Online';
                    statusDot.classList.replace('text-slate-400', 'text-emerald-600');
                }
            })
            .leaving((user) => {
                if (user.type === 'employee') {
                    statusDot.innerHTML = '<span class="inline-block w-2 h-2 rounded-full bg-slate-300 mr-2"></span> Offline';
                    statusDot.classList.replace('text-emerald-600', 'text-slate-400');
                }
            });

            // Typing indicator sending
            input.addEventListener('input', () => {
                channel.whisper('typing', { typing: true });
            });

            // Listening for messages
            channel.listen('.message.sent', (e) => {
                const isMe = (e.sender_type === 'customer');
                const timeStr = new Date(e.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                
                const bubbleHtml = `
                    <div class="flex ${isMe ? 'justify-end' : 'justify-start'} animate-fadeIn">
                        <div class="max-w-[85%] rounded-2xl p-3 shadow-sm ${isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-800 rounded-tl-none border border-slate-200'}">
                            <div class="text-sm leading-relaxed">${e.message}</div>
                            <div class="text-[10px] mt-1 flex justify-end ${isMe ? 'text-indigo-200' : 'text-slate-400'}">
                                ${timeStr}
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', bubbleHtml);
                scrollToBottom();
            })
            .listenForWhisper('typing', (e) => {
                let existing = document.getElementById('typing-display');
                if (!existing) {
                    existing = document.createElement('div');
                    existing.id = 'typing-display';
                    existing.className = 'text-[10px] text-indigo-500 italic px-6 py-1 bg-white/50 backdrop-blur sticky bottom-0';
                    container.after(existing);
                }
                existing.innerText = 'Destek temsilcisi bir mesaj yazıyor...';
                clearTimeout(window.typingTimer);
                window.typingTimer = setTimeout(() => { existing.innerText = ''; }, 3000);
            });
        }

        // Handle AJAX form submission to prevent page reload if on dashboard
        if (chatForm && "{{ Route::currentRouteName() }}" === "customer.dashboard") {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = input.value;
                if (!message.trim()) return;

                const formData = new FormData(chatForm);
                
                // Clear input immediately for better UX
                input.value = '';

                fetch(chatForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Mesaj gönderilemedi');
                    // Message will be added via Echo broadcast
                })
                .catch(error => {
                    console.error(error);
                    alert('Mesaj gönderilirken bir hata oluştu.');
                });
            });
        }
    });
</script>
