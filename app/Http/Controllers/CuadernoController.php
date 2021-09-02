<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionCuaderno;
use Illuminate\Http\Request;
use App\Models\Cuaderno;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class CuadernoController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['all','buscarPorID']]);
    }

    public function all(){
        $cuaderno = Cuaderno::all()->load('producto', 'producto.marca', 'producto.categoria', 'producto.tipoProducto');
        $data=[
            'code'=> 200,
            'status'=>'success',
            'cuaderno'=>$cuaderno
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de cuaderno para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $cuaderno = Cuaderno::findOrFail($id)->load('producto', 'producto.marca', 'producto.categoria', 'producto.tipoProducto');
            $result->code = 200;
            $result->status='success';
            $result->cuaderno=$cuaderno;
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
                'Producto_id'=>'required']);

            if($validate->fails()){
                $data=[
                    'code'=>400,
                    'status'=> 'error',
                    'mensaje'=>'La cuaderno no ha podido crearse',
                    'errores'=>$validate->errors()];
            }else{

                $cuaderno = new Cuaderno();
                $cuaderno->Producto_id=$request->Producto_id;
                $cuaderno->save();
                $data=[
                    'code'=>200,
                    'status'=> 'success',
                    'cuaderno'=>$cuaderno];
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
            $result->message = "Debes seleccionar un id del cuaderno para editar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $cuaderno = Cuaderno::findOrFail($id)->load('producto');
            $cuaderno->Producto_id=$request->Producto_id;
            unset($request->id);
            $cuaderno->save();
            $result->code = 200;
            $result->status='success';
            $result->cuaderno=$cuaderno;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del cuaderno';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del cuaderno para Eliminar";
            return response()->json($result);
        }

        try{

            $configuacionCuadernos= ConfiguracionCuaderno::where('Cuaderno_id','=',$request->id)->get();
            foreach($configuacionCuadernos as $cc){
                $cc->delete();
            }

            $id = $request->id;
            $cuaderno = Cuaderno::findOrFail($id);
            $cuaderno->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Cuaderno Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
