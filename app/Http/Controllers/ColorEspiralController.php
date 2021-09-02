<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ColorEspiral;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ColorEspiralController extends Controller
{
    public function all(){
        $colorespiral = ColorEspiral::all()->load('tipoespiral');
        $data=[
            'code'=> 200,
            'status'=>'success',
            'colorespiral'=>$colorespiral
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de colorespiral para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $colorespiral = ColorEspiral::findOrFail($id)->load('tipoespiral');
            $result->code = 200;
            $result->status='success';
            $result->colorespiral=$colorespiral;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $colorespiral = new ColorEspiral();
        $colorespiral->color = $request->color;
        $colorespiral->colorrgb=$request->colorrgb;
        $colorespiral->estado=$request->estado;
        $colorespiral->TipoEspiral_id=$request->TipoEspiral_id;
        $colorespiral->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'ColorEspiral'=>$colorespiral];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id del colorespiral para editar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $colorespiral = ColorEspiral::findOrFail($id)->load('tipoespiral');
            $colorespiral->color = $request->color;
            $colorespiral->colorrgb=$request->colorrgb;
            $colorespiral->estado=$request->estado;
            $colorespiral->TipoEspiral_id=$request->TipoEspiral_id;
            $colorespiral->save();
            $result->code = 200;
            $result->status='success';
            $result->colorespiral=$colorespiral;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del colorespiral';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del colorespiral para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $colorespiral = ColorEspiral::findOrFail($id);
            $colorespiral->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Colorespiral Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
