<?php

use App\Http\Controllers\Controller;
use App\Models\utils\JsonResponse;
use Illuminate\Http\Request;
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
        'name'=>1,
        'age'=>19
    ];
    return JsonResponse::success(['user' => $user,__('你好世界',[],'en')]);
})->name('a-detail');

//  b法，返回请求内容。
Route::post('/b', function (Request $request) {
    $user = [
        'name'=>1,
        'age'=>19
    ];
    return JsonResponse::success(['user' => $user]);
});

//抛出预期的错误，例如请求字段格式错误

Route::get('/c', [Controller::class, 'store'])->middleware('validation');

//，d法 抛出意外的错误。
Route::post('/d', function (Request $request) {
    $c = 0;
    $res = 1/$c;
});

//  使用URL查询参数's'进行以下逻辑测试。
Route::get('/e', function (Request $request) {
    $s = $request->input('s');

    $stack = [];
    $brackets = [
        ')' => '(',
        ']' => '[',
        '}' => '{',
    ];

    foreach (str_split($s) as $char) {
        if (in_array($char, array_values($brackets))) {
            $stack[] = $char;
        } elseif (in_array($char, array_keys($brackets))) {
            if (empty($stack) || array_pop($stack) !== $brackets[$char]) {
                return JsonResponse::error('Invalid brackets', 400);
            }
        }
    }
    if (empty($stack)) {
        return JsonResponse::success('Valid brackets');
    } else {
        return JsonResponse::error('Invalid brackets', 400);
    }
});

// 根据IP 获取定位的城市
Route::get('/f', function (Request $request) {
    return JsonResponse::success(geoip()->getLocation('120.79.183.110')->toArray());
})->name('f-detail');
