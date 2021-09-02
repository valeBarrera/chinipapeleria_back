<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetallePedido;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DetallePedidoController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function all(){
        $detallePedido = DetallePedido::all()->load('pedido','producto');
        $data=[
            'code'=>200,
            'status'=>'success',
            'Detalle Pedido'=>$detallePedido
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del detalle pedido para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $detallePedido = DetallePedido::findOrFail($id)->load('pedido','producto');
            $result->code = 200;
            $result->status='success';
            $result->detallePedido=$detallePedido;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $detallePedido = new DetallePedido();
        $detallePedido->cantidad = $request->cantidad;
        $detallePedido->precio=$request->precio;
        $detallePedido->Pedido_id=$request->Pedido_id;
        $detallePedido->Producto_id=$request->Producto_id;
        $detallePedido->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'detallePedido'=>$detallePedido];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id del Detalle de pedido para editar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $detallePedido = DetallePedido::findOrFail($id)->load('pedido','producto');
            $detallePedido->cantidad = $request->cantidad;
            $detallePedido->precio=$request->precio;
            $detallePedido->Pedido_id=$request->Pedido_id;
            $detallePedido->Producto_id=$request->Producto_id;
            $detallePedido->save();
            $result->code = 200;
            $result->status='success';
            $result->detallePedido=$detallePedido;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del Detalle de pedido';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del detalle de pedido para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $detallePedido = DetallePedido::findOrFail($id);
            $detallePedido->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Detalle de Pedido Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
