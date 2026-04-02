<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 font-sans">

    <div class="max-w-5xl mx-auto py-12 px-6">
        <!-- Üst Başlık ve Hoşgeldiniz Mesajı -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Müşteri Paneli</h1>
                <p class="text-slate-500 mt-1">Hoş geldiniz, {{ $customer->full_name ?? 'Değerli Müşterimiz' }}</p>
            </div>
            <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700 bg-red-50 px-4 py-2 rounded-lg transition">
                    Çıkış Yap
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-md shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sol Kolon: Profil Özet Kartı -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white shadow-xl shadow-slate-200/50 rounded-2xl p-6 border border-slate-100">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="h-14 w-14 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                            {{ strtoupper(substr($customer->full_name ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-lg font-bold">{{ $customer->full_name ?? 'İsimsiz' }}</h2>
                            <p class="text-xs text-slate-400">{{ $customer->email }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm border-b pb-2 border-slate-50">
                            <span class="text-slate-500">Şirket</span>
                            <span class="font-medium">{{ $customer->company_name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm border-b pb-2 border-slate-50">
                            <span class="text-slate-500">Telefon</span>
                            <span class="font-medium">{{ $customer->phone ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Durum</span>
                            <span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded-full uppercase tracking-wider">
                                {{ $customer->status }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('customer.profile.edit') }}" 
                           class="block w-full text-center bg-slate-900 text-white font-bold py-3 rounded-xl hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                            Profili Düzenle
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sağ Kolon: Hızlı İşlemler ve Bildirimler -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Canlı Destek Kartı -->
                <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl p-8 text-white shadow-xl shadow-indigo-200 relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-2">Desteğe mi ihtiyacınız var?</h3>
                        <p class="text-indigo-100 mb-8 max-w-md">Çalışanlarımız size yardımcı olmak için hazır. Canlı mesajlaşma panelini kullanarak hemen iletişime geçin.</p>
                        <a href="{{ route('customer.messages') }}" 
                           class="inline-flex items-center bg-white text-indigo-700 font-bold px-8 py-3 rounded-xl hover:bg-indigo-50 transition drop-shadow-md">
                            💬 Mesajlaşmaya Başla
                        </a>
                    </div>
                    <!-- Süsleme İkonu -->
                    <div class="absolute -right-10 -bottom-10 opacity-20 transform rotate-12">
                        <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                        </svg>
                    </div>
                </div>

                <!-- Bilgilendirme Kutusu -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h4 class="font-bold mb-4 flex items-center">
                        <span class="mr-2 text-indigo-600">📌</span> Son Aktiviteler
                    </h4>
                    <p class="text-slate-500 text-sm italic py-4 border-t border-slate-50">Henüz yeni bir aktivite bulunmuyor.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toastr & Real-time Support -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Müşteri ID'sini al
            const customerId = {{ $customer->id }};
            console.log('Customer notification listener started for ID:', customerId);

            window.Echo.private('customer.' + customerId)
                .listen('.message.sent', (e) => {
                    console.log('Yeni mesaj bildirimi alındı:', e);

                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: 5000,
                        onclick: function() {
                            window.location.href = "{{ route('customer.messages') }}";
                        }
                    };

                    toastr.info(e.message, '📧 Yeni Destek Mesajı: ' + e.sender_name);
                });
        });
    </script>
</body>
</html>