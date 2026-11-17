@extends('layouts.app')

@section('title', 'Struk digital')

@push('head')
    <meta http-equiv="refresh" content="10">
@endpush

@section('content')
    @php
        $steps = [
            'received' => 'Pesanan Diterima',
            'cooking' => 'Sedang Dimasak',
            'ready' => 'Siap Disajikan',
            'completed' => 'Pesanan Selesai',
        ];
        $statuses = array_keys($steps);
        $currentStep = array_search($order->status, $statuses, true);
    @endphp
    <section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl">
            <div class="bg-navy-950 px-6 py-8 text-white sm:px-10">
                <div class="flex flex-wrap items-start justify-between gap-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[.2em] text-lime-300">Struk digital</p>
                        <h1 class="mt-2 text-3xl font-black">Terima kasih, {{ $order->customer_name }}!</h1>
                        <p class="mt-2 text-sm text-slate-300">Pembayaran berhasil. Pesananmu sudah masuk ke dapur.</p>
                    </div>
                    <span class="rounded-full border border-emerald-300/30 bg-emerald-300/10 px-4 py-2 text-xs font-bold text-emerald-200">✓ Lunas</span>
                </div>
                <div class="mt-7 grid gap-3 border-t border-white/15 pt-5 text-sm sm:grid-cols-3">
                    <div><p class="text-xs text-slate-400">Kode pesanan</p><p class="mt-1 font-mono font-bold">{{ strtoupper(substr(str_replace('-', '', $order->public_id), 0, 10)) }}</p></div>
                    <div><p class="text-xs text-slate-400">Nomor meja</p><p class="mt-1 font-bold">Meja #{{ $order->table_number }}</p></div>
                    <div><p class="text-xs text-slate-400">Waktu pesan</p><p class="mt-1 font-bold">{{ $order->created_at->format('d M Y, H:i') }}</p></div>
                </div>
            </div>

            <div class="grid gap-8 p-6 sm:p-10 lg:grid-cols-[1.1fr_.9fr]">
                <section>
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-700">Live kitchen update</p>
                            <h2 class="mt-1 text-xl font-black text-navy-950">Status pesanan</h2>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[11px] font-semibold text-slate-500">Diperbarui tiap 10 detik</span>
                    </div>
                    <ol class="relative mt-7 space-y-6 before:absolute before:bottom-4 before:left-[15px] before:top-4 before:w-px before:bg-slate-200">
                        @foreach ($steps as $key => $label)
                            @php $stepIndex = array_search($key, $statuses, true); @endphp
                            <li class="relative flex gap-4">
                                <span class="z-10 grid h-8 w-8 shrink-0 place-items-center rounded-full text-xs font-black {{ $stepIndex <= $currentStep ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-400 ring-1 ring-slate-200' }}">
                                    {{ $stepIndex < $currentStep ? '✓' : $stepIndex + 1 }}
                                </span>
                                <div class="pt-1">
                                    <p class="font-bold {{ $stepIndex <= $currentStep ? 'text-navy-950' : 'text-slate-400' }}">{{ $label }}</p>
                                    @if ($key === $order->status)
                                        <p class="mt-1 text-xs font-medium text-emerald-700">Status saat ini</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </section>

                <aside class="rounded-2xl bg-slate-50 p-5 sm:p-6">
                    <h2 class="font-extrabold text-navy-950">Rincian pesanan</h2>
                    <div class="mt-5 space-y-4">
                        @foreach ($order->items as $item)
                            <div class="flex justify-between gap-4 text-sm">
                                <div><p class="font-bold text-slate-800">{{ $item->menu_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p></div>
                                <strong class="shrink-0 text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-5 flex justify-between border-t border-slate-200 pt-4">
                        <span class="text-sm font-bold text-slate-600">Total dibayar</span>
                        <strong class="text-lg font-black text-navy-950">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                    </div>
                    <p class="mt-5 text-xs leading-5 text-slate-500">Struk ini diperbarui otomatis. Simpan tautan ini untuk melacak pesanan.</p>
                </aside>
            </div>
        </div>
        <div class="mt-5 text-center">
            <a href="{{ route('menu.index') }}" class="text-sm font-bold text-emerald-800 hover:underline">Pesan menu lainnya</a>
        </div>
    </section>
@endsection
