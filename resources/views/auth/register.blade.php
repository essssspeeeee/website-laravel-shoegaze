<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-6">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-sky-200/20 blur-3xl"></div>
        <div class="absolute right-6 bottom-20 h-40 w-40 rotate-12 rounded-[28px] bg-indigo-200/15 blur-2xl"></div>
    </div>

    <div class="relative w-full max-w-lg rounded-[32px] border border-slate-200/80 bg-white/85 backdrop-blur-xl shadow-[0_32px_80px_rgba(15,23,42,0.12)] p-10">
        <h2 class="text-center text-3xl font-bold tracking-[0.12em] text-slate-900 mb-8">REGISTER</h2>

        <form method="POST" action="/register" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Nama</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </span>
                    <input type="text" name="name" placeholder="Masukkan Nama"
                           class="w-full rounded-3xl border-2 border-slate-200 bg-white/95 py-3 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition duration-200 ease-in-out hover:border-sky-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                           required>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Username</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </span>
                    <input type="text" name="username" placeholder="Masukkan Username"
                           class="w-full rounded-3xl border-2 border-slate-200 bg-white/95 py-3 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition duration-200 ease-in-out hover:border-sky-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                           required>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 7h16M4 7l8 6 8-6M4 7v10h16V7" />
                        </svg>
                    </span>
                    <input type="email" name="email" placeholder="Masukkan Email"
                           class="w-full rounded-3xl border-2 border-slate-200 bg-white/95 py-3 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition duration-200 ease-in-out hover:border-sky-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                           required>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="11" width="16" height="10" rx="2" />
                            <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                        </svg>
                    </span>
                    <input type="password" name="password" placeholder="Masukkan Password"
                           class="w-full rounded-3xl border-2 border-slate-200 bg-white/95 py-3 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition duration-200 ease-in-out hover:border-sky-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                           required>
                </div>
            </div>

            <button type="submit"
                    class="w-full rounded-3xl bg-gradient-to-r from-sky-500 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_rgba(59,130,246,0.18)] transition duration-200 ease-out hover:-translate-y-0.5 hover:shadow-[0_22px_36px_rgba(59,130,246,0.22)] focus:outline-none focus:ring-4 focus:ring-sky-100">
                Daftar
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">Sudah punya akun? <a href="/login" class="font-semibold text-sky-600 hover:text-sky-700">Login</a></p>
    </div>
</body>
</html>