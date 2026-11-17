@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('menu.index') }}" class="text-sm font-bold text-emerald-800 hover:underline">← Kembali ke menu</a>
        <div class="mt-5 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-700">Pesananmu</p>
                <h1 class="mt-2 text-3xl font-black text-navy-950">Keranjang</h1>
            </div>
            <span class="rounded-full bg-white px-4 py-2 text-sm font-bold text-slate-600 ring-1 ring-slate-200">{{ $items->sum('quantity') }} item</span>
        </div>

        @if ($items->isEmpty())
            <div class="mt-8 rounded-3xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
                <span class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-emerald-50 text-3xl">🛍️</span>
                <h2 class="mt-5 text-xl font-black text-navy-950">Keranjangmu masih kosong</h2>
                <p class="mt-2 text-sm text-slate-500">Yuk, pilih hidangan favorit dari menu kami.</p>
                <a href="{{ route('menu.index') }}" class="mt-6 inline-flex rounded-xl bg-navy-950 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800">Lihat menu</a>
            </div>
        @else
            <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_320px]">
                <div class="space-y-3">
                    @foreach ($items as $item)
                        <article class="flex gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <img src="{{ $item['menu']->image }}" alt="{{ $item['menu']->name }}" class="h-24 w-24 shrink-0 rounded-xl object-cover sm:h-28 sm:w-32">
                            <div class="flex min-w-0 flex-1 flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                <div>
                                    <h2 class="font-extrabold text-navy-950">{{ $item['menu']->name }}</h2>
                                    <p class="mt-1 text-xs text-slate-500">Rp {{ number_format($item['menu']->price, 0, ',', '.') }} / item</p>
                                </div>
                                <div class="flex items-center justify-between gap-4 sm:justify-end">
                                    <form action="{{ route('cart.update', $item['menu']) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="quantity-{{ $item['menu']->id }}" class="sr-only">Jumlah {{ $item['menu']->name }}</label>
                                        <input id="quantity-{{ $item['menu']->id }}" name="quantity" type="number" min="1" max="20" value="{{ $item['quantity'] }}" class="w-16 rounded-lg border-slate-300 text-center text-sm font-bold">
                                        <button class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200">Ubah</button>
                                    </form>
                                    <div class="text-right">
                                        <p class="text-sm font-black text-navy-950">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                        <form action="{{ route('cart.remove', $item['menu']) }}" method="POST" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs font-semibold text-rose-600 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                <aside class="h-fit rounded-3xl bg-navy-950 p-6 text-white shadow-xl">
                    <h2 class="text-lg font-extrabold">Ringkasan pesanan</h2>
                    <div class="mt-5 flex justify-between border-t border-white/15 pt-5 text-sm text-slate-300">
                        <span>Total</span><strong class="text-lg text-white">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    <button type="button" data-open-confirmation class="mt-6 w-full rounded-xl bg-lime-400 px-4 py-3.5 text-sm font-extrabold text-navy-950 transition hover:bg-lime-300">Konfirmasi pesanan</button>
                    <p class="mt-3 text-center text-xs text-slate-400">Total akhir akan dikonfirmasi sebelum pembayaran.</p>
                </aside>
            </div>

            <dialog id="order-confirmation" class="w-[calc(100%-2rem)] max-w-md rounded-3xl border-0 p-0 shadow-2xl">
                <div class="p-7">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-lime-100 text-2xl">✓</span>
                    <h2 class="mt-5 text-2xl font-black text-navy-950">Pesanan sudah sesuai?</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Lanjutkan ke checkout untuk mengisi nama, email, dan nomor meja. Kamu dapat meninjau ulang sebelum simulasi pembayaran.</p>
                    <p class="mt-5 rounded-xl bg-slate-50 px-4 py-3 text-sm font-bold text-navy-950">Total sementara: Rp {{ number_format($total, 0, ',', '.') }}</p>
                    <div class="mt-6 flex gap-3">
                        <button type="button" data-close-confirmation class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Periksa lagi</button>
                        <a href="{{ route('checkout.create') }}" class="flex-1 rounded-xl bg-navy-950 px-4 py-3 text-center text-sm font-bold text-white hover:bg-emerald-800">Lanjut checkout</a>
                    </div>
                </div>
            </dialog>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        const confirmation = document.getElementById('order-confirmation');
        document.querySelector('[data-open-confirmation]')?.addEventListener('click', () => confirmation?.showModal());
        document.querySelector('[data-close-confirmation]')?.addEventListener('click', () => confirmation?.close());
        confirmation?.addEventListener('click', (event) => {
            if (event.target === confirmation) confirmation.close();
        });
    </script>
@endpush
