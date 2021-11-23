<?php

namespace App\Http\Controllers\Api;

use App\Page;
use App\HelpSupport;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Helpers\Helper;
use DB, Validator, Illuminate\Support\Carbon;

class PagesApiController extends BaseController
{
    public function getPageDetails($title)
    {
        try {
            $pageDetail = Page::where('query_title', $title)->first();
            if ($pageDetail) {
                return $this->sendResponse($pageDetail, 'page detail get successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'Page not found']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>$e->getMessage()]);
        }
    }

    public function saveHelpSupport(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            DB::begintransaction();
            $response = HelpSupport::create($request->all());
            DB::commit();
            if ($response) {
                return $this->sendResponse($response, 'Saved successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'Not saved']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>$e->getMessage()]);
        }
    }

    public function getSupportList()
    {
        try {
            $supportList = HelpSupport::orderBy('created_at', 'DESC')->get();
            if ($supportList) {
                return $this->sendResponse($supportList, 'Support list get successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'List not found']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>$e->getMessage()]);
        }
    }
}
