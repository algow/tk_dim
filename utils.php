<?php

require_once __DIR__  . '/vendor/autoload.php';
require_once __DIR__  . '/messages.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Utils {
  private $secret;
  private $alg;

  public function __construct()
  {
    $this->secret = 'Secret123#';
    $this->alg = 'HS256';
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
}