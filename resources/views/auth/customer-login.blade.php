<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow rounded p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">Customer Login</h1>

        <form method="POST" action="{{ route('customer.login.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1 font-medium">E-mail</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">Şifre</label>
                <input
                    type="password"
                    name="password"
                    class="w-full border rounded px-3 py-2"
                    required
                >
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Beni hatırla</label>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2.5 rounded-lg shadow-md hover:shadow-lg transform active:scale-95 transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
    Giriş Yap
</button>

        </form>
    </div>

</body>
</html>