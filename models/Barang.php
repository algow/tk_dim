<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/BaseModel.php';

class Barang extends BaseModel {

  public function fetchAllBarang($id=null) {
    $query = "SELECT * FROM barang WHERE 1=1";

    if($id) {
      $query .= " AND IdBarang=" . $id;
    }

    $barang = $this->getDb()->query($query)->fetchAll();
    return $barang;
  }

  public function insertBarang($id=null) {

  }

}