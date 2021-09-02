<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoHoja;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TipoHojaController extends Controller
{
    public function all(){
        $tipohoja= TipoHoja::all();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'usuario'=>$tipohoja];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){
        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de tipohoja para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipohoja = TipoHoja::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->tipohoja=$tipohoja;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $tipohoja = new TipoHoja();
        $tipohoja->nombre = $request->nombre;
        $tipohoja->gramaje = $request->gramaje;
        $tipohoja->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'tipohoja'=>$tipohoja];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de tipohoja para editar";
            return response()->json($result);
        }

        try{
            $tipohoja = TipoHoja::findOrFail($request->id);
            $tipohoja->nombre = $request->nombre;
            $tipohoja->gramaje = $request->gramaje;
            $tipohoja->save();
            $result->code =200;
            $result->status='success';
            $result->tipohoja=$tipohoja;

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
            $tipohoja = TipoHoja::findOrFail($id);
            $tipohoja->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='tipohoja Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
