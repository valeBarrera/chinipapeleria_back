<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MarcaController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.auth');
    }
    public function all(){
        $marca= Marca::all();
        $data=[
            'code'=>200,
            'status'=>'success',
            'marca'=>$marca
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de la marca de pago para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $marca = Marca::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->marca=$marca;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $marca = new Marca();
        $marca->nombre = $request->nombre;
        $marca->descripcion = $request->descripcion;
        $marca->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'medioPago'=>$marca];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de la marca para editar";
            return response()->json($result);
        }

        try{
            $marca = Marca::findOrFail($request->id);
            $marca->nombre = $request->nombre;
            $marca->descripcion = $request->descripcion;
            $marca->save();
            $result->code = 200;
            $result->status='success';
            $result->marca=$marca;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id de la marca';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccion un id de la marca para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $marca = Marca::findOrFail($id);
            $marca->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Marca Eliminada Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
