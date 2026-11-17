@extends('layouts.app')

@section('title', 'Panel dapur')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-700">Panel admin</p>
                <h1 class="mt-2 text-3xl font-black text-navy-950">Papan dapur</h1>
                <p class="mt-2 text-sm text-slate-500">Pantau pesanan lunas dan perbarui progres penyajian.</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-100">Keluar</button>
            </form>
        </div>

        @if ($orders->isEmpty())
            <div class="mt-8 rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <span class="text-4xl">🍳</span>
                <h2 class="mt-4 text-lg font-extrabold text-navy-950">Belum ada pesanan yang perlu dimasak</h2>
                <p class="mt-2 text-sm text-slate-500">Pesanan akan muncul di sini setelah pembayaran berhasil.</p>
            </div>
        @else
            <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($orders as $order)
                    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-start justify-between gap-4 bg-navy-950 p-5 text-white">
                            <div>
                                <p class="font-mono text-xs font-bold text-lime-300">#{{ strtoupper(substr(str_replace('-', '', $order->public_id), 0, 10)) }}</p>
                                <h2 class="mt-2 text-lg font-extrabold">{{ $order->customer_name }}</h2>
                                <p class="mt-1 text-xs text-slate-300">Meja #{{ $order->table_number }} · {{ $order->created_at->format('H:i') }}</p>
                            </div>
                            <span class="rounded-full bg-emerald-400/15 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-200">Lunas</span>
                        </div>
                        <div class="p-5">
                            <ul class="space-y-2 border-b border-slate-100 pb-4">
                                @foreach ($order->items as $item)
                                    <li class="flex justify-between gap-3 text-sm"><span class="text-slate-700">{{ $item->menu_name }}</span><strong class="shrink-0 text-navy-950">× {{ $item->quantity }}</strong></li>
                                @endforeach
                            </ul>
                            <div class="mt-4 flex justify-between text-sm"><span class="text-slate-500">Total</span><strong class="text-navy-950">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></div>
                            <form action="{{ route('kitchen.status', $order) }}" method="POST" class="mt-5">
                                @csrf
                                @method('PATCH')
                                <label for="status-{{ $order->id }}" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Status dapur</label>
                                <div class="flex gap-2">
                                    <select id="status-{{ $order->id }}" name="status" class="min-w-0 flex-1 rounded-xl border-slate-300 text-sm font-semibold">
                                        <option value="received" @selected($order->status === 'received')>Pesanan Diterima</option>
                                        <option value="cooking" @selected($order->status === 'cooking')>Sedang Dimasak</option>
                                        <option value="ready" @selected($order->status === 'ready')>Siap Disajikan</option>
                                        <option value="completed" @selected($order->status === 'completed')>Pesanan Selesai</option>
                                    </select>
                                    <button class="rounded-xl bg-navy-950 px-4 text-xs font-bold text-white hover:bg-emerald-800">Simpan</button>
                                </div>
                            </form>
                            <a href="{{ route('receipt.show', $order) }}" target="_blank" rel="noopener" class="mt-4 inline-block text-xs font-bold text-emerald-800 hover:underline">Lihat struk pelanggan ↗</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
