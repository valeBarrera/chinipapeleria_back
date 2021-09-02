<?php

namespace App\Http\Middleware;
use App\Helpers\JwtAuth;
use Closure;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checktoken= $jwtAuth->checkToken($token);

        if($checktoken){
            return $next($request);
        }else{
            $data=[
                'code'=>400,
                'status'=> 'error',
                'mensaje'=>'El Usuario No Esta Identificado'];
            return response()->json($data);
        }

    }

}
