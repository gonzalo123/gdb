<?php

include __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/SqlLiteStorage.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\DoctrineServiceProvider;

$app = new Application([
    'debug'      => true,
    'ioServer'   => 'http://localhost:3000',
    'httpServer' => 'http://localhost:3001',
]);

$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

$app->register(new G\Io\EmitterServiceProvider($app['httpServer']));
$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_sqlite',
        'path'   => __DIR__ . '/../../db/app.db.sqlite',
    ],
]);
$app->register(new G\Io\Storage\Provider(new SqlLiteStorage($app['db'])));

$app->get("/conf", function (Application $app, Request $request) {
    $chanel = $request->get('token');
    return $app->json([
        'ioServer' => $app['ioServer'],
        'chanel'   => $chanel
    ]);
});

$app->get('/{key}', function (Application $app, $key) {
    return $app->json($app['gdb.get']($key));
});

$app->post('/{key}', function (Application $app, Request $request, $key) {
    $content = json_decode($request->getContent(), true);

    $chanel = $content['token'];
    $app->json($app['gdb.post']($key, $content['value']));

    $app['io.emit']($chanel, [
        'key'   => $key,
        'value' => $content['value']
    ]);

    return $app->json(true);
});

$app->run();