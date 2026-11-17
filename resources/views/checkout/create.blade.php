@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('cart.index') }}" class="text-sm font-bold text-emerald-800 hover:underline">← Kembali ke keranjang</a>
        <div class="mt-5">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-700">Satu langkah lagi</p>
            <h1 class="mt-2 text-3xl font-black text-navy-950">Detail pesanan</h1>
        </div>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                <p class="font-bold">Periksa kembali data berikut:</p>
                <ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
            <form action="{{ route('checkout.store') }}" method="POST" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                @csrf
                <h2 class="text-lg font-extrabold text-navy-950">Informasi pemesan</h2>
                <p class="mt-1 text-sm text-slate-500">Kami gunakan detail ini untuk mencatat pesananmu.</p>
                <div class="mt-6 space-y-5">
                    <div>
                        <label for="customer_name" class="mb-2 block text-sm font-bold text-slate-700">Nama lengkap</label>
                        <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" autocomplete="name" required maxlength="255" class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-600 focus:ring-emerald-600" placeholder="Contoh: Naya Putri">
                        @error('customer_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="customer_email" class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                        <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}" autocomplete="email" required maxlength="255" class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-600 focus:ring-emerald-600" placeholder="nama@email.com">
                        @error('customer_email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="table_number" class="mb-2 block text-sm font-bold text-slate-700">Nomor meja</label>
                        <select id="table_number" name="table_number" required class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-600 focus:ring-emerald-600">
                            <option value="">Pilih meja #1–#30</option>
                            @for ($number = 1; $number <= 30; $number++)
                                <option value="{{ $number }}" @selected(old('table_number') == $number)>Meja #{{ $number }}</option>
                            @endfor
                        </select>
                        @error('table_number')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button type="submit" class="mt-8 w-full rounded-xl bg-navy-950 px-5 py-4 text-sm font-extrabold text-white transition hover:bg-emerald-800">Buat pesanan & lanjut bayar</button>
                <p class="mt-3 text-center text-xs leading-5 text-slate-500">Simulasi pembayaran berlaku selama 15 menit. Tidak ada transaksi uang sungguhan.</p>
            </form>

            <aside class="h-fit rounded-3xl bg-navy-950 p-6 text-white shadow-xl">
                <h2 class="text-lg font-extrabold">Pesananmu</h2>
                <div class="mt-5 space-y-4">
                    @foreach ($items as $item)
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-slate-300">{{ $item['menu']->name }} <span class="text-slate-500">× {{ $item['quantity'] }}</span></span>
                            <span class="shrink-0 font-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 flex justify-between border-t border-white/15 pt-5">
                    <span class="text-sm text-slate-300">Total pembayaran</span>
                    <strong class="text-lg">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                </div>
            </aside>
        </div>
    </section>
@endsection
