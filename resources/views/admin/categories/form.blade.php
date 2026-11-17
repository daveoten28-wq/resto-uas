@if ($errors->any())
    <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
        <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif
<form action="{{ $action }}" method="POST" class="mt-7 space-y-5">
    @csrf
    @if ($editing) @method('PUT') @endif
    <div>
        <label for="name_id" class="mb-2 block text-sm font-bold text-slate-700">Nama kategori (Indonesia)</label>
        <input id="name_id" name="name_id" required maxlength="255" value="{{ old('name_id', $category->name_id ?: $category->name) }}" class="w-full rounded-xl border-slate-300 px-4 py-3">
    </div>
    <div>
        <label for="name_en" class="mb-2 block text-sm font-bold text-slate-700">Category name (English)</label>
        <input id="name_en" name="name_en" required maxlength="255" value="{{ old('name_en', $category->name_en ?: $category->name) }}" class="w-full rounded-xl border-slate-300 px-4 py-3">
    </div>
    <div>
        <label for="slug" class="mb-2 block text-sm font-bold text-slate-700">Slug <span class="font-normal text-slate-400">(opsional, otomatis dari nama Inggris)</span></label>
        <input id="slug" name="slug" maxlength="255" value="{{ old('slug', $category->slug) }}" class="w-full rounded-xl border-slate-300 px-4 py-3">
    </div>
    <button class="w-full rounded-xl bg-navy-950 px-5 py-3.5 text-sm font-extrabold text-white hover:bg-emerald-800">{{ $editing ? 'Simpan perubahan' : 'Buat kategori' }}</button>
</form>
