<?php

namespace App\Http\Controllers;

use App\Models\Order;

class ReceiptController extends Controller
{
    public function show(Order $order)
    {
        if ($order->payment_status !== 'successful') {
            return redirect()->route('payment.show', $order);
        }

        $order->load('items');

        return view('receipt.show', compact('order'));
    }
}
