<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CategoriaController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.auth');
    }
    public function all(){
        $categoria= Categoria::all();
        $data=[
            'code'=>200,
            'status'=>'success',
            'categoria'=>$categoria
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de la categoria para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $categoria = Categoria::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->categoria=$categoria;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'categoria'=>$categoria];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de la categoria para editar";
            return response()->json($result);
        }

        try{
            $categoria = Categoria::findOrFail($request->id);
            $categoria->nombre = $request->nombre;
            $categoria->descripcion = $request->descripcion;
            $categoria->save();
            $result->code = 200;
            $result->status='success';
            $result->categoria=$categoria;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id de la categoria';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de la categoria para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Categoria Eliminada Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }


}
