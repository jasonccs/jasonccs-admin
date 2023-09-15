<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>开发信息 </title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
</head>

<body>
<div id="cTMail-Wrap" style="box-sizing:border-box;text-align:center;min-width:320px; max-width:660px; border:1px solid #f6f6f6; background-color:#f7f8fa; margin:auto; padding:20px 0 30px; font-family:'helvetica neue',PingFangSC-Light,arial,'hiragino sans gb','microsoft yahei ui','microsoft yahei',simsun,sans-serif">

<table style="width:100%;margin: 0 auto;font-weight:300;margin-bottom:10px;border-collapse:collapse">

    <tbody>

    <tr style="font-weight:300">
        <td style="width:3%;max-width:30px;"></td>
        <td style="max-width:600px;">
            <div id="cTMail-logo" style="width:92px; height:46px;">
                <a href="https://cloud.tencent.com" rel="noopener" target="_blank">
                    <img border="0" src="http://img.xiaoxiangchengbo.com/UploadRoot/logo/logo.png"style="width:92px; height:64px;display:block"></a>
            </div>
            <p style="height:2px;background-color: #00a4ff;border: 0;font-size:0;padding:0;width:100%;margin-top:20px;"></p>

            <div id="cTMail-inner"
                 style="background-color:#fff; padding:23px 0 20px;box-shadow: 0px 1px 1px 0px rgba(122, 55, 55, 0.2);text-align:left;">
                <table style="width:100%;font-weight:300;margin-bottom:10px;border-collapse:collapse;text-align:left;">
                    <tbody>
                    <tr style="font-weight:300">
                        <td style="width:3.2%;max-width:30px;"></td>
                        <td style="max-width:480px;text-align:left;">

                            <h3 id="cTMail-userName" style="font-size:18px;color:#333; line-height:24px; margin:0;">
                                尊敬的用户，您好！</h3>

                            <p class="cTMail-content"
                               style="font-size: 14px; color: rgb(51, 51, 51); line-height: 24px; margin: 6px 0px 0px; word-wrap: break-word; word-break: break-all;">
                                发票信息如下:</p>

                            <p class="cTMail-content"
                               style="font-size: 14px; color: rgb(51, 51, 51); line-height: 24px; margin: 6px 0px 0px; word-wrap: break-word; word-break: break-all;">
                                销方名称：<span
                                        style="border-bottom: 1px dashed rgb(204, 204, 204); z-index: 1; position: static;"
                                        t="7" onclick="return false;" data="">湖南科技有限公司</span></p>

                            <p class="cTMail-content"
                               style="font-size: 14px; color: rgb(51, 51, 51); line-height: 24px; margin: 6px 0px 0px; word-wrap: break-word; word-break: break-all;">
                                价税合计：
                                @if (isset($invoice))
                                    姓名：{{$invoice->name}}
                                    邮箱：{{$invoice->email}}
                                @endif
                            </p>


                            <p class="cTMail-content"
                               style="line-height: 24px; margin: 6px 0px 0px; overflow-wrap: break-word; word-break: break-all;">
                                <span style="color: rgb(51, 51, 51); font-size: 14px;">若您有疑问请咨询客服：<a
                                            href="#"
                                            title=""
                                            style="color: rgb(0, 164, 255); text-decoration: none; word-break: break-all; overflow-wrap: normal; font-size: 14px;"
                                            rel="noopener" target="_blank">0731—22281332</a></span><br>


                            </p>


                            <p class="cTMail-content"
                               style="font-size: 14px; color: rgb(220, 9, 9); line-height: 24px; margin: 6px 0px 0px; word-wrap: break-word; word-break: break-all;">
                                注意查收附件！！</p>

                            <dl style="font-size: 14px; color: rgb(51, 51, 51); line-height: 18px;">

                                <dd style="margin: 0px 0px 6px; padding: 0px; font-size: 12px; line-height: 22px;"><p
                                            id="cTMail-sender"
                                            style="font-size: 14px; line-height: 26px; word-wrap: break-word; word-break: break-all; margin-top: 32px;">
                                        此致 <br>
                                        <strong>湖南科技有限公司</strong></p>
                                </dd>
                            </dl>
                        </td>
                        <td style="width:3.2%;max-width:30px;"></td>
                    </tr>
                    </tbody>

                </table>

            </div>

            <div id="cTMail-copy" style="text-align:center; font-size:12px; line-height:18px; color:#999">
                <table style="width:100%;font-weight:300;margin-bottom:10px;border-collapse:collapse">
                    <tbody>
                    <tr style="font-weight:300">
                        <td style="width:3.2%;max-width:30px;"></td>
                        <td style="max-width:540px;">
                            <p style="text-align:center; margin:20px auto 14px auto;font-size:12px;color:#999;">
                                此为系统邮件，请勿回复。 <a href="#"
                                                style="text-decoration:none;word-break:break-all;word-wrap:normal;    color: #333;"
                                                target="_blank" rel="noopener"></a></p>
                            <p style="text-align:center;margin:0 auto 4px;"><img border="0" src="http://img.xiaoxiangchengbo.com/UploadRoot/logo/logo.png"
                                                                                 style="width:64px; height:64px; margin:0 auto;">
                            </p>

                            <p id="cTMail-rights"
                               style="max-width: 100%; margin:auto;font-size:12px;color:#999;text-align:center;line-height:22px;">
                                关注服务号，为您服务
                            </p>

                        </td>
                        <td style="width:3.2%;max-width:30px;"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </td>

        <td style="width:3%;max-width:30px;"></td>
    </tr>

    </tbody>

</table>

</div>

</body>
</html>
