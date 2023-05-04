<?php

require_once __DIR__  . '/vendor/autoload.php';
require_once __DIR__  . '/messages.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Utils {
  private $secret;
  private $alg;
  private $accessBlacklist;

  public function __construct()
  {
    $this->secret = 'Secret123#';
    $this->alg = 'HS256';

    $this->accessBlacklist = [
      'staf_pengadaan'=> [
        'post'=> ['penjualan'],
        'get'=> ['penjualan']
      ],
      'staf_penjualan'=> [
        'post'=> ['pembelian', 'pembelian-update', 'pembelian-delete'],
        'get'=> ['pembelian']
      ],
      'staf_accounting'=> [
        'post'=> ['penjualan', 'pembelian', 'pembelian-update', 'pembelian-delete']
      ],
      'staf_gudang'=> [
        'post'=> ['penjualan', 'pembelian', 'pembelian-update', 'pembelian-delete']
      ]
    ];
  }

  public function generateJWT($userData) {
    $token = JWT::encode($userData, $this->secret, $this->alg);
    return $token;
  }

  public function validateJwt($token) {
    try {
      $data = JWT::decode($token, new Key($this->secret, $this->alg));

      return $data;
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }

  public function allowAccess($req) {
    try {
      $akses = array_key_exists($req['nama_akses'], $this->accessBlacklist) ? $this->accessBlacklist[$req['nama_akses']] : false;

      if($akses) {
        $method = array_key_exists($req['method'], $akses) ? $akses[$req['method']] : false;

        if($method) {
          if(in_array($req['path'], $method)) {
            return false;
          }
        }
      }

      return true;
    } catch (\Throwable $th) {
      return true;
    }
  }
}