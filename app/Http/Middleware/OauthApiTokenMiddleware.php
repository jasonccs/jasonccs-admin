<?php

namespace App\Http\Middleware;

use App\Models\utils\JsonResponse;
use Closure;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Yansongda\Supports\Str;
use Illuminate\Support\Facades\Auth;

class OauthApiTokenMiddleware extends   BaseMiddleware{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {

        try {
            $origin = $request->server('REQUEST_URI') ?: '';
//
            if (Str::startsWith($origin,'/telescope')){
//                return redirect()->route('login',['errors'=>[]]);
                return redirect('api/auth/c')->with('status', 'Profile updated!');
            }
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

