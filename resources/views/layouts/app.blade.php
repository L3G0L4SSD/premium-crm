<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @if (session('success'))
            <script>
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                };
                toastr.success(@json(session('success')));
            </script>        
        @endif
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if(auth()->check() && auth()->user() instanceof \App\Models\User)
                    const user = @json(auth()->user()->load('role'));
                    const userId = user.id;
                    const deptId = user.department_id;
                    const isManager = user.role && user.role.slug === 'manager';
                    const isAdmin = user.role && user.role.slug === 'super-admin';

                    console.log('Global notification listener started for User:', userId);
                    
                    // 1. Şahsi Bildirimler
                    window.Echo.private('user.' + userId)
                        .listen('.message.sent', (e) => handleNotification(e));

                    // 2. Departman Bildirimleri (Managers only)
                    if (isManager && deptId) {
                        console.log('Manager mode: Listening to department.' + deptId);
                        window.Echo.private('department.' + deptId)
                            .listen('.message.sent', (e) => {
                                // Eğer mesajı atan kişi zaten bu yöneticiyse bildirim gösterme
                                if (e.sender_id != userId) {
                                    handleNotification(e);
                                }
                            });
                    }
            
                    if (isAdmin) {
                        console.log('Admin mode: Listening to admin.monitoring');
                        window.Echo.private('admin.monitoring')
                            .listen('.message.sent', (e) => handleNotification(e));
                    }

                    function handleNotification(e) {
                        console.log('Yeni bildirim işleniyor:', e);
                        
                        // Eğer kullanıcı zaten o konuşma sayfasındaysa anlık toastr gösterme (mesaj zaten ekrana düşecek)
                        if (window.location.pathname.includes('/messages/' + e.conversation_id)) {
                            return;
                        }

                        toastr.info(e.message, 'Yeni Mesaj: ' + e.sender_name, {
                            timeOut: 5000,
                            closeButton: true,
                            progressBar: true,
                            onclick: function() {
                                window.location.href = '/messages/' + e.conversation_id;
                            }
                        });
                    }
                @endif
            });
        </script>
    </body>
</html>
