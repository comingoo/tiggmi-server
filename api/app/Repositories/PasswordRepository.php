<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\PasswordResets;
//use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PasswordRepository
{

    public function checkToken($token)
    {
        return User::where('verification_token', '=', $token)->first();
    }

    public function updatePassword(array $data)
    {
        $resetToken = $this->getPasswordResetData($data['token']);
        if (!$resetToken) {
            //wrong token - throw exception
            return response()->json(['error' => 'token_invalid'], 401);
        }
        if (!$resetToken->is_active || $resetToken->valid_till < Carbon::now()) {
            //token has expired .. throw exception 
            return response()->json(['error' => 'token_expired'], 422);
        }

        $data['email'] = $resetToken->email;
        $data['password'] = password_hash($data['passwordConfirm'], PASSWORD_BCRYPT);

        User::where('email', $data['email'])->update(array('password' => $data['password']));

        //now update the reset token values       
        $resetToken->is_active = false;
        $resetToken->used_at = Carbon::now();
        PasswordResets::where('email', $resetToken->email)
            ->where('token', $resetToken->token)
            ->update(['is_active' => $resetToken->is_active, 'used_at' => $resetToken->used_at]);
        return response()->json(['message' => 'Password Updated'], 200);
    }

    public function checkPasswordResetsToken($token, $email)
    {
       // dd($token);
        $existingResetRequest = $this->checkPasswordResetTokenValidation($email);
        if ($existingResetRequest) {
            return \DB::table('password_resets')->where('email', $email)->where('token', '=', $token)->first();
        }
        return false;
    }

    /**
     * Change Password
     *
     */
    public function changePassword(array $data, $id)
    {
        $data['password'] = password_hash($data['passwordConfirm'], PASSWORD_BCRYPT);
        return User::where('id', $id)
            ->update(array('password' => $data['password']));
    }
    /**
     * Verify/Match oldPassword
     * for loggedin user
     */
    public function oldPasswordVerify(array $data, $id)
    {
        $user = User::find($id);
        $oldpassword = $data['oldPassword'];
        if (Hash::check($oldpassword, $user->password)) {
            return $user;
        }
        return null;
    }
    /**
     * generate token for email account
     * User Forgot password reset Token
     * @parameter email
     * return token
     */
    public function getForgotPasswordToken($user)
    {
        $email = $user->email;       
        //The user can request reset password many times .. we cannot update the token once requested
        //if there is already an existing token, then we return this token back
       // $existingResetRequest = \DB::table('password_resets')->where('email', $email)->where("is_active", 1)->where("valid_till", ">=", Carbon::now())->Orderby("created_at", "desc")->first();
        $existingResetRequest = $this->checkPasswordResetTokenValidation($email);
        if ($existingResetRequest) {
            return $existingResetRequest->token;
        } else {
            //We insert a new one
            $token = hash_hmac('sha256', str_random(40), $user);
            \DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now(),
                'is_active' => 1,
                "valid_till" => (Carbon::now())->addDays(3), //just add three days
            ]);

            return $token;
        }

    }

    public function getPasswordResetData($resetToken)
    {
        return \DB::table('password_resets')->where('token', $resetToken)->first();
    }

    public function checkPasswordResetTokenValidation($email)
    {
        return \DB::table('password_resets')->where('email', $email)->where("is_active", 1)->where("valid_till", ">=", Carbon::now())->Orderby("created_at", "desc")->first();

    }

    public function getEmailByPasswordResetToken($token)
    {
        return \DB::table('password_resets')->where('token', $token)->where("is_active", 1)->where("valid_till", ">=", Carbon::now())->Orderby("created_at", "desc")->first();

    }
}
