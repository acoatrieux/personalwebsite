<?php
// DIC configuration

use Slim\Flash\Messages;
use Slim\Http\Request;
use Slim\Http\Response;

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings/settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['errorHandler'] = function ($c) {
    return function (Request $request, Response $response, $exception) use ($c) {
        return $response->withStatus(500)->withJson($exception);
    };
};

$container['phpErrorHandler'] = function ($c) {
    return function (Request $request, Response $response, $exception) use ($c) {
        return $response->withStatus(500)->withJson($exception);
    };
};

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../templates', [
        'debug' => true,
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new Slim\Views\TwigExtension($router, $uri));
    $view->addExtension(new Twig_Extension_Debug());
    $view->getEnvironment()->addGlobal('flash', $container->get('flash'));

    return $view;
};

$container['flash'] = function () {
    return new Messages();
};
