@extends('layouts.app')

@section('title', 'Simulasi pembayaran')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl sm:p-10">
            <div class="text-center">
                <span class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-amber-100 text-3xl">◷</span>
                <p class="mt-5 text-xs font-bold uppercase tracking-[.2em] text-amber-700">Simulasi pembayaran</p>
                <h1 class="mt-2 text-3xl font-black text-navy-950">Selesaikan pesananmu</h1>
                <p class="mt-3 text-sm leading-6 text-slate-500">Ini adalah halaman demo. Tidak ada uang sungguhan yang akan ditagihkan.</p>
            </div>
            <div class="mt-8 rounded-2xl bg-navy-950 p-6 text-white">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-400">Nomor pesanan</p>
                        <p class="mt-1 font-mono text-sm font-bold">{{ strtoupper(substr(str_replace('-', '', $order->public_id), 0, 10)) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold text-slate-400">Total</p>
                        <p class="mt-1 text-xl font-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="mt-5 border-t border-white/15 pt-4 text-sm text-slate-300">
                    <p>{{ $order->customer_name }} · Meja #{{ $order->table_number }}</p>
                    <p class="mt-1 text-slate-400">{{ $order->customer_email }}</p>
                </div>
            </div>
            <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-center">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-800">Batas waktu pembayaran</p>
                <p id="payment-countdown" data-expires="{{ $order->payment_expires_at?->toIso8601String() }}" class="mt-1 font-mono text-2xl font-black text-amber-950">15:00</p>
            </div>
            <div class="mt-7 grid gap-3 sm:grid-cols-2">
                <form action="{{ route('payment.succeed', $order) }}" method="POST">
                    @csrf
                    <button class="w-full rounded-xl bg-emerald-700 px-5 py-4 text-sm font-extrabold text-white transition hover:bg-emerald-800">Simulasikan pembayaran berhasil</button>
                </form>
                <form action="{{ route('payment.cancel', $order) }}" method="POST">
                    @csrf
                    <button class="w-full rounded-xl border border-slate-300 bg-white px-5 py-4 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Batalkan pembayaran</button>
                </form>
            </div>
            <p class="mt-5 text-center text-xs text-slate-400">Pesanan akan kedaluwarsa otomatis jika tidak diselesaikan dalam 15 menit.</p>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const countdown = document.getElementById('payment-countdown');
        const expiresAt = countdown?.dataset.expires ? new Date(countdown.dataset.expires).getTime() : null;
        const updateCountdown = () => {
            if (!countdown || !expiresAt) return;
            const remaining = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
            countdown.textContent = `${String(Math.floor(remaining / 60)).padStart(2, '0')}:${String(remaining % 60).padStart(2, '0')}`;
            if (remaining === 0) window.location.reload();
        };
        updateCountdown();
        window.setInterval(updateCountdown, 1000);
    </script>
@endpush
