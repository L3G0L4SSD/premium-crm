<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Müşteri Detayı
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-6">{{ $customer->email }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="font-semibold">Ad Soyad</p>
                        <p>{{ $customer->full_name ?? 'Yok' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Şirket Adı</p>
                        <p>{{ $customer->company_name ?? 'Yok' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Telefon</p>
                        <p>{{ $customer->phone ?? 'Yok' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Durum</p>
                        <p>{{ $customer->status }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Departman</p>
                        <p>{{ $customer->department->name ?? 'Yok' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Atanan Çalışan</p>
                        <p>{{ $customer->assignedUser->name ?? 'Yok' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Oluşturan</p>
                        <p>{{ $customer->createdByUser->name ?? 'Yok' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold">Oluşturulma Tarihi</p>
                        <p>{{ $customer->created_at?->format('d.m.Y H:i') }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="font-semibold">Adres</p>
                        <p>{{ $customer->address ?? 'Yok' }}</p>
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-4">
                    <a href="{{ route('messages.start', $customer) }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700 transition">
                        💬 Mesaj Gönder
                    </a>
                    <a href="{{ route('customers.edit', $customer) }}"
                       class="bg-black text-white px-4 py-2 rounded">
                        Düzenle
                    </a>

                    <a href="{{ route('customers.index') }}"
                       class="text-gray-700 underline">
                        Geri dön
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>