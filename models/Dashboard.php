<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/BaseModel.php';

class Dashboard extends BaseModel {

  public function fetchStok() {
    $query = "SELECT
              	IdBarang ,
              	NamaBarang ,
				Satuan ,
              	JumlahPembelian ,
              	JumlahPenjualan ,
              	(JumlahPembelian - JumlahPenjualan) Stok
              FROM
              	(
              	SELECT
              		a.IdBarang ,
              		b.NamaBarang ,
					b.Satuan ,
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

  public function fetchLaba () {
	$query = "
		select
		IdBarang ,
		NamaBarang ,
		Satuan ,
		ItemTerjual ,
		HargaBeli ,
		HargaJual ,
		(ItemTerjual * HargaBeli) Nilai_HPP ,
		(ItemTerjual * HargaJual) NilaiPenjualanBruto ,
		((ItemTerjual * HargaJual) - (ItemTerjual * HargaBeli)) Keuntungan
	from
		(
		select
			a.IdBarang ,
			b.NamaBarang ,
			b.Satuan ,
			sum(nvl(JumlahPenjualan, 0)) ItemTerjual ,
			nvl(avg(a.HargaBeli), 0) HargaBeli,
			nvl(avg(a.HargaJual), 0) HargaJual
		from
			(
			select
				IdBarang ,
				null as HargaBeli ,
				HargaJual ,
				JumlahPenjualan
			from
				penjualan p1
		union all
			select
				IdBarang ,
				HargaBeli ,
				null as HargaJual ,
				null as JumlahPenjualan
			from
				pembelian p2 ) a
		join barang b on
			a.IdBarang = b.IdBarang
		group by
			a.IdBarang,
			b.NamaBarang ) main";

	$dashboard = $this->getDb()->query($query)->fetchAll();
	return $dashboard;
  }

}