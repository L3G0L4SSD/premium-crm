<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Canlı İzleme Paneli (Live Monitoring)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Aktif Konuşmalar Listesi -->
                <div class="col-span-1 bg-white shadow rounded-lg p-4">
                    <h3 class="font-bold text-lg mb-4 border-b pb-2">Aktif Sohbetler</h3>
                    <div id="conversation-list" class="space-y-2">
                        @foreach($conversations as $conv)
                            <div id="conv-card-{{ $conv->id }}" class="p-3 border rounded hover:bg-gray-50 cursor-pointer transition">
                                <p class="font-semibold text-sm">{{ $conv->customer->full_name ?? $conv->customer->email }}</p>
                                <p class="text-xs text-gray-500">Temsilci: {{ $conv->user->name ?? 'Atanmamış' }}</p>
                                <div id="last-msg-{{ $conv->id }}" class="text-[10px] text-blue-600 mt-1 italic truncate">
                                    {{ $conv->messages->last()->message ?? 'Mesaj yok' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Canlı Akış (Live Stream) -->
                <div class="col-span-2 bg-white shadow rounded-lg p-4">
                    <h3 class="font-bold text-lg mb-4 border-b pb-2 flex justify-between items-center">
                        Canlı Akış
                        <span class="bg-red-500 text-white text-[10px] px-2 py-1 rounded animate-pulse">LIVE</span>
                    </h3>
                    <div id="live-stream" class="space-y-4 h-[600px] overflow-y-auto p-2 bg-gray-50 rounded">
                        <p class="text-center text-gray-400 mt-10 italic">Mesajlar bekleniyor...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Admin Monitoring Başlatıldı...');

            window.Echo.private('admin.monitoring')
                .listen('.message.sent', (e) => {
                    console.log('Genel akışa mesaj düştü:', e);

                    let stream = document.getElementById('live-stream');
                    
                    // "Mesajlar bekleniyor" yazısını kaldır
                    if (stream.querySelector('.italic')) {
                        stream.innerHTML = '';
                    }

                    let msgDiv = document.createElement('div');
                    msgDiv.className = 'bg-white p-3 rounded shadow-sm border-l-4 ' + 
                                     (e.sender_type === 'customer' ? 'border-blue-500' : 'border-green-500');

                    msgDiv.innerHTML = `
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-bold text-xs ${e.sender_type === 'customer' ? 'text-blue-600' : 'text-green-600'}">
                                ${e.sender_type.toUpperCase()}
                            </span>
                            <span class="text-[10px] text-gray-400">${e.created_at}</span>
                        </div>
                        <p class="text-sm text-gray-800">${e.message}</p>
                        <div class="mt-2 text-[10px] text-gray-500">
                            Konuşma ID: #${e.conversation_id}
                        </div>
                    `;

                    stream.prepend(msgDiv);

                    // Sol listedeki son mesajı güncelle
                    let lastMsgDisplay = document.getElementById('last-msg-' + e.conversation_id);
                    if (lastMsgDisplay) {
                        lastMsgDisplay.innerText = e.message;
                        // Kartı efektle salla/vurgula
                        let card = document.getElementById('conv-card-' + e.conversation_id);
                        card.classList.add('bg-yellow-50');
                        setTimeout(() => card.classList.remove('bg-yellow-50'), 1000);
                    }
                });
        });
    </script>
</x-app-layout>
