<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Pedido;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;

class PedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }
    public function all(){
        $pedido = Pedido::all()->load('usuario','estadoPedido');
        $data=[
            'code'=> 200,
            'status'=>'success',
            'pedido'=>$pedido
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del pedido a buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $pedido = Pedido::findOrFail($id)->load('usuario', 'estadoPedido');
            $result->code = 200;
            $result->status='success';
            $result->pedido=$pedido;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){

            $jwt = new JwtAuth();
            $token=$request->header('Authorization',null);
            $user=$jwt->checkToken($token,true);
            $now= new DateTime('now');
            $pedido = new Pedido();
            $pedido->fecha_creacion = $now->format('Y-m-d H:i:s');
            $pedido->fecha_termino = $now->add(new DateInterval('P7D'))->format('Y-m-d H:i:s');
            $pedido->Usuario_id=$user->sub;
            $pedido->EstadoPedido_id=1;
            $pedido->save();
            $data=[
                'code'=>200,
                'status'=> 'success',
                'pedido'=>$pedido];
        return response()->json($data);


    }


    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del pedido para Eliminar";
            return response()->json($result);
        }

        if($request->Venta_id != ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "No Puedes Eliminar el Pedido Tiene registrada una venta";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $pedido = Pedido::findOrFail($id);
            $pedido->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Pedido Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    public function asignarVentaPedido(Request $request){
        $result = new \stdClass();
        $result->code = 200;
        if (!isset($request->Venta_id) || !isset($request->id)) {
                    $resp = new \stdClass();
                    $resp->code = 400;
                    $resp->status = 'error';
                    $resp->message = 'No cumple con las precondiciones de los campos';
                    return response(json_encode($resp), 400)
                        ->header('Content-Type', 'application/json');
        }
        try{
            $id = $request->id;
            $pedido = Pedido::findOrFail($id);
            if($pedido->Venta_id!=''){
                $pedido->Venta_id= $request->Venta_id;
                $pedido->save();
                $result->code = 200;
                $result->status='success';
                $result->message='Venta Asignada Correctamente Exitosamente';
            }
            $result->code = 400;
                $result->status='error';
                $result->message='No Puedes Asignar el Pedido Tiene registrada una venta';


        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }
        return response()->json($result);
    }
}
