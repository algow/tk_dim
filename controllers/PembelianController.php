<?php

require_once __DIR__ . '/../models/Pembelian.php';
require_once __DIR__ . '/../messages.php';
require_once __DIR__ . '/../utils.php';


class PembelianController {
  private $pembelian;

  public function __construct()
  {
    $this->pembelian = new Pembelian();
  }

  public function addPembelian($req, $user) {
    try {
      $this->pembelian->insertPembelian($req, $user);

      return json_encode(messages()[0]([]));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }

  public function ubahPembelian($req, $user) {
    try {
      $this->pembelian->updatePembelian($req);

      return json_encode(messages()[0]([]));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }

  public function hapusPembelian($req, $user) {
    try {
      $this->pembelian->deletePembelian($req);

      return json_encode(messages()[0]([]));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }

  public function getAllPembelian() {
    try {
      $data = $this->pembelian->fetchAllPembelian();

      return json_encode(messages()[0]($data));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }

  public function getRekomendasiPembelian($req) {
    try {
      $dataPenawaran = $this->pembelian->fetchPenawaran($req['id_barang']);

      $tokoList = [];

      foreach ($dataPenawaran as $penawaran) {
        $data = [
          'nama_toko' => $penawaran['NamaSupplier'],
          'harga' => $penawaran['HargaSatuan'],
          'diskon' => $penawaran['PersenDiskon'],
          'min_pembelian' => $penawaran['MinPembelian'],
          'max_pembelian' => $penawaran['MaxPembelian']
        ];

        array_push($tokoList, $data);
      }
      // print_r($tokoList);
      // print_r($req['jumlah']);
      // die();

      $hasilRekomendasi = $this->cari_strategi_pembelian($tokoList, $req['jumlah']);

      return json_encode(messages()[0]($hasilRekomendasi));
    } catch (\Throwable $th) {
      return json_encode(messages()[2]($th->getMessage()));
    }
  }


  function cari_strategi_pembelian($toko_list, $jumlah_barang) {
    $min_biaya = PHP_FLOAT_MAX;
    $toko_terpilih = [];
  
    $toko_count = count($toko_list);
  
    // Generate semua kombinasi pembelian menggunakan pendekatan iteratif
    for ($i = 0; $i < pow(2, $toko_count); $i++) {
      $kombinasi_pembelian = [];
      $biaya_total = 0;
      $jumlah_beli_total = 0;
    
      for ($j = 0; $j < $toko_count; $j++) {
        if (($i & (1 << $j)) > 0) {
          $toko = $toko_list[$j];
          $harga_satuan = $toko['harga'];
          $diskon = $toko['diskon'];
          $min_pembelian = $toko['min_pembelian'];
          $max_pembelian = $toko['max_pembelian'];
        
          $jumlah_beli = min($jumlah_barang - $jumlah_beli_total, $max_pembelian);
        
          if ($jumlah_beli > 0) {
            if ($jumlah_beli_total >= $min_pembelian) {
              $biaya = ($jumlah_beli * $harga_satuan) * (1 - $diskon);
            } else {
              $biaya = $jumlah_beli * $harga_satuan;
            }
            
            $biaya_total += $biaya;
            $jumlah_beli_total += $jumlah_beli;
            
            $kombinasi_pembelian[] = [
              'toko' => $toko['nama_toko'], // Jika toko memiliki nama, bisa ditambahkan di data toko
              'harga'=> $toko['harga'],
              'diskon'=> $toko['diskon'],
              'min_pembelian'=> $toko['min_pembelian'],
              'max_pembelian'=> $toko['max_pembelian'],
              'biaya' => intval($biaya),
              'jumlah_beli' => $jumlah_beli,
            ];
          }
        }
      }
    
      if ($jumlah_beli_total == $jumlah_barang && $biaya_total < $min_biaya) {
        $min_biaya = $biaya_total;
        $toko_terpilih = $kombinasi_pembelian;
      }
    }
  
    return [
      'toko' => $toko_terpilih,
      'biaya' => $min_biaya,
      'kuantitas' => $jumlah_barang
    ];
  }

  // private function hitungBiaya($toko, $jumlahBarang) {
  //   $hargaSatuan = $toko['harga'];
  //   $diskon = $toko['diskon'];
  //   $minPembelian = $toko['min_pembelian'];
  //   $maxPembelian = $toko['max_pembelian'];
  
  //   $biaya = 0;
  //   $jumlahBeli = 0;
  
  //   while ($jumlahBarang > 0) {
  //     $pembelian = min($jumlahBarang, $maxPembelian);
  //     $jumlahBeli += $pembelian;

  //     if ($jumlahBeli >= $minPembelian) {
  //       $biaya += ($pembelian * $hargaSatuan) * (1 - $diskon);
  //     } else {
  //       $biaya += $pembelian * $hargaSatuan;
  //     }

  //     $jumlahBarang -= $pembelian;
  //   }
  
  //   return $biaya;
  // }

  // private $raa = [];
  // private function cariKombinasiPembelian($tokoList, $jumlahBarang, $kombinasiPembelian) {
  //   if ($jumlahBarang <= 0) {
  //     return $kombinasiPembelian;
  //   }

  //   $minBiaya = PHP_FLOAT_MAX;
  //   $tokoTerpilih = [];
  //   $jumlahBeli = 0;

  //   foreach ($tokoList as $toko) {
  //     $maxPembelian = $toko['max_pembelian'];
      
  //     if ($maxPembelian > 0 && $maxPembelian < $jumlahBarang) {
  //       $jumlahBeli = $maxPembelian;
  //     } else {
  //       $jumlahBeli = $jumlahBarang;
  //     }

  //     if ($jumlahBeli > 0) {
  //       $biaya = $this->hitungBiaya($toko, $jumlahBeli);
  //       $sisaBarang = $jumlahBarang - $jumlahBeli;
  //       $kombinasiPembelian[] = [
  //         'toko' => $toko['nama_toko'], // Jika toko memiliki nama, bisa ditambahkan di data toko
  //         'harga'=> $toko['harga'],
  //         'diskon'=> $toko['diskon'],
  //         'min_pembelian'=> $toko['min_pembelian'],
  //         'max_pembelian'=> $toko['max_pembelian'],
  //         'biaya' => intval($biaya),
  //         'jumlah_beli' => intval($jumlahBeli),
  //       ];
        
  //       // array_shift($tokoList);
  //       $kombinasi = $this->cariKombinasiPembelian($tokoList, $sisaBarang, $kombinasiPembelian);

  //       $totalBiaya = array_sum(array_column($kombinasi, 'biaya'));

  //       if ($totalBiaya < $minBiaya) {
  //         $minBiaya = $totalBiaya;
  //         $tokoTerpilih = $kombinasi;
  //       }

  //       array_pop($kombinasiPembelian);
  //     }
  //   }

  //   array_push($this->raa, $tokoTerpilih);

  //   return $tokoTerpilih;
  // }

  // private function strategiPembelian($tokoList, $jumlahBarang) {
  //   // $this->cariKombinasiPembelian($tokoList, $jumlahBarang, []);
  //   // echo json_encode($this->raa);
  //   // die();

  //   $hasil = $this->cariKombinasiPembelian($tokoList, $jumlahBarang, []);
  //   // // print_r($hasil);
  //   // // die();

  //   return [
  //     'toko' => $hasil,
  //     'biaya' => array_sum(array_column($hasil, 'biaya')),
  //   ];
  // }
}