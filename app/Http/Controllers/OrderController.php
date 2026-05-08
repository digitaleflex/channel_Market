<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for the authenticated user (Dashboard).
     */
    public function index()
    {
        $orders = Order::with('product')
            ->where('user_id', Auth::id())
            ->where('status', 'success')
            ->latest()
            ->get();

        return view('dashboard', compact('orders'));
    }

    /**
     * Display a listing of all orders (Admin).
     */
    public function adminIndex()
    {
        $orders = Order::with(['user', 'product'])->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }
}
