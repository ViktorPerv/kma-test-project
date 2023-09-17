<?php

declare(strict_types=1);

namespace App\Http;

use App\Db;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

class Kernel
{
    private static Db $db;
    public function handle(Request $request): Response
    {
        static::$db = new Db();

        $dispatch = simpleDispatcher(function (RouteCollector $collector) {

            $routes = include BASE_PATH . '/routes/web.php';

            foreach ($routes as $route) {
                $collector->addRoute(...$route);
            }

        });

        $routeInfo = $dispatch->dispatch($request->getMethod(), $request->getPath());

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return new Response('<h1>404 <br />Страница не найдена</h1>', 404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                return new Response('<h1>405 <br />Метод не разрешён</h1>', 405);
                break;
            case Dispatcher::FOUND:
                [$controller, $method] = $routeInfo[1];
                $vars = $routeInfo[2];
                return call_user_func_array([new $controller(), $method], $vars);
                break;
        }

        return new Response('<h1>Что-то пошло не так</h1>', 500);

    }

    public static function db(): Db
    {
        return static::$db;
    }

}
