<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        $this->expireIfNeeded($order);

        if ($order->payment_status === 'successful') {
            return redirect()->route('receipt.show', $order);
        }

        if ($order->payment_status !== 'pending') {
            return view('payment.failed', compact('order'));
        }

        return view('payment.show', compact('order'));
    }

    public function succeed(Order $order)
    {
        $this->expireIfNeeded($order);

        if ($order->payment_status === 'pending') {
            $order->update(['payment_status' => 'successful']);
        }

        return $order->payment_status === 'successful'
            ? redirect()->route('receipt.show', $order)->with('success', 'Pembayaran berhasil.')
            : redirect()->route('payment.failed', $order);
    }

    public function cancel(Order $order)
    {
        $this->expireIfNeeded($order);

        if ($order->payment_status === 'pending') {
            $order->update(['payment_status' => 'cancelled']);
        }

        return redirect()->route('payment.failed', $order);
    }

    public function failed(Order $order)
    {
        $this->expireIfNeeded($order);

        if ($order->payment_status === 'successful') {
            return redirect()->route('receipt.show', $order);
        }

        return view('payment.failed', compact('order'));
    }

    private function expireIfNeeded(Order $order): void
    {
        if (
            $order->payment_status === 'pending'
            && $order->payment_expires_at
            && $order->payment_expires_at->isPast()
        ) {
            $order->update(['payment_status' => 'expired']);
        }
    }
}
