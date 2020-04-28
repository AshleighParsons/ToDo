<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Http\Request;

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(["prefix"=> "auth"], function () use ($router) {
    $router->post("/register", "AuthController@register");
    $router->post("/login", ["uses" => "AuthController@authenticate"]);
});

/**
 * Routes for todos
 */
$router->group(
    [
        "middleware" => "jwt.auth",
        "prefix" => "api/v1/todos"
    ],
    function () use ($router) {
        $router->get("/", "TodosController@index");
        $router->post("/", "TodosController@store");
        $router->get("/{id}", "TodosController@show");
        $router->put("/{id}", "TodosController@update");
        $router->delete("/{id}", "TodosController@destroy");
    }
);