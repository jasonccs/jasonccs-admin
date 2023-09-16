<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticate implements JWTSubject
{

    use HasApiTokens, HasFactory, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format(Carbon::now()->toDateTimeString());
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'id',
        'email_verified_at',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
//    protected $casts = [
//        'name' => 'required|between:2,6',
//        'password' => 'required|numeric',
//    ];

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
