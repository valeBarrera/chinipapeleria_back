<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionAgenda;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ConfiguracionAgendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except'=>['all','buscarPorID']]);
    }
    public function all(){
        $configuracionAgenda = ConfiguracionAgenda::all()->load('colorespiral','agenda', 'disenos' );
        $data=[
            'code'=> 200,
            'status'=>'success',
            'configuracionAgenda'=>$configuracionAgenda
        ];
        return response()->json($data);
    }
    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de la configuracion agenda para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracionAgenda = ConfiguracionAgenda::findOrFail($id)->load('colorespiral','agenda', 'disenos' );
            $result->code = 200;
            $result->status='success';
            $result->configuracionAgenda=$configuracionAgenda;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        if(!empty($request->all())){
            $validate = Validator::make($request->all(),[
                'comentarios'=>'required',
                'observaciones'=>'required',
                'costo_extra'=>'required',
                'DetallePedido_id'=>'required',
                'ColorEspiral_id'=>'required',
                'Agenda_id'=>'required',
                'Diseno_id'=>'required']);
            if($validate->fails()){
                $data=[
                    'code'=>400,
                    'status'=> 'error',
                    'mensaje'=>'La configuracion de agenda no ha podido Crearse',
                    'errores'=>$validate->errors()];
            }else{
                $configuracionAgenda = new ConfiguracionAgenda();
                $configuracionAgenda->comentarios = $request->comentarios;
                $configuracionAgenda->observaciones=$request->observaciones;
                $configuracionAgenda->costo_extra=$request->costo_extra;
                $configuracionAgenda->DetallePedido_id=$request->DetallePedido_id;
                $configuracionAgenda->ColorEspiral_id=$request->ColorEspiral_id;
                $configuracionAgenda->Agenda_id=$request->Agenda_id;
                $configuracionAgenda->save();
                $configuracionAgenda->disenos()->attach($request->Diseno_id);
                $data=[
                    'code'=>200,
                    'status'=> 'success',
                    'ConfiguracionAgenda'=>$configuracionAgenda];
            }
        }else{
            $data=[
                'code'=>200,
                'status'=> 'error',
                'mensaje'=>'No ha ingresado los parametros correcto'];
        }

        return response()->json($data);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del Agenda para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracionAgenda = ConfiguracionAgenda::findOrFail($id);
            $configuracionAgenda->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Configuracion de Agenda Eliminada Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function editar(Request $request){
        if(!empty($request->all())){
            $validate = Validator::make($request->all(),[
                'comentarios'=>'required',
                'observaciones'=>'required',
                'costo_extra'=>'required',]);
            if($validate->fails()){
                $data=[
                    'code'=>400,
                    'status'=> 'error',
                    'mensaje'=>'La Agendo no ha podido Editarse',
                    'errores'=>$validate->errors()];
            }else{
                $configuracionAgenda = ConfiguracionAgenda::findOrFail($request->id);
                $configuracionAgenda->comentarios = $request->comentarios;
                $configuracionAgenda->observaciones=$request->observaciones;
                $configuracionAgenda->costo_extra=$request->costo_extra;
                unset($request->id);
                unset($request->DetallePedido_id);
                unset($request->ColorEspiral_id);
                unset($request->Agenda_id);
                $configuracionAgenda->save();
                $data=[
                    'code'=>200,
                    'status'=> 'success',
                    'Agenda'=>$configuracionAgenda];
            }
        }else{
            $data=[
                'code'=>200,
                'status'=> 'error',
                'mensaje'=>'No ha ingresado los parametros correcto'];
        }

        return response()->json($data);
    }
}
