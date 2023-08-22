<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'required|between:2,6',
        'password' => 'required|numeric',
    ];
    public function validateRequest($requestData): ?\Illuminate\Support\MessageBag
    {
        $rules = [
            'name' => 'required',
            'password' => 'required',
            // 添加其他字段的验证规则...
        ];
        $messages = [
            'name.required' => 'name 字段是必填的。',
            'name.between' => 'name 最小6个字符',
            'password.required' => 'password 是必须的',
            'password.numeric' => 'password 字段必须为数字。',
            // 添加其他字段的错误消息...
        ];
        $validator = Validator::make($requestData, $rules, $messages);

        if ($validator->fails()) {
            return $validator->errors();
        }
        return null; // 验证通过，返回 null
    }


}
