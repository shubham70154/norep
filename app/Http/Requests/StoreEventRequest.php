<?php

namespace App\Http\Requests;

use App\Event;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('product_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'required'
            ],
        ];
    }
}
