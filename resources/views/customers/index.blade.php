<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Müşteriler
            </h2>

           <a href="{{ route('customers.create') }}" 
            style="display: inline-flex; align-items: center; background-color: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: background-color 0.3s;"
            onmouseover="this.style.backgroundColor='#1d4ed8'" 
            onmouseout="this.style.backgroundColor='#2563eb'">
   
    <span style="margin-right: 8px; font-size: 1.2rem;">+</span>
    Yeni Müşteri Ekle
</a>

        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Müşteri Listesi</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2 text-left">ID</th>
                                <th class="border px-4 py-2 text-left">E-mail</th>
                                <th class="border px-4 py-2 text-left">Ad Soyad</th>
                                <th class="border px-4 py-2 text-left">Departman</th>
                                <th class="border px-4 py-2 text-left">Atanan</th>
                                <th class="border px-4 py-2 text-left">Oluşturan</th>
                                <th class="border px-4 py-2 text-left">Durum</th>
                                <th class="border px-4 py-2 text-left">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <td class="border px-4 py-2">{{ $customer->id }}</td>
                                    <td class="border px-4 py-2">{{ $customer->email }}</td>
                                    <td class="border px-4 py-2">{{ $customer->full_name ?? 'Yok' }}</td>
                                    <td class="border px-4 py-2">{{ $customer->department->name ?? 'Yok' }}</td>
                                    <td class="border px-4 py-2">{{ $customer->assignedUser->name ?? 'Yok' }}</td>
                                    <td class="border px-4 py-2">{{ $customer->createdByUser->name ?? 'Yok' }}</td>
                                    <td class="border px-4 py-2">{{ $customer->status }}</td>
                                    <td class="border px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('messages.start', $customer) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded hover:bg-blue-700 transition" title="Mesaj Gönder">
                                                💬 Mesaj
                                            </a>
                                            <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700 transition">
                                                Detay
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-3 py-1 bg-gray-600 text-white text-xs font-bold rounded hover:bg-gray-700 transition">
                                                Düzenle
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="border px-4 py-4 text-center text-gray-500">
                                        Müşteri bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>