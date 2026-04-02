<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Müşteri Düzenle
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-6">{{ $customer->email }} müşterisini düzenle</h3>

                <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium mb-2">Ad Soyad</label>
                        <input
                            type="text"
                            name="full_name"
                            value="{{ old('full_name', $customer->full_name) }}"
                            class="border rounded px-3 py-2 w-full"
                        >
                        @error('full_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium mb-2">Şirket Adı</label>
                        <input
                            type="text"
                            name="company_name"
                            value="{{ old('company_name', $customer->company_name) }}"
                            class="border rounded px-3 py-2 w-full"
                        >
                        @error('company_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium mb-2">Telefon</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $customer->phone) }}"
                            class="border rounded px-3 py-2 w-full"
                        >
                        @error('phone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium mb-2">Adres</label>
                        <textarea
                            name="address"
                            class="border rounded px-3 py-2 w-full"
                            rows="4"
                        >{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium mb-2">Durum</label>
                        <select name="status" class="border rounded px-3 py-2 w-full">
                            <option value="pending_profile" {{ old('status', $customer->status) == 'pending_profile' ? 'selected' : '' }}>
                                Pending Profile
                            </option>
                            <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium mb-2">Atanan Çalışan</label>
                        <select name="assigned_to" class="border rounded px-3 py-2 w-full">
                            <option value="">Seçiniz</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('assigned_to', $customer->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex items-center gap-4">
                        <button type="submit" class="bg-black text-red-700 px-5 py-2 underline rounded">
                            Kaydet
                        </button>

                        <a href="{{ route('customers.index') }}" class="text-gray-700 underline">
                            Geri dön
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>