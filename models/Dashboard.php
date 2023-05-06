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

  public function fetchLaba () {
	$query = "
	select
		sum(ItemTerjual) ItemTerjual,
		sum(Nilai_HPP) Nilai_HPP,
		sum(NilaiPenjualanBruto) NilaiPenjualanBruto,
		sum(Keuntungan) Keuntungan
	from
		(
		select
			IdBarang ,
			NamaBarang ,
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
				sum(nvl(JumlahPenjualan, 0)) ItemTerjual ,
				avg(a.HargaBeli) HargaBeli,
				avg(a.HargaJual) HargaJual
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
				b.NamaBarang ) c ) main";

	$dashboard = $this->getDb()->query($query)->fetchAll();
	return $dashboard;
  }

}