<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::paginate(15);
            return view('admin.orders.index', compact('orders'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function pendingOrders()
    {
        try {
            $orders = Order::where('order_status',0)->paginate(15);
            return view('admin.orders.pending', compact('orders'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function completeOrders()
    {
        try {
            $orders = Order::where('order_status',1)->paginate(15);
            return view('admin.orders.complete', compact('orders'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $order = Order::find($id);
            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
