<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Canlı Destek</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 font-sans">

    <div class="max-w-4xl mx-auto py-10 px-4">
        @include('customer.partials.chat', ['conversation' => $conversation])
    </div>
</body>
</html>

