<?php

namespace App\Http\Controllers;

use App\Http\Annotations\ValidateRequestParams;
use App\Models\User;
use App\Models\utils\JsonResponse;
use Doctrine\Common\Annotations\AnnotationReader;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Larke\Admin\Annotation\RouteRule;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    /**
     * @ValidateRequestParams(
     *     rules={
     *         "name"={"NotNull", "Length(min=3,max=10)"},
     *         "email"={"Email","NotNull"},
     *         "age"={"Regex(/^[A-Za-z]+$/)"}
     *     },
     *     errorMessages={
     *         "name "={
     *             "NotNull"="必填啊.",
     *             "Length"="长度 3-10 位啊."
     *         },
     *         "email"={
     *             "NotNull"="不能为空.",
     *             "Email"="必须为有效的邮箱."
     *         },
     *         "age" ={
     *              "Regex"="必须为字母组合类型"
     *          }
     *     }
     * )
     */
    // 存储用户的信息
    public function store(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        return JsonResponse::success([], '保存成功');
    }

    public function telescope(Request $request): \Illuminate\Http\JsonResponse
    {
        $user=Auth::loginUsingId(1); // 注意 App\Providers\TelescopeServiceProvider 中的 gate() 邮箱对应才有权限
//        Auth::logout();// 退出
//        dd($user);
        return JsonResponse::success([], '保存成功');
    }

}
