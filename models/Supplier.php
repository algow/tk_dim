<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/BaseModel.php';

class Supplier extends BaseModel {

  public function fetchAllSupplier($id=null) {
    $query = "SELECT * FROM supplier WHERE 1=1";

    if($id) {
      $query .= " AND IdSupplier=" . $id;
    }

    $supplier = $this->db->query($query)->fetchAll();
    return $supplier;
  }
}