<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class InvoicePaid extends Notification implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var int 尝试次数
     */
    public int $tries = 3;

    public int $maxExceptions = 3;

    // 超时时间
    public int $timeout = 120;

    /**
     * @var bool 超时失败
     */
    public bool $failOnTimeout = true;

    //fry自定义的数据
    public array $data;

//    /**
//     * Execute the job.
//     */
//    public function handle(): void
//    {
//
//        Log::info('fry_job_test',$this->data);
//        Redis::throttle('key')->allow(10)->every(60)->then(function () {
//            // Lock obtained, process the podcast...
//        }, function () {
//            // Unable to obtain lock...
//            $this->release(10);
//        });
//    }
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {

    }

    /**
     * 定义每个通知通道应该使用哪个连接。
     *
     * @return array<string, string>
     */
    public function viaConnections(): array
    {
        return [
            'mail' => 'database', //选择什么方式进行存储 database或者redis 在 .env 中 QUEUE_DRIVER 也需要改为 database或者redis php artisan queue:listen database --queue='userCreateTask' --tries=3  --sleep=3 --timeout=60
            'database' => 'sync',
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
       /* return (new MailMessage)
                    ->subject('发票支付成功')
                    ->greeting('你好!')
                    ->line('您有一个新订单.')
                    ->lineIf(true, "支出金额: 111")
                    ->action('Notification Action', url('/'))
                    ->line('感谢您使用我们的服务!');*/
        return (new MailMessage)->view(
            'email.invoice', ['invoice' => $notifiable]
        )->subject('发票支付成功')->action('Notification Action', url('/'));

    }

    /**
     *  通知渠道将通知信息存储在一个数据库 notifications 表中。该表将包含通知类型以及描述通知的 JSON 数据结构等信息
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'send email',
            'notifiable_type' => 'send email',
            'notifiable_id' => 1111,
            'data' => $notifiable->toArray(),
        ];
    }


//    /**
//     * 定义每个通知通道应使用哪条队列。
//     *
//     * @return array<string, string>
//     */
//    public function viaQueues(): array
//    {
//        return [
//            'mail' => 'notifyEmail',
//            'sms' => 'notifySms',
//        ];
//    }

    /**
     * 确定通知的传递延迟.
     * @param object $notifiable
     * @return array
     */
    public function withDelay(object $notifiable): array
    {
        return [
            'mail' => now()->addSeconds(10),
            'sms' => now()->addSeconds(10),
        ];
    }

}
