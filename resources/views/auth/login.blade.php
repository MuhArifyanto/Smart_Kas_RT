<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Smart Kas RT</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col lg:flex-row" style="background:#f0f4ff">

    {{-- Kiri/Atas: Panel dekoratif --}}
    <div class="flex w-full lg:w-1/2 relative overflow-hidden flex-col items-center justify-center py-10 lg:py-0"
        style="background: linear-gradient(135deg, #1E3A8A 0%, #0D9488 100%)">
        {{-- Dekorasi lingkaran --}}
        <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full" style="background:rgba(255,255,255,0.06)"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 rounded-full" style="background:rgba(255,255,255,0.06)"></div>
        <div class="absolute top-1/4 right-8 w-32 h-32 rounded-full" style="background:rgba(255,255,255,0.04)"></div>
        {{-- Logo --}}
        <div class="relative z-10 flex items-center justify-center">
            <div class="w-72 h-72 lg:w-[450px] lg:h-[450px] rounded-full overflow-hidden flex items-center justify-center bg-white/10 backdrop-blur-sm relative">
                <img src="{{ asset('images/logo.png') }}" alt="Smart Kas RT Logo"
                    class="relative z-10 w-full h-full object-contain filter drop-shadow-[0_0_20px_rgba(255,255,255,0.6)]"
                    onerror="this.style.opacity='0'">
            </div>
        </div>
        {{-- Teks --}}
        <div class="relative z-10 text-center px-8 mt-4 lg:mt-0">
            <h1 class="text-2xl lg:text-3xl font-bold text-white">Smart Kas RT</h1>
            <p class="text-xs lg:text-sm mt-1" style="color:rgba(255,255,255,0.75)">Sistem Manajemen Keuangan RT Modern</p>
            <div class="mx-auto mt-3 h-0.5 w-16 rounded-full bg-white opacity-30"></div>
        </div>
    </div>

    {{-- Kanan: Form Login --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">

            <h2 class="text-3xl font-bold text-gray-800 mb-1">Masuk</h2>
            <p class="text-gray-500 mb-8 text-sm">Selamat datang kembali! Silakan masuk ke akun Anda.</p>

            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            placeholder="nama@email.com" required autofocus
                            class="w-full pl-10 pr-4 py-3 border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                    </div>
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input id="password" type="password" name="password"
                            placeholder="••••••••" required
                            class="w-full pl-10 pr-12 py-3 border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <button type="button" onclick="togglePwd('password','eyeIcon')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Remember & Lupa --}}
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="hover:underline" style="color:#0D9488">Lupa kata sandi?</a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full py-3 font-semibold rounded-lg text-sm text-white transition-colors duration-200"
                    style="background: linear-gradient(135deg, #1E3A8A 0%, #0D9488 100%)">
                    Masuk
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400">atau masuk dengan</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Google Firebase --}}
            <button type="button" onclick="loginWithGoogle(event)"
                class="w-full flex items-center justify-center gap-3 py-3 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-sm text-gray-700 font-medium transition-colors duration-200">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Masuk dengan Google
            </button>

            <div id="googleError" class="hidden mt-2 p-3 bg-red-100 text-red-700 rounded-lg text-xs"></div>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color:#0D9488">Daftar</a>
            </p>

            {{-- Demo Box --}}
            <div class="mt-6 p-4 rounded-xl text-sm" style="background:#eff6ff; border:1px solid #bfdbfe">
                <p class="font-semibold text-gray-700 mb-2">Demo Login:</p>
                <div class="space-y-1 text-gray-600">
                    <p>Admin: <span class="font-mono" style="color:#1E3A8A">admin@rt.com</span> / <span class="font-mono" style="color:#1E3A8A">admin</span></p>
                    <p>Warga: <span class="font-mono" style="color:#0D9488">warga@rt.com</span> / <span class="font-mono" style="color:#0D9488">warga</span></p>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePwd(id, iconId) {
            const input = document.getElementById(id);
            const icon = document.getElementById(iconId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    </script>

    {{-- Firebase SDK --}}
    <script type="module">
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js';
        import { getAuth, signInWithPopup, signInWithRedirect, getRedirectResult, GoogleAuthProvider } from 'https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js';

        const firebaseConfig = {
            apiKey:            "{{ config('services.firebase.api_key') }}",
            authDomain:        "{{ config('services.firebase.auth_domain') }}",
            projectId:         "{{ config('services.firebase.project_id') }}",
            storageBucket:     "{{ config('services.firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
            appId:             "{{ config('services.firebase.app_id') }}",
        };

        const app      = initializeApp(firebaseConfig);
        const auth     = getAuth(app);
        const provider = new GoogleAuthProvider();

        // Cek hasil redirect saat halaman dimuat (untuk mobile)
        window.addEventListener('load', async () => {
            try {
                const result = await getRedirectResult(auth);
                if (result) {
                    const idToken = await result.user.getIdToken();
                    handleFirebaseLogin(idToken);
                }
            } catch (e) {
                console.error("Redirect error:", e);
                const errEl = document.getElementById('googleError');
                if (errEl) {
                    errEl.textContent = 'Login Gagal: ' + e.message;
                    errEl.classList.remove('hidden');
                }
            }
        });

        async function handleFirebaseLogin(idToken) {
            const errEl = document.getElementById('googleError');
            try {
                const res = await fetch('{{ route("auth.firebase", [], false) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id_token: idToken })
                });

                const data = await res.json();
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    errEl.textContent = data.error || 'Login gagal.';
                    errEl.classList.remove('hidden');
                }
            } catch (e) {
                errEl.textContent = 'Terjadi kesalahan sistem: ' + e.message;
                errEl.classList.remove('hidden');
            }
        }

        function isSessionStorageAvailable() {
            try {
                const key = '__storage_test__';
                window.sessionStorage.setItem(key, key);
                window.sessionStorage.removeItem(key);
                return true;
            } catch (e) {
                return false;
            }
        }

        window.loginWithGoogle = function(event) {
            const errEl = document.getElementById('googleError');
            if (errEl) errEl.classList.add('hidden');
            
            // Ambil elemen tombol secara aman
            const btn = (event && event.currentTarget) ? event.currentTarget : document.querySelector('button[onclick*="loginWithGoogle"]');
            const originalContent = btn.innerHTML;
            
            // CATATAN PENTING: Jangan gunakan 'await' sebelum signInWithPopup
            // agar browser menganggapnya sebagai "Direct User Gesture" dan tidak memblokir popup.
            signInWithPopup(auth, provider)
                .then(async (result) => {
                    // Popup berhasil dibuka dan user sudah login
                    btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memverifikasi...';
                    btn.disabled = true;

                    try {
                        const idToken = await result.user.getIdToken();
                        await handleFirebaseLogin(idToken);
                    } catch (e) {
                        btn.innerHTML = originalContent;
                        btn.disabled = false;
                        if (errEl) {
                            errEl.textContent = 'Gagal memproses data: ' + e.message;
                            errEl.classList.remove('hidden');
                        }
                    }
                })
                .catch((error) => {
                    // Reset tombol jika gagal atau dibatalkan
                    btn.innerHTML = originalContent;
                    btn.disabled = false;

                    if (error.code === 'auth/popup-blocked') {
                        if (errEl) {
                            errEl.innerHTML = '<b>Popup Diblokir!</b> Harap izinkan popup pada browser Anda atau klik kembali tombol untuk mencoba lagi.';
                            errEl.classList.remove('hidden');
                        }
                    } else if (error.code !== 'auth/cancelled-popup-request' && error.code !== 'auth/popup-closed-by-user') {
                        if (errEl) {
                            errEl.textContent = 'Login Gagal: ' + error.message;
                            errEl.classList.remove('hidden');
                        }
                    }
                });
        };
    </script>
</body>
</html>
