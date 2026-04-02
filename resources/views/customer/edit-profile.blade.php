<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Düzenle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="max-w-3xl mx-auto py-10 px-4">
        <div class="bg-white shadow rounded p-6">
            <h1 class="text-2xl font-bold mb-6">Profil Düzenle</h1>

            <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-1 font-medium">Ad Soyad</label>
                    <input
                        type="text"
                        name="full_name"
                        value="{{ old('full_name', $customer->full_name) }}"
                        class="w-full border rounded px-3 py-2"
                        required
                    >
                    @error('full_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 font-medium">Şirket</label>
                    <input
                        type="text"
                        name="company_name"
                        value="{{ old('company_name', $customer->company_name) }}"
                        class="w-full border rounded px-3 py-2"
                    >
                    @error('company_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 font-medium">Telefon</label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $customer->phone) }}"
                        class="w-full border rounded px-3 py-2"
                    >
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 font-medium">Adres</label>
                    <textarea
                        name="address"
                        class="w-full border rounded px-3 py-2"
                        rows="4"
                    >{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="bg-black text-red-500 px-4 py-2 underline rounded">
                        Kaydet
                    </button>

                    <a href="{{ route('customer.dashboard') }}" class="text-gray-700 underline">
                        Geri dön
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>