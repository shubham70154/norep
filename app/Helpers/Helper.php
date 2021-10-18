<?php

namespace App\Helpers;

use Mailgun\Mailgun;

use Hash, Auth, Mail, File, Log, Storage, Setting, DB, Validator;

use App\Admin, App\User, App\ContentCreator, App\StaticPage;

use Carbon\Carbon;

class Helper {

    public static function custom_validator($request, $request_inputs, $custom_errors = []) {

        $validator = Validator::make($request, $request_inputs, $custom_errors);

        if($validator->fails()) {

            $error = implode(',', $validator->messages()->all());

            throw new \Exception($error, 101);

        }
    }
}
