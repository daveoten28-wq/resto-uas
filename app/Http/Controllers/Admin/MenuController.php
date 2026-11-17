<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;
use Throwable;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->latest()->paginate(15);

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::orderBy('name_en')->get();

        return view('admin.menus.create', [
            'menu' => new Menu,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? $validated['name']);
        $validated['description'] = $validated['description_id'];
        $imagePath = $request->file('image')?->store('menus', 'public');

        if ($request->hasFile('image') && ! $imagePath) {
            throw new RuntimeException('Foto menu gagal disimpan. Silakan coba lagi.');
        }

        $validated['image'] = $imagePath ?? '';

        try {
            Menu::create($validated);
        } catch (Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::orderBy('name_en')->get();

        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $this->validatedData($request, $menu);
        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? $validated['name'], $menu->id);
        $validated['description'] = $validated['description_id'];
        $oldImage = $menu->image;
        $imagePath = $request->file('image')?->store('menus', 'public');

        if ($request->hasFile('image') && ! $imagePath) {
            throw new RuntimeException('Foto menu gagal disimpan. Silakan coba lagi.');
        }

        if ($imagePath) {
            $validated['image'] = $imagePath;
        } else {
            unset($validated['image']);
        }

        try {
            $menu->update($validated);
        } catch (Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        if ($imagePath) {
            $this->deleteStoredImage($oldImage);
        }

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $image = $menu->image;
        $menu->delete();

        $this->deleteStoredImage($image);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Menu $menu = null): array
    {
        $imageRules = $menu
            ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']
            : ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];

        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('menus', 'slug')->ignore($menu?->id),
            ],
            'description_id' => ['required', 'string', 'max:5000'],
            'description_en' => ['required', 'string', 'max:5000'],
            'price' => ['required', 'integer', 'between:20000,80000'],
            'image' => $imageRules,
            'is_available' => ['nullable', 'boolean'],
        ]);

        return array_merge($validated, [
            'is_available' => $request->boolean('is_available'),
        ]);
    }

    private function isStoredImage(?string $image): bool
    {
        return filled($image)
            && ! filter_var($image, FILTER_VALIDATE_URL)
            && Storage::disk('public')->exists($image);
    }

    private function deleteStoredImage(?string $image): void
    {
        if ($this->isStoredImage($image) && ! Storage::disk('public')->delete($image)) {
            throw new RuntimeException('Foto menu lama gagal dihapus dari penyimpanan.');
        }
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $suffix = 2;

        while (
            Menu::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
