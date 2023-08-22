<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RequestLog;
use Symfony\Component\HttpFoundation\Response;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $request->request_id = (string) Str::uuid();
        $response = $next($request);
        $logEntry = new RequestLog();
        $logEntry->request_id = $request->request_id;
        $logEntry->method = $request->method();
        $logEntry->path = $request->path();
        $logEntry->request_data = json_encode($request->all());
        $logEntry->response_content = $response->getContent();
        $logEntry->save();
        return $response;
    }
}
