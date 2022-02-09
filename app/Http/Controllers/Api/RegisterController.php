<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\User;
use App\Event;
use App\SubEvent;
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
            //'email' => 'required|email|unique:users,email',
            'mobile_no' => 'required',
            'password' => 'required',
            'user_type' => 'required'
        ]);
        
        if($validator->fails()){
            //return $validator->errors();
            return $this->sendError('Validation Error.', $validator->messages()->first());       
        }
   
        $input = $request->all();
        DB::beginTransaction();
        $input['password'] = bcrypt($input['password']);
        $input['email'] = strtolower($input['email']);
        $input['user_type'] = ucfirst(strtolower($input['user_type']));
        $user = User::create($input);
        $success['token'] =  $user->createToken('Norep App')->accessToken;
        $success['user_details'] =  $user;
        DB::commit();
        $user->sendEmailVerificationNotification();
   
        return $this->sendResponse($success, 'User register successfully, Please verify your email id.');
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
                return $this->sendError('Validation Error.', $validator->errors()->first());       
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
            return $this->sendError('Exception error.', ['error'=>$e->getMessage()]);
        }   
    }

    /**
     * @method reset_password()
     *
     * @uses To reset the password
     *
     *
     * @param object $request - Email id
     *
     * @return send mail to the valid user
     */

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|confirmed',
                'token' => 'required|string',
                'password_confirmation'=>'required'
            ]);
            
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }

            DB::beginTransaction();

            $password_reset = \App\PasswordReset::where('token', $request->token)->first();

            if(!$password_reset){
                return $this->sendError('Not found.', ['error'=>'Invalid Token']);
            }

            $user = User::where('email', $password_reset->email)->first();

            $user->password = \Hash::make($request->password);

            $user->save();

            \App\PasswordReset::where('email', $user->email) ->delete();

            DB::commit();
            return $this->sendResponse([], 'Password reset successfully.');

        } catch(Exception $e) {
            DB::rollback();
            return $this->sendError('Exception error.', ['error'=>$e->getMessage()]);
        }
    }

   public function saveTermCondition(Request $request)
    {
        try {
            $user = User::where('id', $request->user_id)->update([
                    'is_terms_conditions_accept' => $request->is_terms_conditions_accept
                ]);
            if ($user) {
                return $this->sendResponse($user, 'Terms & Condition accepted successfully.');
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>$e->getMessage()]);
        }
    }

    public function getRefereesList()
    {
        try {
            $getAllRefereeLists = User::select('id')->where('user_type', 'Judge')->orderBy('name', 'ASC')->get();

            $getEventFutureLists = Event::where([
                            ['status' , 1],
                            ['start_date', '>=', Carbon::today()]
                        ])->select('id','referee_id')->get();
                
                $result = '';
                foreach ($getEventFutureLists as $referees) {
                    if (!is_null($referees->referee_id)) {
                        $result .= str_replace('"',"",$referees->referee_id) .',';
                    }
                }
                $refereeArray = explode(',', rtrim($result, ','));
                $refereeIds = array_unique($refereeArray);
            //$getEventAssignRefereeLists = SubEvent::whereIn('event_id', (array)$getEventFutureLists)->get();
            $freeReferee = [];
            foreach ($getAllRefereeLists as $referee) {
                if (!in_array($referee->id, $refereeIds)) {
                    $freeReferee[] = $referee->id;
                }
            }
            $getFreeRefereeLists = User::select('name','id')
                                    ->whereIn('id', $freeReferee)
                                    ->orderBy('name', 'ASC')->get();

            if ($getFreeRefereeLists) {
                return $this->sendResponse($getFreeRefereeLists, 'Referee list get successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'Referee List not found']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }
}