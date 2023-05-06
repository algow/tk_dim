<?php

require_once __DIR__ . '/../database.php';

class BaseModel {
  private $db;

  public function __construct() {
    $this->db = database();
  }

  public function getDb() {
    return $this->db;
  }
}