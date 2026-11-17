<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $menus = Menu::whereIn('id', array_keys($cart))
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(function ($quantity, $menuId) use ($menus) {
                $menu = $menus->get($menuId);

                if (! $menu) {
                    return null;
                }

                return [
                    'menu' => $menu,
                    'quantity' => (int) $quantity,
                    'subtotal' => $menu->price * (int) $quantity,
                ];
            })
            ->filter()
            ->values();

        $total = $items->sum('subtotal');

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Menu $menu)
    {
        abort_unless($menu->is_available, 404);

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $cart = session('cart', []);
        $cart[$menu->id] = min(20, ($cart[$menu->id] ?? 0) + ($validated['quantity'] ?? 1));
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', $menu->name.' ditambahkan ke keranjang.');
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        abort_unless($menu->is_available, 404);

        $cart = session('cart', []);
        abort_unless(array_key_exists($menu->id, $cart), 404);

        $cart[$menu->id] = $validated['quantity'];
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Jumlah pesanan diperbarui.');
    }

    public function remove(Menu $menu)
    {
        $cart = session('cart', []);
        unset($cart[$menu->id]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Menu dihapus dari keranjang.');
    }
}
