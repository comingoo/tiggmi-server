<?php

namespace App\Services;

use App\Repositories\PasswordRepository;

class PasswordService
{
    public function __construct(PasswordRepository $pwRepository)
    {
        $this->pwRepository = $pwRepository;
    }

    public function resetPassword(array $data)
    {
       // dd($data);
        $response = $this->verifyPasswordRecovery($data['token']);

        if ($response['status'] === 200) {

            $data['token'] = $response['token'];
            $data['email'] = $response['email'];
            //Post Validation
            $validator = \Validator::make(
                $data,
                [
                    'token' => 'required',
                    'email' => 'required|email',
                    'passwordConfirm' => 'required|string|min:6',
                ]
            );

            if ($validator->fails()) {
                return array('error' => $validator->errors(), 'status' => 422);
            }

            $user = $this->pwRepository->updatePassword($data);
            return array('status' => $response['status'], 'message' => 'Password Updated Successfully!');        
         }
         return $response;
    }

    public function verifyPasswordRecovery($token)
    {
        $data = $this->getEmailByPasswordResetToken($token);
        if (count($data) > 0) {
            $email = $data->email;
            $user = $this->pwRepository->checkPasswordResetsToken($token, $email);
            return array('status' => 200, 'email' => $user->email, 'token' => $user->token);
        }
        return array('status' => 401, 'error' => 'Token Mismatch!');
    }
    /**
     * Change Password for user
     *
     * @param array ['oldPassword','passwordConfirm']
     * @return
     */
    public function changePassword(array $data, $id)
    {
        //Post Validation
        $validator = \Validator::make(
            $data,
            [
                'oldPassword' => 'required|string|min:6',
                'passwordConfirm' => 'required|string|min:6',
            ]
        );

        if ($validator->fails()) {
            return array('error' => $validator->errors(), 'status' => 422);
        }
        //verify Old Password First
        if (!is_null($this->pwRepository->oldPasswordVerify($data, $id))) {
            //update the password
            $user = $this->pwRepository->changePassword($data, $id);
            return array('success' => 'Password changed', 'status' => 200);
        }

        return array('error' => 'Old Password is not correct', 'status' => 401);

    }

    public function getForgotPasswordToken($user)
    {
        return $this->pwRepository->getForgotPasswordToken($user);
    }

    public function getEmailByPasswordResetToken($token)
    {
        return $this->pwRepository->getEmailByPasswordResetToken($token);
    }
}
