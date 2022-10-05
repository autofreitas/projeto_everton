<?php
ob_start();

//header("Location: https://automacaonaveia.cemf.com.br/service/aguarde.php");

require __DIR__ . "/vendor/autoload.php";

/**
 *  BOOTSTRAP
 */

use Source\Core\Session;
use CoffeeCode\Router\Router;

$session = new Session();
$route = new Router(url(), ":");

/*
 *  WEB CLIENT GET
 */
$route->namespace("Source\App")->group(null);

$route->get("/", "App:pageConstruct");
$route->get("/theme", "App:theme");

/**  LOGIN */
$route->get("/login", "App:login");
$route->post("/login", "App:login");


$route->get("/email", "App:email");
$route->get("/session", "App:session");  

$route->get("/solicitar-recuperacao/{email}", "App:recoverPass");
$route->get("/recuperar/{code}", "App:reset");
$route->post("/recuperar/resetar", "App:reset");


/**
 * Aplicacão
 * Quando o usuário estiver conectado
 */
$route->namespace("Source\App")->group("app");

/** DASHBOARD */
$route->get("/{layout}", "App:dashboard");
$route->post("/user", "App:user");
$route->post("/address", "App:address");
$route->post("/password", "App:password");

/** MODULE */
$route->get("/modulo/{token}", "App:moduleConfig");
$route->post("/modulo/{token}", "App:moduleConfig");

/** ADICIONANDO PROPRIEDADE AO MODULO */
$route->post("/owner", "App:OwnerModule");

/** PUSH MODULE */
$route->post("/push", "App:push");
$route->post("/publish", "App:publish");


/*
 *  ERROR ROUTE
 */
$route->namespace("Source\App")->group("/ops");
$route->get("/{errcode}","App:error");

/*
 * ROUTE
 */
$route ->dispatch();

/*
 *  ERROR REDIRECT
 */
if($route->error()){
   $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();