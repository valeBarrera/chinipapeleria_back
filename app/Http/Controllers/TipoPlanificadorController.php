<?php

namespace App\Http\Controllers;

use App\Models\TipoPlanificador;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TipoPlanificadorController extends Controller
{
    public function all(){
        $tipoPlanificador= TipoPlanificador::all();
        $data=[
            'code'=>200,
            'status'=>'success',
            'tipoPlanificador'=>$tipoPlanificador
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del tipo Planificador para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipoPlanificador = TipoPlanificador::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->tipoPlanificador=$tipoPlanificador;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $tipoPlanificador = new TipoPlanificador();
        $tipoPlanificador->nombre = $request->nombre;
        $tipoPlanificador->descripcion = $request->descripcion;
        $tipoPlanificador->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'tipo Planificador'=>$tipoPlanificador];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id del tipo Planificador para editar";
            return response()->json($result);
        }

        try{
            $tipoPlanificador = TipoPlanificador::findOrFail($request->id);
            $tipoPlanificador->nombre = $request->nombre;
            $tipoPlanificador->descripcion = $request->descripcion;
            $tipoPlanificador->save();
            $result->code = 200;
            $result->status='success';
            $result->tipoPlanificador=$tipoPlanificador;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del tipo Planificador';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del tipo Planificador para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tipoPlanificador = TipoPlanificador::findOrFail($id);
            $tipoPlanificador->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Tipo Planificador Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
