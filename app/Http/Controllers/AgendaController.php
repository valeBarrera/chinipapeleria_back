<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;
use App\Helpers\JwtAuth;
use App\Helpers\Role;
use App\Models\ConfiguracionAgenda;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['all','buscarPorID']]);
    }
    public function all(){
        $Agenda = Agenda::all()->load('secciones','producto','tamanioHoja','tipoTapa', 'tipoHoja','producto.marca', 'producto.categoria', 'producto.tipoProducto');
        $data=[
            'code'=> 200,
            'status'=>'success',
            'Agenda'=>$Agenda
        ];
        return response()->json($data);
    }
    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de Agenda para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $Agenda = Agenda::findOrFail($id)->load('secciones','producto','tamanioHoja','tipoTapa', 'tipoHoja','producto.marca', 'producto.categoria', 'producto.tipoProducto');;
            $result->code = 200;
            $result->status='success';
            $result->Agenda=$Agenda;
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
                'Producto_id'=>'required',
                'TamanioHoja_id'=>'required',
                'TipoHoja_id'=>'required',
                'TipoTapa_id'=>'required',
                'Seccion_id'=>'required']);
            if($validate->fails()){
                $data=[
                    'code'=>400,
                    'status'=> 'error',
                    'mensaje'=>'La Agendo no ha podido Crearse',
                    'errores'=>$validate->errors()];
            }else{
                $Agenda = new Agenda();
                $Agenda->cantidad_hojas = $request->cantidad_hojas;
                $Agenda->Producto_id=$request->Producto_id;
                $Agenda->TamanioHoja_id=$request->TamanioHoja_id;
                $Agenda->TipoHoja_id=$request->TipoHoja_id;
                $Agenda->TipoTapa_id=$request->TipoTapa_id;
                $Agenda->save();
                $Agenda->secciones()->attach($request->Seccion_id);
                $data=[
                    'code'=>200,
                    'status'=> 'success',
                    'Agenda'=>$Agenda];
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
            $configuracionAgendas= ConfiguracionAgenda::where('Agenda_id','=',$request->id)->get();
            foreach($configuracionAgendas as $fc){
                $fc->delete();
            }
            $id = $request->id;
            $Agenda = Agenda::findOrFail($id);
            $Agenda->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Agenda Eliminada Exitosamente';
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
                'Producto_id'=>'required',
                'TamanioHoja_id'=>'required',
                'TipoHoja_id'=>'required',
                'TipoTapa_id'=>'required',
                'Seccion_id'=>'required']);
            if($validate->fails()){
                $data=[
                    'code'=>400,
                    'status'=> 'error',
                    'mensaje'=>'La Agendo no ha podido Editarse',
                    'errores'=>$validate->errors()];
            }else{
                $Agenda = Agenda::findOrFail($request->id);
                $Agenda->cantidad_hojas = $request->cantidad_hojas;
                unset($request->id);
                unset($request->TamanioHoja_id);
                unset($request->TipoHoja_id);
                unset($request->TipoTapa_id);
                unset($request->Producto_id);
                $Agenda->save();
                $data=[
                    'code'=>200,
                    'status'=> 'success',
                    'Agenda'=>$Agenda];
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
