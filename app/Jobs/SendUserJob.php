<?php

namespace App\Jobs;


use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Mockery\Exception;


class SendUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public int $maxExceptions = 3;


    public bool $failOnTimeout = true;

    protected array $user;

    /**
     * @param array $user
     * @param int $delay   延迟的意思。可以实现定时任务 单位秒
     */
    public function __construct(array $user, int $delay=10)
    {
        $this->user = $user;
        // 设置延迟的时间，delay() 方法的参数代表多少秒之后执行
        $this->delay($delay);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //判断订单状态是否为已支付的订单

        try {
            $user = User::create([
                'name' => '字段是',
                'password' => '字段是',
                'email' => '15219109921@qq.com',
            ]);
            // 通过事务执行 sql
            DB::transaction(function () {
                User::whereRaw('email =?',['15219109921@qq.com'])->update(['password' => md5(123456)]);
            });
        }catch (Exception $exception){
            Log::error('用户创建失败',['exception'=>$exception->getMessage()]);
        }

    }

    /**
     * 失败处理  存表单独设置定时任务 重新跑
     * @param Exception $exception
     */
    public function failed(Exception $exception)
    {
         Log::error('用户创建失败',['exception'=>$exception->getMessage()]);
    }

}
