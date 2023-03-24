<?php

use Workerman\Worker;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\RawRequest;

// 自动加载类
require_once __DIR__ . '/../vendor/autoload.php';

// 创建一个 worker，监听 9000 端口，使用 http 协议通讯
$http_worker = new Worker("http://0.0.0.0:9000");

// 启动 4 个进程
$http_worker->count = 10;

// 定义根目录，所有请求都从这里开始
define('APP_PATH', __DIR__ . '/application/');

// 处理 HTTP 请求
$http_worker->onMessage = function (TcpConnection $connection, RawRequest $request) {

    // 指定当前模块
    define('MODULE_NAME', 'index');
    // 指定当前控制器
    define('CONTROLLER_NAME', 'index');
    // 指定当前操作
    define('ACTION_NAME', 'index');

    // 引入框架
    require_once __DIR__ . '/thinkphp/base.php';

    // 创建应用对象
    $app = new think\App(APP_PATH);

    // 处理请求并响应
    $think_response = $app->httpRequest->build($request)->run();
    $workerman_response = new Response();
    $workerman_response->withHeader('Content-Type', 'text/html;charset=utf-8');
    $workerman_response->withBody($think_response->getContent());
    $connection->send($workerman_response);
};

// 运行 worker
Worker::runAll();
