<?php
define('WIDTH', 700);
define('HEIGHT', 400);
define('ARROW_SIZE', 10);
define('FONT_PATH', __DIR__ . '/latinmodern-math.otf');

$image = imagecreate(WIDTH, HEIGHT);
$background_color = imagecolorallocate($image, 255, 255, 255);
$line_color = imagecolorallocate($image, 0, 0, 255);
$axis_color = imagecolorallocate($image, 0, 0, 0); // 坐标轴颜色

// 方程参数
$type = isset($_GET['type']) ? $_GET['type'] : '';
$m = isset($_GET['m']) ? floatval($_GET['m']) : 0;
$b = isset($_GET['b']) ? floatval($_GET['b']) : 0;
$c = isset($_GET['c']) ? floatval($_GET['c']) : 0;
$d = isset($_GET['d']) ? floatval($_GET['d']) : 0;
$a = isset($_GET['a']) ? floatval($_GET['a']) : 0;

imageline($image, 0, HEIGHT / 2, WIDTH, HEIGHT / 2, $axis_color); // X轴
imageline($image, WIDTH / 2, 0, WIDTH / 2, HEIGHT, $axis_color); // Y轴
// X轴箭头
imageline($image, WIDTH, HEIGHT / 2, WIDTH - ARROW_SIZE, HEIGHT / 2 - ARROW_SIZE / 2, $axis_color);
imageline($image, WIDTH, HEIGHT / 2, WIDTH - ARROW_SIZE, HEIGHT / 2 + ARROW_SIZE / 2, $axis_color);
// Y轴箭头
imageline($image, WIDTH / 2, 0, WIDTH / 2 - ARROW_SIZE / 2, ARROW_SIZE, $axis_color);
imageline($image, WIDTH / 2, 0, WIDTH / 2 + ARROW_SIZE / 2, ARROW_SIZE, $axis_color);
// 添加轴标签
imagettftext($image, 20, 0, WIDTH / 2 - 20, HEIGHT / 2 + 20, $axis_color, FONT_PATH, 'o');
imagettftext($image, 20, 0, WIDTH - 40, HEIGHT / 2 + 20, $axis_color, FONT_PATH, 'x');
imagettftext($image, 20, 0, WIDTH / 2 + 5, 20, $axis_color, FONT_PATH, 'y');
imagettftext($image, 10, 0, 0, HEIGHT - 5, $axis_color, FONT_PATH, 'https://github.com/XiaoFeng-QWQ/MathFuncImgGen');
imagettftext($image, 10, 0, 0, 15, $axis_color, FONT_PATH, "Type:$type  M:$m  B:$b  C:$c  D:$d  A:$a");


// 计算绘制图像的范围
$min_x = -WIDTH;
$max_x = (WIDTH / 2) - ARROW_SIZE;
// 使线居中
$scale_x = WIDTH / 2;
$scale_y = HEIGHT / 2;

// 定义函数计算方法
$functions = [
    'linear' => fn($x) => $m * $x + $b,
    'quadratic' => fn($x) => $m * $x * $x + $b * $x + $c,
    'cubic' => fn($x) => $m * $x * $x * $x + $b * $x * $x + $c * $x + $d,
    'sine' => fn($x) => $m * sin($b * $x + $c),
    'cosine' => fn($x) => $m * cos($b * $x + $c),
    'tangent' => fn($x) => $m * tan($b * $x + $c),
    'triangle' => fn($x) => $m * asin(sin($b * $x + $c)) * (2 / M_PI), // Triangle wave using arcsine
    'sawtooth' => fn($x) => $m * (fmod($x + $b, 2 * M_PI) - M_PI), // Sawtooth wave
    'inverse_proportion' => fn($x) => $a / $x + $b,
    'hyperbola' => fn($x) => $a / $x,
    'square' => fn($x) => $m * (sin($b * $x + $c) >= 0 ? 1 : -1) // 方波生成
];

// 绘制函数图像
$prev_x = null;
$prev_y = null;

for ($x = $min_x; $x < $max_x; $x += 0.1) {
    if (isset($functions[$type])) {
        $y = $functions[$type]($x);
    } else {
        continue;
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
