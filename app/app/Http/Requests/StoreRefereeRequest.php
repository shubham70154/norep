<?php

namespace App\Http\Requests;

use App\Referee;
use Illuminate\Foundation\Http\FormRequest;

class StoreRefereeRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('user_create');
    }

    public function rules()
    {
        return [
            'name'     => [
                'required',
            ],
            'email'    => [
                'required',
            ],
            'phone_number' => [
                'required',
            ],
            'gender' => [
                'required',
            ]
        ];
    }
}
