<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Cors
{

    private array $allow_origin = [
        'http://localhost:9527',
        'http://localhost:8080',
        'http://jason-admin.whweimei.cn'
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        if (in_array($origin, $this->allow_origin)) {
            $response->header('Access-Control-Allow-Origin',$origin);
            $response->header('Access-Control-Allow-Headers', 'X-Requested-With, Access-Control-Request-Method, Access-Control-Request-Headers, X-Token, Access-Token, Authorization, Origin, Content-Type, Cookie, Accept');
            $response->header('Access-Control-Expose-Headers', 'X-My-Custom-Header, X-Another-Custom-Header');
            $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT,DELETE,HEAD,TRACE, OPTIONS');
            $response->header('Access-Control-Allow-Credentials', 'true');
        }
        return $response;
    }

    /**
     * 在响应发送到浏览器后处理任务。
     */
    public function terminate(Request $request, Response $response): void
    {

    }

}
