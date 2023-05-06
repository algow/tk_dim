<?php

require_once __DIR__ . '/../models/Dashboard.php';
require_once __DIR__ . '/../messages.php';
require_once __DIR__ . '/../utils.php';

class DashboardController {
  private $dashboard;

  public function __construct()
  {
    $this->dashboard = new Dashboard();
  }

  public function getStok($req) {
    try {

      $data = $this->dashboard->fetchStok();

      return json_encode(messages()[0]($data));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }
}