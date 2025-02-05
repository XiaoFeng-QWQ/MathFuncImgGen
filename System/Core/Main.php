<?php

namespace App\Core;


/**
 * 入口类
 * 
 * @copyright 2024 XiaoFeng-QWQ
 * @version FRAMEWORK_VERSION
 * @author XiaoFeng-QWQ <1432777209@qq.com>
 */
class Main
{
    public Route $route;

    public function __construct()
    {
        $this->route = new Route(); // 初始化 $route 属性
    }

    /**
     * 启动程序
     *
     * @return void
     */
    public function run(): void
    {
        // 启动路由
        $this->route->processRoutes();
    }
}
