<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/BaseModel.php';

class Pembelian extends BaseModel {

  public function insertPembelian($data, $userData) {
    $query = "
      INSERT INTO Pembelian (JumlahPembelian, HargaBeli, IdBarang, IdPengguna, IdSupplier)
      VALUES (?, ?, ?, ?, ?);
    ";

    $stmt= $this->db->prepare($query);
    $stmt->execute([
      $data['jumlah'], 
      $data['harga_satuan'], 
      $data['id_barang'], 
      $userData->IdPengguna, 
      $data['id_supplier']
    ]);
  }
}