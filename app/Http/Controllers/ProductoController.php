<?php

namespace App\Http\Controllers;
use App\Helpers\Role;
use App\Models\Agenda;
use App\Models\Cuaderno;
use App\Models\FlashCard;
use App\Models\Lapiz;
use App\Models\Planificador;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DateTime;

class ProductoController extends Controller
{

    public function all(){
        $producto= Producto::all()->load('categoria', 'marca', 'tipoproducto');
        $data=[
            'code'=>200,
            'status'=>'success',
            'Producto'=>$producto
        ];
        return response()->json($data);
    }

    public function buscarPorID(Request $request){

        $result = new \stdClass();
        $result->code = 200;

        if($request->id == ''){
            $result->code=400;
            $result->status='error';
            $result->message = "Debes seleccionar un id del producto para buscar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $producto = Producto::findOrFail($id)->load('categoria', 'marca', 'tipoproducto');
            $result->code = 200;
            $result->status='success';
            $result->producto=$producto;
        }catch(ModelNotFoundException $e){
            $result->code =400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }

    Public function crear(Request $request){

        $token = $request->header('Authorization');

        $role= new Role();
        if($role->admin($token)){
            $validator = Validator::make($request->all(), [
                'nombre' => 'bail|required|string',
                'precio' => 'bail|required|numeric',
                'stock' => 'bail|required|numeric',
                'Categoria_id' => 'bail|required|numeric',
                'Marca_id' => 'bail|required|numeric',
                'TipoProducto_id' => 'bail|required|numeric',
                'img' => 'required|file|mimes:jpeg,bmp,png,jpg',
                'TipoPunta_id' => 'numeric',
                'color' => 'string',
                'colorrgb' => 'string',
                'descripcion' => 'string',
                'alto' => 'numeric',
                'cantidad_hojas' => 'numeric',
                'ancho' => 'numeric',
                'unidad_medida' => 'string',
                'TipoHoja_id' => 'numeric',
                'TipoTapa_id' => 'numeric',
                'ColorEspiral_id' => 'numeric',
                'TamanioHoja_id' => 'numeric',
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

            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->precio=$request->precio;
            $producto->stock=$request->stock;
            $path = $request->img->store('public/producto');
            $path = str_replace("public/", "storage/", $path);
            $producto->img = $path;
            //$producto->TipoProducto_id=$request->TipoProducto_id;
            $producto->Categoria_id=$request->Categoria_id;
            $producto->Marca_id=$request->Marca_id;
            $producto->TipoProducto_id = $request->TipoProducto_id;

            switch ($producto->TipoProducto_id) {
                case Producto::LAPIZ:
                    if (
                        !isset($request->descripcion) || !isset($request->color)
                        || !isset($request->colorrgb) || !isset($request->TipoPunta_id)
                    ) {
                        $resp = new \stdClass();
                        $resp->code = 200;
                        $resp->status = 'error';
                        $resp->message = 'No cumple con las precondiciones de los campos';
                        return response(json_encode($resp), 200)
                        ->header('Content-Type', 'application/json');
                    }
                    break;
                case Producto::CUADERNO:
                    //No tiene caracteristicas no configurables
                    break;
                case Producto::PLANIFICADOR:
                    if (!isset($request->cantidad_hojas) || !isset($request->TipoHoja_id)
                        || !isset($request->TipoTapa_id) || !isset($request->ColorEspiral_id)
                        || !isset($request->TamanioHoja_id)) {
                        $resp = new \stdClass();
                        $resp->code = 200;
                        $resp->status = 'error';
                        $resp->message = 'No cumple con las precondiciones de los campos';
                        return response(json_encode($resp), 200)
                            ->header('Content-Type', 'application/json');
                    }
                    break;
                case Producto::AGENDA:
                    if (!isset($request->cantidad_hojas) || !isset($request->TipoHoja_id)
                    || !isset($request->TipoTapa_id)  || !isset($request->TamanioHoja_id)) {
                    $resp = new \stdClass();
                    $resp->code = 200;
                    $resp->status = 'error';
                    $resp->message = 'No cumple con las precondiciones de los campos';
                    return response(json_encode($resp), 200)
                        ->header('Content-Type', 'application/json');
                }
                    break;
                case Producto::FLASHCARD:
                    if (!isset($request->descripcion) || !isset($request->ancho)
                        || !isset($request->alto) || !isset($request->unidad_medida) || !isset($request->cantidad_hojas)) {
                        $resp = new \stdClass();
                        $resp->code = 200;
                        $resp->status = 'error';
                        $resp->message = 'No cumple con las precondiciones de los campos';
                        return response(json_encode($resp), 200)
                            ->header('Content-Type', 'application/json');
                    }
                    break;
            }

            //Se comprobo que los campos para la especificacion de cada producto estan
            //se guarda el producto, para tener un id de producto
            $producto->save();

            switch ($producto->TipoProducto_id) {
                case Producto::LAPIZ:
                    $lapiz = new Lapiz();
                    $lapiz->color = $request->color;
                    $lapiz->color_rgb = $request->colorrgb;
                    $lapiz->descripcion = $request->descripcion;
                    $lapiz->Producto_id = $producto->id;
                    $lapiz->TipoPunta_id = $request->TipoPunta_id;
                    $lapiz->save();
                    break;
                case Producto::CUADERNO:
                    $cuaderno = new Cuaderno();
                    $cuaderno->Producto_id = $producto->id;
                    $cuaderno->save();
                    break;
                case Producto::PLANIFICADOR:
                    $planificador = new Planificador();
                    $planificador->Producto_id = $producto->id;
                    $planificador->cantidad_hojas = $request->cantidad_hojas;
                    $planificador->TamanioHoja_id = $request->TamanioHoja_id;
                    $planificador->TipoTapa_id = $request->TipoTapa_id;
                    $planificador->TipoHoja_id = $request->TipoHoja_id;
                    $planificador->ColorEspiral_id = $request->ColorEspiral_id;
                    $planificador->save();
                    break;
                case Producto::AGENDA:
                    $agenda = new Agenda();
                    $agenda->Producto_id = $producto->id;
                    $agenda->cantidad_hojas = $request->cantidad_hojas;
                    $agenda->TamanioHoja_id = $request->TamanioHoja_id;
                    $agenda->TipoTapa_id = $request->TipoTapa_id;
                    $agenda->TipoHoja_id = $request->TipoHoja_id;
                    $agenda->save();
                    break;
                case Producto::FLASHCARD:
                    $flashcard = new FlashCard();
                    $flashcard->Producto_id = $producto->id;
                    $flashcard->descripcion = $request->descripcion;
                    $flashcard->unidad_medida = $request->unidad_medida;
                    $flashcard->cantidad_hojas = $request->cantidad_hojas;
                    $flashcard->ancho = $request->ancho;
                    $flashcard->largo = $request->alto;
                    $flashcard->save();
                    break;
            }

            $resp = new \stdClass();
            $resp->code = 200;
            $resp->status = 'success';
            $resp->message = 'Producto registrado con éxito.';
            $resp->data = $producto;
            return response(json_encode($resp), 200)
                ->header('Content-Type', 'application/json');
        }

        $resp = new \stdClass();
        $resp->code = 400;
        $resp->status = 'error';
        $resp->message = 'No tiene autorización para realizar esto.';
        return response(json_encode($resp), 200)
            ->header('Content-Type', 'application/json');
    }

    public function editar(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|numeric',
            'nombre' => 'bail|nullable|string',
            'precio' => 'bail|nullable|numeric',
            'stock' => 'bail|nullable|numeric',
            'Categoria_id' => 'bail|nullable|numeric',
            'Marca_id' => 'bail|nullable|numeric',
            'img' => 'nullable|file|mimes:jpeg,bmp,png,jpg'
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
            $producto = Producto::findOrFail($request->id);
            if(isset($request->nombre)){
                $producto->nombre = $request->nombre;
            }
            if(isset($request->precio)){
                $producto->precio = $request->precio;
            }
            if(isset($request->stock)){
                $producto->stock = $request->stock;
            }
            if(isset($request->Categoria_id)){
                $producto->Categoria_id = $request->Categoria_id;
            }
            if(isset($request->Marca_id)){
                $producto->Marca_id = $request->Marca_id;
            }
            if(isset($request->img)){
                $path = $request->img->store('public/producto');
                $path = str_replace("public/", "storage/", $path);
                $producto->img = $path;
            }

            $producto->save();

            $resp = new \stdClass();
            $resp->code = 200;
            $resp->status = 'success';
            $resp->message = 'Diseño actualizado con éxito.';
            $resp->data = $producto;
            return response(json_encode($resp), 200)
                ->header('Content-Type', 'application/json');

        }catch(ModelNotFoundException $e){
            $resp = new \stdClass();
            $resp->code = 400;
            $resp->status = 'error';
            $resp->message = 'Producto no encontrado';
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
            $result->message = "Debes seleccionar un id del producto para Eliminar";
            return response()->json($result);
        }

        try{
            $id = $request->id;
            $producto = Producto::findOrFail($id);
            $producto->delete();
            $result->code = 200;
            $result->status='success';
            $result->message='Producto Eliminado Exitosamente';
        }catch(ModelNotFoundException $e){
            $result->code = 400;
            $result->status='error';
            $result->message='No se encontro el id';
        }

        return response()->json($result);
    }
}
