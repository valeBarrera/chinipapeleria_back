<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoPunta;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TipoPuntaController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['all']]);
    }
    public function all(){
        $tipopunta= TipoPunta::all();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'usuario'=>$tipopunta];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){
        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de tipopunta para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipopunta = TipoPunta::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->tipopunta=$tipopunta;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $tipopunta = new TipoPunta();
        $tipopunta->nombre = $request->nombre;
        $tipopunta->descripcion = $request->descripcion;
        $tipopunta->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'tipopunta'=>$tipopunta];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de tipopunta para editar";
            return response()->json($result);
        }

        try{
            $tipopunta = TipoPunta::findOrFail($request->id);
            $tipopunta->nombre = $request->nombre;
            $tipopunta->descripcion = $request->descripcion;
            $tipopunta->save();
            $result->code =200;
            $result->status='success';
            $result->tipopunta=$tipopunta;

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
            $result->message = "Debes seleccionar un id de tipopunta para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipopunta = TipoPunta::findOrFail($id);
            $tipopunta->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Tipopunta Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
