<?php

require_once __DIR__  . '/messages.php';
require_once __DIR__ . '/router.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/utils.php';

function run() {
  $router = new Router($_SERVER, $_GET, $_POST);

  $router->post(
    '/login', 
    function($req){
      $UserController = new UserController();
      echo $UserController->doLogin($req);
      die();
    }
  );

  $authHeader = array_key_exists('Authorization', apache_request_headers()) ? apache_request_headers()['Authorization'] : null;

  if(!$authHeader) {
    echo json_encode(messages()[2]());
    die();
  }
  
  $Utils = new Utils();
  $userData = $Utils->validateJwt($authHeader);

  

//   $router->get('/', function($req){
//     $HomeController = new HomeController();
//     $HomeController->index($req);
//   });

//   $router->get('/login', function($req){
//     $HomeController = new HomeController();
//     $HomeController->login($req);
//   });

//   $router->get('/user', function($req){
//     $UserController = new UserController();
//     $UserController->doLogin($req);
//   });
}

run();