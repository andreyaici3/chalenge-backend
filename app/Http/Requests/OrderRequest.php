<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        $rule = [
            "order_date" => "required|date",
            "pickup_date" => "required|date|after_or_equal:order_date",
            "dropoff_date" => "required|date|after:pickup_date",
            "pickup_location" => "required|string",
            "dropoff_location" => "required|string",
        ];

        if ($this->isMethod("PUT")) {
            $rule["car_id"] = "required|exists:cars,id";
        }

        return $rule;
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => "error",
            'message' => 'Validasi gagal.',
            'errCode' => "VALIDATION_ERROR",
            'errors' => $validator->errors(),
        ], 422));
    }
}
