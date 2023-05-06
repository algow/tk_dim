<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/BaseModel.php';

class Pelanggan extends BaseModel {

  public function fetchAllPelanggan($id=null) {
    $query = "SELECT * FROM Pelanggan WHERE 1=1";

    if($id) {
      $query .= " AND IdPelanggan=" . $id;
    }

    $pelanggan = $this->getDb()->query($query)->fetchAll();
    return $pelanggan;
  }
}