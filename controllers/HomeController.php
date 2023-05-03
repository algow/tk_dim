<?php

class HomeController {

  public function __construct()
  {
  }

  public function index($req) {
    include __DIR__ . '/../views/index.php';    
  }

  public function login($req) {
    include __DIR__ . '/../views/login.php';    
  }
}