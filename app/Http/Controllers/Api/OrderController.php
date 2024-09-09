<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController as BaseController;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderController extends BaseController
{
    protected $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $userId = auth()->id(); // Assuming you are using authentication
        if ($status) {
            $orders = Order::where('user_id', $userId)->where('status', $status)->get();
        } else {
            $orders = Order::where('user_id', $userId)->get()->groupBy('status');
        }
        return $this->sendResponse($orders, 'Order retrieved successfully.');
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        if (!$order) {
            return $this->sendError('Order not found.');
        }
        return $this->sendResponse($order, 'Order retrieved successfully.');
    }


    public function store(OrderRequest $request)
    {
        $userId = auth()->id();
        $order = $this->orderService->processOrder($request, $userId);

        if ($order instanceof \Illuminate\Http\JsonResponse) {
            return $order;
        }

        return $this->sendResponse($order, 'Order processed successfully.');
    }


    public function update(OrderRequest $request, $id)
    {
        $order = $this->orderService->updateOrder($request, $id);

        if ($order instanceof \Illuminate\Http\JsonResponse) {
            return $order;
        }

        return $this->sendResponse($order, 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $response = $this->orderService->deleteOrder($id);

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response;
        }

        return $this->sendResponse([], 'Order deleted successfully.');
    }

}
