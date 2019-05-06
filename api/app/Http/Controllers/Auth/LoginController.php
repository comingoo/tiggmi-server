<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use App\Services\LoginService;
use Validator;
use Carbon\Carbon;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'http://localhost:8000';

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
     * Check User Login Attempt 
     *
     * @return Response
     */
    public function handleLogin(Request $request)
    {
        $messages = [
            'email.required' => "Please enter a valid email address",      
            'password.required'=> "Please provide a password"
        ];
        // Validate fields
        $validator = Validator::make($request->all(), [
            // Using a rgex for validate name.
            'email' => 'required|email',
            'password' => 'required|min:5'
        ], $messages);

        //Redirect back if validation fails
        if($validator->fails()) {
             //$failedRules = $validator->failed();
             // dd($failedRules);
            return response()->json($validator,500);
         
        }
               
        $credentials = $request->only('email', 'password');
        $response = $this->loginService->postLogin($credentials);
        if(!empty($response)){
                return response()->json($response, $this-> successStatus); 
                     
        }
        return response()->json(['Failure'=>[ 'return'=>false,'token'=>null,'user_details'=>null,'error'=>'Invalid Login Credentials']], 401);;
    }
    
    /**
     * Admin Log out 
     * @parameter Bearer logout
     * @return Response
    */
    public function handleLogout(Request $request)
    {
        $token = $request->bearerToken();
        
        if (!empty($token)) {
            
            try {
               $response =  $this->loginService->postLogOut($token); 
               if(empty($response)){
                    return response()->json([
                        'error' => 'Invalid Bearer Token',
                        'message' => 'LogOut Failed'
                    ], 401);
               }

            } catch (\Exception $e) {
                return $e->getMessage();
            }
            
        } else {
           
            return response()->json([
                'error' => 'Please  Enter Bearer Token',
                'message' => 'LogOut Failed'
            ], $this->successStatus);
        }

        

        return response()->json([
            'success'=>1,
            'return'=>true,
            'message' => \Lang::get('Logout!'),
        ], $this->successStatus);
    }
}
