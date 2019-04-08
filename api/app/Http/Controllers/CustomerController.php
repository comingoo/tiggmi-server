<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Services\CustomerLoginService as LoginService;
use Validator;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerController extends Controller
{
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
     * Customer  profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $token = $request->bearerToken();

        if (!empty($token)) {
            $user = Customer::where('remember_token', '=', $token)->first();
            if (count($user) > 0) {
                if ($user->remember_token == $token) {
                    $user = Customer::find($user->id);
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

}
