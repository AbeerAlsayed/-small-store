<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderRequestUpdate;
use App\Models\Order;
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
        $userId = auth()->id(); // Assuming you are using authentication

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

        $adminUser=User::where('role','admin')->get();

        Notification::send($adminUser,new OrderCreated($order));

        return $this->sendResponse(['order'=>$order,'adminUser'=>$adminUser], 'Order created successfully.');


    }


    public function update(OrderRequestUpdate $request, Order $order)
    {


        $order->update($request->all());

        return $this->sendResponse($order, 'Order updated successfully.');
    }


    public function destroy(Order $order)
    {

        $order->delete();

        return $this->sendResponse([], 'Order deleted successfully.');

    }
}
