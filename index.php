<?php

require_once 'vendor/autoload.php';

use Alura\Financeiro\Client\App\EnrollClient\{EnrollClient, EnrollClientInputData};
use Psr\Container\ContainerInterface;
use Swoole\Http\{Request, Response, Server};

/** @var ContainerInterface $container */
$container = require_once 'di.php';

$server = new Server('0.0.0.0', 9501);
$server->on('request', function (Request $request, Response $response) use ($container) {
    $response->header('Access-Control-Allow-Origin', '*');
    $response->header('Access-Control-Allow-Headers', 'Content-Type');
    $response->header('Access-Control-Allow-Methods', 'OPTIONS, POST');

    if ($request->getMethod() === 'OPTIONS') {
        $response->setStatusCode(204);
        return;
    }

    $path = $request->server['path_info'] ?? '/';

    if ($request->getMethod() !== 'POST' || $path !== '/clients') {
        $response->setStatusCode(404);
        return;
    }

    $inputData = EnrollClientInputData::fromArray(json_decode($request->rawContent(), true));
    $enrollClient = $container->get(EnrollClient::class);
    $enrollClient($inputData);

    $response->setStatusCode(201);
    $response->end();
});

$server->start();
