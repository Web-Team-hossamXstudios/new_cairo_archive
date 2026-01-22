<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'land_id' => 'required|exists:lands,id',
            'room_id' => 'nullable|exists:rooms,id',
            'lane_id' => 'nullable|exists:lanes,id',
            'stand_id' => 'nullable|exists:stands,id',
            'rack_id' => 'nullable|exists:racks,id',
            'file_name' => 'nullable|string|max:255',
            'document' => 'required|file|mimes:pdf|max:51200',
            'items_json' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'العميل مطلوب',
            'land_id.required' => 'القطعة مطلوبة',
            'document.required' => 'ملف PDF مطلوب',
            'document.mimes' => 'يجب أن يكون الملف بصيغة PDF',
            'document.max' => 'حجم الملف يجب ألا يتجاوز 50 ميجابايت',
        ];
    }
}
