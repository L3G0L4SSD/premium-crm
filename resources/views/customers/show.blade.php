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
                       style="display: inline-flex; align-items: center; background-color: #2563eb; color: white; padding: 10px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.2s;"
                       onmouseover="this.style.backgroundColor='#1d4ed8'; this.style.transform='translateY(-1px)'" 
                       onmouseout="this.style.backgroundColor='#2563eb'; this.style.transform='translateY(0)'">
                       <span style="margin-right: 8px; font-size: 1.2rem;">💬</span> Mesaj Gönder
                    </a>
                    <a href="{{ route('customers.edit', $customer) }}"
                       style="display: inline-flex; align-items: center; background-color: #111827; color: white; padding: 10px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.2s;"
                       onmouseover="this.style.backgroundColor='#030712'; this.style.transform='translateY(-1px)'" 
                       onmouseout="this.style.backgroundColor='#111827'; this.style.transform='translateY(0)'">
                       <span style="margin-right: 8px;">✏️</span> Düzenle
                    </a>
                    <a href="{{ route('customers.index') }}"
                       class="text-gray-600 hover:text-gray-900 font-medium"
                       style="text-decoration: underline; margin-left: auto;">
                        Geri dön
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>