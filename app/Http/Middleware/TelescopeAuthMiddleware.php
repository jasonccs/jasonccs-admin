<?php

namespace App\Http\Middleware;

use App\Models\utils\JsonResponse;
use Closure;
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
//                dd(111);
                $request->headers->set('Authorization', 'Bearer ');
            }
        } catch (\Exception $e) {
             return JsonResponse::error('222222222',Response::HTTP_OK);
        }
        return $next($request);
    }
}

