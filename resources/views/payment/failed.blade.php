@extends('layouts.app')

@section('title', 'Pembayaran tidak selesai')

@section('content')
    @php
        $cancelled = $order->payment_status === 'cancelled';
        $expired = $order->payment_status === 'expired';
    @endphp
    <section class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-8 text-center shadow-xl sm:p-12">
            <span class="mx-auto grid h-20 w-20 place-items-center rounded-full {{ $cancelled ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700' }} text-4xl">{{ $cancelled ? '↩' : '!' }}</span>
            <p class="mt-6 text-xs font-bold uppercase tracking-[.2em] {{ $cancelled ? 'text-amber-700' : 'text-rose-700' }}">Pembayaran {{ $cancelled ? 'dibatalkan' : 'tidak berhasil' }}</p>
            <h1 class="mt-2 text-3xl font-black text-navy-950">{{ $cancelled ? 'Kamu membatalkan pembayaran.' : ($expired ? 'Waktu pembayaran habis.' : 'Pembayaran belum selesai.') }}</h1>
            <p class="mx-auto mt-4 max-w-md text-sm leading-6 text-slate-500">
                {{ $cancelled ? 'Pesanan ini belum diteruskan ke dapur. Kamu bisa kembali ke katalog dan membuat pesanan baru.' : 'Pesanan ini kedaluwarsa dan belum diteruskan ke dapur. Silakan buat pesanan baru jika masih ingin menikmati menu kami.' }}
            </p>
            <div class="mx-auto mt-7 max-w-sm rounded-2xl bg-slate-50 p-4 text-left text-sm">
                <div class="flex justify-between gap-4"><span class="text-slate-500">Status</span><strong class="capitalize text-navy-950">{{ $order->payment_status }}</strong></div>
                <div class="mt-2 flex justify-between gap-4"><span class="text-slate-500">Total</span><strong class="text-navy-950">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></div>
            </div>
            <a href="{{ route('menu.index') }}" class="mt-7 inline-flex rounded-xl bg-navy-950 px-6 py-3.5 text-sm font-bold text-white hover:bg-emerald-800">Kembali ke katalog</a>
        </div>
    </section>
@endsection
