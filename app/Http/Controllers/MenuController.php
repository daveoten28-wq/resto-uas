<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $locale = session('locale', 'id');
        $categories = Category::withCount('menus')->orderBy('name')->get();

        $menus = Menu::with('category')
            ->where('is_available', true)
            ->when($request->filled('kategori'), function ($query) use ($request) {
                $query->whereHas('category', function ($categoryQuery) use ($request) {
                    $categoryQuery->where('slug', (string) $request->input('kategori'));
                });
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('menu.index', compact('categories', 'menus', 'locale'));
    }

    public function setLocale(Request $request)
    {
        $validated = $request->validate([
            'locale' => ['required', 'in:id,en'],
        ]);

        $request->session()->put('locale', $validated['locale']);

        return back();
    }
}
