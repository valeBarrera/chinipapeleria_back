<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\JwtAuth;
use App\Helpers\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['registrar','login']]);
    }

    public function all(Request $request){
        $token = $request->header('Authorization');
        $role= new Role();
        if($role->admin($token)){
            $usuario= User::all();
            $data=[
            'code'=>200,
            'status'=> 'success',
            'usuario'=>$usuario];
        return response()->json($data);
        }

        $data=[
            'code'=>400,
            'status'=> 'error',
            'mensaje'=>'el usuario no es administrador'];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $user= $jwtAuth->checkToken($token,true);

        try{
            $usuario = User::findOrFail($user->sub);
            $result->code = 200;
            $result->status='success';
            $result->usuario=$usuario;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }
        return response()->json($result);
    }

    Public function registrar(Request $request){

        //validacion
        if(!empty($request->all())){
            $validate = Validator::make($request->all(),[
                'nombre'=>'required',
                'apellido'=>'required',
                'rut'=>'required|unique:usuario',
                'codigo_verificacion'=>'required',
                'password'=>'required',
                'email'=>'required|email']);
            if ($validate->fails()){
                $data=[
                    'code'=>400,
                    'status'=> 'error',
                    'mensaje'=>'El usuario no ha podido Crearse',
                    'errores'=>$validate->errors()];
            }else{

                $usuario = new User();

                $usuario->nombre = $request->nombre;
                $usuario->apellido = $request->apellido;
                $usuario->rut = $request->rut;
                $usuario->codigo_verificacion = $request->codigo_verificacion;
                $usuario->email = $request->email;
                $usuario->numero = $request->numero;
                $usuario->ciudad = $request->ciudad;
                $usuario->calle = $request->calle;
                //cifrado de password
                $pwd = Hash::make($request->password);

                $usuario->password= $pwd;
                $usuario->role='ROLE_USER';
                $usuario->save();
                $data=[
                    'code'=>200,
                    'status'=> 'success',
                    'mensaje'=>'el usuario se ha creado correctamente',
                    'usuario'=>$usuario];
            }
        }else{
            $data=[
                'code'=>200,
                'status'=> 'error',
                'mensaje'=>'No ha ingresado los parametros correcto'];
        }

        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de usuario para editar";
            return response()->json($result);
        }

        try{
            $usuario = User::findOrFail($request->id);
            $usuario->nombre = $request->nombre;
            $usuario->apellido = $request->apellido;
            $usuario->rut = $request->rut;
            $usuario->codigo_verificacion = $request->codigo_verificacion;
            $usuario->email = $request->email;
            $usuario->numero = $request->numero;
            $usuario->ciudad = $request->ciudad;
            $usuario->calle = $request->calle;
            $usuario->save();
            $result->code =200;
            $result->status='success';
            $result->usuario=$usuario;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de usuario para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $usuario = User::findOrFail($id);
            $usuario->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Cliente Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    public function login(Request $request){

        $jwtAuth = new JwtAuth();

        $validate = Validator::make($request->all(),[
            'password'=>'required',
            'email'=>'required|email']);

        if ($validate->fails()){
            $data=[
                'code'=>400,
                'status'=> 'error',
                'mensaje'=>'El usuario no ha podido identificarse',
                'errores'=>$validate->errors()];
        }else{
            $pwd = $request->password;
            $email= $request->email;
            $data=$jwtAuth->signup($email,$pwd);
            if(!empty($request->gettoken)){
                $data= $jwtAuth->signup($email,$pwd,true);
            }

        }
        return response()->json($data);

    }

    public function update( Request $request){
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checktoken= $jwtAuth->checkToken($token);
        if($checktoken && !empty($request->all())){
            $user= $jwtAuth->checkToken($token,true);
            $validate = Validator::make($request->all(),[
                'nombre'=>'required',
                'apellido'=>'required',
                'rut'=>'required|unique:usuario',
                'codigo_verificacion'=>'required',
                'password'=>'required',
                'email'=>'required|email'.$user->sub]);

                unset($request->id);
                unset($request->role);
                unset($request->remenber_token);
                unset($request->password);
                $user_update = User::Where('id',$user->sub)->update($request->all());
                $data=[
                    'code'=>200,
                    'status'=> 'success',
                    'usuario'=>'El usuario a sido actualizado'];

        }else{
            $data=[
                'code'=>400,
                'status'=> 'error',
                'mensaje'=>'El usuario no ha podido identificarse'];
        }

        return response()->json($data);
    }

    public function allDetallePedido(Request $request){
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $user= $jwtAuth->checkToken($token,true);
        $detallePedido=DB::select('select * from detallepedido where Pedido_id in (select id from pedido where Usuario_id= ?)', [$user->sub]);
        $data=[
            'code'=>200,
            'status'=> 'success',
            'detallePedido'=>$detallePedido];
        return response()->json($data);
    }

    public function admin(Request $request){
        $token = $request->header('Authorization');
        $role= new Role();
        if($role->admin($token)){
        return response()->json(true);
        }
        return response()->json(false);
    }
}
