<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TamanioHoja;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TamanioHojaController extends Controller
{
    public function all(){
        $tamaniohoja= TamanioHoja::all();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'usuario'=>$tamaniohoja];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){
        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de tamaño de hoja para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tamaniohoja = TamanioHoja::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->tamaniohoja=$tamaniohoja;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $tamaniohoja = new TamanioHoja();
        $tamaniohoja->nombre = $request->nombre;
        $tamaniohoja->ancho = $request->ancho;
        $tamaniohoja->largo = $request->largo;
        $tamaniohoja->unidad_medida = $request->unidad_medida;
        $tamaniohoja->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'tamaniohoja'=>$tamaniohoja];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de tamaño de hoja para editar";
            return response()->json($result);
        }

        try{
            $tamaniohoja = TamanioHoja::findOrFail($request->id);
            $tamaniohoja->nombre = $request->nombre;
            $tamaniohoja->ancho = $request->ancho;
            $tamaniohoja->largo = $request->largo;
            $tamaniohoja->unidad_medida = $request->unidad_medida;
            $tamaniohoja->save();
            $result->code =200;
            $result->status='success';
            $result->tamaniohoja=$tamaniohoja;

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
            $result->message = "Debes seleccionar un id de tamaño hoja para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $tamaniohoja = TamanioHoja::findOrFail($id);
            $tamaniohoja->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='TamanioHoja Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
