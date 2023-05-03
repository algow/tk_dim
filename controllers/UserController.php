<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../messages.php';
require_once __DIR__ . '/../utils.php';


class UserController {
  private $user;

  public function __construct()
  {
    $this->user = new User();
  }

  public function doLogin($req) {
    try {
      if(!isset($req['username']) || !isset($req['password'])) {
        return json_encode(messages()[1]('username/password kosong'));
      }

      $userData = $this->user->fetchUser($req['username']);

      if(!$userData) {
        return json_encode(messages()[1]('username/password tidak ditemukan'));
      }

      if(password_verify($req['password'], $userData['Password']) === false) {
        return json_encode(messages()[1]('username/password tidak ditemukan'));
      }

      $Utils = new Utils();

      $data = [
        'IdPengguna'=> $userData['IdPengguna'],
        'NamaPengguna'=> $userData['NamaPengguna'],
        'NamaDepan'=> $userData['NamaDepan'],
        'NamaBelakang'=> $userData['NamaBelakang'],
        'Alamat'=> $userData['Alamat'],
        'IdAkses'=> $userData['IdAkses'],
        'NamaAkses'=> $userData['NamaAkses']
      ];

      $data['Token'] = $Utils->generateJWT($data);
  
      return json_encode(messages()[0]($data));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }
}