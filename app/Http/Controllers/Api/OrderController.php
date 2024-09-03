<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController as BaseController;


class OrderController extends BaseController
{
    public function get_user(){
        $user=User::all();
        return $this->sendResponse($user, 'User retrieved successfully.');

    }
    public function index(Request $request)
    {
        $status = $request->input('status');
        dd($status);
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

    public function store(Request $request)
    {
        $userId = auth()->id(); // Assuming you are using authentication

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
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $order = Order::where($request->user_id, $userId)->get()->create($request->all());

        if ($order) {
            return $this->sendResponse($order, 'Order created successfully.');
        }else{
            return $this->sendError('error', ['error' =>'Order not created.']);
}

    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->sendError('Order not found.');
        }
        return $this->sendResponse($order, 'Order retrieved successfully.');
    }

    public function update(Request $request, Order $order)
    {
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
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $order->update($validator->validated());

        return $this->sendResponse($order, 'Order updated successfully.');
    }


    public function destroy(Order $order)
    {

        $order->delete();

        return $this->sendResponse([], 'Order deleted successfully.');

    }
}
