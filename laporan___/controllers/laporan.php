<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

	class laporan extends MY_Controller 
	{
	   	
	    public function __construct() 
	    {
	        parent::__construct();
	        $this->load->helper('func_helper');
	    }
	     
	    public function bukukaspenerimaan()
		{
		
			$data['kaspenerimaan'] = $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),2) - COALESCE(SUM(trx_penjualan_det.potongan),2)) as total
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			GROUP BY trx_penjualan_det.id_penjualan");
			
			$content = $this->load->view("bukukaspenerimaan", $data, true);
			
			echo $content;
		}
		
		public function laporanpenjualan()
		{
		
			$data['penjualan'] = $this->db->query("SELECT trx_penjualan.* , 
			(COALESCE(SUM(trx_penjualan_det.harga),2) - COALESCE(SUM(trx_penjualan_det.potongan),2)) as total
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
			(COALESCE(SUM(trx_penjualan_det.harga),2) - COALESCE(SUM(trx_penjualan_det.potongan),2)) as total 
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
			(COALESCE(SUM(trx_pembelian_persediaan_det.harga),2) - COALESCE(SUM(trx_pembelian_persediaan_det.potongan),2)) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			GROUP BY trx_pembelian_persediaan_det.id_pembelian");
			
			$content = $this->load->view("bukukaspengeluaran", $data, true);
			
			echo $content;
		}
		
		public function laporanpembelianpersediaan()
		{
		
			$data['kaspengeluaran'] = $this->db->query("SELECT trx_pembelian_persediaan.* , 
			(COALESCE(SUM(trx_pembelian_persediaan_det.harga),2) - COALESCE(SUM(trx_pembelian_persediaan_det.potongan),2)) as total 
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
			(COALESCE(SUM(trx_pembelian_persediaan_det.harga),2) - COALESCE(SUM(trx_pembelian_persediaan_det.potongan),2)) as total 
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
			(COALESCE(SUM(trx_penjualan_det.harga),2) - COALESCE(SUM(trx_penjualan_det.potongan),2)) as total
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
			(COALESCE(SUM(trx_penjualan_det.harga),2) - COALESCE(SUM(trx_penjualan_det.potongan),2)) as total
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
			(COALESCE(SUM(trx_penjualan_det.harga),2) - COALESCE(SUM(trx_penjualan_det.potongan),2)) as total
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
			(COALESCE(SUM(trx_penjualan_det.harga),2) - COALESCE(SUM(trx_penjualan_det.potongan),2)) as total
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
		
		public function previewperubahandana()
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
			$idperusahaan = $_SESSION['IDSekolah'];
			$data['perusahaan'] = $this->db->query("SELECT * FROM sys_perusahaan 
			WHERE sys_perusahaan.id_perusahaan = '".$idperusahaan."' ");
			
			$content = $this->load->view("laporanneraca", $data, true);
			
			echo $content;
		}
	}

/* End of file user.php */
