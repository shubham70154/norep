<?php

namespace App\Http\Controllers\Api;

use App\File;
use App\Page;
use App\HelpSupport;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Helpers\Helper;
use DB, Validator, Storage, Illuminate\Support\Carbon;

class FilesApiController extends BaseController
{
    public function fileUpload(Request $request)
    {
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'norepFiles/'.$fileName;
            // Save File on S3 Bucket.
            Storage::disk('s3')->put($filePath, fopen($file, 'r+'));
            $url = asset(Storage::disk('s3')->url($filePath));

            if ($url) {
                $file = File::create([
                    'url' => $url,
                    'type' => $request->type,
                    'status' => 0
                ]);
            }
            
            return $this->sendResponse(['url' => $url], 'You have successfully uploaded file.');
        }
    }

    public function deleteFile(Request $request)
    {
        if($request->has('url')) {
            $url = $request->url;
            $domain = env('AWS_URL', 'https://norep68cf33f0074c418bab3b13445eed70bf162639-dev.s3.us-east-2.amazonaws.com/');
            $getpath = explode($domain, $url);
            if(count($getpath)==2){
                Log::info('path to delete '.$getpath[1]);
                $response = Storage::disk('s3')->delete(trim($getpath[1]));
                $file = File::where("url", $url)->delete();
                    
                return $this->sendResponse($response, 'You have successfully deleted file.');
            }
        }

    }
}
