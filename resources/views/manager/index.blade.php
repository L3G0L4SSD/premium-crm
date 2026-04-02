<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manager Panel
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000,
            };
            toastr.success(@json(session('success')));
        });
    </script>
@endif
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Kullanıcı Listesi</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2 text-left">ID</th>
                                <th class="border px-4 py-2 text-left">Ad</th>
                                <th class="border px-4 py-2 text-left">Email</th>
                                <th class="border px-4 py-2 text-left">Departman</th>
                                <th class="border px-4 py-2 text-left">Rol</th>
                                <th class="border px-4 py-2 text-left">Durum</th>
                                <th class="border px-4 py-2 text-left">İşlem</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="border px-4 py-2">{{ $user->id }}</td>

                                    <td class="border px-4 py-2">
                                        {{ $user->name }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $user->email }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $user->department->name ?? 'Yok' }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $user->role->name ?? 'Yok' }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        @if($user->is_active)
                                            <span class="text-green-600 font-semibold">Aktif</span>
                                        @else
                                            <span class="text-red-600 font-semibold">Pasif</span>
                                        @endif
                                    </td>

                                    <td class="border px-4 py-2">
                                        <a href="{{ route('manager.users.edit', $user) }}"
                                           class="text-blue-600 hover:underline">
                                            Düzenle
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="border px-4 py-4 text-center text-gray-500">
                                        Kullanıcı bulunamadı.
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