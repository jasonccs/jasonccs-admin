<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\utils\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // 存储用户的信息
    public function store(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        // 验证请求
        $errors = $user->validateRequest($request->all());
        if ($errors) {
            return JsonResponse::error(['errors' => $errors], 400);
        }
        // 验证通过，继续处理逻辑...
         return JsonResponse::success([], '保存成功');
    }

}
