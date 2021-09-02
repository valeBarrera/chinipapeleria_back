<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionPlanificador;
use Illuminate\Http\Request;
use App\Models\Planificador;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class PlanificadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['all','buscarPorID']]);
    }

    public function all(){
        $planificador = Planificador::all()->load('producto','tipohoja','tamaniohoja','tipotapa','colorespiral');
        $data=[
            'code'=> 200,
            'status'=>'success',
            'planificador'=>$planificador
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de planificador para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $planificador = Planificador::findOrFail($id)->load('producto','tipohoja','tamaniohoja','tipotapa','colorespiral');
            $result->code = 200;
            $result->status='success';
            $result->planificador=$planificador;
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
                'cantidad_hojas'=>'required',
                'Producto_id'=>'required',
                'TipoHoja_id'=>'required',
                'TamanioHoja_id'=>'required',
                'TipoTapa_id'=>'required',
                'ColorEspiral_id'=>'required']);

            if($validate->fails()){
                $data=[
                    'code'=>400,
                    'status'=> 'error',
                    'mensaje'=>'El planificador no ha podido crearse',
                    'errores'=>$validate->errors()];
            }else{

                $planificador = new Planificador();
                $planificador->cantidad_hojas = $request->cantidad_hojas;
                $planificador->Producto_id=$request->Producto_id;
                $planificador->TipoHoja_id=$request->TipoHoja_id;
                $planificador->TamanioHoja_id=$request->TamanioHoja_id;
                $planificador->TipoTapa_id=$request->TipoTapa_id;
                $planificador->ColorEspiral_id=$request->ColorEspiral_id;
                $planificador->save();
                $data=[
                    'code'=>200,
                    'status'=> 'success',
                    'Planificador'=>$planificador];
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
            $result->message = "Debes seleccionar un id del planificador para editar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $planificador = Planificador::findOrFail($id)->load('producto','tipohoja','tamaniohoja','tipotapa','colorespiral');
            $planificador->cantidad_hojas = $request->cantidad_hojas;
            $planificador->Producto_id=$request->Producto_id;
            $planificador->TipoHoja_id=$request->TipoHoja_id;
            $planificador->TamanioHoja_id=$request->TamanioHoja_id;
            $planificador->TipoTapa_id=$request->TipoTapa_id;
            $planificador->ColorEspiral_id=$request->ColorEspiral_id;
            unset($request->id);
            $planificador->save();
            $result->code = 200;
            $result->status='success';
            $result->planificador=$planificador;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del planificador';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del planificador para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracionPlanificadores= ConfiguracionPlanificador::where('Planificador_id','=',$request->id)->get();
            foreach($configuracionPlanificadores as $cp){
                $cp->delete();
            }
            $planificador = Planificador::findOrFail($id);

            $planificador->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Planificador Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }
        return response()->json($result);
    }
}
