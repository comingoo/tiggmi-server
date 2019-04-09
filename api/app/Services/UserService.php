<?php
namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
   private $userRepository ;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     *
     *Check if user is registered or not
     */

    public function findUser($email,$mobile)
    {
       
        $userExists = $this->userRepository->getUser($email,$mobile);

        if ($userExists) {
            return $userExists;
        }

       return null ;
    }
    /**
     * UPDATE USER PROFILE BY Admin 
     * @parameter admin's bearer token,name,dob,gender,password,status,email,mobileno,roleId
     * @return response 
     *  
     */
    public function update(array $data)
    {
        return $this->userRepository->update($data);
    }
    
    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }

    public function updatePassword(array $data)
    {
        return $this->userRepository->updatePassword($data);
    }

    /**
     * UPDATE USER PROFILE BY USER ITSELF
     * @PARAMETER USERID,NAME,EMAIL,MOBILE
     * 
     *  @RETURN RESPONSE
     * 
     */
    public function updateProfile($id, array $data){
        return $this->userRepository->updateProfile($id,$data); 
    }
   
}

