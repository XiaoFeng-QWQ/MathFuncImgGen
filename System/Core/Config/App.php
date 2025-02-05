<?php

namespace App\Core\Config;

/**
 * 应用配置
 */
class App
{
    /**
     * 路由规则
     * @var array
     */
    public array $routeRules = [
        // 基本路由
        '/' => [
            'file' => [
                '/index.php'
            ]
        ],
        '/fun' => [
            'file' => [
                '/fun.php'
            ]
        ]
    ];
}
