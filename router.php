<?php

class Router {
  private $server;
  private $path;
  private $method;
  private $get;
  private $post;

  public function __construct($server, $get=null, $post=null) {
    $this->server = $server;
    $this->path = $this->formatRoute(
      explode(
        '?',
        substr($server['REQUEST_URI'], 8)
      )[0]
    );

    $this->method = strtolower($server['REQUEST_METHOD']);

    $this->get = $get;
    $this->post = $post;
  }

  public function get($path, $callback, $userData=null) {
    $targetPath = $this->formatRoute($path);
    
    if($this->method === 'get' && $this->path === $targetPath) {
      $callback($this->get, $userData);
    }
  }

  public function post($path, $callback, $userData=null) {
    $targetPath = $this->formatRoute($path);

    if($this->method === 'post' && $this->path === $targetPath) {
      $callback($this->post, $userData);
    }
  }

  private function formatRoute($str)
  {
    $result = str_replace('/', '', $str);
    return strtolower($result);
  }
}