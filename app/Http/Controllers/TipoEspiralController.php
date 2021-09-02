<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoEspiral;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TipoEspiralController extends Controller
{
    public function all(){
        $tipoespiral= TipoEspiral::all();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'usuario'=>$tipoespiral];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){
        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de tipoespiral para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipoespiral = TipoEspiral::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->tipoespiral=$tipoespiral;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del tipoespiral';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $tipoespiral = new TipoEspiral();
        $tipoespiral->nombre = $request->nombre;
        $tipoespiral->descripcion = $request->descripcion;
        $tipoespiral->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'tipoespiral'=>$tipoespiral];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de tipoespiral para editar";
            return response()->json($result);
        }

        try{
            $tipoespiral = TipoEspiral::findOrFail($request->id);
            $tipoespiral->nombre = $request->nombre;
            $tipoespiral->descripcion = $request->descripcion;
            $tipoespiral->save();
            $result->code =200;
            $result->status='success';
            $result->tipoespiral=$tipoespiral;

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
            $result->message = "Debes seleccionar un id de tipoespiral para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipoespiral = TipoEspiral::findOrFail($id);
            $tipoespiral->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Tipoespiral Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
