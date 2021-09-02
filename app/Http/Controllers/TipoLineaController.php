<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoLinea;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TipoLineaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['all']]);
    }

    public function all(){
        $tipolinea= TipoLinea::all();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'tipo Linea'=>$tipolinea];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){
        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de tipolinea para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipolinea = TipoLinea::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->tipolinea=$tipolinea;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $tipolinea = new TipoLinea();
        $tipolinea->nombre = $request->nombre;
        $tipolinea->descripcion = $request->descripcion;
        $tipolinea->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'tipolinea'=>$tipolinea];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de tipolinea para editar";
            return response()->json($result);
        }

        try{
            $tipolinea = TipoLinea::findOrFail($request->id);
            $tipolinea->nombre = $request->nombre;
            $tipolinea->descripcion = $request->descripcion;
            $tipolinea->save();
            $result->code =200;
            $result->status='success';
            $result->tipolinea=$tipolinea;

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
            $result->message = "Debes seleccionar un id de tipolinea para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipolinea = TipoLinea::findOrFail($id);
            $tipolinea->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Tipolinea Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
