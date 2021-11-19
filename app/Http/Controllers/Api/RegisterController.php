<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator, DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
   
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile_no' => 'required',
            'password' => 'required',
            'user_type' => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['email'] = strtolower($input['email']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('Norep App')->accessToken;
        $success['user_details'] =  $user;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => strtolower($request->email), 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('Norep App')-> accessToken; 
            $success['user_details'] =  $user;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    /**
     * User Profile update api
     *
     * @return \Illuminate\Http\Response
     */
    public function userProfileUpdate(Request $request, $userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->update($request->all());
            return $this->sendResponse($user, 'User profile updated successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return $this->sendResponse([], 'User logged out successfully.');
    }

    public function getAuthUser($userId)
    {
        $user = User::find($userId);
        if($user) {
            return $this->sendResponse($user, 'User details get successfully.');
        }
        return $this->sendError('Not found.', ['error'=>'User not found!']);
    }

    public function saveDeviceToken(Request $request)
    {
        Log::info('Come: ' . json_encode($request));
        $user = User::where('id', $request->id)->update(['device_token'=>$request->device_token]);
        if ($user) {
            return $this->sendResponse($user, 'Token added successfully.');
        }
        
    }

    public function forgetPassword(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);
            
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $user = User::firstWhere('email' , $request->email);

            if(!$user) {
                return $this->sendError('Not found.', ['error'=>'User not found with is email id!']);
            }


            $token =random_int(100000, 999999); //app('auth.password.broker')->createToken($user);

            \App\PasswordReset::where('email', $user->email)->delete();

            \App\PasswordReset::insert([
                'email'=>$user->email,
                'token'=>$token,
                'created_at'=>Carbon::now()
            ]);

            $email_data['subject'] = "Password Reset NoRep";

            $email_data['email']  = $user->email;

            $email_data['name']  = $user->name;

            $email_data['page'] = "emails.user.forget-password";

            $email_data['url'] = $token;

            $this->dispatch(new \App\Jobs\SendEmailJob($email_data));

            DB::commit();

            return $this->sendResponse([], 'Password token send successfully.');

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }   
    }
}