<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diseno;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DisenoController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.auth', ['except'=>['all']]);
    }
    public function all(){
        $diseno= Diseno::all();
        $data=[
            'code'=>200,
            'status'=> 'success',
            'medioPago'=>$diseno];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){
        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de diseño para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $diseno = Diseno::findOrFail($id);
            $result->code = 200;
            $result->status='success';
            $result->diseno=$diseno;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){

        $validator = Validator::make($request->all(), [
            'nombre' => 'bail|required|string',
            'image' => 'required|file|mimes:jpeg,bmp,png,jpg'
        ]);

        if ($validator->fails()) {
            $resp = new \stdClass();
            $resp->code = 200;
            $resp->status = 'error';
            $resp->message = 'No cumple con las precondiciones de los campos';
            $resp->errors = $validator->errors();
            return response(json_encode($resp), 200)
                ->header('Content-Type', 'application/json');
        }

        $diseno = new Diseno();
        $diseno->nombre = $request->nombre;
        $path = $request->image->store('public/diseno');
        $path = str_replace("public/", "storage/", $path);
        $diseno->path = $path;
        $diseno->save();
        $resp = new \stdClass();
        $resp->code = 200;
        $resp->status = 'success';
        $resp->message = 'Diseño registrado con éxito.';
        $resp->data = $diseno;
        return response(json_encode($resp), 200)
            ->header('Content-Type', 'application/json');
    }

    public function editar(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'bail|numeric|required',
            'nombre' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,bmp,png,jpg'
        ]);

        if ($validator->fails()) {
            $resp = new \stdClass();
            $resp->code = 200;
            $resp->status = 'error';
            $resp->message = 'No cumple con las precondiciones de los campos';
            $resp->errors = $validator->errors();
            return response(json_encode($resp), 200)
                ->header('Content-Type', 'application/json');
        }

        try{
            $diseno = Diseno::findOrFail($request->id);

            if(!isset($request->nombre)){
                $diseno->nombre = $request->nombre;
            }

            if(!isset($request->image)){
                $path = $request->image->store('public/diseno');
                $path = str_replace("public/", "storage/", $path);
                $diseno->path = $path;
            }

            $diseno->save();

            $resp = new \stdClass();
            $resp->code = 200;
            $resp->status = 'success';
            $resp->message = 'Diseño actualizado con éxito.';
            $resp->data = $diseno;
            return response(json_encode($resp), 200)
                ->header('Content-Type', 'application/json');

        }catch(ModelNotFoundException $e){
            $resp = new \stdClass();
            $resp->code = 200;
            $resp->status = 'error';
            $resp->message = 'Diseño no encontrado';
            return response(json_encode($resp), 200)
                ->header('Content-Type', 'application/json');
        }

    }

    public function eliminar(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code = 400;
            $result->status='error';
            $result->message = "Debes seleccionar un id de seccion para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $diseno = Diseno::findOrFail($id);
            $diseno->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Diseño Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
