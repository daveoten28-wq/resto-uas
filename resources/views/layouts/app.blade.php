<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ruang Rasa') · Ruang Rasa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 950: '#07111f', 900: '#0b1728', 800: '#122238', 700: '#1b304a' },
                        lime: { 400: '#b7f36b', 500: '#9fe34f' }
                    },
                    boxShadow: { glow: '0 20px 70px rgba(159, 227, 79, .13)' }
                }
            }
        };
    </script>
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        dialog::backdrop { background: rgba(3, 10, 20, .76); backdrop-filter: blur(5px); }
    </style>
    @stack('head')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="sticky top-0 z-30 border-b border-white/10 bg-navy-950 text-white shadow-lg shadow-slate-950/10">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('menu.index') }}" class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-2xl bg-lime-400 text-lg font-black text-navy-950">R</span>
                <span>
                    <span class="block text-sm font-bold tracking-wide">RUANG RASA</span>
                    <span class="block text-[10px] uppercase tracking-[.25em] text-slate-400">Kitchen & dining</span>
                </span>
            </a>
            <nav class="flex items-center gap-3 text-sm font-semibold sm:gap-6">
                <a href="{{ route('menu.index') }}" class="hidden text-slate-300 transition hover:text-white sm:inline">Menu</a>
                @auth
                    <a href="{{ route('admin.menus.index') }}" class="hidden text-slate-300 transition hover:text-white md:inline">Admin</a>
                    <a href="{{ route('kitchen.index') }}" class="hidden text-slate-300 transition hover:text-white md:inline">Dapur</a>
                @endauth
                <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 rounded-full border border-white/15 px-4 py-2 transition hover:border-lime-400/60 hover:text-lime-300">
                    {{ session('locale', 'id') === 'en' ? 'Cart' : 'Keranjang' }}
                    <span class="grid min-w-5 place-items-center rounded-full bg-lime-400 px-1.5 py-0.5 text-[11px] font-black text-navy-950">{{ collect(session('cart', []))->sum() }}</span>
                </a>
            </nav>
        </div>
    </header>

    @if (session('success') || session('error'))
        <div class="mx-auto max-w-7xl px-4 pt-5 sm:px-6 lg:px-8">
            <div class="rounded-2xl border px-4 py-3 text-sm font-medium {{ session('success') ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-rose-200 bg-rose-50 text-rose-800' }}">
                {{ session('success') ?? session('error') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="mt-20 border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-8 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <span>© {{ date('Y') }} Ruang Rasa · Dibuat dengan rasa.</span>
            <span>Pesan dengan mudah, nikmati setiap suapan.</span>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
