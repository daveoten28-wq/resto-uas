@extends('layouts.app')

@section('title', 'Login admin')

@section('content')
    <section class="mx-auto max-w-md px-4 py-16 sm:px-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl sm:p-9">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-700">Akses terbatas</p>
            <h1 class="mt-2 text-3xl font-black text-navy-950">Login dapur</h1>
            <p class="mt-2 text-sm text-slate-500">Masuk dengan akun terdaftar untuk mengelola pesanan.</p>
            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
            @endif
            <form action="{{ route('login.store') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-600 focus:ring-emerald-600">
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-slate-700">Kata sandi</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-emerald-600 focus:ring-emerald-600">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600"> Ingat saya</label>
                <button class="w-full rounded-xl bg-navy-950 px-5 py-3.5 text-sm font-extrabold text-white hover:bg-emerald-800">Masuk ke panel dapur</button>
            </form>
        </div>
    </section>
@endsection
