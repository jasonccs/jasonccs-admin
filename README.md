
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
