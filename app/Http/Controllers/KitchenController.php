<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KitchenController extends Controller
{
    private const STATUSES = [
        'received',
        'cooking',
        'ready',
        'completed',
    ];

    public function index()
    {
        $orders = Order::with('items')
            ->where('payment_status', 'successful')
            ->latest()
            ->get();

        return view('kitchen.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        abort_unless($order->payment_status === 'successful', 404);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(self::STATUSES)],
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('kitchen.index')->with('success', 'Status dapur berhasil diperbarui.');
    }
}
