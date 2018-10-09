<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

	class main extends MY_Controller 
	{
	   	
	    public function __construct() 
	    {
	        parent::__construct();
			setDatabase($_SESSION['Database']);
	        $this->load->helper('func_helper');
			
			$this->load->model('user_model', 'UserModel');
	    }
	         
		public function index()
		{	
			//echo "<pre>";print_r($_SESSION['IDUnit']);"</pre>";
			//exit();
			if(!isset($_SESSION['IDUser']))
			{
				echo "<script>window.location = '".base_url()."'</script>";
			}
			
			$wherepemasukan = ($_SESSION['IDUnit'] != 1 ) ? " AND trx_penjualan.id_unit = '".$_SESSION['IDUnit']."'" : "";
			
			$data['pemasukan'] = $this->db->query("SELECT *, COALESCE(SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item)) as total 
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			WHERE MONTH(trx_penjualan.tanggal_penjualan) = '".date("m")."' and YEAR(trx_penjualan.tanggal_penjualan) = '".date("Y")."'". $wherepemasukan);

			
			$wherepengeluaran = ($_SESSION['IDUnit'] != 1 ) ? " AND trx_pembelian_persediaan.id_unit = '".$_SESSION['IDUnit']."'" : "";
			$data['pengeluaran'] = $this->db->query("SELECT *, COALESCE(SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah)) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			WHERE MONTH(trx_pembelian_persediaan.tanggal) = '".date("m")."' and YEAR(trx_pembelian_persediaan.tanggal) = '".date("Y")."'".$wherepengeluaran);

			
			for($i = 1; $i <= 12; $i++)
			{
				$gColomPemasukan = $this->db->query("SELECT *, COALESCE(SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item)) as total 
				FROM trx_penjualan
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
				WHERE YEAR(trx_penjualan.tanggal_penjualan) = '".date("Y")."' AND MONTH(trx_penjualan.tanggal_penjualan) = '".$i."'
				$wherepemasukan
				GROUP BY MONTH(trx_penjualan.tanggal_penjualan)");
			
				$data['gColomPemasukan'][] = ($gColomPemasukan->num_rows() > 0) ? (int)$gColomPemasukan->first_row()->total : 0;
			
			
				$gColomPengeluaran = $this->db->query("SELECT *, COALESCE(SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah)) as total 
				FROM trx_pembelian_persediaan
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
				WHERE YEAR(trx_pembelian_persediaan.tanggal) = '".date("Y")."' AND MONTH(trx_pembelian_persediaan.tanggal) = '".$i."'
				$wherepengeluaran
				GROUP BY MONTH(trx_pembelian_persediaan.tanggal)");
				
				$data['gColomPengeluaran'][] = ($gColomPengeluaran->num_rows() > 0) ? (int)$gColomPengeluaran->first_row()->total : 0;
			}
			
			$gPieTotalPemasukan = $this->db->query("SELECT *, COALESCE(SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item)) as total 
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			WHERE YEAR(trx_penjualan.tanggal_penjualan) = '".date("Y")."'".$wherepemasukan);
		
			$data['gPieTotalPemasukan'] = ($gPieTotalPemasukan->num_rows() > 0) ? (int)$gPieTotalPemasukan->first_row()->total : 0;
			
			$gPieTotalPengeluaran = $this->db->query("SELECT *, COALESCE(SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah)) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			WHERE YEAR(trx_pembelian_persediaan.tanggal) = '".date("Y")."'".$wherepengeluaran);
		
			$data['gPieTotalPengeluaran'] = ($gPieTotalPengeluaran->num_rows() > 0) ? (int)$gPieTotalPengeluaran->first_row()->total : 0;
			
			if($_SESSION['IDUnit'] == 1)
			{
				$departemen = $this->db->query("SELECT * FROM mst_departemen");
				
				foreach($departemen->result() as $row)
				{
				
					$pemasukan = $this->db->query("SELECT COALESCE(SUM(trx_penjualan_det.jumlah_item * trx_penjualan_det.harga),0) as total 
										FROM trx_penjualan_det
										LEFT JOIN mst_item ON mst_item.id_item = trx_penjualan_det.id_item
										LEFT JOIN trx_penjualan ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan
										LEFT JOIN mst_departemen ON mst_departemen.id_departemen = trx_penjualan.id_unit
										GROUP BY trx_penjualan.id_unit");
										
					
										
					$json['name'] = $row->nama_departemen;
					$json['y'] = (!isset($pemasukan->first_row()->total)) ? 0 : (int)$pemasukan->first_row()->total;
					
					$data['gPiePemasukan'][] = $json;
					
					$pengeluaran = $this->db->query("SELECT SUM(trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga) as total 
										FROM trx_pembelian_persediaan_det
										LEFT JOIN mst_item ON mst_item.id_item = trx_pembelian_persediaan_det.id_item
										LEFT JOIN trx_pembelian_persediaan ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
										WHERE trx_pembelian_persediaan.id_unit = '".$row->id_departemen."' AND trx_pembelian_persediaan.id_unit <> 1
										GROUP BY mst_item.id_kategori_item");
										
					
										
					$json['name'] = $row->nama_departemen;
					$json['y'] = (!isset($pengeluaran->first_row()->total)) ? 0 : (int)$pengeluaran->first_row()->total;
					
					$data['gPiePengeluaran'][] = $json;
					
				}
				
				$data['setbagian'] = "Per/ unit";
			}
			else
			{
				$wherekat = ($_SESSION['IDUnit'] != 1) ? " WHERE mst_kategori_item.id_unit = '".$_SESSION['IDUnit']."'" : "";
				
				$kategoriItem = $this->db->query("SELECT * FROM mst_kategori_item
				$wherekat
				GROUP BY mst_kategori_item.id_kategori_item");
				
				foreach($kategoriItem->result() as $row)
				{
					
					$pemasukan = $this->db->query("SELECT COALESCE(SUM(trx_penjualan_det.jumlah_item * trx_penjualan_det.harga),0) as total 
										FROM trx_penjualan_det
										LEFT JOIN mst_item ON mst_item.id_item = trx_penjualan_det.id_item
										LEFT JOIN trx_penjualan ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan
										LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
										WHERE mst_item.id_kategori_item = '".$row->id_kategori_item."'
										$wherepemasukan
										GROUP BY mst_item.id_kategori_item");
										
					$json['name'] = $row->nama_kategori;
					$json['y'] = (!isset($pemasukan->first_row()->total)) ? 0 : (int)$pemasukan->first_row()->total;
					
					$data['gPiePemasukan'][] = $json;
										
					$pengeluaran = $this->db->query("SELECT SUM(trx_pembelian_persediaan_det.jumlah * trx_pembelian_persediaan_det.harga) as total 
										FROM trx_pembelian_persediaan_det
										LEFT JOIN mst_item ON mst_item.id_item = trx_pembelian_persediaan_det.id_item
										LEFT JOIN trx_pembelian_persediaan ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
										LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
										WHERE mst_item.id_kategori_item = '".$row->id_kategori_item."'
										$wherepengeluaran
										GROUP BY mst_item.id_kategori_item");
								
										
					$json['name'] = $row->nama_kategori;
					$json['y'] = (!isset($pengeluaran->first_row()->total)) ? 0 : (int)$pengeluaran->first_row()->total;
					
					$data['gPiePengeluaran'][] = $json;
				}
				
				$data['setbagian'] = "Kategori";
			}
			
			//echo "<pre>";print_r($json);"</pre>";
			//exit();
			
			$data['saldoawal'] = $this->db->query("SELECT SUM(mst_saldo_awal.nominal) as nominal FROM mst_saldo_awal WHERE mst_saldo_awal.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_induk) LIKE '1.1.1%')");
			$data['saldoakhir'] = ($data['saldoawal']->first_row()->nominal + $data['pemasukan']->first_row()->total) - $data['pengeluaran']->first_row()->total;
			$temp['content'] = $this->load->view("dashboard", $data, true);
			
			$temp['perush'] = $this->db->get('sys_perusahaan');
			$temp['jenis'] 	= $this->db->get('ref_jenis_usaha');
			$temp['prov'] 	= $this->db->get('mst_propinsi');
			$temp['kab'] 	= $this->db->query("SELECT * FROM mst_kabupaten ORDER BY nama_kabupaten ASC");
			$temp['kabs'] 	= $this->db->query("SELECT * FROM mst_kabupaten LEFT JOIN sys_perusahaan
				ON sys_perusahaan.id_kabupaten = mst_kabupaten.id_kabupaten GROUP BY sys_perusahaan.`id_kabupaten` ORDER BY nama_kabupaten ASC");
			
			$temp['period'] = $this->db->get('periode_transaksi');
			$temp['akun']	= $this->UserModel->treeDataKodeAkun($tipeAkun = 0);
			
			
			echo $this->load->view("template", $temp);
			
		}
		
		public function getmenuchild()
		{
			$kodemodul = $this->input->post("kodemodul");
			$induk = $this->input->post("induk");
			$idmodul = $this->input->post("idmodul");
			
			$content = $this->db->query("SELECT (SELECT CAST(REPLACE(kode_modul,'.','') AS UNSIGNED) ) AS kodeUrut, sys_modul.* 
			FROM sys_modul
			LEFT JOIN sys_group_modul ON sys_group_modul.id_modul = sys_modul.id_modul
			WHERE sys_modul.induk = '".$kodemodul."' AND sys_modul.induk_menu = '".$idmodul."' AND sys_modul.aktif = '1'
			AND sys_group_modul.id_sekolah = '".$_SESSION['IDSekolah']."' AND sys_group_modul.id_group = '".$_SESSION['IDGroup']."'
			ORDER BY kodeUrut ASC");
			
			$html['content'] = '<ul class="nav" id="menulevel'.@$content->first_row()->level_id.'" style="text-align:left;background:#429489; padding:0px 0px 10px 10px !important;">';
			
			if($content->num_rows() > 0)
			{
				foreach($content->result_array() as $menu)
				{
					if($menu['header'])
					{
						$class = "parent";
						$click = "";
					}
					else
					{
						$class = "child";
						$click = "";
					}
					
					$html['content'] .= '<li class="'.$class.'" onclick=openChild(this,"'.$menu['kode_modul'].'","'.$menu['induk'].'","'.$menu['id_modul'].'") ><a onclick="openPage(this)" data="'.$menu['link_modul'].'"  style="padding:0px !important; color:#555; font-size:12px; font-family:tahoma;">'.$menu['nama_modul'].'</a></li>';
				}
				
				$html['flag'] = true;
			}
			else
			{
				$html['flag'] = false;
			}
			
			$html['content'] .= '</ul>';
			
			echo json_encode($html);
		}
		

	}

/* End of file user.php */