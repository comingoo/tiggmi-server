<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

      /*  //retrieveByCredentials
        if ($customer = app('auth')->getProvider()->retrieveByCredentials($request->only('email', 'password'))) {
            $token = Token::create([
                'customer_id' => $customer->id
            ]);

            if ($token->sendCode()) {
                session()->set("token_id", $token->id);
                session()->set("customer_id", $customer->id);
                session()->set("remember", $request->get('remember'));

                return redirect("code");
            }

            $token->delete();// delete token because it can't be sent
            return redirect('/login')->withErrors([
                "Unable to send verification code"
            ]);
        }

        return redirect()->back()
            ->withInputs()
            ->withErrors([
                $this->username() => \Lang::get('auth.failed')
            ]);
            */
    }
}
