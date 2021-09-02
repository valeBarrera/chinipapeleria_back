<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionFlashCard;
use Illuminate\Http\Request;
use App\Models\FlashCard;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FlashCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except'=>['all','buscarPorID']]);
    }

    public function all(){
        $flashCard=FlashCard::all()->load('producto');
        $data=[
            'code'=> 200,
            'status'=>'success',
            'Flash Card'=>$flashCard
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del Flash Card para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $flashCard = FlashCard::findOrFail($id)->load('producto');
            $result->code = 200;
            $result->status='success';
            $result->flashCard=$flashCard;
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
            $result->message = "Debes seleccionar un id de la Flash Card para Eliminar";
            return response()->json($result);
        }

        try{
            $flashCards= ConfiguracionFlashCard::where('FlashCard_id','=',$request->id)->get();
            foreach($flashCards as $fc){
                $fc->delete();
            }
            $id = $request->id;
            $flashCard = FlashCard::findOrFail($id);
            $flashCard->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Flash Card Eliminada Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $flashCard = new FlashCard();
        $flashCard->descripcion = $request->descripcion;
        $flashCard->cantidad_hojas =$request->cantidad_hojas;
        $flashCard->ancho=$request->ancho;
        $flashCard->largo=$request->largo;
        $flashCard->unidad_medida=$request->unidad_medida;
        $flashCard->Producto_id=$request->Producto_id;
        $flashCard->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'Flash Card'=>$flashCard];
        return response()->json($data);
    }

    Public function editar(Request $request){
        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id del Flash Card para editar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $flashCard = FlashCard::findOrFail($id)->load('producto');
            $flashCard->descripcion = $request->descripcion;
            $flashCard->cantidad_hojas =$request->cantidad_hojas;
            $flashCard->ancho=$request->ancho;
            $flashCard->largo=$request->largo;
            $flashCard->unidad_medida=$request->unidad_medida;
            $flashCard->Producto_id=$request->Producto_id;
            $flashCard->save();
            $result->code = 200;
            $result->status='success';
            $result->flashCard=$flashCard;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id de la Flash Card ';
        }

        return response()->json($result);
    }

}
