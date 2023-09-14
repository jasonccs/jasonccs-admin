<?php

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use App\Models\utils\JsonResponse;
    use App\Notifications\InvoicePaid;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Notification;
    use Illuminate\Support\Facades\Route;
    use Rap2hpoutre\LaravelLogViewer\LogViewerController;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "web" middleware group. Make something great!
    |
    */

// 日志插件
    Route::get('logs', [LogViewerController::class, 'index']);

    // a法，返回请求内容。
    Route::get('/a', function (Request $request) {
        $user = [
            'name' => 1,
            'age' => 19
        ];
        return JsonResponse::success(['user' => $user, __('你好世界', [], 'en')]);
    })->name('a-detail');

//  b法，返回请求内容。
    Route::post('/b', function (Request $request) {
        $user = [
            'name' => 1,
            'age' => 19
        ];
        return JsonResponse::success(['user' => $user]);
    });

//抛出预期的错误，例如请求字段格式错误
    Route::get('/c', [Controller::class, 'store'])->middleware('validation');

    // a法，返回请求内容。
    Route::get('/d', function (Request $request) {
        $invoice = [
            'name' => 1,
            'age' => 19
        ];
        $user = ['1521910992@qq.com'];
        $users = User::whereIn('id', [1, 2, 3])->get(); // 获取要通知的用户
        // 延迟推送
        Notification::send($users, (new InvoicePaid($invoice)));
        return JsonResponse::success(['user' => $user, __('你好世界', [], 'en')]);
    })->name('a-detail');

// 根据IP 获取定位的城市
    Route::get('/f', function (Request $request) {
        return JsonResponse::success(geoip()->getLocation('120.79.183.110')->toArray());
    })->name('f-detail');
