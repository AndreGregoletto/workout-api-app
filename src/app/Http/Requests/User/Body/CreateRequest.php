<?php

namespace App\Http\Requests\User\Body;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->status == true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "height"           => 'sometimes|nullable|numeric', 
            "weight"           => 'sometimes|nullable|numeric', 
            "r_biceps"         => 'sometimes|nullable|numeric', 
            "l_biceps"         => 'sometimes|nullable|numeric',
            "r_forearm"        => 'sometimes|nullable|numeric', 
            "l_forearm"        => 'sometimes|nullable|numeric', 
            "chest"            => 'sometimes|nullable|numeric', 
            "waist"            => 'sometimes|nullable|numeric', 
            "pelvic_girdle"    => 'sometimes|nullable|numeric', 
            "r_thigh"          => 'sometimes|nullable|numeric', 
            "l_thigh"          => 'sometimes|nullable|numeric',
            "r_shin"           => 'sometimes|nullable|numeric', 
            "l_shin"           => 'sometimes|nullable|numeric',
            "shoulder_length"  => 'sometimes|nullable|numeric', 
            "measurement_date" => "sometimes|nullable|date_format:Y-m-d",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422)
        );
    }  
}
