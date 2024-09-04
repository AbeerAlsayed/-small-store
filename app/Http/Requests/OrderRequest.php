<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules(): array
    {
        return [
            'order_number' => 'required|string|unique:orders',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,processing,completed,declined',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'is_paid' => 'required|boolean',
            'payment_method' => 'required|in:cash_on_delivery,credit_card',
            'notes' => 'nullable|string',
        ];
    }
}
