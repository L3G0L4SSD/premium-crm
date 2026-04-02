<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Canlı Destek</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 font-sans">

    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-100">
            <!-- Chat Header -->
            <div class="bg-slate-50 border-b border-slate-200 p-4 flex justify-between items-center text-sm font-bold">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-indigo-600 rounded-full flex items-center justify-center text-white">
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
            <div id="messages" class="h-[500px] overflow-y-auto p-6 space-y-4 bg-[#f0f2f5] custom-scrollbar">
                @forelse($conversation->messages as $msg)
                    @php $isMe = ($msg->sender_type === 'customer'); @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] rounded-2xl p-3 shadow-sm {{ $isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-800 rounded-tl-none border border-slate-200' }}">
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
                <form method="POST" action="{{ route('customer.messages.store') }}" class="flex items-end space-x-3">
                    @csrf
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="message" 
                            id="message-input"
                            placeholder="Mesajınızı yazın..." 
                            class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm py-3 px-4 bg-slate-50"
                            required
                        >
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-xl transition shadow-lg shadow-indigo-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>
                <div class="mt-4 flex justify-between items-center text-xs text-slate-400">
                    <a href="{{ route('customer.dashboard') }}" class="hover:text-indigo-600 transition">← Panele Dön</a>
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
            let conversationId = {{ $conversation->id }};
            let container = document.getElementById('messages');
            let input = document.getElementById('message-input');
            let statusDot = document.getElementById('representative-status');

            // Auto-scroll to bottom
            container.scrollTop = container.scrollHeight;

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

            // Yazıyor bilgisi
            input.addEventListener('input', () => {
                channel.whisper('typing', { typing: true });
            });

            channel.listen('.message.sent', (e) => {
                let isMe = (e.sender_type === 'customer');
                let bubbleHtml = `
                    <div class="flex ${isMe ? 'justify-end' : 'justify-start'} animate-fadeIn">
                        <div class="max-w-[80%] rounded-2xl p-3 shadow-sm ${isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-800 rounded-tl-none border border-slate-200'}">
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
                existing.innerText = 'Destek temsilcisi bir mesaj yazıyor...';
                clearTimeout(window.typingTimer);
                window.typingTimer = setTimeout(() => { existing.innerText = ''; }, 3000);
            });
        });
    </script>
</body>
</html>
