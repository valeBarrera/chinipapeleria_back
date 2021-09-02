<?php

namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class JwtAuth{
    public $key;
    public function __construct()
    {
        $this->key='esto_es_una_clave_muy_importante_y_secreta_imposible_de_revelar-987465132';
    }
    public function signup($email,$password, $getToken=null){

        $user = User::where('email', $email)->first();

        $singup = false;
        if (Hash::check($password, $user->password)){
            $singup = true;
        }

        if ($singup){
            $token =array(
                'sub'=>$user->id,
                'email'=>$user->email,
                'nombre'=>$user->nombre,
                'apellido'=>$user->apellido,
                'role'=>$user->role,
                'iat'=>time(),
                'exp'=>time()+(7*24*60*60)
            );
            $jwt = JWT::encode($token,$this->key,'HS256');
            $decode = JWT::decode($jwt,$this->key, ['HS256']);

            if(is_null($getToken)){
                $data =array('token' => $jwt, 'rol' => $user->role);
                return $data;
            }else{
                $data =array('token' => $decode);
                return $data;
            }
        }else{
            $data =array(
                'status'=>'error',
                'message'=>'login incorrecto'
            );
        }

        return $data;


    }

    public function checkToken($jwt, $getIdentity=false){
        $auth=false;
        try{
            $jwt = str_replace('""','',$jwt);
            $decode = JWT::decode($jwt,$this->key, ['HS256']);
        }catch(\UnexpectedValueException $e){
            $auth=false;
        }catch(\DomainException $e){
            $auth=false;
        }

        if(!empty($decode) && is_object($decode) && isset($decode->sub)){
            $auth=true;
        }else{
            $auth=false;
        }

        if($getIdentity){
            return $decode;
        }

        return $auth;
    }
}
