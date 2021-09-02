<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracionFlashCard;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConfiguracionFlashCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function all(){
        $configuracionFlashCard=ConfiguracionFlashCard::all()->load('diseño','tipoLinea');
        $data=[
            'code'=> 200,
            'status'=>'success',
            'Configuracion Flash Card'=>$configuracionFlashCard
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de la configuracion Flash Card para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracionFlashCard = ConfiguracionFlashCard::findOrFail($id)->load('diseño','tipoLinea');
            $result->code = 200;
            $result->status='success';
            $result->configuracionFlashCard=$configuracionFlashCard;
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
            $result->message = "Debes seleccionar un id de la configuracion Flash Card para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracionFlashCard = ConfiguracionFlashCard::findOrFail($id);
            $configuracionFlashCard->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Configuracion Flash Card Eliminada Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $configuracionFlashCard = new ConfiguracionFlashCard();
        $configuracionFlashCard->colorrgb = $request->colorrgb;
        $configuracionFlashCard->DetallePedido_id=$request->DetallePedido_id;
        $configuracionFlashCard->FlashCard_id=$request->FlashCard_id;
        $configuracionFlashCard->Diseno_id=$request->Diseno_id;
        $configuracionFlashCard->TipoFlashCard_id=$request->TipoFlashCard_id;
        $configuracionFlashCard->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'Configuracion Flash Card'=>$configuracionFlashCard];
        return response()->json($data);
    }

    Public function editar(Request $request){
        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->message = "Debes seleccionar un id de la Configuracion Flash Card para editar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $configuracionFlashCard = ConfiguracionFlashCard::findOrFail($id)->load('diseño','tipoLinea');
            $configuracionFlashCard->colorrgb = $request->colorrgb;
            $configuracionFlashCard->DetallePedido_id=$request->DetallePedido_id;
            $configuracionFlashCard->FlashCard_id=$request->FlashCard_id;
            $configuracionFlashCard->Diseno_id=$request->Diseno_id;
            $configuracionFlashCard->TipoFlashCard_id=$request->TipoFlashCard_id;
            $configuracionFlashCard->save();
            $result->code = 200;
            $result->status='success';
            $result->configuracionFlashCard=$configuracionFlashCard;

        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id de la Configuracion Flash Card ';
        }

        return response()->json($result);
    }
}
