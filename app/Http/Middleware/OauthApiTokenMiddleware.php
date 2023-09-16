<?php

namespace App\Http\Middleware;

use App\Models\utils\JsonResponse;
use Closure;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class OauthApiTokenMiddleware extends   BaseMiddleware{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {

        try {
            $user = $this->auth?->parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return JsonResponse::error('无效的token',Response::HTTP_OK);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return JsonResponse::error('token 已过期',Response::HTTP_OK);
            } else {
                return JsonResponse::error('缺失请求的授权头token',Response::HTTP_OK);
            }
        }
        return $next($request);
    }
}

