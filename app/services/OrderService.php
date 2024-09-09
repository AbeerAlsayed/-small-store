<?php
namespace App\services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderService {
    public function processOrder($request, $userId) {
        return DB::transaction(function () use ($request, $userId) {
            $product = Product::findOrFail($request->product_id);
            $order = Order::where('order_number', $request->order_number)->first();

            if ($order) {
                $originalQuantity = $order->quantity;
                $order->quantity += $request->quantity;
                $order->grand_total = $order->quantity * $product->price;
                $order->save();

                $quantityChange = $originalQuantity - $order->quantity;
                $product->incrementQuantity($quantityChange);
            } else {
                if ($product->quantity >= $request->quantity) {
                    $order = Order::create([
                        'order_number' => $request->order_number,
                        'user_id' => $userId,
                        'status' => $request->status,
                        'grand_total' => $request->quantity * $product->price,
                        'quantity' => $request->quantity,
                        'is_paid' => $request->is_paid,
                        'payment_method' => $request->payment_method,
                        'notes' => $request->notes,
                    ]);
                    $product->decrementQuantity($request->quantity);
                } else {
                    return new JsonResponse(['success' => false, 'message' => 'Not enough product quantity'], 400);
                }
            }

            return $order;
        });
    }

    public function updateOrder($request, $id) {
        return DB::transaction(function () use ($request, $id) {
            $order = Order::findOrFail($id);
            if ($order->status !== 'pending') {
                return new JsonResponse(['success' => false, 'message' => 'Only pending orders can be updated'], 403);
            }

            $originalQuantity = $order->quantity;
            $order->update($request->all());

            $quantityChange = $originalQuantity - $order->quantity;
            $product = Product::findOrFail($order->product_id);
            $product->incrementQuantity($quantityChange);

            return $order;
        });
    }

    public function deleteOrder($id) {
        return DB::transaction(function () use ($id) {
            $order = Order::findOrFail($id);
            if ($order->status !== 'pending') {
                return new JsonResponse(['success' => false, 'message' => 'Only pending orders can be deleted'], 403);
            }

            $quantityChange = $order->quantity;
            $order->delete();

            $product = Product::findOrFail($order->product_id);
            $product->incrementQuantity($quantityChange);

            return true;
        });
    }
}
