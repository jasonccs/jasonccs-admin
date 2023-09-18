<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\Passport\PassportController;
use App\Jobs\SendUserJob;
use App\Models\User\User;
use App\Models\utils\JsonResponse;
use App\Notifications\InvoicePaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

// 日志插件
    Route::get('logs', [LogViewerController::class, 'index']);
    // 数据监控面板
//    Route::get('/telescope', [Controller::class, 'telescope']);

    Route::get('/slack', function (Request $request) {
        logger()->channel('slack')->critical(
            '错误的消息',
            [
                'environment' => app()->environment(),
                'url' => app()->runningInConsole() ? 'CLI' : request()->method() . ' ' . request()->fullUrl(),
                'user' => '',
                'view in Telescope' => url('telescope/exceptions/' . 1),
            ]
        );
        return JsonResponse::success('发了slack 通知 请注意查收');
    })->name('a-detail');


//    Route::middleware(['throttle:uploads'])->namespace('App\Http\Controllers\Passport')->group(function ($outer) {
    Route::namespace('App\Http\Controllers\Passport')->group(function ($outer) {

        Route::controller(PassportController::class)->group(function () {
            Route::get('/orders/{id}', 'show');
            Route::get('/passport', 'passport')->name('passport');
        });

    });

    // a法，返回请求内容。
    Route::get('/aa', function (Request $request) {
        $user = [
            'name' => 1,
            'age' => 19
        ];
        return JsonResponse::success(['user' => $user, __('你好世界', [], 'en')]);
    })->name('a-detail');

//  b法，返回请求内容。
    Route::get('/b', function (Request $request) {
        $request->session()->put('state', $state = Str::random(40));
        $http = new GuzzleHttp\Client;
        $response = $http->post('http://jason-admin.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => '3',
                'client_secret' => '8FiWSyWry2ckfX7tJyCDDbV6Wj9eOYMohBhO8cgi',
                'redirect_uri' => 'http://jason-admin.com/b',
                'code' => $request->input('code'),
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    });

//抛出预期的错误，例如请求字段格式错误
    Route::get('/c', [Controller::class, 'store'])->middleware('validation');

    // 发送消息队列
    Route::get('/d', function (Request $request) {
        //php artisan queue:listen database --queue='notifyEmail' --tries=3  --sleep=3 --timeout=60
        $users = User::whereIn('id', [1, 2, 3])->get(); // 获取要通知的用户
        Notification::send($users, (new InvoicePaid())->onQueue('notifyEmail'));
        // 或者这个
//      Notification::route('mail','1521910992@qq.com')->notify(new InvoicePaid());
        return JsonResponse::success('邮件队列通知发送成功');

    })->name('d-detail');

    //发送一个延迟的队列 （创建用户的其他的信息）
    Route::get('/k', function (Request $request) {
        $user = [
            'name' => '字段是',
            'password' => '字段是',
            'email' => '152921@qq.com',
        ];
        dispatch(new SendUserJob($user))->onQueue('userCreateTask')->onConnection('database')->delay(5); // 使用的是  database 存储驱动
        # 最后执行 php artisan  queue:work  database --queue='userCreateTask'  --tries=3 --sleep=3 --timeout=60
    })->name('k-detail');

    // 查询发送的 使用 Notification 通知，进行分页
    Route::get('/h', function (Request $request) {

        $user = User::findOrFail(1);
        $notifications = $user->notifications()->paginate(20);
        return JsonResponse::success($notifications);

    })->name('h-detail');



// 根据IP 获取定位的城市
    Route::get('/f', function (Request $request) {
        return JsonResponse::success(geoip()->getLocation('120.79.183.110')->toArray());
    })->name('f-detail');


    // 获取ip 城市定位
    Route::get('/g', function (Request $request) {

    //    $location = GeoIP::getLocation('222.128.24.20')->toArray();
        dd(geoip('36.157.165.83')->toArray());
    });

//Auth::routes();

Route::get('/home', [\App\Http\Controllers\Web\HomeController::class, 'index'])->name('home');
Route::get('/logout', [\App\Http\Controllers\Web\HomeController::class, 'logout'])->name('logout');

//Route::namespace('App\Http\Controllers\Auth')->group(function ($outer) {
//
//    Route::get('login', 'LoginController@showLoginForm')->name('login');
//
//    Route::post('login', 'LoginController@login');
//
//});
Route::get('/login2', function () {
    return view('login');
});
