<?php

namespace App\Http\Controllers;
use App\Models\TipoProducto;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

class TipoProductoController extends Controller
{
    public function all(){
        $tipoProducto= TipoProducto::all();
        $data=[
            'code'=>200,
            'status'=>'success',
            'tipoProducto'=>$tipoProducto
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del tipo producto para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipoProducto = TipoProducto::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->tipoProducto=$tipoProducto;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $tipoProducto = new TipoProducto();
        $tipoProducto->nombre = $request->nombre;
        $tipoProducto->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'tipo Producto'=>$tipoProducto];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id del tipo producto para editar";
            return response()->json($result);
        }

        try{
            $tipoProducto = TipoProducto::findOrFail($request->id);
            $tipoProducto->nombre = $request->nombre;
            $tipoProducto->save();
            $result->code = 200;
            $result->status='success';
            $result->tipoProducto=$tipoProducto;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del tipo producto';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del tipo producto para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipoProducto = TipoProducto::findOrFail($id);
            $tipoProducto->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Tipo Producto Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
