<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|string|unique:orders',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,processing,completed,declined',
            'grand_total' => 'required|numeric',
            'item_count' => 'required|integer',
            'is_paid' => 'required|boolean',
            'payment_method' => 'required|in:cash_on_delivery,credit_card',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = Order::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully.',
            'data' => $order,
        ]);
    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'order_number' => 'required|string|unique:orders,order_number,' . $order->id,
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,processing,completed,declined',
            'grand_total' => 'required|numeric',
            'item_count' => 'required|integer',
            'is_paid' => 'required|boolean',
            'payment_method' => 'required|in:cash_on_delivery,credit_card',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $order->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.',
            'data' => $order,
        ]);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully.',
        ]);
    }
}
