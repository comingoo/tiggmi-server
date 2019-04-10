<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordService;
use App\Services\UserService;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */
    private $passwordService, $userService;
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PasswordService $passwordService, UserService $userService)
    {
        $this->passwordService = $passwordService;
        $this->userService = $userService;
       // $this->middleware('guest');
    }

    public function resetTokenVerify($token)
    {
        dd($token);
        //retrieve user's email by PasswordResetToken
        $user = $this->passwordService->getEmailByPasswordResetToken($token);
        if (count($user) > 0) {
            $response = $this->passwordService->verifyPasswordRecovery($token, $user->email);
            return response()->json($response, $response['status']);
        }
        return response()->json(['error' => 'Token Invalid!'], 401);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        $response = $this->passwordService->resetPassword($request->all());

        return response()->json($response, $response['status']);

    }

    /**
     * Change Password for Loggedin user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $token = $request->bearerToken();

        if (!empty($token)) {
            $user = User::where('remember_token', '=', $token)->first();
            $id = $user->id;
            //dd($id);
            $response = $this->passwordService->changePassword($request->all(), $id);
            return response()->json($response, $response['status']);
        }
    }

    
    /**
     * Send resetPasswordLink to user email-address
     * API parmaters [email]
     * API response 
     * send reset Password Token Link to [email] 
     * @param  \Illuminate\Http\Request  $request
     * @return response
     */
    public function requestResetPassword(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        $enteredEmail = $request->input('email');
        $user = $this->userService->getUserByEmail($enteredEmail);
        if ($user) {
            $token = $this->passwordService->getForgotPasswordToken($user);
            if (!is_null($token)) {
                //Send email to user to reset password
                $user->sendPasswordResetNotification($token);
            }
            else{
                Log::error("Should have got the token from the passsowrd service requested foir : " . $enteredEmail);
            }
        }
        else            {
            Log::warning("Forgot password request with unknown email " . $enteredEmail);
        }
        return response()->json(['operation' => 'requestResetPassword', 'response' => 'success', 'message' => 'Reset Password Mail Sent Successfully'], 200);
    }
}
