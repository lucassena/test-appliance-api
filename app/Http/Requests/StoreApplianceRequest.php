<?php

namespace App\Http\Requests;

use App\Domain\Enums\ApplianceVoltage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreApplianceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'voltage' => ['required', new Enum(ApplianceVoltage::class)],
            'brand_id' => 'required|exists:brands,id',
        ];
    }
}
