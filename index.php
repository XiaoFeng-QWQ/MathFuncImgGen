<?php
// 强制设置时区为国内
date_default_timezone_set("Asia/Shanghai");


require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.global.php';

// 检查请求头，避免在非图片请求时注册自定义异常处理器
if (strpos($_SERVER['HTTP_ACCEPT'], 'text') !== false) {
    require __DIR__ . '/System/Core/Helpers/handleException.php';
    // 注册自定义异常处理器
    set_exception_handler('HandleException');
}

use App\Core\Main;

$App = new Main;
$App->run();
