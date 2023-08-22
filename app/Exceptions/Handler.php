<?php

namespace App\Exceptions;

use App\Models\ErrorLog;
use App\Models\utils\JsonResponse;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected ErrorLog $logEntry;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->logEntry = new ErrorLog();
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function render($request, Throwable $exception): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|ResponseAlias|\Illuminate\Http\RedirectResponse
    {

        if (!config('app.debug')) {
            return  JsonResponse::error('Internal Server Error', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
        if ($exception instanceof ValidationException) { //bad req
            return  JsonResponse::error($exception->errors(), ResponseAlias::HTTP_BAD_REQUEST);
        }else if ($exception instanceof NotFoundHttpException){ //404
            return  JsonResponse::error($exception->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        }
        return  JsonResponse::error($exception->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);

    }


    public function report(Throwable $exception)
    {
        if ($this->shouldntReport($exception)) {
            // 启用sql查询日志
            $this->logEntry->request_id = request()->request_id;
            $this->logEntry->error_type = get_class($exception);
            $this->logEntry->error_message = $exception->getMessage();
            $this->logEntry->error_trace = $exception->getTraceAsString();
            try {
                $this->logEntry->save();
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
            Log::error($exception);
        }
    }
}
