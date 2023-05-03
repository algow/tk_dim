<?php

header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: *'); 

require_once __DIR__ . '/utils.php';
require_once __DIR__  . '/messages.php';
require_once __DIR__ . '/router.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/BarangController.php';
require_once __DIR__ . '/controllers/PembelianController.php';
require_once __DIR__ . '/controllers/SupplierController.php';


function run() {
  $_POST = json_decode(file_get_contents('php://input'), true);
  $router = new Router($_SERVER, $_GET, $_POST);

  $router->post(
    '/login', 
    function($req){
      $UserController = new UserController();
      echo $UserController->doLogin($req);
      die();
    }
  );

  $router->get(
    '/barang', 
    function($req){
      $BarangController = new BarangController();
      echo $BarangController->getAllBarang($req);
      die();
    }
  );

  $authHeader = array_key_exists('Authorization', apache_request_headers()) 
    ? apache_request_headers()['Authorization'] 
    : null;

  if(!$authHeader) {
    echo json_encode(messages()[2]());
    die();
  }
  
  $Utils = new Utils();
  $userData = $Utils->validateJwt($authHeader);

  $router->post(
    '/pembelian', 
    function($req, $user){
      $PembelianController = new PembelianController();
      echo $PembelianController->addBarang($req, $user);
      die();
    },
    $userData
  );

  $router->get(
    '/supplier', 
    function($req){
      $SupplierController = new SupplierController();
      echo $SupplierController->getAllSupplier($req);
      die();
    }
  );
  
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