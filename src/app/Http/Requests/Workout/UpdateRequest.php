<?php

namespace App\Http\Requests\Workout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'name'        => 'sometimes|string|max:255',
            'image'       => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|string|max:255',
            'cicle'       => 'sometimes|string|max:255',
            'duration'    => 'sometimes|integer|min:1|max:120',
            'status'      => 'sometimes|boolean',
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
