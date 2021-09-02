<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seccion;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SeccionController extends Controller
{
    public function all(){
        $seccion= Seccion::all();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'medioPago'=>$seccion];
        return response()->json($data);
    } 

    public function buscarPorID(Request $request){
        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de seccion para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $seccion = Seccion::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->seccion=$seccion;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $seccion = new Seccion();
        $seccion->nombre = $request->nombre;
        $seccion->descripcion = $request->descripcion;
        $seccion->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'seccion'=>$seccion];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de seccion para editar";
            return response()->json($result);
        }

        try{
            $seccion = Seccion::findOrFail($request->id);
            $seccion->nombre = $request->nombre;
            $seccion->descripcion = $request->descripcion;
            $seccion->save();
            $result->code =200;
            $result->status='success';
            $result->seccion=$seccion;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id de seccion';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de seccion para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $seccion = seccion::findOrFail($id);
            $seccion->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Seccion Eliminada Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
