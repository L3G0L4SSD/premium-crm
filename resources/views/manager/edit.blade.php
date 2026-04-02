<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kullanıcı Düzenle
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-6">{{ $user->name }} kullanıcısını düzenle</h3>
                <form method="POST" action="{{ route('manager.users.update', $user) }}" class="space-y-6">
                    @csrf  
                    @method('PUT')
                    <div>
                        <label class="block font-medium mb-2">Departman</label>
                        <select name="department_id" class="border rounded px-3 py-2 w-full">
                            <option value="">Seçiniz</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ $user->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>
                    <div>
                        <label class="block font-medium mb-2">Rol</label>
                        <select name="role_id" class="border rounded px-3 py-2 w-full">
                            <option value="">Seçiniz</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium mb-2">Durum</label>
                        <select name="is_active" class="border rounded px-3 py-2 w-full">
                            <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Pasif</option>
                        </select>
                        @error('is_active')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex items-center gap-4">
                        <button type="submit" class="bg-black text-red-500 px-5 py-2 rounded">
                            Kaydet
                        </button>

                        <a href="{{ route('manager.index') }}" class="text-gray-700 underline">
                            Geri dön
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>