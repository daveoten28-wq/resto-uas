@extends('layouts.app')

@section('title', 'Tambah menu')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('admin.menus.index') }}" class="text-sm font-bold text-emerald-800 hover:underline">← Kembali ke menu</a>
        <div class="mt-5 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-700">Menu baru</p>
            <h1 class="mt-2 text-3xl font-black text-navy-950">Tambah menu</h1>
            @include('admin.menus.form', ['action' => route('admin.menus.store'), 'editing' => false])
        </div>
    </section>
@endsection
