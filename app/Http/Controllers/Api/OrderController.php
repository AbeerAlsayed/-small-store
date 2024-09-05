<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderRequestUpdate;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController as BaseController;


class OrderController extends BaseController
{
    public function get_user(){
        $user=User::all();
        return $this->sendResponse($user, 'User retrieved successfully.');

    }
    public function get_notify(){
        $user=DB::table('notifications')->get();
        return $this->sendResponse($user, 'User retrieved successfully.');

    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $userId = auth()->id(); // Assuming you are using authentication
        if ($status) {
            $orders = Order::where('user_id', $userId)
                ->where('status', $status)
                ->get();
        } else {
            $orders = Order::where('user_id', $userId)
                ->get()
                ->groupBy('status');
        }
        return $this->sendResponse($orders, 'Order retrieved successfully.');
    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->sendError('Order not found.');
        }
        return $this->sendResponse($order, 'Order retrieved successfully.');
    }


    public function store(OrderRequest $request)
    {
        DB::transaction(function () use ($request) {
            $order=Order::where('order_number',$request->order_number)->first();
            if ($order) {
                $order->quantity += $request->quantity;
                $order->grand_total = $request->grand_total;
                $order->save();
            }else{
                $product = Product::findOrFail($request->product_id);
                if ($product->quantity >= $request->quantity) {
                    $order = Order::create([
                        'order_number' => uniqid('ORD-'),
                        'user_id' => $request->user_id,
                        'status' => 'pending',
                        'grand_total' => $request->quantity * $request->price,
                        'quantity' => $request->quantity,
                        'is_paid' => $request->is_paid,
                        'payment_method' => 'cash_on_delivery',
                        'notes' => $request->notes,
                    ]);
                    $product->quantity -= $request->quantity;
                    $product->save();
                } else {
                    return response()->json(['success' => false, 'message' => 'Not enough product quantity'], 400);
                }
            }

            // Notify admin users
            $adminUser = User::where('role', 'admin')->get();
            Notification::send($adminUser, new OrderCreated($order));

            return $this->sendResponse($order, 'Order processed successfully.');
        });
    }


    public function update(OrderRequestUpdate $request, Order $order)
    {
        $order = DB::transaction(function () use ($request, $order) {
            $order->update($request->all());
            return $order;
        });

        return $this->sendResponse($order, 'Order updated successfully.');
    }


    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->delete();
        });
        return $this->sendResponse([], 'Order deleted successfully.');

    }
}
