<?php

namespace App\Http\Requests;

use App\Rules\CheckDeliveryStatusRule;
use App\Rules\CheckOrderRule;
use App\Rules\CheckOrderStatusRule;
use App\Rules\CheckPaymentStatusRule;
use App\Rules\CheckTypeOrderRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,_id',
            'channel_id' => 'required|exists:channels,_id',
            'type' => ['required',new CheckTypeOrderRule()],
            'total_price' => 'required|numeric',
            'order_status' => ['required',new CheckOrderStatusRule()],
            'delivery_status' => ['required',new CheckDeliveryStatusRule()],
            'payment_status' => ['required',new CheckPaymentStatusRule()],
            'status' => ['required',new CheckOrderRule()],
            'confirmed_by' =>'required|exists:channels,_id',
            'internal_note' => 'max:500',
            'external_note' => 'max:500',
            'products' => 'required|array|min:1',
            'products.*.has_variants' => 'required|boolean',
            'products.*.product_id' => 'required|exists:products,_id|distinct',
            'products.*.variants' => 'array',
            'products.*.variants.*.product_variant_option_value_id' => 'required|exists:product_variant_option_values,_id'
        ];
    }
}
