<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\Controller;
Use App\Services\LoginService as LoginService;
use Validator;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    use AuthenticatesUsers;
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
     * User  profile.
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
                    'message' => 'User Not found',
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
