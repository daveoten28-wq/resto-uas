<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menus')->orderBy('name_en')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create', ['category' => new Category]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? $validated['name_en']);
        $validated['name'] = $validated['name_id'];

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $this->validatedData($request, $category);
        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? $validated['name_en'], $category->id);
        $validated['name'] = $validated['name_id'];

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori dan menu di dalamnya berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name_id')->ignore($category?->id),
                Rule::unique('categories', 'name')->ignore($category?->id),
            ],
            'name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name_en')->ignore($category?->id),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($category?->id),
            ],
        ]);
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $suffix = 2;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
