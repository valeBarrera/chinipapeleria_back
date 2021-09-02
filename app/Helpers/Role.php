<?php
namespace App\Helpers;
use App\Helpers\JwtAuth;

class Role{

    public function admin($token){
        $jwtAuth = new JwtAuth();
        $user=$jwtAuth->checkToken($token,true);
        if($user->role =='ROLE_ADMIN'){
            return true;
        }
        return false;
    }
}
