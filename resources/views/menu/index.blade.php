@extends('layouts.app')

@section('title', 'Menu')

@section('content')
    <section class="relative overflow-hidden bg-navy-950 text-white">
        <div class="absolute -right-24 -top-32 h-96 w-96 rounded-full bg-lime-400/10 blur-3xl"></div>
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 md:grid-cols-[1.1fr_.9fr] md:items-center md:py-24 lg:px-8">
            <div class="relative z-10">
                <p class="mb-5 inline-flex items-center gap-2 rounded-full border border-lime-300/25 bg-lime-300/10 px-4 py-2 text-xs font-bold uppercase tracking-[.2em] text-lime-300">
                    <span class="h-2 w-2 rounded-full bg-lime-400"></span> {{ $locale === 'en' ? 'Fresh from our kitchen' : 'Segar dari dapur kami' }}
                </p>
                <h1 class="max-w-2xl text-4xl font-black leading-[1.08] tracking-tight sm:text-6xl">{{ $locale === 'en' ? 'Good food,' : 'Makan enak,' }}<br><span class="text-lime-300">{{ $locale === 'en' ? 'without the wait.' : 'tanpa menunggu.' }}</span></h1>
                <p class="mt-6 max-w-xl text-base leading-7 text-slate-300 sm:text-lg">{{ $locale === 'en' ? 'Choose your favorites, order from your table, and let us prepare them with care.' : 'Pilih menu favoritmu, pesan dari meja, lalu biarkan kami menyiapkannya dengan sepenuh hati.' }}</p>
                <a href="#daftar-menu" class="mt-8 inline-flex items-center gap-2 rounded-full bg-lime-400 px-6 py-3 text-sm font-extrabold text-navy-950 shadow-glow transition hover:-translate-y-0.5 hover:bg-lime-300">{{ $locale === 'en' ? 'Explore menu' : 'Jelajahi menu' }} <span aria-hidden="true">↓</span></a>
            </div>
            <div class="relative">
                <div class="absolute -inset-4 rounded-[2rem] border border-white/10"></div>
                <img src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=1100&q=85" alt="Hidangan segar dari dapur Ruang Rasa" class="relative h-72 w-full rounded-[1.7rem] object-cover shadow-2xl sm:h-96">
                <div class="absolute -bottom-5 left-5 rounded-2xl bg-white px-5 py-4 text-navy-950 shadow-xl sm:left-auto sm:right-5">
                    <span class="block text-xs font-semibold text-slate-500">{{ $locale === 'en' ? 'Made with fresh ingredients' : 'Dibuat dengan bahan segar' }}</span>
                    <span class="mt-1 block font-black">{{ $locale === 'en' ? 'Every day, for you.' : 'Setiap hari, untukmu.' }}</span>
                </div>
            </div>
        </div>
    </section>

    <section id="daftar-menu" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-700">{{ $locale === 'en' ? 'From our kitchen' : 'Dari dapur kami' }}</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight text-navy-950">{{ $locale === 'en' ? 'Our menu' : 'Menu pilihan' }}</h2>
            </div>
            <form action="{{ route('locale.update') }}" method="POST" class="flex items-center gap-2">
                @csrf
                <label for="locale" class="sr-only">{{ $locale === 'en' ? 'Language' : 'Bahasa' }}</label>
                <select id="locale" name="locale" onchange="this.form.submit()" class="rounded-full border-slate-300 bg-white px-4 py-2 text-sm font-bold text-navy-950">
                    <option value="id" @selected($locale === 'id')>Indonesia</option>
                    <option value="en" @selected($locale === 'en')>English</option>
                </select>
            </form>
        </div>

        <div class="mb-8 flex gap-2 overflow-x-auto pb-2">
            <a href="{{ route('menu.index') }}" class="shrink-0 rounded-full px-4 py-2 text-sm font-bold {{ !request('kategori') ? 'bg-navy-950 text-white' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:ring-navy-300' }}">{{ $locale === 'en' ? 'All menu' : 'Semua menu' }}</a>
            @foreach ($categories as $category)
                <a href="{{ route('menu.index', ['kategori' => $category->slug]) }}" class="shrink-0 rounded-full px-4 py-2 text-sm font-bold {{ request('kategori') === $category->slug ? 'bg-navy-950 text-white' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:ring-navy-300' }}">{{ $category->localizedName($locale) }}</a>
            @endforeach
        </div>

        @if ($menus->isEmpty())
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <p class="text-lg font-bold text-navy-950">{{ $locale === 'en' ? 'No menu available in this category yet.' : 'Menu belum tersedia di kategori ini.' }}</p>
                <a href="{{ route('menu.index') }}" class="mt-3 inline-block text-sm font-bold text-emerald-700 hover:underline">{{ $locale === 'en' ? 'View all menu' : 'Lihat semua menu' }}</a>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($menus as $menu)
                    <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="relative overflow-hidden">
                            <img
                                src="{{ $menu->image ? (filter_var($menu->image, FILTER_VALIDATE_URL) ? $menu->image : asset('storage/'.$menu->image)) : asset('images/menu-placeholder.svg') }}"
                                onerror="this.onerror=null;this.src='{{ asset('images/menu-placeholder.svg') }}'"
                                alt="{{ $menu->name }}"
                                loading="lazy"
                                class="h-52 w-full object-cover transition duration-500 group-hover:scale-105"
                            >
                            <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-bold text-navy-900 shadow">{{ $menu->category->localizedName($locale) }}</span>
                        </div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-extrabold text-navy-950">{{ $menu->name }}</h3>
                                <span class="shrink-0 text-sm font-black text-emerald-800">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            </div>
                            <p class="mt-2 min-h-10 text-sm leading-5 text-slate-500">{{ $menu->localizedDescription($locale) }}</p>
                            <form action="{{ route('cart.add', $menu) }}" method="POST" class="mt-5">
                                @csrf
                                <div class="flex gap-2">
                                    <div class="flex items-center rounded-xl border border-slate-300">
                                        <button type="button" data-quantity-minus aria-label="{{ $locale === 'en' ? 'Decrease quantity' : 'Kurangi jumlah' }}" class="grid h-11 w-10 place-items-center text-lg font-bold text-slate-600 hover:text-navy-950">−</button>
                                        <input type="number" name="quantity" min="1" max="20" value="1" aria-label="{{ $locale === 'en' ? 'Quantity' : 'Jumlah' }}" class="h-11 w-12 border-0 p-0 text-center text-sm font-bold focus:ring-0">
                                        <button type="button" data-quantity-plus aria-label="{{ $locale === 'en' ? 'Increase quantity' : 'Tambah jumlah' }}" class="grid h-11 w-10 place-items-center text-lg font-bold text-slate-600 hover:text-navy-950">+</button>
                                    </div>
                                    <button type="submit" class="flex-1 rounded-xl bg-navy-950 px-3 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">{{ $locale === 'en' ? 'Add to cart' : 'Tambah ke keranjang' }}</button>
                                </div>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-8">{{ $menus->links() }}</div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-quantity-minus], [data-quantity-plus]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = button.parentElement.querySelector('input[name="quantity"]');
                const delta = button.hasAttribute('data-quantity-plus') ? 1 : -1;
                input.value = Math.min(Number(input.max), Math.max(Number(input.min), Number(input.value || 1) + delta));
            });
        });
    </script>
@endpush
