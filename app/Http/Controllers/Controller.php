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
use Larke\Admin\Annotation\RouteRule;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    /**
     * @ValidateRequestParams(
     *     rules={
     *         "field1"={"NotNull", "Length(min=3,max=10)"},
     *         "field2"={"Email","NotNull"}
     *     },
     *     errorMessages={
     *         "field1"={
     *             "NotNull"="必填啊.",
     *             "Length"="长度 3-10 位啊."
     *         },
     *         "field2"={
     *             "NotNull"="The field2 不能为空.",
     *             "Email"="必须为有效的邮箱."
     *         }
     *     }
     * )
     */
    // 存储用户的信息
    public function store(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        return JsonResponse::success([], '保存成功');
    }

}
