<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/BaseModel.php';

class Penjualan extends BaseModel {

  public function insertPenjualan($data, $userData) {
    $query = "
      INSERT INTO Penjualan (JumlahPenjualan, HargaJual, IdBarang, IdPengguna, IdPelanggan)
      VALUES (?, ?, ?, ?, ?);
    ";

    $stmt= $this->db->prepare($query);
    $stmt->execute([
      $data['jumlah'], 
      $data['harga_satuan'], 
      $data['id_barang'], 
      $userData->IdPengguna, 
      $data['id_pelanggan']
    ]);
  }

  public function fetchAllPenjualan() {
    $query = "select 
    p.* ,
    p.JumlahPenjualan * p.HargaJual NilaiPenjualan,
    b.NamaBarang ,
    b.Satuan ,
    p2.NamaPelanggan ,
    p2.KeteranganPelanggan ,
    a.NamaPengguna ,
    a.NamaAkses
  from penjualan p 
  join barang b on p.IdBarang = b.IdBarang 
  join pelanggan p2 on p.IdPelanggan = p2.IdPelanggan 
  join (
    select
      p3.IdPengguna ,
      p3.NamaPengguna ,
      h.NamaAkses 
    from pengguna p3 
    join hakakses h on p3.IdAkses = h.IdAkses
  ) a on p.IdPengguna = a.IdPengguna
  where 1 = 1
  order by p.IdPenjualan desc
    ";

    $penjualan = $this->db->query($query)->fetchAll();
    return $penjualan;
  }
}