<?php

require_once __DIR__ . '/../database.php';

class BaseModel {
  protected $db;

  public function __construct() {
    $this->db = database();
  }
}