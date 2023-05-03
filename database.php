<?php

function database() {
  $host = '10.100.244.78';
  $port = '1000';
  $database = 'tk_dim';
  $user = 'root';
  $password = 'password';

  try {
    $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database;
    $conn = new PDO($dsn, $user, $password);

    return $conn;
  } catch (PDOException $e) {
    return null;
  }
}
