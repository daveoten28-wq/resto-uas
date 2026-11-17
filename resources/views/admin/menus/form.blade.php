@if ($errors->any())
    <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
        <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif
<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="mt-7 space-y-5">
    @csrf
    @if ($editing) @method('PUT') @endif
    <div>
        <label for="name" class="mb-2 block text-sm font-bold text-slate-700">Nama menu</label>
        <input id="name" name="name" required maxlength="255" value="{{ old('name', $menu->name) }}" class="w-full rounded-xl border-slate-300 px-4 py-3">
    </div>
    <div>
        <label for="category_id" class="mb-2 block text-sm font-bold text-slate-700">Kategori</label>
        <select id="category_id" name="category_id" required class="w-full rounded-xl border-slate-300 px-4 py-3">
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $menu->category_id) == $category->id)>{{ $category->name_id ?: $category->name }} / {{ $category->name_en ?: $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="description_id" class="mb-2 block text-sm font-bold text-slate-700">Deskripsi (Indonesia)</label>
        <textarea id="description_id" name="description_id" required rows="3" class="w-full rounded-xl border-slate-300 px-4 py-3">{{ old('description_id', $menu->description_id ?: $menu->description) }}</textarea>
    </div>
    <div>
        <label for="description_en" class="mb-2 block text-sm font-bold text-slate-700">Description (English)</label>
        <textarea id="description_en" name="description_en" required rows="3" class="w-full rounded-xl border-slate-300 px-4 py-3">{{ old('description_en', $menu->description_en) }}</textarea>
    </div>
    <div>
        <label for="price" class="mb-2 block text-sm font-bold text-slate-700">Harga (Rp20.000–Rp80.000)</label>
        <input id="price" name="price" type="number" min="20000" max="80000" step="1000" required value="{{ old('price', $menu->price) }}" class="w-full rounded-xl border-slate-300 px-4 py-3">
    </div>
    <div>
        <label for="image" class="mb-2 block text-sm font-bold text-slate-700">Foto menu</label>
        @if ($editing && $menu->image)
            <img
                src="{{ filter_var($menu->image, FILTER_VALIDATE_URL) ? $menu->image : asset('storage/'.$menu->image) }}"
                alt="Foto {{ $menu->name }}"
                class="mb-3 h-40 w-56 rounded-xl object-cover"
            >
        @endif
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" @required(! $editing) class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-navy-950 file:px-4 file:py-2 file:font-bold file:text-white">
        <p class="mt-2 text-xs text-slate-500">Format JPG, PNG, atau WEBP. Maksimal 5 MB. Kosongkan saat edit untuk mempertahankan foto saat ini.</p>
        @error('image')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <label class="flex items-center gap-3 text-sm font-bold text-slate-700">
        <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $menu->exists ? $menu->is_available : true)) class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
        Tampilkan sebagai menu tersedia
    </label>
    <button class="w-full rounded-xl bg-navy-950 px-5 py-3.5 text-sm font-extrabold text-white hover:bg-emerald-800">{{ $editing ? 'Simpan perubahan' : 'Buat menu' }}</button>
</form>
