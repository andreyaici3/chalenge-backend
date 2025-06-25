<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CarRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $data = [
            "car_name" => "required|string",
            "day_rate" => "required",
            "month_rate" => "required",
        ];

        if ($this->isMethod("PUT")) {
            $data["image"] = "nullable|image|mimes:png,jpg|max:2048";
        } else {
            $data["image"] = "required|image|mimes:png,jpg|max:2048";
        }
        return $data;
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
