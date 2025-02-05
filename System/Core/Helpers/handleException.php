<?php

/**
 * 系统异常处理
 *
 * @param Exception $e 异常对象
 * @return void
 */
function handleException($e)
{
    // 输出错误信息到屏幕
    echo formatExceptionOutput($e);
}

/**
 * 获取文件中指定行的代码片段
 *
 * @param string $file 文件路径
 * @param int $line 行号
 * @param int $padding 上下文行数
 * @return string
 */
function getCodeSnippet($file, $line, $padding = 5)
{
    if (!is_readable($file)) {
        return '';
    }

    $lines = file($file);
    $start = max(0, $line - $padding - 1);
    $end = min(count($lines), $line + $padding);

    $snippet = '';
    for ($i = $start; $i < $end; $i++) {
        $lineNumber = $i + 1;
        $lineContent = htmlspecialchars($lines[$i]);
        if ($lineNumber === $line) {
            $snippet .= "<span class=\"error-line\">$lineNumber: $lineContent</span>";
        } else {
            $snippet .= "$lineNumber: $lineContent";
        }
    }

    return $snippet;
}

/**
 * 格式化异常输出
 *
 * @param Exception $exception 异常对象
 * @return string
 */
function formatExceptionOutput($exception)
{
    // 设置字符编码
    header('Content-Type: text/html; charset=UTF-8');

    $codeSnippet = getCodeSnippet($exception->getFile(), $exception->getLine());

    $output = '
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>程序出了点问题……</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.bootcdn.net/ajax/libs/prism-themes/1.9.0/prism-a11y-dark.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .error-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-header {
            font-size: 24px;
            color: #b71c1c;
            margin-bottom: 20px;
        }
        .error-section {
            margin-bottom: 20px;
        }
        .error-section h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }
        pre code {
            white-space: pre-wrap;
            word-break: break-word;
        }
        .error-line {
            background-color: #ffcccc;
            display: block;
        }
        .error-footer {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-header">哎呀，程序出错了！</div>
        <div class="error-section">
            <h3>错误信息:</h3>
            <pre><code class="language-php">' . htmlspecialchars($exception->getMessage()) . '</code></pre>
        </div>';

    if (defined('FRAMEWORK_DEBUG') && FRAMEWORK_DEBUG) {
        $output .= '
        <div class="error-section">
            <h3>错误堆栈:</h3>
            <pre><code class="language-php">' . htmlspecialchars($exception->getTraceAsString()) . '</code></pre>
        </div>
        <div class="error-section">
            <h3>错误代码片段:</h3>
            <pre><code class="language-php">' . $codeSnippet . '</code></pre>
        </div>
        <div class="error-section">
            <h3>原始信息:</h3>
            <pre><code class="language-php">' . htmlspecialchars(var_export($exception, true)) . '</code></pre>
        </div>
        <div class="error-section">
            <h3>请求URL:</h3>
            <pre><code class="language-php">' . htmlspecialchars($_SERVER['REQUEST_URI']) . '</code></pre>
        </div>
        <div class="error-section">
            <h3>请求参数:</h3>
            <pre><code class="language-php">' . htmlspecialchars(var_export($_REQUEST, true)) . '</code></pre>
        </div>
        <div class="error-section">
            <h3>会话数据:</h3>
            <pre><code class="language-php">' . htmlspecialchars(var_export($_SESSION, true)) . '</code></pre>
        </div>
        <div class="error-section">
            <h3>环境信息:</h3>
            <pre><code class="language-php">服务器信息:' . htmlspecialchars(mb_convert_encoding(php_uname(), 'GB18030')) . PHP_EOL . 'PHP版本:' . phpversion() . '</code></pre>
        </div>
        <p>日志已记录到 ' . FRAMEWORK_DIR . '/Writable/logs/' . '</p>
        ';
    }

    $output .= '
        <div class="error-footer">
            <hr>
            <p>V:' . FRAMEWORK_VERSION . '</p>
        </div>
    </div>
    <script src="https://cdn.bootcdn.net/ajax/libs/prism/9000.0.1/prism.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/prism/9000.0.1/components/prism-php.min.js"></script>
</body>
</html>';

    http_response_code(500);

    return $output;
}
