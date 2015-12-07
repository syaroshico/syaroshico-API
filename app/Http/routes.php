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

use \App\Tools\Options\Options;

$app->get( '/', function () {
//    return ;
    return view('index');

    return "There is no content.";
} );

$app->get( '/api/v1/count.json', 'CountApiController@count' );