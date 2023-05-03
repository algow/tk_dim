<?php

require_once __DIR__ . '/../models/Pembelian.php';
require_once __DIR__ . '/../messages.php';
require_once __DIR__ . '/../utils.php';


class PembelianController {
  private $pembelian;

  public function __construct()
  {
    $this->pembelian = new Pembelian();
  }

  public function addBarang($req, $user) {
    try {
      $this->pembelian->insertPembelian($req, $user);

      return json_encode(messages()[0]([]));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }
}