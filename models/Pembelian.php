<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/BaseModel.php';

class Pembelian extends BaseModel {

  public function updatePembelian($data){
    $queries = [
    "UPDATE tk_dim.pembelian
    SET JumlahPembelian=? 
    WHERE IdPembelian=?",
    "UPDATE tk_dim.pembelian
    SET HargaBeli=?
    WHERE IdPembelian=?",
    "UPDATE tk_dim.pembelian
    SET JumlahPembelian=?,HargaBeli=?
    WHERE IdPembelian=?"
    ];

    $query = $queries[0];
    $updateParameter = [];

    if (isset($data['jumlahpembelian']) && isset($data['hargabeli'])){
        $query = $queries[2];
        array_push($updateParameter,$data["JumlahPembelian"],$data["HargaBeli"]);
    } elseif (isset($data['hargabeli'])){
        $query = $queries[1];
        array_push($updateParameter,$data["HargaBeli"]);
    } else {
        $query = $queries[0];
        array_push($updateParameter,$data["JumlahPembelian"]);
    }

    array_push($updateParameter,$data["IdPembelian"]);


    $stmt= $this->getDb()->prepare($query);
    $stmt->execute($updateParameter);
}

public function deletePembelian($data){
  $query = 
  "DELETE FROM tk_dim.pembelian
   WHERE IdPembelian=?";

  $stmt= $this->getDb()->prepare($query);
  $stmt->execute([$data["IdPembelian"]]);
}

  public function insertPembelian($data, $userData) {
    $query = "
      INSERT INTO Pembelian (JumlahPembelian, HargaBeli, IdBarang, IdPengguna, IdSupplier)
      VALUES (?, ?, ?, ?, ?);
    ";

    $stmt= $this->getDb()->prepare($query);
    $stmt->execute([
      $data['jumlah'], 
      $data['harga_satuan'], 
      $data['id_barang'], 
      $userData->IdPengguna, 
      $data['id_supplier']
    ]);
  }

  public function fetchAllPembelian() {
    $query = "select 
        p.* ,
        (p.JumlahPembelian * p.HargaBeli) NilaiPembelian,
        b.NamaBarang ,
        b.Satuan ,
        s.NamaSupplier ,
        s.KeteranganSupplier ,
        a.NamaPengguna ,
        a.NamaAkses
      from pembelian p 
      join barang b on p.IdBarang = b.IdBarang 
      join supplier s on p.IdSupplier = s.IdSupplier 
      join (
        select
          p2.IdPengguna ,
          p2.NamaPengguna ,
          h.NamaAkses 
        from pengguna p2 
        join hakakses h on p2.IdAkses = h.IdAkses
      ) a on p.IdPengguna = a.IdPengguna
      where 1 = 1
      order by p.IdPembelian desc
    ";

    $pembelian = $this->getDb()->query($query)->fetchAll();
    return $pembelian;
  }
}