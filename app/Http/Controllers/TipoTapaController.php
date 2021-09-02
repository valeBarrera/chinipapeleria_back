<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoTapa;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TipoTapaController extends Controller
{
    public function all(){
        $tipotapa= TipoTapa::all();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'usuario'=>$tipotapa];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){
        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de tipotapa para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipotapa = Tipotapa::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->tipotapa=$tipotapa;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $tipotapa = new TipoTapa();
        $tipotapa->nombre = $request->nombre;
        $tipotapa->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'tipotapa'=>$tipotapa];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de tipotapa para editar";
            return response()->json($result);
        }

        try{
            $tipotapa = TipoTapa::findOrFail($request->id);
            $tipotapa->nombre = $request->nombre;
            $tipotapa->save();
            $result->code =200;
            $result->status='success';
            $result->tipotapa=$tipotapa;

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
            $result->message = "Debes seleccionar un id de tipotapa para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipotapa = Tipotapa::findOrFail($id);
            $tipotapa->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Tipotapa Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
