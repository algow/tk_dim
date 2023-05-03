<?php

require_once __DIR__ . '/../models/Barang.php';
require_once __DIR__ . '/../messages.php';
require_once __DIR__ . '/../utils.php';

class BarangController {
  private $barang;

  public function __construct()
  {
    $this->barang = new Barang();
  }

  public function getAllBarang($req) {
    try {
      $idBarang = array_key_exists('id', $req) ? $req['id'] : null;
      $data = $this->barang->fetchAllBarang($idBarang);

      return json_encode(messages()[0]($data));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }
}