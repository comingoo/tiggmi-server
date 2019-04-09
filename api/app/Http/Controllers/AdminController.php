<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\Controller;
Use App\Services\LoginService as LoginService;
Use App\Services\UserService;
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

    public function __construct(LoginService $loginService, UserService $userService)
    {
        //$this->middleware('guest')->except('logout');
        $this->loginService = $loginService;
        $this->userService = $userService;
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

     /**
     * Admin Profile Edit.
     *
     * @parameter user's bearer token,name,mobile,email
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request)
    {
        $token = $request->bearerToken();

        if (!empty($token)) {
            $user = User::where('remember_token', '=', $token)->first();
            if (count($user) > 0) {
                if ($user->remember_token == $token) {
                    $data = $request->all();
                    $messages = [
                        'name.required' => 'Username is required',
                       // 'mobile' => 'Please provide a valid or unique mobile no',
                        'email' => 'Please enter valid or unique email address'
                    ];
                    // Validate fields
                    $validator = Validator::make($request->all(), [
                    // Using a rgex for validate name.
                        'name' => 'required',
                       // 'mobile' => 'min:10|max:10|unique:users,mobile,'.$user->id,
                        'email' => 'email|unique:customers|unique:users,email,'.$user->id
                    ], $messages);

                    //Redirect back if validation fails
                    if ($validator->fails()) {
                         $failedRules = $validator->failed();
                         dd($failedRules);
                        return response()->json($validator, 401);
                    }
                    $response = $this->userService->updateProfile($user->id, $data);
                    //dd($user);
                    return response()->json($response, $response['responseCode']);
                }

                return  response()->json(['failure' => [
                'return' => false,
                'message' => 'Invalid Token access',
                'error' => 'Unauthorized Access', ],
                ], 422);
            }

            return  response()->json(['failure' => [
            'return' => false,
            'message' => 'User Not found',
            'error' => 'Unauthorized Access', ],
            ], 404);
        }

        return  response()->json(['failure' => [
            'return' => false,
            'message' => 'Invalid Token access',
            'error' => 'Unauthorized Access', ],
            ], 422);
    }
}
