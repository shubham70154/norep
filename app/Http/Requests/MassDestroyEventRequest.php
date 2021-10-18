<?php

namespace App\Http\Requests;

use App\Event;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class MassDestroyEventRequest extends FormRequest
{
    public function authorize()
    {
        return abort_if(Gate::denies('product_delete'), 403, '403 Forbidden') ?? true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:products,id',
        ];
    }
}
