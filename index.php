<?php

header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: *'); 
header('Access-Control-Allow-Methods: *');


require_once __DIR__ . '/utils.php';
require_once __DIR__  . '/messages.php';
require_once __DIR__ . '/router.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/BarangController.php';
require_once __DIR__ . '/controllers/PembelianController.php';
require_once __DIR__ . '/controllers/PenjualanController.php';
require_once __DIR__ . '/controllers/SupplierController.php';
require_once __DIR__ . '/controllers/PelangganController.php';
require_once __DIR__ . '/controllers/DashboardController.php';

function run() {
  $_POST = json_decode(file_get_contents('php://input'), true);
  $router = new Router($_SERVER, $_GET, $_POST);

  // /barang

  // unrestricted path start 
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


  // get user data then authenticate
  $authHeader = array_key_exists('Authorization', apache_request_headers()) 
    ? apache_request_headers()['Authorization'] 
    : null;

  if(!$authHeader) {
    echo json_encode(messages()[2]());
    die();
  }
  
  $Utils = new Utils();
  $userData = $Utils->validateJwt($authHeader);


  // apakah path dan method boleh diakses oleh current user?
  $dataAkses = [
    'nama_akses'=> $userData->NamaAkses,
    'method'=> strtolower($_SERVER['REQUEST_METHOD']),
    'path'=> str_replace('/', '', $_SERVER['PATH_INFO'])
  ];

  $allowed = $Utils->allowAccess($dataAkses);

  if(!$allowed) {
    http_response_code(403);
    echo json_encode(messages()[1]('akses tidak diizinkan'));
    die();
  }

  // restricted path
  $router->get(
    '/pembelian', 
    function($req){
      $PembelianController = new PembelianController();
      echo $PembelianController->getAllPembelian();
      die();
    }
  );

  $router->post(
    '/pembelian', 
    function($req, $user){
      $PembelianController = new PembelianController();
      echo $PembelianController->addPembelian($req, $user);
      die();
    },
    $userData
  );

  $router->post(
    '/pembelian-update', 
    function($req, $user){
      $PembelianController = new PembelianController();
      echo $PembelianController->ubahPembelian($req, $user);
      die();
    },
    $userData
  );

  $router->post(
    '/pembelian-delete', 
    function($req, $user){
      $PembelianController = new PembelianController();
      echo $PembelianController->hapusPembelian($req, $user);
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

  $router->get(
    '/penjualan', 
    function($req){
      $PenjualanController = new PenjualanController();
      echo $PenjualanController->getAllPenjualan();
      die();
    }
  );

  $router->post(
    '/penjualan', 
    function($req, $user){
      $PenjualanController = new PenjualanController();
      echo $PenjualanController->addPenjualan($req, $user);
      die();
    },
    $userData
  );

  $router->get(
    '/pelanggan',
    function($req){
      $PelangganController = new PelangganController();
      echo $PelangganController->getAllPelanggan($req);
      die();
    }
  );

  $router->get(
    '/stok',
    function($req){
      $DashboardController = new DashboardController();
      echo $DashboardController->getStok($req);
      die();
    }
  );

  $router->get(
    '/labarugi',
    function($req){
      $DashboardController = new DashboardController();
      echo $DashboardController->getLaba($req);
      die();
    }
  );

  $router->get(
    '/rekomendasi', 
    function($req){
      $PembelianController = new PembelianController();
      echo $PembelianController->getRekomendasiPembelian($req);
      die();
    }
  );

}

run();
