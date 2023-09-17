<?php

use App\Http\Kernel;
use App\Http\Request;
use Dotenv\Dotenv;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$kernel = new Kernel();

$response = $kernel->handle(Request::createFromGlobals());

$response->send();
