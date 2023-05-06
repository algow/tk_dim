<?php

require_once __DIR__ . '/../models/Pelanggan.php';
require_once __DIR__ . '/../messages.php';
require_once __DIR__ . '/../utils.php';

class PelangganController {
  private $pelanggan;

  public function __construct()
  {
    $this->pelanggan = new Pelanggan();
  }

  public function getAllPelanggan($req) {
    try {
      $idPelanggan = array_key_exists('id', $req) ? $req['id'] : null;
      $data = $this->pelanggan->fetchAllPelanggan($idPelanggan);

      return json_encode(messages()[0]($data));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }
}