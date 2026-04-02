<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Yeni Müşteri Oluştur
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block font-medium mb-2">Müşteri E-mail</label>
                        <input type="email" name="email" class="border rounded px-3 py-2 w-full" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium mb-2">Geçici Şifre</label>
                        <input type="password" name="password" class="border rounded px-3 py-2 w-full">
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                        style="display: inline-flex; align-items: center; justify-content: center; background-color: #059669; color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s ease; width: auto;"
                        onmouseover="this.style.backgroundColor='#047857'; this.style.transform='translateY(-1px)';" 
                        onmouseout="this.style.backgroundColor='#059669'; this.style.transform='translateY(0)';"
>
                        <svg style="margin-right: 8px; width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                            Müşteri Oluştur
                        </button>


                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>