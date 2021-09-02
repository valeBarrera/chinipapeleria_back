<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracionCuaderno;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ConfiguracionCuadernoController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function all(){
        $configuracioncuaderno = ConfiguracionCuaderno::all()->load('cuaderno','detallepedido','tipolinea','tipohoja','tipotapa','tamaniohoja','colorespiral');
        $data=[
            'code'=> 200,
            'status'=>'success',
            'configuracioncuaderno'=>$configuracioncuaderno
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de configuracioncuaderno para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracioncuaderno = ConfiguracionCuaderno::findOrFail($id)->load('cuaderno','detallepedido','tipolinea','tipohoja','tipotapa','tamaniohoja','colorespiral');
            $result->code = 200;
            $result->status='success';
            $result->configuracioncuaderno=$configuracioncuaderno;
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
                'Cuaderno_id'=>'required',
                'DetallePedido_id'=>'required',
                'TipoLinea_id'=>'required',
                'TipoHoja_id'=>'required',
                'TipoTapa_id'=>'required',
                'TamanioHoja_id'=>'required',
                'ColorEspiral_id'=>'required']);

            if($validate->fails()){
                $data=[
                    'code'=>400,
                    'status'=> 'error',
                    'mensaje'=>'La configuracioncuaderno no ha podido crearse',
                    'errores'=>$validate->errors()];
            }else{
                
                $configuracioncuaderno = new ConfiguracionCuaderno();
                $configuracioncuaderno->cantidad_hojas = $request->cantidad_hojas;
                $configuracioncuaderno->Cuaderno_id=$request->Cuaderno_id;
                $configuracioncuaderno->DetallePedido_id=$request->DetallePedido_id;
                $configuracioncuaderno->TipoLinea_id=$request->TipoLinea_id;
                $configuracioncuaderno->TipoHoja_id=$request->TipoHoja_id;
                $configuracioncuaderno->TipoTapa_id=$request->TipoTapa_id;
                $configuracioncuaderno->TamanioHoja_id=$request->TamanioHoja_id;
                $configuracioncuaderno->ColorEspiral_id=$request->ColorEspiral_id;
                $configuracioncuaderno->save();
                $data=[
                    'code'=>200,
                    'status'=> 'success',
                    'configuracioncuaderno'=>$configuracioncuaderno];
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
            $result->message = "Debes seleccionar un id del configuracioncuaderno para editar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracioncuaderno = ConfiguracionCuaderno::findOrFail($id)->load('cuaderno','detallepedido','tipolinea','tipohoja','tipotapa','tamaniohoja','colorespiral');
            $configuracioncuaderno->cantidad_hojas = $request->cantidad_hojas;
            $configuracioncuaderno->Cuaderno_id=$request->Cuaderno_id;
            $configuracioncuaderno->DetallePedido_id=$request->DetallePedido_id;
            $configuracioncuaderno->TipoLinea_id=$request->TipoLinea_id;
            $configuracioncuaderno->TipoHoja_id=$request->TipoHoja_id;
            $configuracioncuaderno->TipoTapa_id=$request->TipoTapa_id;
            $configuracioncuaderno->TamanioHoja_id=$request->TamanioHoja_id;
            $configuracioncuaderno->ColorEspiral_id=$request->ColorEspiral_id;
            unset($request->id);
            $configuracioncuaderno->save();
            $result->code = 200;
            $result->status='success';
            $result->configuracioncuaderno=$configuracioncuaderno;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del configuracioncuaderno';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del configuracioncuaderno para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracioncuaderno = ConfiguracionCuaderno::findOrFail($id);
            $configuracioncuaderno->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Configuracioncuaderno Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
