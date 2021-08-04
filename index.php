<?php

require_once 'vendor/autoload.php';

use Alura\Financeiro\Client\App\EnrollClient\EnrollClient;
use Alura\Financeiro\Client\App\EnrollClient\EnrollClientInputData;
use Siler\Swoole;
use Siler\Route;
use Siler\Http\Request;

$handler = function () {
    try {
        /** @var \Psr\Container\ContainerInterface $container */
        $container = require_once 'di.php';

        Swoole\cors();
        Route\post('/clients', function () use ($container) {
            $inputData = EnrollClientInputData::fromArray(Request\json());
            $enrollClient = $container->get(EnrollClient::class);
            $enrollClient($inputData);
            Swoole\emit('', 201);
        });
        Swoole\emit('Not found', 404);
    } catch (Throwable $e) {
        Swoole\emit($e->getMessage(), 500);
    }
};

Swoole\http($handler)->start();
