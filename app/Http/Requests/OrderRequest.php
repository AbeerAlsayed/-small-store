<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Order $order): array
    {
        return [
            'order_number' =>'required', 'string', Rule::unique('orders')->ignore($order->id),
            'status' => 'required|in:pending,processing,completed,declined',
            'quantity' => 'required|integer',
            'is_paid' => 'required|boolean',
            'payment_method' => 'required|in:cash_on_delivery,credit_card',
            'notes' => 'nullable|string',
        ];
    }
}
