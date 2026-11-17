<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function create()
    {
        [$items, $total] = $this->cartItems();

        if ($items->isEmpty()) {
            return redirect()->route('menu.index')->with('error', 'Keranjang masih kosong.');
        }

        return view('checkout.create', compact('items', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'table_number' => ['required', 'integer', 'between:1,30'],
        ]);

        $cart = session('cart', []);
        $menus = Menu::whereIn('id', array_keys($cart))
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        if (count($cart) === 0 || $menus->count() !== count($cart)) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang kosong atau terdapat menu yang sudah tidak tersedia. Periksa kembali keranjang Anda.',
            ]);
        }

        foreach ($cart as $quantity) {
            if (! is_numeric($quantity) || (int) $quantity < 1 || (int) $quantity > 20) {
                throw ValidationException::withMessages([
                    'cart' => 'Jumlah menu di keranjang tidak valid.',
                ]);
            }
        }

        $total = collect($cart)->reduce(function ($sum, $quantity, $menuId) use ($menus) {
            return $sum + ($menus->get($menuId)->price * (int) $quantity);
        }, 0);

        $order = DB::transaction(function () use ($validated, $cart, $menus, $total) {
            $order = Order::create([
                'public_id' => (string) Str::uuid(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => '',
                'customer_email' => $validated['customer_email'],
                'table_number' => $validated['table_number'],
                'status' => 'received',
                'payment_status' => 'pending',
                'payment_expires_at' => now()->addMinutes(15),
                'total_amount' => $total,
                'total_price' => $total,
            ]);

            foreach ($cart as $menuId => $quantity) {
                $menu = $menus->get($menuId);
                $quantity = (int) $quantity;

                $order->items()->create([
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'quantity' => $quantity,
                    'unit_price' => $menu->price,
                    'subtotal' => $menu->price * $quantity,
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('payment.show', $order);
    }

    private function cartItems()
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

        return [$items, $items->sum('subtotal')];
    }
}
