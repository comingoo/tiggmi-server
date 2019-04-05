<?php
namespace App\Services;

use App\Repositories\RoleRepository;

class RoleService
{
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     *Page  Siebar list
     *
     */

    public function addNewRole($data)
    {
       
        $array = $this->roleRepository->addNewRole($data);
        return $array ;
    }

    public function getRoleList()
    {
       
        $array = $this->roleRepository->getRoleList();
        return $array ;
    }
    public function currentRoles()
    {
        
        $array = $this->roleRepository->currentRoles();
        return $array ;
    }

    public function get_role_wise_permission($data){
        $array = $this->roleRepository->get_role_wise_permission($data) ;
        return $array ; 
    }
      
    public function addNewPermssion($data){
       return $this->roleRepository->addNewPermssion($data) ;
       
    }
    public function get_role_by_id($id)
    {
        return $this->roleRepository->get_role_by_id($id) ;
    }

    public function updateRole($data)
    {
        return $this->roleRepository->updateRole($data) ;
    }
    public function deleteRole($id)
    {
        return $this->roleRepository->deleteRole($id) ;
    }
}

