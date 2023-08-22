<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *  错误日志处理类
 *  记录到日志中
 */
class ErrorLog extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'request_id',
        'error_type',
        'error_message',
        'error_trace',
    ];
    protected $guarded = [];

}
