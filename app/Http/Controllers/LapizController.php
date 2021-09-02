<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapiz;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LapizController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['all','buscarPorID']]);
    }

    public function all(){
        $lapiz = Lapiz::all()->load('producto','tipopunta');
        $data=[
            'code'=> 200,
            'status'=>'success',
            'lapiz'=>$lapiz
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de lapiz para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $lapiz = Lapiz::findOrFail($id)->load('producto','tipopunta', 'producto.marca', 'producto.categoria', 'producto.tipoProducto');
            $result->code = 200;
            $result->status='success';
            $result->lapiz=$lapiz;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $lapiz = new Lapiz();
        $lapiz->color = $request->color;
        $lapiz->color_rgb=$request->color_rgb;
        $lapiz->descripcion=$request->descripcion;
        $lapiz->Producto_id=$request->Producto_id;
        $lapiz->TipoPunta_id=$request->TipoPunta_id;
        $lapiz->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'Lapiz'=>$lapiz];
        return response()->json($data);
    }

    public function editar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id del lapiz para editar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $lapiz = Lapiz::findOrFail($id)->load('producto','tipopunta');
            $lapiz->color = $request->color;
            $lapiz->color_rgb=$request->color_rgb;
            $lapiz->descripcion=$request->descripcion;
            $lapiz->Producto_id=$request->Producto_id;
            $lapiz->TipoPunta_id=$request->TipoPunta_id;
            $lapiz->save();
            $result->code = 200;
            $result->status='success';
            $result->lapiz=$lapiz;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id del lapiz';
        }

        return response()->json($result);
    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del lapiz para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $lapiz = Lapiz::findOrFail($id);
            $lapiz->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Lapiz Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
