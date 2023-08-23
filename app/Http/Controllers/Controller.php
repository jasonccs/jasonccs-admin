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
     *         "field2"={"Email"}
     *     },
     *     errorMessages={
     *         "field1"={
     *             "Symfony\Component\Validator\Constraints\NotNull"="The field1 field is required.",
     *             "Symfony\Component\Validator\Constraints\Length"="The field1 field length must be between 3 and 10 characters."
     *         },
     *         "field2"={
     *             "Symfony\Component\Validator\Constraints\Email"="The field2 field must be a valid email address."
     *         }
     *     }
     * )
     */
    // 存储用户的信息
    public function store(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        // 验证请求
//        $errors = $user->validateRequest($request->all());
//        if ($errors) {
//            return JsonResponse::error(['errors' => $errors], 400);
//        }
        // 验证通过，继续处理逻辑...
         return JsonResponse::success([], '保存成功');
    }

}
