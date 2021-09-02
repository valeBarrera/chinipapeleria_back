<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstadoPedido;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EstadoPedidoController extends Controller
{
    public function all(){
        $estadoPedido= EstadoPedido::all();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'medioPago'=>$estadoPedido];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccion un id de estadoPedido de pago para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $estadoPedido = EstadoPedido::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->estadoPedido=$estadoPedido;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $estadoPedido = new EstadoPedido();
        $estadoPedido->nombre = $request->nombre;
        $estadoPedido->descripcion = $request->descripcion;
        $estadoPedido->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'medioPago'=>$estadoPedido];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de Estado de pedido para editar";
            return response()->json($result);
        }

        try{
            $estadoPedido = EstadoPedido::findOrFail($request->id);
            $estadoPedido->nombre = $request->nombre;
            $estadoPedido->descripcion = $request->descripcion;
            $estadoPedido->save();
            $result->code = 200;
            $result->status='success';
            $result->estadoPedido=$estadoPedido;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del Estado de Pedido';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccion un id de Estado de Pedido para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $estadoPedido = EstadoPedido::findOrFail($id);
            $estadoPedido->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Estado de Pedido Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
