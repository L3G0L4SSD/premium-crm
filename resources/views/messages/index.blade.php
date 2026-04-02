<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mesaj Kutusu
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Konuşmalar</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2 text-left">ID</th>
                                <th class="border px-4 py-2 text-left">Customer</th>
                                <th class="border px-4 py-2 text-left">E-mail</th>
                                <th class="border px-4 py-2 text-left">Konu</th>
                                <th class="border px-4 py-2 text-center">Okunmamış</th>
                                <th class="border px-4 py-2 text-left">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($conversations as $conversation)
                                <tr id="conv-row-{{ $conversation->id }}" class="{{ $conversation->unread_count > 0 ? 'bg-red-50 font-bold' : '' }}">
                                    <td class="border px-4 py-2">{{ $conversation->id }}</td>
                                    <td class="border px-4 py-2">{{ $conversation->customer->full_name ?? 'Yok' }}</td>
                                    <td class="border px-4 py-2">{{ $conversation->customer->email }}</td>
                                    <td class="border px-4 py-2">{{ $conversation->subject ?? 'Konuşma' }}</td>
                                    <td class="border px-4 py-2 text-center" id="unread-count-{{ $conversation->id }}">
                                        @if($conversation->unread_count > 0)
                                            <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-full">
                                                {{ $conversation->unread_count }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('messages.show', $conversation) }}" class="text-blue-600 underline">
                                            Aç
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border px-4 py-4 text-center text-gray-500">
                                        Konuşma bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(auth()->check())
                let userId = {{ auth()->id() }};
                
                window.Echo.private('user.' + userId)
                    .listen('.message.sent', (e) => {
                        console.log('Inbox canlı güncelleme için mesaj alındı:', e);
                        
                        // İlgili konuşma satırını bul
                        let countSpan = document.getElementById('unread-count-' + e.conversation_id);
                        let row = document.getElementById('conv-row-' + e.conversation_id);

                        if (countSpan) {
                            // Eğer daha önce hiç mesaj yoksa (çizgi varsa) temizle ve sayı ekle
                            if (countSpan.innerText.trim() === '-') {
                                countSpan.innerHTML = '<span class="bg-red-600 text-white text-xs px-2 py-1 rounded-full">1</span>';
                            } else {
                                // Mevcut sayıyı artır
                                let badge = countSpan.querySelector('span');
                                if (badge) {
                                    let currentCount = parseInt(badge.innerText);
                                    badge.innerText = currentCount + 1;
                                }
                            }
                            
                            // Satırı vurgula
                            if (row) {
                                row.classList.add('bg-red-50', 'font-bold');
                            }
                        }
                    });
            @endif
        });
    </script>
</x-app-layout>