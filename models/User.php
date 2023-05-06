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
    join (SELECT * FROM hakakses WHERE AKTIF=1) h on p.IdAkses=h.IdAkses
    where p.NamaPengguna='".$uname."'
    ";

    $user = $this->getDb()->query($query)->fetch();
    return $user;
  }
}