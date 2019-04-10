<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth; 
use App\Services\CustomerLoginService as LoginService;
use Validator;
use Carbon\Carbon;

class CustomerLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'http://localhost:8000/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $loginService;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $successStatus = 200;

    public function __construct(LoginService $loginService)
    {
        //$this->middleware('guest')->except('logout');
        $this->loginService = $loginService;
      
    }

    /**
     * Check Customer Login Attempt 
     *
     * @return Response
     */
    public function handleCustomerLogin(Request $request)
    {
        $messages = [
            'mobile.required' => "Please enter a mobile no",      
            'password.required'=> "Please provide a password"
        ];
        // Validate fields
        $validator = Validator::make($request->all(), [
            // Using a rgex for validate name.
            'mobile' => 'required|min:10|max:10|regex:/[0-9]{9}/',
            'password' => 'required|min:5'
        ], $messages);

        //Redirect back if validation fails
        if($validator->fails()) {
            // $failedRules = $validator->failed();
             //dd($failedRules);
            return response()->json($validator,500);
         
        }
        // Check mobile number exists or not 
        $verifyMobile = CustomerLoginController::VerifyMobile($request->mobile);
        if($verifyMobile['result']['return']){
            if($verifyMobile['result']['active']){
                $credentials = $request->only('mobile', 'password');
                $response = $this->loginService->postLogin($credentials);
                if(!empty($response)){
                    
                    return response()->json($response, $this->successStatus); 
                
                }
                return response()->json(['Failure'=>[ 'return'=>false,'token'=>null,'user_details'=>null,'error'=>'Invalid Login Credentials']], 401);
    
            }
            $verifyMobile['message'] = 'Account not Active';
            return response()->json($verifyMobile, $verifyMobile['responseCode']);
      
        }else{
            return response()->json($verifyMobile,$verifyMobile['result']['errorcode']);
        }


   }

    /**
     * Check Customer Mobile Exists or not
     * @parameter mobile
     * @response array
     * 
     */
    public function VerifyMobile($mobile){
        return $this->loginService->VerifyMobile($mobile);

    }

    public function authenticated()
    {
        $user = Auth::customer();
        $user->token_2fa_expiry = Carbon\Carbon::now();
        $user->save();
        return redirect($redirectTo);
    }
    /**
     * verify Customer OTP valid for 15 min 
     *
     * @return Response
     */
    public function verifyCustomerOTP(Request $request)
    {
        $request->validate([
            'OTP' => 'required'
        ]);
        $credentials = $request->only('mobile', 'password','OTP');
        $response = $this->loginService->verifyOTP($credentials);
        if(!empty($response)){
            
            return response()->json($response, $this->successStatus); 
           
        }
        return response()->json(['Failure'=>[ 'return'=>false,'token'=>null,'user_details'=>null,'error'=>'Invalid Login Credentials or OTP']], 401);;
  
    }
    /**
     * Customer  profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $token = $request->bearerToken();

        if (!empty($token)) {
            $user = User::where('remember_token', '=', $token)->first();
            if (count($user) > 0) {
                if ($user->remember_token == $token) {
                    $user = User::find($user->id);
                    //dd($user);
                    return response()->json(['success' => $user], $this->successStatus);
                }

                return  response()->json(['failure' => [
                    'return' => false,
                    'message' => 'Customer Not found',
                    'error' => 'Unauthorized Access', ],
                    ], 404);
            }
        }

        return  response()->json(['failure' => [
            'return' => false,
            'message' => 'Invalid Token access',
            'error' => 'Unauthorized Access', ],
            ], 422);
    }

    /**
     * Customer Log out 
     * @parameter Bearer logout
     * @return Response
    */
    public function handleLogout(Request $request)
    {
        $token = $request->bearerToken();
        
        if (!empty($token)) {
            
            try
            {
               $response =  $this->loginService->postLogOut($token); 
               if(empty($response)){
                    return response()->json(['error' => 'Invalid Bearer Token', 'message' => 'LogOut Failed'], 401);
               }
              return response()->json($response, $this->successStatus);

            } catch (\Exception $e) {
                return $e->getMessage();
            }
            
        } else {
           
            return response()->json([
                'error' => 'Please  Enter Bearer Token',
                'message' => 'LogOut Failed'
            ], $this->successStatus);
        }

    }

}
