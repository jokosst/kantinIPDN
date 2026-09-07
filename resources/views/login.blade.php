<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/icon.ico')}}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/img/icon.ico')}}">
    <title>Login — Kasir App</title>

    <!-- Inter Font - Local (offline) -->
    <link rel="stylesheet" href="{{ asset('assets/css/inter.css') }}">

    <!-- Tailwind CSS - Local (offline) -->
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.login.css') }}">
</head>

<body class="bg-gradient-kasir min-h-screen flex items-center justify-center p-4">

    <!-- Decorative background circles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-white opacity-5 rounded-full"></div>
        <div class="absolute -bottom-24 -right-16 w-96 h-96 bg-white opacity-5 rounded-full"></div>
        <div class="absolute top-1/2 left-1/4 w-40 h-40 bg-blue-300 opacity-10 rounded-full"></div>
    </div>

    <!-- Card -->
    <div class="relative w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Card header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-8 text-center">
                <!-- Icon -->
                <div class="mx-auto mb-3 rounded-2xl flex items-center justify-center"
                     style="width:5rem;height:5rem;background-color:rgba(255,255,255,0.15);">
                    <img src="{{ asset('assets/img/itplogo.png') }}" alt="Logo"
                         style="width:3.5rem;height:3.5rem;object-fit:contain;display:block;">
                </div>
                <h1 class="text-2xl font-bold text-white tracking-wide">ITPOS - LOGIN APLIKASI</h1>
                <p class="text-blue-200 text-sm" style="margin-top:-10px">Sistem Point of Sale</p>
            </div>

            <!-- Card body -->
            <div class="px-8 py-8">

                @if(session('error'))
                    <div class="mb-5 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ url('login') }}" method="post" autocomplete="off">
                    @csrf

                    <!-- Username -->
                    <div class="mb-5">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Username
                        </label>
                        <div class="input-icon-wrapper">
                            <svg class="input-left-icon w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                placeholder="Masukkan username"
                                autofocus
                                class="input-with-icon w-full pr-4 py-3 border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                       transition duration-200"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-7">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password
                        </label>
                        <div class="input-icon-wrapper">
                            <svg class="input-left-icon w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                class="input-with-icon input-with-icon-right w-full py-3 border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                       transition duration-200"
                            >
                            <!-- Toggle show/hide password -->
                            <button type="button" class="toggle-password" onclick="togglePassword()" id="toggleBtn" aria-label="Toggle password visibility">
                                <!-- Eye icon (password hidden) -->
                                <svg id="iconEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- Eye-off icon (password visible) -->
                                <svg id="iconEyeOff" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                               text-white font-semibold rounded-lg
                               transition duration-200 shadow-md hover:shadow-lg
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Masuk
                    </button>

                </form>
            </div>

            <!-- Card footer -->
            <div class="px-8 pb-6 text-center">
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Kasir App. All rights reserved.</p>
            </div>

        </div>
    </div>
    <script>
        function togglePassword() {
            const input     = document.getElementById('password');
            const iconEye    = document.getElementById('iconEye');
            const iconEyeOff = document.getElementById('iconEyeOff');

            if (input.type === 'password') {
                input.type = 'text';
                iconEye.classList.add('hidden');
                iconEyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                iconEye.classList.remove('hidden');
                iconEyeOff.classList.add('hidden');
            }
        }
    </script>

</body>
</html>