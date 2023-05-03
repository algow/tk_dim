<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
  public function fetchUser($uname) {
    $query = "SELECT 
      p.*,
      h.NamaAkses,
      h.Keterangan
    FROM pengguna p 
    join hakakses h on p.IdAkses=h.IdAkses
    where p.NamaPengguna='".$uname."'
    ";

    $user = $this->db->query($query)->fetch();
    return $user;
  }
}