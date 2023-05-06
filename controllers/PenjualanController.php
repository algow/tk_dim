<?php

require_once __DIR__ . '/../models/Penjualan.php';
require_once __DIR__ . '/../messages.php';
require_once __DIR__ . '/../utils.php';


class PenjualanController {
  private $penjualan;

  public function __construct()
  {
    $this->penjualan = new Penjualan();
  }

  public function addPenjualan($req, $user) {
    try {
      $this->penjualan->insertPenjualan($req, $user);

      return json_encode(messages()[0]([]));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }

  public function getAllPenjualan() {
    try {
      $data = $this->penjualan->fetchAllPenjualan();

      return json_encode(messages()[0]($data));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }
}