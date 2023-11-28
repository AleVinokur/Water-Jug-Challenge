<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChallengeRequest extends BaseApiRequest
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
            'bucket_x' => 'required|integer|min:1',
            'bucket_y' => 'required|integer|min:1',
            'measure_z' => 'required|integer|min:1',


        ];
    }


    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'bucket_x.required' => 'The Bucket X field is required.',
            'bucket_x.integer' => 'The Bucket X field must be an integer.',
            'bucket_x.min' => 'The Bucket X field must be at least 1.',
            'bucket_y.required' => 'The Bucket Y field is required.',
            'bucket_y.integer' => 'The Bucket Y field must be an integer.',
            'bucket_y.min' => 'The Bucket Y field must be at least 1.',
            'measure_z.required' => 'The Measurement Z field is required.',
            'measure_z.integer' => 'The Measurement Z field must be an integer.',
            'measure_z.min' => 'The Measurement Z field must be at least 1.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'bucket_x' => 'Bucket X',
            'bucket_y' => 'Bucket Y',
            'measure_z' => 'Measurement Z',
        ];
    }
}
