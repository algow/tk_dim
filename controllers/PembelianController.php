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

  public function addPembelian($req, $user) {
    try {
      $this->pembelian->insertPembelian($req, $user);

      return json_encode(messages()[0]([]));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }

  public function ubahPembelian($req, $user) {
    try {
      $this->pembelian->updatePembelian($req);

      return json_encode(messages()[0]([]));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }

  public function hapusPembelian($req, $user) {
    try {
      $this->pembelian->deletePembelian($req);

      return json_encode(messages()[0]([]));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }

  public function getAllPembelian() {
    try {
      $data = $this->pembelian->fetchAllPembelian();

      return json_encode(messages()[0]($data));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }
}