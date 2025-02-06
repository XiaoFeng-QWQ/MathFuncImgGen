<?php
// 强制设置时区为国内
date_default_timezone_set("Asia/Shanghai");


require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.global.php';

use App\Core\Main;

$App = new Main;
$App->run();
