
http://jason-admin.com/login

jwt-auth secret [APhjdVYFvyBSi1Xe3o46f8y7ljIv8QeE5IgGTVgf8S4I7OwS7mNDr3mq8wk0XlFd] set successfully.

jwt 登录
http://jason-admin.com/api/auth/login

注册
http://jason-admin.com/api/auth/register

退出
http://jason-admin.com/api/auth/logout

刷新token
http://jason-admin.com/api/auth/refresh

带授权头 Authorization 能获取用户登录的信息 
http://jason-admin.com/api/auth/me

## 清除缓存
php artisan optimize:clear

## Telescope 调试工具 查看所有开发的消息 env 为local  其他需要密码 授权访问
http://json-admin.com/telescope/requests

web.php 开启如下的路由，在product 的 开发环境 进行登录授权访问
Route::get('/telescope', [Controller::class, 'telescope']);



## GeoIP 根据ip 获取用户地理位置信息
- 获取最新的ip的定位数据库 参考 (https://learnku.com/laravel/t/39865)
- php artisan geoip:update 下载最新的 geoip.mmdb 数据库文件

## 使用说明
- 打开route/web.php 找到k的路由
- [开启一个创建用户的消息队列]
- 访问 http://json-admin.com/k
- 在控制台执行 php artisan queue:listen database --queue='userCreateTask' --tries=3  --sleep=3 --timeout=60
- 这是通过数据库作为存储驱动的延时消息队列
- ![img.png](img.png)




## 发送邮件通知 可以设置延时
- 打开route/web.php 找到d的路由
-  [开启发送邮件通知消息队列]
- 访问 http://json-admin.com/k
- 在控制台执行 php artisan queue:listen database --queue='notifyEmail' --tries=3  --sleep=3 --timeout=60
- ![img_1.png](img_1.png)
