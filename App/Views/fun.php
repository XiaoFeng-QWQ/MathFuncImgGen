<?php
$width = 600;
$height = 400;
$arrow_size = 10;
$image = imagecreate($width, $height);
$fontPath = __DIR__ . '/latinmodern-math.otf';
$background_color = imagecolorallocate($image, 255, 255, 255);
$line_color = imagecolorallocate($image, 0, 0, 255);

// 方程参数
$type = isset($_GET['type']) ? $_GET['type'] : '';
$m = isset($_GET['m']) ? floatval($_GET['m']) : 0;
$b = isset($_GET['b']) ? floatval($_GET['b']) : 0;
$c = isset($_GET['c']) ? floatval($_GET['c']) : 0;
$d = isset($_GET['d']) ? floatval($_GET['d']) : 0;
$a = isset($_GET['a']) ? floatval($_GET['a']) : 0;

// 绘制坐标轴
$axis_color = imagecolorallocate($image, 0, 0, 0); // 坐标轴颜色
imageline($image, 0, $height / 2, $width, $height / 2, $axis_color); // X轴
imageline($image, $width / 2, 0, $width / 2, $height, $axis_color); // Y轴

// 添加箭头
// X轴箭头
imageline($image, $width, $height / 2, $width - $arrow_size, $height / 2 - $arrow_size / 2, $axis_color);
imageline($image, $width, $height / 2, $width - $arrow_size, $height / 2 + $arrow_size / 2, $axis_color);
// Y轴箭头
imageline($image, $width / 2, 0, $width / 2 - $arrow_size / 2, $arrow_size, $axis_color);
imageline($image, $width / 2, 0, $width / 2 + $arrow_size / 2, $arrow_size, $axis_color);

// 添加轴标签
imagettftext($image, 20, 0, $width / 2 - 20, $height / 2 + 20, $axis_color, $fontPath, 'o');
imagettftext($image, 20, 0, $width - 40, $height / 2 + 20, $axis_color, $fontPath, 'x');
imagettftext($image, 20, 0, $width / 2 + 5, 20, $axis_color, $fontPath, 'y');

// 计算绘制图像的范围
$min_x = -256;
$max_x = 256;
$scale_x = $width / 2; // X轴的比例尺
$scale_y = $height / 2; // Y轴的比例尺

// 绘制函数图像
$prev_x = null;
$prev_y = null;

for ($x = $min_x; $x < $max_x; $x += 0.1) {
    switch ($type) {
        case 'linear':
            // 一元一次函数 y = mx + b
            $y = $m * $x + $b;
            break;
        case 'quadratic':
            // 一元二次函数 y = mx^2 + bx + c
            $y = $m * $x * $x + $b * $x + $c;
            break;
        case 'cubic':
            // 一元三次函数 y = mx^3 + bx^2 + cx + d
            $y = $m * $x * $x * $x + $b * $x * $x + $c * $x + $d;
            break;
        case 'sine':
            // 正弦函数 y = m * sin(b * x + c)
            $y = $m * sin($b * $x + $c);
            break;
        case 'inverse_proportion':
            // 反比例函数 y = a / x + b
            $y = $a / $x + $b;
            break;
        case 'hyperbola':
            // 双曲线函数 y = a / x
            $y = $a / $x;
            break;
        default:
            break;
    }

    // 将数学坐标映射为图像坐标
    $image_x = (int)($scale_x + $x); // 将x轴偏移到中心
    $image_y = (int)($scale_y - $y); // 将y轴偏移到中心

    // 连接相邻的点
    if ($prev_x !== null && $prev_y !== null) {
        imageline($image, $prev_x, $prev_y, $image_x, $image_y, $line_color);
    }

    // 更新上一个点的坐标
    $prev_x = $image_x;
    $prev_y = $image_y;
}

header('Content-Type: image/png');
imagepng($image);

imagedestroy($image);
