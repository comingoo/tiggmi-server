<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Services\CustomerLoginService as LoginService;
Use App\Services\CustomerService ;
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

    public function __construct(LoginService $loginService,CustomerService $customerService)
    {
        //$this->middleware('guest')->except('logout');
        $this->loginService = $loginService;
        $this->customerService = $customerService  ;
      
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

     /**
     * Customer Profile Edit.
     *
     * @parameter user's bearer token,name,mobile,email
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request)
    {
        $token = $request->bearerToken();

        if (!empty($token)) {
            $user = Customer::where('remember_token', '=', $token)->first();
            if (count($user) > 0) {
                if ($user->remember_token == $token) {
                    $data = $request->all();
                    $messages = [
                        'name.required' => 'Username is required',
                        'mobile.required' => 'Please provide a valid or unique mobile no',
                       // 'email.required' => 'Please enter email address'
                    ];
                    // Validate fields
                    $validator = Validator::make($request->all(), [
                    // Using a rgex for validate name.
                        'name' => 'required',
                        'mobile' => 'required|string|min:10|max:10|unique:customers,mobile,'.$user->id,
                       // 'email' => 'required|email|unique:customers|unique:customers'
                    ], $messages);

                    //Redirect back if validation fails
                    if ($validator->fails()) {
                        // $failedRules = $validator->failed();
                        //  dd($failedRules);
                        return response()->json($validator, 401);
                    }
                    $response = $this->customerService->updateProfile($user->id, $data);
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
