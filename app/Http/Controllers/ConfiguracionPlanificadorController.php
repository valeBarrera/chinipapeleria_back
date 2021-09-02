<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracionPlanificador;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ConfiguracionPlanificadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['all']]);
    }

    public function all(){
        $configuracionplanificador = ConfiguracionPlanificador::all()->load('tipoplanificador','detallepedido','planificador');
        $data=[
            'code'=> 200,
            'status'=>'success',
            'configuracionplanificador'=>$configuracionplanificador
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de configuracion de planificador para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracionplanificador = ConfiguracionPlanificador::findOrFail($id)->load('tipoplanificador','detallepedido','planificador');
            $result->code = 200;
            $result->status='success';
            $result->configuracionplanificador=$configuracionplanificador;
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
                'Planificador_id'=>'required',
                'DetallePedido_id'=>'required',
                'TipoPlanificador_id'=>'required']);
            if($validate->fails()){
                $data=[
                    'code'=>400,
                    'status'=> 'error',
                    'mensaje'=>'La configuracion de planificador no ha podido Crearse',
                    'errores'=>$validate->errors()];
            }else{
                $configuracionplanificador = new ConfiguracionPlanificador();
                $configuracionplanificador->Planificador_id=$request->Planificador_id;
                $configuracionplanificador->DetallePedido_id=$request->DetallePedido_id;
                $configuracionplanificador->TipoPlanificador_id=$request->TipoPlanificador_id;
                $configuracionplanificador->save();
                $data=[
                    'code'=>200,
                    'status'=> 'success',
                    'configuracionplanificador'=>$configuracionplanificador];
            }

        }else{
            $data=[
                'code'=>200,
                'status'=> 'error',
                'mensaje'=>'No ha ingresado los parametros correcto'];
        }

        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de configuracion de planificador para editar";
            return response()->json($result);
        }

        try{
            
            $id = $request->id;
            $configuracionplanificador = ConfiguracionPlanificador::findOrFail($id)->load('tipoplanificador','detallepedido','planificador');
            $configuracionplanificador->Planificador_id=$request->Planificador_id;
            $configuracionplanificador->DetallePedido_id=$request->DetallePedido_id;
            $configuracionplanificador->TipoPlanificador_id=$request->TipoPlanificador_id;
            unset($request->id);
            $configuracionplanificador->save();
            $result->code = 200;
            $result->status='success';
            $result->configuracionplanificador=$configuracionplanificador;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id de configuracion de planificador';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de configuracion de planificador para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracionplanificador = ConfiguracionPlanificador::findOrFail($id);
            $configuracionplanificador->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Configuracion de planificador Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}