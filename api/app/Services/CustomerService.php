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

