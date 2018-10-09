<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class laporan extends MY_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('func_helper');
		setDatabase($_SESSION['Database']);
	}

	public function bukukaspenerimaan()
	{
		
		$data['kaspenerimaan'] = $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),0) - COALESCE(SUM(trx_penjualan_det.potongan),0)) as total
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			GROUP BY trx_penjualan_det.id_penjualan");

		$content = $this->load->view("bukukaspenerimaan", $data, true);

		echo $content;
	}

	public function caribukaspenerimaan()
	{
		$data = array('bukas' => array());
		$tglAwal 	= date("Y-m-d", strtotime($this->input->post('tanggalawal')));
		$tglAkhir 	= date("Y-m-d", strtotime($this->input->post('tanggalakhir')));

		$bukas 	= $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),0) - COALESCE(SUM(trx_penjualan_det.potongan),0)) as total
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			WHERE (trx_penjualan.tanggal_penjualan BETWEEN '".$tglAwal."' AND '".$tglAkhir."') 
			GROUP BY trx_penjualan_det.id_penjualan");

		$salad = 0; $totSalad = 0;
		foreach($bukas->result_array() as $bu => $kas)
		{
			$salad += $kas['total'];
			$buk['tanggal'] 	= date("d-m-Y", strtotime($kas['tanggal_penjualan']));
			$buk['nobukti'] 	= $kas['no_transaksi'];
			$buk['keterangan'] 	= $kas['keterangan'];
			$buk['total'] 		= number_format($kas['total'],2);
			$buk['salad'] 		= number_format($salad,2);

			$data['bukas'][] 	= $buk;
		}

		echo json_encode($data);
	}

	public function printbukaspenerimaan($awal, $akhir)
	{
		$data = array('bukas' => array());
		$tglAwal 	= date("Y-m-d", strtotime($awal));
		$tglAkhir 	= date("Y-m-d", strtotime($akhir));

		$bukas 	= $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),0) - COALESCE(SUM(trx_penjualan_det.potongan),0)) as total
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			WHERE (trx_penjualan.tanggal_penjualan BETWEEN '".$tglAwal."' AND '".$tglAkhir."') 
			GROUP BY trx_penjualan_det.id_penjualan");

		$salad = 0; $totSalad = 0;
		foreach($bukas->result_array() as $bu => $kas)
		{
			$salad += $kas['total'];
			$buk['tanggal'] 	= date("d-m-Y", strtotime($kas['tanggal_penjualan']));
			$buk['nobukti'] 	= $kas['no_transaksi'];
			$buk['keterangan'] 	= $kas['keterangan'];
			$buk['total'] 		= number_format($kas['total'],2);
			$buk['salad'] 		= number_format($salad,2);

			$data['bukas'][] 	= $buk;
		}

		$konten 	= $this->load->view("printbukaspenerimaan", $data, true);
		echo $konten;
	}

	public function laporanpenjualan()
	{
		
		$data['penjualan'] = $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),0) - COALESCE(SUM(trx_penjualan_det.potongan),0)) as total
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			WHERE trx_penjualan.id_pelanggan <> 0
			GROUP BY trx_penjualan_det.id_penjualan");

		$content = $this->load->view("laporanpenjualan", $data, true);

		echo $content;
	}

	public function laporanpenerimaanlain()
	{
		
		$data['penerimaan'] = $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),0) - COALESCE(SUM(trx_penjualan_det.potongan),0)) as total 
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			WHERE trx_penjualan.id_pelanggan = 0
			GROUP BY trx_penjualan_det.id_penjualan");

		$content = $this->load->view("laporanpenerimaan", $data, true);

		echo $content;
	}

	public function bukukaspengeluaran()
	{
		
		$data['kaspengeluaran'] = $this->db->query("SELECT trx_pembelian_persediaan.* , 
			(COALESCE(SUM(trx_pembelian_persediaan_det.harga),0) - COALESCE(SUM(trx_pembelian_persediaan_det.potongan),0)) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			GROUP BY trx_pembelian_persediaan_det.id_pembelian");

		$content = $this->load->view("bukukaspengeluaran", $data, true);

		echo $content;
	}

	public function caribukaspengeluan()
	{
		$data = array('bukas' => array());
		$tglAwal 	= date("Y-m-d", strtotime($this->input->post('tanggalawal')));
		$tglAkhir 	= date("Y-m-d", strtotime($this->input->post('tanggalakhir')));

		$bukas 	= $this->db->query("SELECT trx_pembelian_persediaan.* , 
			(COALESCE(SUM(trx_pembelian_persediaan_det.harga),0) - COALESCE(SUM(trx_pembelian_persediaan_det.potongan),0)) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			WHERE trx_pembelian_persediaan.tanggal BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
			GROUP BY trx_pembelian_persediaan_det.id_pembelian");

		$salad = 0; $totSalad = 0;
		foreach($bukas->result_array() as $bu => $kas)
		{
			$salad += $kas['total'];
			$buk['tanggal'] 	= date("d-m-Y", strtotime($kas['tanggal']));
			$buk['nobukti'] 	= $kas['nomor_transaksi'];
			$buk['keterangan'] 	= $kas['deskripsi'];
			$buk['total'] 		= number_format($kas['total'],2);
			$buk['salad'] 		= number_format($salad,2);

			$data['bukas'][] 	= $buk;
		}

		echo json_encode($data);
	}

	public function printbukaspengeluaran($awal, $akhir)
	{
		$data = array('bukas' => array());
		$tglAwal 	= date("Y-m-d", strtotime($awal));
		$tglAkhir 	= date("Y-m-d", strtotime($akhir));

		$bukas 	= $this->db->query("SELECT trx_pembelian_persediaan.* , 
			(COALESCE(SUM(trx_pembelian_persediaan_det.harga),0) - COALESCE(SUM(trx_pembelian_persediaan_det.potongan),0)) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			WHERE trx_pembelian_persediaan.tanggal BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
			GROUP BY trx_pembelian_persediaan_det.id_pembelian");

		$salad = 0; $totSalad = 0;
		foreach($bukas->result_array() as $bu => $kas)
		{
			$salad += $kas['total'];
			$buk['tanggal'] 	= date("d-m-Y", strtotime($kas['tanggal']));
			$buk['nobukti'] 	= $kas['nomor_transaksi'];
			$buk['keterangan'] 	= $kas['deskripsi'];
			$buk['total'] 		= number_format($kas['total'],2);
			$buk['salad'] 		= number_format($salad,2);

			$data['bukas'][] 	= $buk;
		}

		$konten 	= $this->load->view("printbukaspengeluaran", $data, true);
		echo $konten;
	}

	public function laporanpembelianpersediaan()
	{
		
		$data['kaspengeluaran'] = $this->db->query("SELECT trx_pembelian_persediaan.* , 
			(COALESCE(SUM(trx_pembelian_persediaan_det.harga),0) - COALESCE(SUM(trx_pembelian_persediaan_det.potongan),0)) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			WHERE trx_pembelian_persediaan.id_pemasok <> 0
			GROUP BY trx_pembelian_persediaan_det.id_pembelian");

		$content = $this->load->view("laporanpembelianpersediaan", $data, true);

		echo $content;
	}

	public function laporanbiaya()
	{
		
		$data['kaspengeluaran'] = $this->db->query("SELECT trx_pembelian_persediaan.* , 
			(COALESCE(SUM(trx_pembelian_persediaan_det.harga),0) - COALESCE(SUM(trx_pembelian_persediaan_det.potongan),0)) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			WHERE trx_pembelian_persediaan.id_pemasok = 0
			GROUP BY trx_pembelian_persediaan_det.id_pembelian");

		$content = $this->load->view("laporanbiaya", $data, true);

		echo $content;
	}

	public function penjualanperkategori()
	{
		$data['penjualan'] = $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),0) - COALESCE(SUM(trx_penjualan_det.potongan),0)) as total
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			WHERE trx_penjualan.id_pelanggan <> 0
			GROUP BY trx_penjualan_det.id_penjualan");

		$data['kategori'] = $this->db->query("SELECT * FROM mst_kategori_item");

		$content = $this->load->view("laporanpenjualan_perkategori", $data, true);

		echo $content;
	}

	public function caridata_perkategori()
	{	
		$idx = $this->input->post("idx");
		$data = $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),0) - COALESCE(SUM(trx_penjualan_det.potongan),0)) as total
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			LEFT JOIN mst_item ON mst_item.id_item = trx_penjualan_det.id_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_item
			WHERE trx_penjualan.id_pelanggan <> 0
			AND mst_kategori_item.id_kategori_item = '".$idx."'
			GROUP BY trx_penjualan_det.id_penjualan");


		$saldoakhir = 0;
		foreach($data->result() as $row)
		{
			
			$saldoakhir += $row->total;
			$result['tanggal'] = date("d-m-Y", strtotime($row->tanggal_penjualan));
			$result['nobukti'] = $row->no_transaksi;
			$result['keterangan'] = $row->keterangan;
			$result['debet'] = number_format($row->total, 2);
			$result['kredit'] = number_format(0, 2);
			$result['saldoakhir'] = number_format($saldoakhir, 2);

			$json['data'][] = $result;
		}

		echo json_encode($json);
	}

	public function penjualanperitem()
	{
		$data['penjualan'] = $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),0) - COALESCE(SUM(trx_penjualan_det.potongan),0)) as total
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			WHERE trx_penjualan.id_pelanggan <> 0
			GROUP BY trx_penjualan_det.id_penjualan");

		$data['kategori'] = $this->db->query("SELECT * FROM mst_kategori_item");

		$content = $this->load->view("laporanpenjualan_peritem", $data, true);

		echo $content;
	}

	public function penjualanpertanggal()
	{
		$data['penjualan'] = $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),0) - COALESCE(SUM(trx_penjualan_det.potongan),0)) as total
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			WHERE trx_penjualan.id_pelanggan <> 0
			GROUP BY trx_penjualan_det.id_penjualan");


		$content = $this->load->view("laporanpenjualan_pertanggal", $data, true);

		echo $content;
	}


	public function lapperubahandana()
	{

		$idperusahaan = $_SESSION['IDSekolah'];
		$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
			WHERE sys_perusahaan.id_perusahaan = '".$idperusahaan."' ");

		$content = $this->load->view("laporanperubahandana", $data, true);

		echo $content;
	}

	public function previewperubahandana2()
	{
		$tglAwal 	= date("Y-m-d", strtotime($this->input->post('tanggalawal')));
		$tglAkhir 	= date("Y-m-d", strtotime($this->input->post('tanggalakhir')));

		$kewerJual  = "SELECT item.nama_item, (judet.jumlah_item * judet.harga) AS total FROM trx_penjualan ju
		LEFT JOIN trx_penjualan_det judet ON ju.id_penjualan = judet.id_penjualan
		LEFT JOIN mst_item item ON judet.id_item = item.id_item
		WHERE item.id_kategori_item =";
		$kewerBeli 	= "SELECT item.nama_item, (judet.jumlah * judet.harga) AS total FROM trx_pembelian_persediaan ju
		LEFT JOIN trx_pembelian_persediaan_det judet ON ju.id_pembelian = judet.id_pembelian
		LEFT JOIN mst_item item ON judet.id_item = item.id_item
		WHERE item.id_kategori_item =";
		$werjual  	= " AND (ju.tanggal_penjualan BETWEEN '".$tglAwal."' AND '".$tglAkhir."' ) ";
		$werbeli  	= " AND (ju.tanggal BETWEEN '".$tglAwal."' AND '".$tglAkhir."' ) ";

			// ZAKAT
		$data['zakat'] = array('terima' => array(), 'keluar' => array(), 'totTerima' => 0, 'totKeluar' => 0);
				// terima
		$terimaZakat = $this->db->query("".$kewerJual." 6 ".$werjual." ");

		foreach ($terimaZakat->result_array() as $a => $bb) {
			$data['zakat']['terima'][] = $bb;
			$data['zakat']['totTerima'] +=+ $bb['total'];
		}

				// keluar
		$keluarZakat = $this->db->query("".$kewerBeli." 6 ".$werbeli." ");

		foreach ($keluarZakat->result_array() as $c => $dd) {
			$data['zakat']['keluar'][] = $dd;
			$data['zakat']['totKeluar'] +=+ $dd['total'];
		}

			// DANA INFAQ / SEDEKAH
		$data['infaq'] = array('terima' => array(), 'keluar' => array(), 'totTerima' => 0, 'totKeluar' => 0);
				// terima
		$terimaInfaq = $this->db->query("".$kewerJual." 7 ".$werjual." ");

		foreach ($terimaInfaq->result_array() as $e => $ff) {
			$data['infaq']['terima'][] = $ff;
			$data['infaq']['totTerima'] +=+ $ff['total'];
		}

				// keluar
		$keluarInfaq = $this->db->query("".$kewerBeli." 7 ".$werbeli." ");

		foreach ($keluarInfaq->result_array() as $g => $hh) {
			$data['infaq']['keluar'][] = $hh;
			$data['infaq']['totKeluar'] +=+ $hh['total'];
		}

			// DANA AMIL
		$data['amil'] = array('terima' => array(), 'keluar' => array(), 'totTerima' => 0, 'totKeluar' => 0);
				// terima
		$terimaAmil = $this->db->query("".$kewerJual." 8 ".$werjual." ");

		foreach ($terimaAmil->result_array() as $i => $jj) {
			$data['amil']['terima'][] = $jj;
			$data['amil']['totTerima'] +=+ $jj['total'];
		}

				// keluar
		$keluarAmil = $this->db->query("".$kewerBeli." 8 ".$werbeli." ");

		foreach ($keluarAmil->result_array() as $k => $ll) {
			$data['amil']['keluar'][] = $ll;
			$data['amil']['totKeluar'] +=+ $ll['total'];
		}

			// NON-HALAL
		$data['non'] = array('terima' => array(), 'keluar' => array(), 'totTerima' => 0, 'totKeluar' => 0);
				// terima
		$terimaNon = $this->db->query("".$kewerJual." 9 ".$werjual." ");

		foreach ($terimaNon->result_array() as $i => $jj) {
			$data['non']['terima'][] = $jj;
			$data['non']['totTerima'] +=+ $jj['total'];
		}

				// keluar
		$keluarNon = $this->db->query("".$kewerBeli." 9 ".$werbeli." ");

		foreach ($keluarNon->result_array() as $k => $ll) {
			$data['non']['keluar'][] = $ll;
			$data['non']['totKeluar'] +=+ $ll['total'];

		}

		echo json_encode($data);
	}

	function lapneraca()
	{



		$content = $this->load->view("laporanneraca", "", true);

		echo $content;
	}

	function printneraca()
	{
		$idperusahaan = $_SESSION['IDSekolah'];
		$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
			WHERE sys_perusahaan.id_perusahaan = '".$idperusahaan."' ");

		$content = $this->load->view("printneraca", $data, true);

		echo $content;
	}

	function neracapotrait()
	{

		$content = $this->load->view("laporanneracapotrait", "", true);

		echo $content;
	}

	public function previewlaporanneracapotrait($tipe = NULL, $periode = NULL, $nilai=NULL)
	{
		$idperusahaan = $_SESSION['IDSekolah'];

		if($tipe == NULL)
		{
			$data['periode'] = $this->input->post("periode");
			$data['nilai'] = $this->input->post("nilai");
		}

		$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
			WHERE sys_perusahaan.id_perusahaan = '".$idperusahaan."' ");

		$content = $this->load->view("laporan/cetaklaporanneracapotrait",$data, true);

		echo $content;
	}

	public function previewperubahandana()
	{
		$tglAwal 	= date("Y-m-d", strtotime($this->input->post('tanggalawal')));
		$tglAkhir 	= date("Y-m-d", strtotime($this->input->post('tanggalakhir')));


			// ZAKAT

		$zakatTerima = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '4.1.1.01')");

		$totTerima = 0;
		foreach($zakatTerima->result_array() as $zTerima)
		{
			$totalZakatTerima = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_penjualan_det.jumlah_item * trx_penjualan_det.harga),0) - COALESCE(trx_penjualan_det.potongan,0)) as total, 
				mst_item.nama_item as nama_item, 
				mst_kategori_item.id_akun as id_akun 
				FROM  mst_item
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_item = mst_item.id_item 
				LEFT JOIN  trx_penjualan ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan 
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item 
				WHERE mst_item.id_item = '".$zTerima['id_item']."'
				AND (trx_penjualan.tanggal_penjualan >= '".$tglAwal."' AND trx_penjualan.tanggal_penjualan <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");
			
			$total = (isset($totalZakatTerima->first_row()->total)) ? $totalZakatTerima->first_row()->total : 0;

			$rowzTerima['nama_item'] = $zTerima['nama_item'];
			$rowzTerima['idakun'] = $zTerima['id_akun'];
			$rowzTerima['total'] = number_format($total);

			$json['zakat']['terima'][] = $rowzTerima;

			$totTerima += $total;
		}
		$json['zakat']['totTerima'] = number_format($totTerima);

		$zakatKeluar = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '5.1.1.01')");

		$totKeluar = 0;
		foreach($zakatKeluar->result_array() as $zKeluar)
		{
			
			$totalZakatKeluar = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga),0) - COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total,
				mst_item.nama_item as nama_item,
				mst_kategori_item.id_akun as id_akun
				FROM mst_item
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_item = mst_item.id_item
				LEFT JOIN trx_pembelian_persediaan ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
				WHERE mst_item.id_item = '".$zKeluar['id_item']."'
				AND (trx_pembelian_persediaan.tanggal >= '".$tglAwal."' AND trx_pembelian_persediaan.tanggal <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalZakatKeluar->first_row()->total)) ? $totalZakatKeluar->first_row()->total : 0;

			$rowzKeluar['nama_item'] = $zKeluar['nama_item'];
			$rowzKeluar['idakun'] = $zKeluar['id_akun'];
			$rowzKeluar['total'] = number_format($total);

			$json['zakat']['keluar'][] = $rowzKeluar;

			$totKeluar += $total;
		}

		$json['zakat']['totKeluar'] = number_format($totKeluar);
		$json['zakat']['totZakat'] = number_format(($totTerima) - $totKeluar);

			// DANA INFAQ / INFAQ

		$infaqTerima = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '4.1.2.01')");

		$totTerima = 0;

		foreach($infaqTerima->result_array() as $infqTerima)
		{
			
			$totalInfaqTerima = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_penjualan_det.jumlah_item * trx_penjualan_det.harga),0) - COALESCE(trx_penjualan_det.potongan,0)) as total, 
				mst_item.nama_item as nama_item, 
				mst_kategori_item.id_akun as id_akun 
				FROM  mst_item
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_item = mst_item.id_item 
				LEFT JOIN  trx_penjualan ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan 
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item 
				WHERE mst_item.id_item = '".$infqTerima['id_item']."'
				AND (trx_penjualan.tanggal_penjualan >= '".$tglAwal."' AND trx_penjualan.tanggal_penjualan <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");
			
			$total = (isset($totalInfaqTerima->first_row()->total)) ? $totalInfaqTerima->first_row()->total : 0;

			$rowInfqTerima['nama_item'] = $infqTerima['nama_item'];
			$rowInfqTerima['idakun'] = $infqTerima['id_akun'];
			$rowInfqTerima['total'] = number_format($total);

			$json['infaq']['terima'][] = $rowInfqTerima;

			$totTerima += $total;
		}

		$json['infaq']['totTerima'] = number_format($totTerima);


		$infaqKeluar = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '5.1.2.01')");

		$totKeluar = 0;

		foreach($infaqKeluar->result_array() as $infqKeluar)
		{

			$totalInfaqKeluar = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga),0) - COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total,
				mst_item.nama_item as nama_item,
				mst_kategori_item.id_akun as id_akun
				FROM mst_item
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_item = mst_item.id_item
				LEFT JOIN trx_pembelian_persediaan ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
				WHERE mst_item.id_item = '".$infqKeluar['id_item']."'
				AND (trx_pembelian_persediaan.tanggal >= '".$tglAwal."' AND trx_pembelian_persediaan.tanggal <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalInfaqKeluar->first_row()->total)) ? $totalInfaqKeluar->first_row()->total : 0;

			$rowinfqKeluar['nama_item'] = $infqKeluar['nama_item'];
			$rowinfqKeluar['idakun'] = $infqKeluar['id_akun'];
			$rowinfqKeluar['total'] = number_format($total);

			$json['infaq']['keluar'][] = $rowinfqKeluar;

			$totKeluar += $total;
		}

		$json['infaq']['totKeluar'] = number_format($totKeluar);
		$json['infaq']['totInfaq'] = number_format(($totTerima ) - $totKeluar);

			// DANA AMIL
		$amilTerima = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '4.1.3.01')");

		$totTerima = 0;

		foreach($amilTerima->result_array() as $amlTerima)
		{

			$totalAmlTerima = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_penjualan_det.jumlah_item * trx_penjualan_det.harga),0) - COALESCE(trx_penjualan_det.potongan,0)) as total, 
				mst_item.nama_item as nama_item, 
				mst_kategori_item.id_akun as id_akun 
				FROM  mst_item
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_item = mst_item.id_item 
				LEFT JOIN  trx_penjualan ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan 
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item 
				WHERE mst_item.id_item = '".$amlTerima['id_item']."'
				AND (trx_penjualan.tanggal_penjualan >= '".$tglAwal."' AND trx_penjualan.tanggal_penjualan <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalAmlTerima->first_row()->total)) ? $totalAmlTerima->first_row()->total : 0;

			$rowAmlTerima['nama_item'] = $amlTerima['nama_item'];
			$rowAmlTerima['idakun'] = $amlTerima['id_akun'];
			$rowAmlTerima['total'] = number_format($total);

			$json['amil']['terima'][] = $rowAmlTerima;

			$totTerima += $total;
		}

		$json['amil']['totTerima'] = number_format($totTerima);

		$amilKeluar =  $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '5.1.3.01')");


		$totKeluar = 0;

		foreach($amilKeluar->result_array() as $amlKeluar)
		{

			$totalAmlKeluar = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga),0) - COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total,
				mst_item.nama_item as nama_item,
				mst_kategori_item.id_akun as id_akun
				FROM mst_item
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_item = mst_item.id_item
				LEFT JOIN trx_pembelian_persediaan ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
				WHERE mst_item.id_item = '".$amlKeluar['id_item']."'
				AND (trx_pembelian_persediaan.tanggal >= '".$tglAwal."' AND trx_pembelian_persediaan.tanggal <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalAmlKeluar->first_row()->total)) ? $totalAmlKeluar->first_row()->total : 0;

			$rowamlKeluar['nama_item'] = $amlKeluar['nama_item'];
			$rowamlKeluar['idakun'] = $amlKeluar['id_akun'];
			$rowamlKeluar['total'] = number_format($total);

			$json['amil']['keluar'][] = $rowamlKeluar;
			
			$totKeluar += $total;
		}

		$json['amil']['totKeluar'] = number_format($totKeluar);
		$json['amil']['totAmil'] = number_format(($totTerima) - $totKeluar);

			// NON HALAL //
		$nonhalalTerima = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '4.1.4.01')");

		$totTerima = 0;

		foreach($nonhalalTerima->result_array() as $nhalal)
		{

			$totalnhalal = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_penjualan_det.jumlah_item * trx_penjualan_det.harga),0) - COALESCE(trx_penjualan_det.potongan,0)) as total, 
				mst_item.nama_item as nama_item, 
				mst_kategori_item.id_akun as id_akun 
				FROM  mst_item
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_item = mst_item.id_item 
				LEFT JOIN  trx_penjualan ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan 
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item 
				WHERE mst_item.id_item = '".$nhalal['id_item']."'
				AND (trx_penjualan.tanggal_penjualan >= '".$tglAwal."' AND trx_penjualan.tanggal_penjualan <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalnhalal->first_row()->total)) ? $totalnhalal->first_row()->total : 0;

			$rowNonHalal['nama_item'] = $nhalal['nama_item'];
			$rowNonHalal['idakun'] = $nhalal['id_akun'];
			$rowNonHalal['total'] = number_format($total);

			$json['non']['terima'][] = $rowNonHalal;

			$totTerima += $total;
		}

		$json['non']['totTerima'] = number_format($totTerima);

		$nonhalalKeluar = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '5.1.4.01')");


		$totKeluar = 0;

		foreach($nonhalalKeluar->result_array() as $nhalalKeluar)
		{
			$totalnhalalKeluar = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga),0) - COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total,
				mst_item.nama_item as nama_item,
				mst_kategori_item.id_akun as id_akun
				FROM mst_item
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_item = mst_item.id_item
				LEFT JOIN trx_pembelian_persediaan ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
				WHERE mst_item.id_item = '".$nhalalKeluar['id_item']."'
				AND (trx_pembelian_persediaan.tanggal >= '".$tglAwal."' AND trx_pembelian_persediaan.tanggal <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalnhalalKeluar->first_row()->total)) ? $totalnhalalKeluar->first_row()->total : 0;

			$rownhalalKeluar['nama_item'] = $nhalalKeluar['nama_item'];
			$rownhalalKeluar['idakun'] = $nhalalKeluar['id_akun'];
			$rownhalalKeluar['total'] = number_format($total);

			$json['non']['keluar'][] = $rownhalalKeluar;

			$totKeluar += $total;
		}

		$json['non']['totKeluar'] = number_format($totKeluar);
		$json['non']['totNon'] = number_format(($totTerima) - $totKeluar);

		$totalZakat = str_replace(",","",$json['zakat']['totZakat']);
		$totalInfaq = str_replace(",","",$json['infaq']['totInfaq']);
		$totalAmil = str_replace(",","",$json['amil']['totAmil']);
		$totalNon = str_replace(",","",$json['non']['totNon']);

		$json['totalAll'] = number_format(($totalZakat + $totalInfaq + $totalAmil + $totalNon));




		echo json_encode($json);

	}

	function bukukasharian()
	{
		$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
			WHERE sys_perusahaan.id_perusahaan = '".$_SESSION['IDSekolah']."' ");

		$content = $this->load->view("laporanbukukasharian", $data, true);

		echo $content;			
	}

	public function previewbukasharian()
	{
		$tglAwal 	= date("Y-m-d", strtotime($this->input->post('tanggalawal')));
		$tglAkhir 	= date("Y-m-d", strtotime($this->input->post('tanggalakhir')));

		$werjual  	= "WHERE (ju.tanggal_penjualan BETWEEN '".$tglAwal."' AND '".$tglAkhir."' ) ";
		$werbeli  	= "WHERE (juu.tanggal BETWEEN '".$tglAwal."' AND '".$tglAkhir."' ) ";
		$kewerJual  = $this->db->query("SELECT * FROM (
			SELECT ju.id_penjualan AS id, ju.tanggal_penjualan AS tgl, ju.no_transaksi AS no_bukti, 
			ju.keterangan AS keterangan, SUM(judet.jumlah_item * judet.harga) AS debet, CONCAT(0) AS kredit 
			FROM trx_penjualan ju
			LEFT JOIN trx_penjualan_det judet ON ju.id_penjualan = judet.id_penjualan
			LEFT JOIN mst_item item ON judet.id_item = item.id_item
			".$werjual." GROUP BY judet.id_penjualan
			UNION 
			SELECT juu.id_pembelian AS id, juu.tanggal AS tgl, juu.nomor_transaksi AS no_bukti,
			juu.deskripsi AS keterangan, CONCAT(0) AS debet, SUM(juudet.jumlah * juudet.harga) AS kredit 
			FROM trx_pembelian_persediaan juu
			LEFT JOIN trx_pembelian_persediaan_det juudet ON juu.id_pembelian = juudet.id_pembelian
			LEFT JOIN mst_item item ON juudet.id_item = item.id_item
			".$werbeli." GROUP BY juudet.id_pembelian
			) aa ORDER BY aa.tgl");

		foreach($kewerJual->result_array() as $aa => $bb)
		{
			$bb['kredit'] 	= str_replace('.0000', '', $bb['kredit']);
			$bb['tgl'] 		= date("d-m-Y", strtotime($bb['tgl']));
			$data['data'][] = $bb;
		}

		$jojon = json_encode($data);
		echo $jojon;

	}


	function printperubdana2($tglAwal, $tglAkhir)
	{
		$tglAwal = date("Y-m-d", strtotime($tglAwal));
		$tglAkhir= date("Y-m-d", strtotime($tglAkhir));
		$werjual  	= "WHERE (ju.tanggal_penjualan BETWEEN '".$tglAwal."' AND '".$tglAkhir."' ) ";
		$werbeli  	= "WHERE (juu.tanggal BETWEEN '".$tglAwal."' AND '".$tglAkhir."' ) ";

		$kewerJual  = "SELECT item.nama_item, (judet.jumlah_item * judet.harga) AS total FROM trx_penjualan ju
		LEFT JOIN trx_penjualan_det judet ON ju.id_penjualan = judet.id_penjualan
		LEFT JOIN mst_item item ON judet.id_item = item.id_item
		WHERE item.id_kategori_item =";
		$kewerBeli 	= "SELECT item.nama_item, (judet.jumlah * judet.harga) AS total FROM trx_pembelian_persediaan ju
		LEFT JOIN trx_pembelian_persediaan_det judet ON ju.id_pembelian = judet.id_pembelian
		LEFT JOIN mst_item item ON judet.id_item = item.id_item
		WHERE item.id_kategori_item =";
		$werjual  	= " AND (ju.tanggal_penjualan BETWEEN '".$tglAwal."' AND '".$tglAkhir."' ) ";
		$werbeli  	= " AND (ju.tanggal BETWEEN '".$tglAwal."' AND '".$tglAkhir."' ) ";

			// ZAKAT
		$data['zakat'] = array('terima' => array(), 'keluar' => array(), 'totTerima' => 0, 'totKeluar' => 0);
				// terima
		$terimaZakat = $this->db->query("".$kewerJual." 6 ".$werjual." ");

		foreach ($terimaZakat->result_array() as $a => $bb) {
			$data['zakat']['terima'][] = $bb;
			$data['zakat']['totTerima'] +=+ $bb['total'];
		}

				// keluar
		$keluarZakat = $this->db->query("".$kewerBeli." 6 ".$werbeli." ");

		foreach ($keluarZakat->result_array() as $c => $dd) {
			$data['zakat']['keluar'][] = $dd;
			$data['zakat']['totKeluar'] +=+ $dd['total'];
		}

		$totZakat = $data['zakat']['totTerima'] - $data['zakat']['totKeluar'];
		$data['zakat']['totZakat'] = number_format($totZakat);
		$data['zakat']['totKeluar'] = number_format($data['zakat']['totKeluar']);
		$data['zakat']['totTerima'] = number_format($data['zakat']['totTerima']);

			// DANA INFAQ / SEDEKAH
		$data['infaq'] = array('terima' => array(), 'keluar' => array(), 'totTerima' => 0, 'totKeluar' => 0);
				// terima
		$terimaInfaq = $this->db->query("".$kewerJual." 7 ".$werjual." ");

		foreach ($terimaInfaq->result_array() as $e => $ff) {
			$data['infaq']['terima'][] = $ff;
			$data['infaq']['totTerima'] +=+ $ff['total'];
		}

				// keluar
		$keluarInfaq = $this->db->query("".$kewerBeli." 7 ".$werbeli." ");

		foreach ($keluarInfaq->result_array() as $g => $hh) {
			$data['infaq']['keluar'][] = $hh;
			$data['infaq']['totKeluar'] +=+ $hh['total'];
		}

		$totInfaq = $data['infaq']['totTerima'] - $data['infaq']['totKeluar'];
		$data['infaq']['totInfaq'] = number_format($totInfaq);
		$data['infaq']['totTerima'] = number_format($data['infaq']['totTerima']);
		$data['infaq']['totKeluar'] = number_format($data['infaq']['totKeluar']);


			// DANA AMIL
		$data['amil'] = array('terima' => array(), 'keluar' => array(), 'totTerima' => 0, 'totKeluar' => 0);
				// terima
		$terimaAmil = $this->db->query("".$kewerJual." 8 ".$werjual." ");

		foreach ($terimaAmil->result_array() as $i => $jj) {
			$data['amil']['terima'][] = $jj;
			$data['amil']['totTerima'] +=+ $jj['total'];
		}

				// keluar
		$keluarAmil = $this->db->query("".$kewerBeli." 8 ".$werbeli." ");

		foreach ($keluarAmil->result_array() as $k => $ll) {
			$data['amil']['keluar'][] = $ll;
			$data['amil']['totKeluar'] +=+ $ll['total'];
		}

		$totAmil 	= $data['amil']['totTerima'] - $data['amil']['totKeluar'];
		$data['amil']['totAmil']	= number_format($totAmil);
		$data['amil']['totTerima'] = number_format($data['amil']['totTerima']);
		$data['amil']['totKeluar'] = number_format($data['amil']['totKeluar']);

			// NON-HALAL
		$data['non'] = array('terima' => array(), 'keluar' => array(), 'totTerima' => 0, 'totKeluar' => 0);
				// terima
		$terimaNon = $this->db->query("".$kewerJual." 9 ".$werjual." ");

		foreach ($terimaNon->result_array() as $i => $jj) {
			$data['non']['terima'][] = $jj;
			$data['non']['totTerima'] +=+ $jj['total'];
		}

				// keluar
		$keluarNon = $this->db->query("".$kewerBeli." 9 ".$werbeli." ");

		foreach ($keluarNon->result_array() as $k => $ll) {
			$data['non']['keluar'][] = $ll;
			$data['non']['totKeluar'] +=+ $ll['total'];
		}

		$totNon 	= $data['non']['totTerima'] - $data['non']['totKeluar'];
		$data['non']['totNon'] 	= number_format($totNon);
		$data['non']['totTerima'] = number_format($data['non']['totTerima']);
		$data['non']['totKeluar'] = number_format($data['non']['totKeluar']);

		$data['totalAll'] = number_format($totZakat + $totInfaq + $totAmil + $totNon);

		$konten = $this->load->view('printperubdana', $data, true);
		echo $konten;
	}

	function printperubdana($tglAwal, $tglAkhir)
	{
		$tglAwal 	= date("Y-m-d", strtotime($tglAwal));
		$tglAkhir 	= date("Y-m-d", strtotime($tglAkhir));


			// ZAKAT

		$zakatTerima = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '4.1.1.01')");

		$totTerima = 0;
		foreach($zakatTerima->result_array() as $zTerima)
		{
			$totalZakatTerima = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_penjualan_det.jumlah_item * trx_penjualan_det.harga),0) - COALESCE(trx_penjualan_det.potongan,0)) as total, 
				mst_item.nama_item as nama_item, 
				mst_kategori_item.id_akun as id_akun 
				FROM  mst_item
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_item = mst_item.id_item 
				LEFT JOIN  trx_penjualan ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan 
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item 
				WHERE mst_item.id_item = '".$zTerima['id_item']."'
				AND (trx_penjualan.tanggal_penjualan >= '".$tglAwal."' AND trx_penjualan.tanggal_penjualan <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");
			
			$total = (isset($totalZakatTerima->first_row()->total)) ? $totalZakatTerima->first_row()->total : 0;

			$rowzTerima['nama_item'] = $zTerima['nama_item'];
			$rowzTerima['idakun'] = $zTerima['id_akun'];
			$rowzTerima['total'] = number_format($total);

			$json['zakat']['terima'][] = $rowzTerima;

			$totTerima += $total;
		}

		$json['zakat']['totTerima'] = number_format($totTerima);

		$zakatKeluar = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '5.1.1.01')");

		$totKeluar = 0;
		foreach($zakatKeluar->result_array() as $zKeluar)
		{
			
			$totalZakatKeluar = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga),0) - COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total,
				mst_item.nama_item as nama_item,
				mst_kategori_item.id_akun as id_akun
				FROM mst_item
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_item = mst_item.id_item
				LEFT JOIN trx_pembelian_persediaan ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
				WHERE mst_item.id_item = '".$zKeluar['id_item']."'
				AND (trx_pembelian_persediaan.tanggal >= '".$tglAwal."' AND trx_pembelian_persediaan.tanggal <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalZakatKeluar->first_row()->total)) ? $totalZakatKeluar->first_row()->total : 0;

			$rowzKeluar['nama_item'] = $zKeluar['nama_item'];
			$rowzKeluar['idakun'] = $zKeluar['id_akun'];
			$rowzKeluar['total'] = number_format($total);

			$json['zakat']['keluar'][] = $rowzKeluar;

			$totKeluar += $total;
		}

		$json['zakat']['totKeluar'] = number_format($totKeluar);
		$json['zakat']['totZakat'] = number_format(($totTerima) - $totKeluar);

			// DANA INFAQ / INFAQ

		$infaqTerima = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '4.1.2.01')");

		$totTerima = 0;

		foreach($infaqTerima->result_array() as $infqTerima)
		{
			
			$totalInfaqTerima = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_penjualan_det.jumlah_item * trx_penjualan_det.harga),0) - COALESCE(trx_penjualan_det.potongan,0)) as total, 
				mst_item.nama_item as nama_item, 
				mst_kategori_item.id_akun as id_akun 
				FROM  mst_item
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_item = mst_item.id_item 
				LEFT JOIN  trx_penjualan ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan 
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item 
				WHERE mst_item.id_item = '".$infqTerima['id_item']."'
				AND (trx_penjualan.tanggal_penjualan >= '".$tglAwal."' AND trx_penjualan.tanggal_penjualan <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");
			
			$total = (isset($totalInfaqTerima->first_row()->total)) ? $totalInfaqTerima->first_row()->total : 0;

			$rowInfqTerima['nama_item'] = $infqTerima['nama_item'];
			$rowInfqTerima['idakun'] = $infqTerima['id_akun'];
			$rowInfqTerima['total'] = number_format($total);

			$json['infaq']['terima'][] = $rowInfqTerima;

			$totTerima += $total;
		}

		$json['infaq']['totTerima'] = number_format($totTerima);


		$infaqKeluar = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '5.1.2.01')");

		$totKeluar = 0;

		foreach($infaqKeluar->result_array() as $infqKeluar)
		{

			$totalInfaqKeluar = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga),0) - COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total,
				mst_item.nama_item as nama_item,
				mst_kategori_item.id_akun as id_akun
				FROM mst_item
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_item = mst_item.id_item
				LEFT JOIN trx_pembelian_persediaan ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
				WHERE mst_item.id_item = '".$infqKeluar['id_item']."'
				AND (trx_pembelian_persediaan.tanggal >= '".$tglAwal."' AND trx_pembelian_persediaan.tanggal <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalInfaqKeluar->first_row()->total)) ? $totalInfaqKeluar->first_row()->total : 0;

			$rowinfqKeluar['nama_item'] = $infqKeluar['nama_item'];
			$rowinfqKeluar['idakun'] = $infqKeluar['id_akun'];
			$rowinfqKeluar['total'] = number_format($total);

			$json['infaq']['keluar'][] = $rowinfqKeluar;

			$totKeluar += $total;
		}

		$json['infaq']['totKeluar'] = number_format($totKeluar);
		$json['infaq']['totInfaq'] = number_format(($totTerima ) - $totKeluar);

			// DANA AMIL
		$amilTerima = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '4.1.3.01')");

		$totTerima = 0;

		foreach($amilTerima->result_array() as $amlTerima)
		{

			$totalAmlTerima = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_penjualan_det.jumlah_item * trx_penjualan_det.harga),0) - COALESCE(trx_penjualan_det.potongan,0)) as total, 
				mst_item.nama_item as nama_item, 
				mst_kategori_item.id_akun as id_akun 
				FROM  mst_item
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_item = mst_item.id_item 
				LEFT JOIN  trx_penjualan ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan 
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item 
				WHERE mst_item.id_item = '".$amlTerima['id_item']."'
				AND (trx_penjualan.tanggal_penjualan >= '".$tglAwal."' AND trx_penjualan.tanggal_penjualan <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalAmlTerima->first_row()->total)) ? $totalAmlTerima->first_row()->total : 0;

			$rowAmlTerima['nama_item'] = $amlTerima['nama_item'];
			$rowAmlTerima['idakun'] = $amlTerima['id_akun'];
			$rowAmlTerima['total'] = number_format($total);

			$json['amil']['terima'][] = $rowAmlTerima;

			$totTerima += $total;
		}

		$json['amil']['totTerima'] = number_format($totTerima);

		$amilKeluar =  $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '5.1.3.01')");


		$totKeluar = 0;

		foreach($amilKeluar->result_array() as $amlKeluar)
		{

			$totalAmlKeluar = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga),0) - COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total,
				mst_item.nama_item as nama_item,
				mst_kategori_item.id_akun as id_akun
				FROM mst_item
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_item = mst_item.id_item
				LEFT JOIN trx_pembelian_persediaan ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
				WHERE mst_item.id_item = '".$amlKeluar['id_item']."'
				AND (trx_pembelian_persediaan.tanggal >= '".$tglAwal."' AND trx_pembelian_persediaan.tanggal <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalAmlKeluar->first_row()->total)) ? $totalAmlKeluar->first_row()->total : 0;

			$rowamlKeluar['nama_item'] = $amlKeluar['nama_item'];
			$rowamlKeluar['idakun'] = $amlKeluar['id_akun'];
			$rowamlKeluar['total'] = number_format($total);

			$json['amil']['keluar'][] = $rowamlKeluar;
			
			$totKeluar += $total;
		}

		$json['amil']['totKeluar'] = number_format($totKeluar);
		$json['amil']['totAmil'] = number_format(($totTerima) - $totKeluar);

			// NON HALAL //
		$nonhalalTerima = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '4.1.4.01')");

		$totTerima = 0;

		foreach($nonhalalTerima->result_array() as $nhalal)
		{

			$totalnhalal = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_penjualan_det.jumlah_item * trx_penjualan_det.harga),0) - COALESCE(trx_penjualan_det.potongan,0)) as total, 
				mst_item.nama_item as nama_item, 
				mst_kategori_item.id_akun as id_akun 
				FROM  mst_item
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_item = mst_item.id_item 
				LEFT JOIN  trx_penjualan ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan 
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item 
				WHERE mst_item.id_item = '".$nhalal['id_item']."'
				AND (trx_penjualan.tanggal_penjualan >= '".$tglAwal."' AND trx_penjualan.tanggal_penjualan <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalnhalal->first_row()->total)) ? $totalnhalal->first_row()->total : 0;

			$rowNonHalal['nama_item'] = $nhalal['nama_item'];
			$rowNonHalal['idakun'] = $nhalal['id_akun'];
			$rowNonHalal['total'] = number_format($total);

			$json['non']['terima'][] = $rowNonHalal;

			$totTerima += $total;
		}

		$json['non']['totTerima'] = number_format($totTerima);


		$nonhalalKeluar = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			WHERE mst_kategori_item.id_akun = (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '5.1.4.01')");


		$totKeluar = 0;

		foreach($nonhalalKeluar->result_array() as $nhalalKeluar)
		{
			$totalnhalalKeluar = $this->db->query("SELECT *, 
				SUM(COALESCE((trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga),0) - COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total,
				mst_item.nama_item as nama_item,
				mst_kategori_item.id_akun as id_akun
				FROM mst_item
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_item = mst_item.id_item
				LEFT JOIN trx_pembelian_persediaan ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
				WHERE mst_item.id_item = '".$nhalalKeluar['id_item']."'
				AND (trx_pembelian_persediaan.tanggal >= '".$tglAwal."' AND trx_pembelian_persediaan.tanggal <= '".$tglAkhir."')
				GROUP BY mst_item.id_item");

			$total = (isset($totalnhalalKeluar->first_row()->total)) ? $totalnhalalKeluar->first_row()->total : 0;

			$rownhalalKeluar['nama_item'] = $nhalalKeluar['nama_item'];
			$rownhalalKeluar['idakun'] = $nhalalKeluar['id_akun'];
			$rownhalalKeluar['total'] = number_format($total);

			$json['non']['keluar'][] = $rownhalalKeluar;

			$totKeluar += $total;
		}

		$json['non']['totKeluar'] = number_format($totKeluar);
		$json['non']['totNon'] = number_format(($totTerima) - $totKeluar);

		$totalZakat = str_replace(",","",$json['zakat']['totZakat']);
		$totalInfaq = str_replace(",","",$json['infaq']['totInfaq']);
		$totalAmil = str_replace(",","",$json['amil']['totAmil']);
		$totalNon = str_replace(",","",$json['non']['totNon']);

		$json['totalAll'] = number_format(($totalZakat + $totalInfaq + $totalAmil + $totalNon));

		$konten = $this->load->view('printperubdana', $json, true);
		echo $konten;
	}

	public function perubahanaset()
	{
		
		

		$content = $this->load->view("laporanperubahanaset","",true);

		echo $content;
	}

	public function printlaporanaset()
	{
		$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
			WHERE sys_perusahaan.id_perusahaan = '".$_SESSION['IDSekolah']."'");

		$data['piutang'] = $this->db->query("SELECT 
			mst_akun.id_akun as idakun,
			mst_akun.nama_akun as namaakun, 
			CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeakun 
			FROM mst_akun 
			WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.1.3%'
			AND mst_akun.level = '4'");

		$data['asettetap'] = $this->db->query("SELECT 
			mst_akun.id_akun as idakun,
			mst_akun.nama_akun as namaakun, 
			CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeakun 
			FROM mst_akun 
			WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.2.1%'
			AND mst_akun.level = '4' and mst_akun.header = '0'");

		$content = $this->load->view("printlaporankelolaaset", $data, true);

		echo $content;
	}

	public function calk()
	{
		$content = $this->load->view("cetak_laporan_calk_view","",true);

		echo $content;
	}

		// tyas 19_mei
	public function previewlaporancalk($tipe=NULL, $periode=NULL, $nilai=NULL)
	{
		$this->load->model("laporan_model","ModelKodeAkun");

		if($tipe==NULL)
		{
			$data['periode'] = $this->input->post("periode");
			$data['nilai'] = $this->input->post("nilai");
		}

		$idperusahaan = $_SESSION['IDSekolah'];
		$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
			WHERE sys_perusahaan.id_perusahaan = '".$idperusahaan."' ");

		$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');

		$content = $this->load->view("laporan/cetaklaporancalk",$data,true);

		echo $content;
	}

	public function settingnarasicalk()
	{
		
		$data['narasi'] = $this->db->query("SELECT * FROM ref_narasi_calk");
		
		$content = $this->load->view("opennarasicalk","",true);

		echo $content;
	}

	public function UpdateCALKPointA()
	{
		$param = $this->db->query("SELECT * FROM ref_narasi_calk WHERE ref_narasi_calk.id_perusahaan = '".$_SESSION['IDSekolah']."'");

		$this->form_validation->set_rules('pointA1', 'Point A1', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pointA2', 'Point A2', 'trim|required|xss_clean');


		if ( ! $this->form_validation->run() )
		{               
			$errorMessage = form_error('pointA1').form_error('pointA2');
			$messageData = ConstructMessageResponse($errorMessage , 'warning');

			echo $messageData;
			exit;
		}
		else
		{       

			$pointA1    = $this->input->post('pointA1', true);
			$pointA2    = $this->input->post('pointA2', true);

			$data       =  array("point_a1"  => $pointA1,
				"point_a2"  => $pointA2,
				"id_perusahaan" => $_SESSION['IDSekolah']);

			if($param->num_rows() == 0)
			{
				$this->db->insert("ref_narasi_calk", $data);
			}
			else
			{
				$this->db->where("id_perusahaan", $_SESSION['IDSekolah']);
				$this->db->update("ref_narasi_calk", $data);
			}
			$messageData = ConstructMessageResponse("Data telah berhasil disimpan" , 'success');
			echo $messageData;
		}

	}

	public function UpdateCALKPointB()
	{

		$param = $this->db->query("SELECT * FROM ref_narasi_calk WHERE ref_narasi_calk.id_perusahaan = '".$_SESSION['IDSekolah']."'");

		$this->form_validation->set_rules('pointBA', 'Point B.a ', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pointBB', 'Point B.b', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pointBC', 'Point B.c', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pointBD', 'Point B.d', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pointBE', 'Point B.e', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pointBF', 'Point B.f', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pointBG', 'Point B.g', 'trim|required|xss_clean');


		if ( ! $this->form_validation->run() )
		{               
			$errorMessage = form_error('pointBA').form_error('pointBB').form_error('pointBC').form_error('pointBD').
			form_error('pointBE').form_error('pointBF').form_error('pointBG');
			$messageData = ConstructMessageResponse($errorMessage , 'warning');
			echo $messageData;
			exit;
		}
		else
		{   
			$pointBA    = $this->input->post('pointBA', true);
			$pointBB    = $this->input->post('pointBB', true);
			$pointBC    = $this->input->post('pointBC', true);
			$pointBD    = $this->input->post('pointBD', true);
			$pointBE    = $this->input->post('pointBE', true);
			$pointBF    = $this->input->post('pointBF', true);
			$pointBG    = $this->input->post('pointBG', true);

			$data       =  array("point_ba"  => $pointBA,
				"point_bb"  => $pointBB,
				"point_bc"  => $pointBC,
				"point_bd"  => $pointBD,
				"point_be"  => $pointBE,
				"point_bf"  => $pointBF,
				"point_bg"  => $pointBG,
				"id_perusahaan" => $_SESSION['IDSekolah']);

			if($param->num_rows() == 0)
			{
				$this->db->insert("ref_narasi_calk", $data);
			}
			else
			{

				$this->db->where("id_perusahaan", $_SESSION['IDSekolah']);
				$this->db->update("ref_narasi_calk", $data);
			}
			$messageData = ConstructMessageResponse("Data telah berhasil disimpan" , 'success');
			echo $messageData;
		}

	}

	public function UpdateCALKPointD()
	{

		$param = $this->db->query("SELECT * FROM ref_narasi_calk WHERE ref_narasi_calk.id_perusahaan = '".$_SESSION['IDSekolah']."'");
		$this->form_validation->set_rules('pointD', 'Point D ', 'trim|required|xss_clean');  

		if ( ! $this->form_validation->run() )
		{               
			$errorMessage = form_error('pointD');
			$messageData = ConstructMessageResponse($errorMessage , 'warning');
			echo $messageData;
			exit;
		}
		else
		{   
			$pointD    = $this->input->post('pointD', true);

			$data      =  array("point_d"  => $pointD,
				"id_perusahaan" => $_SESSION['IDSekolah']);

			if($param->num_rows() == 0)
			{
				$this->db->insert("ref_narasi_calk", $data);
			}
			else
			{
				$this->db->where("id_perusahaan", $_SESSION['IDSekolah']);
				$this->db->update("ref_narasi_calk", $data);
			}
			$messageData = ConstructMessageResponse("Data telah berhasil disimpan" , 'success');
			echo $messageData;
		}

	}

	public function getperiode()
	{
		$periode = $this->input->post("periode");

		if($periode == "Bulan")
		{
			$html = "<select name='nilai' class='form-control'>";

			for($i = 1; $i <=12; $i++)
			{
				$html .= "<option value='".$i."'>".convertBulan($i)."</option>";
			}

			$html .= "</select>";
		}
		elseif($periode == "Triwulan")
		{
			$html = "<select name='nilai' class='form-control'>";

			for($i = 1; $i <=4; $i++)
			{
				$html .= "<option value='".$i."'>Triwulan ".$i."</option>";
			}

			$html .= "</select>";
		}
		elseif($periode == "Semester")
		{
			$html = "<select name='nilai' class='form-control'>";

			for($i = 1; $i <=2; $i++)
			{
				$html .= "<option value='".$i."'>Semester ".$i."</option>";
			}

			$html .= "</select>";
		}
		elseif($periode == "Tahun")
		{
			$html = "<select name='nilai' class='form-control'>";


			$html .= "<option value='".GetTahunPeriode()."'>".GetTahunPeriode()."</option>";


			$html .= "</select>";
		}

		$json['select'] = $html;
		$json['label'] = $periode;

		echo json_encode($json);
	}

	public function previewlaporanneraca()
	{
		$idperusahaan = $_SESSION['IDSekolah'];

		$data['periode'] = $this->input->post("periode");
		$data['nilai'] = $this->input->post("nilai");


		$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
			WHERE sys_perusahaan.id_perusahaan = '".$idperusahaan."' ");

		$content = $this->load->view("laporan/cetaklaporanneraca",$data, true);

		echo $content;
	}

	public function previewlaporanperubahanaset()
	{
		$idperusahaan = $_SESSION['IDSekolah'];

		$data['periode'] = $this->input->post("periode");
		$data['nilai'] = $this->input->post("nilai");


		$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
			WHERE sys_perusahaan.id_perusahaan = '".$idperusahaan."' ");

		$data['piutang'] = $this->db->query("SELECT 
			mst_akun.id_akun as idakun,
			mst_akun.nama_akun as namaakun, 
			CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeakun 
			FROM mst_akun 
			WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.1.3%'
			AND mst_akun.level = '4'");

		$data['asettetap'] = $this->db->query("SELECT 
			mst_akun.id_akun as idakun,
			mst_akun.nama_akun as namaakun, 
			CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeakun 
			FROM mst_akun 
			WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.2.1%'
			AND mst_akun.level = '4' and mst_akun.header = '0'");

		$content = $this->load->view("laporan/cetaklaporanperubahanaset",$data, true);

		echo $content;
	}

	public function neracasaldo()
	{	
		
		$content = $this->load->view("laporan/laporanneracasaldo","",true);

		echo $content;
	}

	public function previewlaporanneracasaldo($tipe=NULL, $periode=NULL, $nilai=NULL)
	{
		$this->load->model("laporan_model","ModelKodeAkun");

		if($tipe == NULL)
		{
			$data['periode'] = $this->input->post("periode");
			$data['nilai'] = $this->input->post("nilai");
			$data['tipe'] 	= $tipe;
		}

		$idperusahaan = $_SESSION['IDSekolah'];
		$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
			WHERE sys_perusahaan.id_perusahaan = '".$idperusahaan."' ");

		$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');

		$content = $this->load->view("laporan/cetaklaporanneracasaldo",$data,true);

		echo $content;
	}

	public function aruskas()
	{
			/*
			$kasbank = $this->db->query("SELECT GROUP_CONCAT(trx_jurnal.id_jurnal) as idjurnal FROM trx_jurnal
			LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
			left join mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
			WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.1.1%' 
			AND trx_jurnal.id_sumber_trans <> (SELECT ref_sumber_trans.id_sumber_trans FROM ref_sumber_trans WHERE ref_sumber_trans.kode = 'SA')");
			*/
			
			
			
			$content = $this->load->view("laporan/laporanaruskas", "",true);
			
			echo $content;
		}
		
		public function previewlaporanaruskas($tipe = NULL, $periode=NULL, $nilai=NULL)
		{
			if($tipe == NULL)
			{
				$data['periode'] = $this->input->post("periode");
				$data['nilai'] = $this->input->post("nilai");
			}
			
			$idperusahaan = $_SESSION['IDSekolah'];
			$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
				WHERE sys_perusahaan.id_perusahaan = '".$idperusahaan."' ");
			
			$data['kasbank'] = $this->db->query("SELECT GROUP_CONCAT(trx_jurnal.id_jurnal) as idjurnal FROM trx_jurnal
				LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
				left join mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
				WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.1.1%' 
				AND trx_jurnal.id_sumber_trans <> (SELECT ref_sumber_trans.id_sumber_trans FROM ref_sumber_trans WHERE ref_sumber_trans.kode = 'SA')
				");
			
			
			$content = $this->load->view("laporan/cetaklaporanaruskas", $data,true);
			
			echo $content;
		}

		function printbukasharian($tglAwal, $tglAkhir)
		{
			$tglAwal = date("Y-m-d", strtotime($tglAwal));
			$tglAkhir= date("Y-m-d", strtotime($tglAkhir));
			$werjual  	= "WHERE (ju.tanggal_penjualan BETWEEN '".$tglAwal."' AND '".$tglAkhir."' ) ";
			$werbeli  	= "WHERE (juu.tanggal BETWEEN '".$tglAwal."' AND '".$tglAkhir."' ) ";
			$kewerJual  = $this->db->query("SELECT * FROM (
				SELECT ju.id_penjualan AS id, ju.tanggal_penjualan AS tgl, ju.no_transaksi AS no_bukti, 
				ju.keterangan AS keterangan, SUM(judet.jumlah_item * judet.harga) AS debet, CONCAT(0) AS kredit 
				FROM trx_penjualan ju
				LEFT JOIN trx_penjualan_det judet ON ju.id_penjualan = judet.id_penjualan
				LEFT JOIN mst_item item ON judet.id_item = item.id_item
				".$werjual." GROUP BY judet.id_penjualan
				UNION 
				SELECT juu.id_pembelian AS id, juu.tanggal AS tgl, juu.nomor_transaksi AS no_bukti,
				juu.deskripsi AS keterangan, CONCAT(0) AS debet, SUM(juudet.jumlah * juudet.harga) AS kredit 
				FROM trx_pembelian_persediaan juu
				LEFT JOIN trx_pembelian_persediaan_det juudet ON juu.id_pembelian = juudet.id_pembelian
				LEFT JOIN mst_item item ON juudet.id_item = item.id_item
				".$werbeli." GROUP BY juudet.id_pembelian
				) aa ORDER BY aa.tgl");

			foreach($kewerJual->result_array() as $aa => $bb)
			{
				$bb['kredit'] 	= str_replace('.0000', '', $bb['kredit']);
				$bb['tgl'] 		= date("d-m-Y", strtotime($bb['tgl']));
				$data['datane'][] = $bb;
			}

			// echo print_r($data);

			$konten 	= $this->load->view('printbukasharian', $data, true);
			echo $konten;
		}


		/*YUDHA WAS HERE*/

		public function printkdakun()
		{
			$this->load->model("laporan_model","ModelKodeAkun");
			
			
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			$filename ="exportLaporankodeakun.xls";
			$contents = $this->load->view("laporan/cetaklaporankodeakun",$data);
			header('Content-type: application/ms-excel');
			header('Content-Disposition: attachment; filename='.$filename);
			echo $contents;

		}

			public function printkdakunpdf()
		{
			$data['perush'] = $this->db->query("SELECT * FROM sys_perusahaan WHERE id_perusahaan='".$_SESSION['IDSekolah']."'");
			$this->load->model("laporan_model","ModelKodeAkun");
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			$filename ="exportLaporankodeakunpdf.pdf";
			$contents = $this->load->view("laporan/cetaklaporankodeakunpdf",$data);
			echo $contents;
		}

	}

	/* End of file user.php */
