<?php

namespace App\Http\Middleware;

use App\Models\utils\JsonResponse;
use Closure;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use \Larke\Admin\Middleware\Authenticate as LarkeAdminAuth ;
use Yansongda\Supports\Str;

class TelescopeAuthMiddleware extends LarkeAdminAuth{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        try {
            $origin = $request->server('REQUEST_URI') ?: '';
            if (Str::startsWith($origin,'/telescope')){
                $cookies = $request->cookie('token');
                $request->headers->set('Authorization', 'Bearer '.$cookies);
            }
        } catch (\Exception $e) {
             return JsonResponse::error($e->getMessage(),Response::HTTP_OK);
        }
        return $next($request);
    }
}

