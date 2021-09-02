<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DateTime;

use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function all(){
        $venta = Venta::all()->load('medioPago');

        $data=[
            'code'=>200,
            'status'=>'success',
            'venta'=>$venta
        ];
        return response()->json($data);

    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de la Venta para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $venta = Venta::findOrFail($id)->load('mediopago');
            $result->code = 200;
            $result->status='success';
            $result->venta=$venta;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){
        $now= new DateTime('now');
        $venta = new Venta();
        $venta->fecha= $now->format('Y-m-d H:i:s');
        $venta->MedioPago_id= $request->MedioPago_id;
        $venta->save();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'venta'=>$venta];
        return response()->json($data);
    }

}
