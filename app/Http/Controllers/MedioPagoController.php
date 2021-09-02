<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedioPago;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedioPagoController extends Controller
{
    public function all(){
        $medioPago= MedioPago::all();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'medioPago'=>$medioPago];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccion un id de medio de pago para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $medioPago = MedioPago::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->medioPago=$medioPago;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }



    Public function crear(Request $request){
        $medioPago = new MedioPago();
        $medioPago->nombre = $request->nombre;
        $medioPago->descripcion = $request->descripcion;
        $medioPago->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'medioPago'=>$medioPago];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de Medio De Pago para editar";
            return response()->json($result);
        }

        try{
            $medioPago = MedioPago::findOrFail($request->id);
            $medioPago->nombre = $request->nombre;
            $medioPago->descripcion = $request->descripcion;
            $medioPago->save();
            $result->code = 200;
            $result->status='success';
            $result->medioPago=$medioPago;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del Medio De Pago';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccion un id de Medio de Pago para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $medioPago = MedioPago::findOrFail($id);
            $medioPago->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Medio De Pago Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
