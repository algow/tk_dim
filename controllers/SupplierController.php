<?php

require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../messages.php';
require_once __DIR__ . '/../utils.php';

class SupplierController {
  private $supplier;

  public function __construct()
  {
    $this->supplier = new Supplier();
  }

  public function getAllSupplier($req) {
    try {
      $idSupp = array_key_exists('id', $req) ? $req['id'] : null;
      $data = $this->supplier->fetchAllSupplier($idSupp);

      return json_encode(messages()[0]($data));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }
}