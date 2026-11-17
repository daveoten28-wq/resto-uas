@extends('layouts.app')

@section('title', 'Kelola kategori')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-700">Administrator</p>
                <h1 class="mt-2 text-3xl font-black text-navy-950">Kategori menu</h1>
                <div class="mt-4 flex gap-4 text-sm font-bold">
                    <a href="{{ route('admin.categories.index') }}" class="text-emerald-800">Kategori</a>
                    <a href="{{ route('admin.menus.index') }}" class="text-slate-500 hover:text-navy-950">Menu</a>
                </div>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="rounded-xl bg-navy-950 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800">+ Tambah kategori</a>
        </div>

        <div class="mt-7 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="bg-navy-950 text-xs uppercase tracking-wider text-slate-300">
                        <tr><th class="px-5 py-4">Nama Indonesia</th><th class="px-5 py-4">English name</th><th class="px-5 py-4">Slug</th><th class="px-5 py-4">Menu</th><th class="px-5 py-4 text-right">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-5 py-4 font-bold text-navy-950">{{ $category->name_id ?: $category->name }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $category->name_en ?: $category->name }}</td>
                                <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $category->slug }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $category->menus_count }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="font-bold text-emerald-800 hover:underline">Edit</a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini beserta semua menu di dalamnya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="font-bold text-rose-600 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">Belum ada kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5">{{ $categories->links() }}</div>
    </section>
@endsection
