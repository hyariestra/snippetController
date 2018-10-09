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
			WHERE sys_perusahaan.id_perusahaan = '".$idperusahaan."'");
			
			$content = $this->load->view("laporanperubahandana", $data, true);
			
			echo $content;
		}
		
		public function previewperubahandana()
		{
			echo "<pre>";print_r($_POST);"</pre>";
		}
	}

/* End of file user.php */
