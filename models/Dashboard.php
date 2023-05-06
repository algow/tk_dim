<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/BaseModel.php';

class Dashboard extends BaseModel {

  public function fetchStok() {
    $query = "SELECT
              	IdBarang ,
              	NamaBarang ,
              	JumlahPembelian ,
              	JumlahPenjualan ,
              	(JumlahPembelian - JumlahPenjualan) Stok
              FROM
              	(
              	SELECT
              		a.IdBarang ,
              		b.NamaBarang ,
              		sum(nvl(a.JumlahPembelian, 0)) JumlahPembelian,
              		sum(nvl(a.JumlahPenjualan, 0)) JumlahPenjualan
              	FROM
              		(
              		SELECT
              			IdBarang ,
              			JumlahPenjualan ,
              			NULL AS JumlahPembelian
              		FROM
              			penjualan p1
              	UNION ALL
              		SELECT
              			IdBarang ,
              			NULL AS JumlahPenjualan,
              			JumlahPembelian
              		FROM
              			pembelian p2
              ) a
              	JOIN barang b ON
              		a.IdBarang = b.IdBarang
              	GROUP BY
              		a.IdBarang,
              		b.NamaBarang
              ) main";


    $dashboard = $this->getDb()->query($query)->fetchAll();
    return $dashboard;
  }
}