<?php
namespace App\Services;

use App\Repositories\CustomerRepository;

class CustomerService
{
   private $CustomerRepository ;
    public function __construct(CustomerRepository $CustomerRepository)
    {
        $this->CustomerRepository = $CustomerRepository;
    }

    /**
     *
     *Check if user is registered or not
     */

    public function findUser($mobile)
    {
       
        $userExists = $this->CustomerRepository->getUser($mobile);

        if ($userExists) {
            return $userExists;
        }

       return null ;
    }
        
    public function delete($id)
    {
        return $this->CustomerRepository->delete($id);
    }

    public function updatePassword(array $data)
    {
        return $this->CustomerRepository->updatePassword($data);
    }
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
        if (!is_null($this->CustomerRepository->oldPasswordVerify($data, $id))) {
            //update the password
            $user = $this->CustomerRepository->changePassword($data, $id);
            return array('success' => 'Password changed', 'status' => 200);
        }

        return array('error' => 'Old Password is not correct', 'status' => 401);

    }
    /**
     * UPDATE Customer PROFILE BY Customer ITSELF
     * @PARAMETER USERID,NAME,email,mobile
     * @RETURN RESPONSE
     * 
     */
    public function updateProfile($id, array $data){
        return $this->CustomerRepository->updateProfile($id,$data); 
    }
   
}

