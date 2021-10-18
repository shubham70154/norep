<?php

namespace App\Http\Requests;

use App\Referee;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRefereeRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('user_edit');
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
