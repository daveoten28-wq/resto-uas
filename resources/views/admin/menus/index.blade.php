@extends('layouts.app')

@section('title', 'Kelola menu')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-700">Administrator</p>
                <h1 class="mt-2 text-3xl font-black text-navy-950">Menu restoran</h1>
                <div class="mt-4 flex gap-4 text-sm font-bold">
                    <a href="{{ route('admin.categories.index') }}" class="text-slate-500 hover:text-navy-950">Kategori</a>
                    <a href="{{ route('admin.menus.index') }}" class="text-emerald-800">Menu</a>
                </div>
            </div>
            <a href="{{ route('admin.menus.create') }}" class="rounded-xl bg-navy-950 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800">+ Tambah menu</a>
        </div>

        <div class="mt-7 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[780px] text-left text-sm">
                    <thead class="bg-navy-950 text-xs uppercase tracking-wider text-slate-300">
                        <tr><th class="px-5 py-4">Menu</th><th class="px-5 py-4">Kategori</th><th class="px-5 py-4">Harga</th><th class="px-5 py-4">Ketersediaan</th><th class="px-5 py-4 text-right">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($menus as $menu)
                            <tr>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $menu->image ? (filter_var($menu->image, FILTER_VALIDATE_URL) ? $menu->image : asset('storage/'.$menu->image)) : asset('images/menu-placeholder.svg') }}" alt="" class="h-12 w-14 rounded-lg object-cover">
                                        <div><p class="font-bold text-navy-950">{{ $menu->name }}</p><p class="mt-1 font-mono text-[11px] text-slate-400">{{ $menu->slug }}</p></div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $menu->category->name_en ?: $menu->category->name }}</td>
                                <td class="px-5 py-4 font-bold text-navy-950">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                <td class="px-5 py-4"><span class="rounded-full px-3 py-1 text-xs font-bold {{ $menu->is_available ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">{{ $menu->is_available ? 'Tersedia' : 'Nonaktif' }}</span></td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.menus.edit', $menu) }}" class="font-bold text-emerald-800 hover:underline">Edit</a>
                                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="font-bold text-rose-600 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">Belum ada menu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5">{{ $menus->links() }}</div>
    </section>
@endsection
