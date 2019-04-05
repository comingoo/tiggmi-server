<?php
namespace App\Repositories;

use App\Models\Role ;

class RoleRepository
{
    /**
     * Insert/add New Role Type to role_master 
     * 
     */
    public static function addNewRole(array $data)
    {
     
       if(is_array($data) && count($data)>0){
                
        $data = array(
            'roleName' => $data['name']
          );
        $role = new Role();
        $role->fill($data);
        $role->save();
        return $role ;
       }
       return $data ;
             
    }
     /**
     * get Role Detail by RoleId to role_master 
     * 
     */

    public static function get_role_by_id($id){
       return Role::find($id);
    }
    
    public function currentRoles()
    {
            return \DB::table('role')->get() ;
          
    }
       /**
     * 
     *  Update Role
     */
    public function updateRole(array $data)
    {
       // dd($data);         
       if(is_array($data) && count($data)>0){
       
        $role = Role::find($data['id']);
        $role->id = $data['id'] ;
        $role->roleName = $data['name'];
        $role->save();
        return $role ;
       }
       
    }
    
    /**
     * 
     *  Delete Role
     */
    public function deleteRole($id)
    {
       // dd($id);  
        
       $userAuth  = \Auth::user();
       $currentUser = Role::find($userAuth->roleId);
       if($currentUser->roleId == 1 && $userAuth->roleId != $id ){
            \DB::table('role')->where('id',$id)->delete() ;
            return 'User Account Deleted' ;
       }
       else{
           return 'You are not allowed to delete account! ';
       }
      
       
    }
}