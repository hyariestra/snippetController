<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class setup extends MY_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('func_helper');
		setDatabase($_SESSION['Database']);

	}

	    // maste perusahaan
	public function masterperusahaan()
	{
		
		$data['setting'] = $this->db->get('setup_general');
		$data['perush'] = $this->db->get('sys_perusahaan');
		$data['jenis'] 	= $this->db->get('ref_jenis_usaha');
		$data['prov'] 	= $this->db->get('mst_propinsi');
		$data['kab'] 	= $this->db->query("SELECT * FROM mst_kabupaten ORDER BY nama_kabupaten ASC");
		$data['kabs'] 	= $this->db->query("SELECT * FROM mst_kabupaten LEFT JOIN sys_perusahaan
			ON sys_perusahaan.id_kabupaten = mst_kabupaten.id_kabupaten GROUP BY sys_perusahaan.`id_kabupaten` ORDER BY nama_kabupaten ASC");

		$content = $this->load->view('setup/master_perusahaan', $data, true);
		echo $content;
	}

	public function cobaperusahaan()
	{
		$data['coba']= $this->db->get('mst_test');
		$content = $this->load->view('setup/cobaperusahaan', $data, true);
		echo $content;
	}

	public function cobatest2()
	{
		$data['cobatest2'] = $this->db->get('mst_test2');
		$content = $this->load->view('setup/cobatest2', $data, true);
		echo $content;
	}

	public function cobatest3()
	{
		$data['cobatest3']= $this->db->get('mst_test3');
		$content = $this->load->view('setup/cobanext', $data, true);
		echo $content;
	}


	public function simpanperusahaan()
	{
			// s_idperush=1&s_nama=UD.+Syncore+Indonesia&s_alamat=Jl.+solo+km+9%2C7+sleman+yogyakarta&s_telp=-&s_pemilik=Niza+Wibyana+tito&s_jenis=1&s_deskripsi=Jasa+Konsultasi+akuntansi+dengan+menggunakan+software
			/*echo "<pre>".print_r($_POST)."</pre>";
			exit();*/
			
			$idP 	= $this->input->post('s_idperush');
			
			if (empty($_POST['gambar'])) {
				
				$idP 	= $this->input->post('s_idperush');
				$data['nama_perusahaan'] 	= $this->input->post('s_nama');
				$data['alamat_perusahaan'] 	= $this->input->post('s_alamat');
				$data['no_telp'] 			= $this->input->post('s_telp');
				$data['nama_pemilik'] 		= $this->input->post('s_pemilik');
				$data['id_jenis_usaha'] 	= $this->input->post('s_jenis');
				$data['id_kabupaten'] 		= $this->input->post('id_kab');
				$data['id_propinsi'] 		= $this->input->post('id_prov');
				$data['deskripsi'] 			= $this->input->post('s_deskripsi');
				

				if($idP)
				{
					$this->db->where('id_perusahaan', $idP);
					$this->db->update('sys_perusahaan', $data);
					$jojon['theID'] 	= $idP;
				}else{
					$this->db->insert('sys_perusahaan', $data);
					$jojon['theID'] 	= $this->db->insert_id();
				}

				$getGambar = $this->db->query("SELECT gambar FROM sys_perusahaan WHERE id_perusahaan = '".$jojon['theID']."' ")->row();
				$jojon['gambar'] = $getGambar->gambar;
				$jojon['flag'] 	= true;

				echo json_encode($jojon);
			}else{
				$idP 	= $this->input->post('s_idperush');
				$data['nama_perusahaan'] 	= $this->input->post('s_nama');
				$data['alamat_perusahaan'] 	= $this->input->post('s_alamat');
				$data['no_telp'] 			= $this->input->post('s_telp');
				$data['nama_pemilik'] 		= $this->input->post('s_pemilik');
				$data['id_jenis_usaha'] 	= $this->input->post('s_jenis');
				$data['id_kabupaten'] 		= $this->input->post('id_kab');
				$data['id_propinsi'] 		= $this->input->post('id_prov');
				$data['deskripsi'] 			= $this->input->post('s_deskripsi');
				$data['gambar'] 			= $this->input->post('gambar');

				if($idP)
				{
					$this->db->where('id_perusahaan', $idP);
					$this->db->update('sys_perusahaan', $data);
					$jojon['theID'] 	= $idP;
				}else{
					$this->db->insert('sys_perusahaan', $data);
					$jojon['theID'] 	= $this->db->insert_id();
				}

				$getGambar = $this->db->query("SELECT gambar FROM sys_perusahaan WHERE id_perusahaan = '".$jojon['theID']."' ")->row();
				$jojon['gambar'] = $getGambar->gambar;
				$jojon['flag'] 	= true;

				echo json_encode($jojon);
			}
		}
		
		// EOF maste perusahaan


		// Master Periode Transaksi
		public function periodetransaksi()
		{
			$data['period'] 	= $this->db->get('periode_transaksi');

			$content 	= $this->load->view('setup/master_periodetransaksi', $data, true);
			echo $content;
		}

		public function simpanperiodetransaksi()
		{
			$idP 	= $this->input->post('idperiode');
			$data['bulan'] 	= $this->input->post('bulan');
			$data['tahun'] 	= $this->input->post('tahun');

			if($idP)
			{
				$this->db->where('id_periode', $idP);
				$this->db->update('periode_transaksi', $data);
				$jojon['theID'] 	= $idP;
			}else{
				$this->db->insert('periode_transaksi', $data);
				$jojon['theID'] 	= $this->db->insert_id();
			}

			$jojon['flag'] 	= true;

			echo json_encode($jojon);
		}
		// EOF Master Peiode Transaksi


		// Master MataUang Start ~
		public function mastermatauang()
		{
			$data['matauang'] 	= $this->db->get('mst_mata_uang');
			$content 	= $this->load->view('master_matauang', $data, true);

			echo $content;
		}

		public function simpanmatauang()
		{
			$data['nama_mata_uang'] 	= $this->input->post('new_matauang');
			$data['negara'] 	= $this->input->post('new_negara');

			$this->db->insert("mst_mata_uang", $data);
			$data['newID'] 	= $this->db->insert_id();

			$data['flag'] 	= true;

			echo json_encode($data);
		}


		public function simpantest()
		{
			$data['nama'] = $this->input->post('new_nama');
			$data['ket'] = $this->input->post('new_ket');
			$this->db->insert("mst_test", $data);
			$data['newID'] = $this->db->insert_id();
			$data['flag'] = true;
			echo json_encode($data);
		}

		public function simpantest2()
		{
			$data['nama'] = $this->input->post('new_nama');
			$data['alamat'] = $this->input->post('new_alamat');
			$data['ket'] = $this->input->post('new_ket');
			$this->db->insert("mst_test2", $data);
			$data['newID'] = $this->db->insert_id();
			$data['flag'] = true;
			echo json_encode($data);
		}



		public function simpanmatauangubah()
		{
			$idP 	= $this->input->post('edit_id_mata_uang');
			$data['nama_mata_uang'] 	= $this->input->post('edit_matauang');
			$data['negara'] 	= $this->input->post('edit_negara');

			$this->db->where('id_mata_uang', $idP);
			$this->db->update('mst_mata_uang', $data);

			$data['newID'] 	= $idP;
			$data['flag'] 	= true;

			echo json_encode($data);
		}

		public function hapusmatauang()
		{
			$idP = $this->input->post('id_mata_uang');

			$this->db->where('id_mata_uang', $idP);
			$this->db->delete('mst_mata_uang');

			$jojon['flag'] 	= true;
			echo json_encode($jojon);
		}
		// EOFMaster MataUang

		public function hapusdatatest()
		{
			$idP = $this->input->post('id_mata_uang');
			
			$this->db->where('id', $idP);
			$this->db->delete('mst_test');
			
			$jojon['flag']=true;
			echo json_encode($jojon);
		}


		public function hapusdatatest2()
		{
			$idP = $this->input->post('id_pendaftar');

			$this->db->where('id', $idP);
			$this->db->delete('mst_test2');

			$jojon['flag']=true;
			echo json_encode($jojon);
		}

		// Master pelanggan START
		public function masterpelanggan()
		{
			$data['setting'] = $this->db->get('setup_general');
			$data['pelanggan'] 	= $this->db->get('mst_pelanggan');
			$data['unit'] 	= $this->db->query("SELECT * FROM mst_departemen");

			$content 	= $this->load->view('setup/master_pelanggan', $data, true);
			echo $content;
		}

		public function simpanpelanggan()
		{
			$data['nama_pelanggan'] 	= $this->input->post('new_nama');
			$data['alamat_pelanggan'] 	= $this->input->post('new_alamat');
			$data['no_telp_1'] 	= $this->input->post('new_telp');
			$data['no_telp_2'] 	= $this->input->post('new_telp_2');
			$data['email'] 	= $this->input->post('new_email');
			$data['id_unit'] 	= $this->input->post('new_unit');

			$this->db->insert('mst_pelanggan', $data);
			$idkontak = $this->db->insert_id();
			
			$data['newID'] 	= $this->db->insert_id();
			$data['flag'] 	= true;
			
			echo json_encode($data);
			
			/*
			$getkodeakun = $this->db->query("SELECT * FROM mst_akun 
			WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.1.3%'
			and mst_akun.level = 4");
			
			$kodeakun = $getkodeakun->num_rows() + 1;
			$kodeakun = str_pad($kodeakun, 2, "0",STR_PAD_LEFT);
			
			$akun['nama_akun'] =  $this->input->post('new_nama');
			$akun['kode_akun'] = $kodeakun;
			$akun['kode_induk'] = $getkodeakun->first_row()->kode_induk;
			$akun['id_induk'] = $getkodeakun->first_row()->id_induk;
			$akun['header'] = 0;
			$akun['saldo_normal'] = "D";
			$akun['level'] = 4;
			
			$this->db->insert("mst_akun", $akun);
			$idakun = $this->db->insert_id();

		
			
			$ref['id_kontak'] = $idkontak;
			$ref['id_akun'] = $idakun;
			$ref['tipe'] = "bkm";
			
			$this->db->insert("ref_link_piutang", $ref);
			*/


		}

		public function simpanpelangganubah()
		{
			$idP 	= $this->input->post('edit_id_pelanggan');
			$data['nama_pelanggan'] 	= $this->input->post('edit_nama');
			$data['alamat_pelanggan'] 	= $this->input->post('edit_alamat');
			$data['no_telp_1'] 	= $this->input->post('edit_telp');
			$data['no_telp_2'] 	= $this->input->post('edit_telp_2');
			$data['email'] 	= $this->input->post('edit_email');	
			$data['id_unit'] 	= $this->input->post('edit_unit');	

			$this->db->where('id_pelanggan', $idP);
			$this->db->update('mst_pelanggan', $data);
			
			//$idakun = $this->db->query("SELECT * FROM ref_link_piutang 
			//WHERE ref_link_piutang.id_kontak = '".$idP."'")->first_row()->id_akun;
			
			//$this->db->where("id_akun", $idakun);
			//$this->db->where("mst_akun", array("nama_akun" => $this->input->post('edit_nama')));

			$data['newID'] 	= $idP;
			$data['flag'] 	= true;

			echo json_encode($data);
		}

		public function simpantestubah()
		{
			$id 	= $this->input->post('edit_id_pemasok');
			$data['nama'] 	= $this->input->post('edit_nama');
			$data['ket'] 	= $this->input->post('edit_ket');
			
			$this->db->where('id', $id);
			$this->db->update('mst_test', $data);
			
			//$idakun = $this->db->query("SELECT * FROM ref_link_piutang 
			//WHERE ref_link_piutang.id_kontak = '".$idP."'")->first_row()->id_akun;
			
			//$this->db->where("id_akun", $idakun);
			//$this->db->where("mst_akun", array("nama_akun" => $this->input->post('edit_nama')));

			$data['newID'] 	= $id;
			$data['flag'] 	= true;

			echo json_encode($data);

		}

		public function simpantestubah2()
		{
			$id = $this->input->post('edit_id_pendaftar');
			$data['nama'] = $this->input->post('edit_nama');
			$data['alamat'] = $this->input->post('edit_alamat');
			$data['ket'] = $this->input->post('edit_ket');

			$this->db->where('id', $id);
			$this->db->update('mst_test2', $data);

			$data['newID'] = $id;
			$data['flag'] = true;

			echo json_encode($data);
		}


		public function hapuspelanggan()
		{
			$idP = $this->input->post('id_pelanggan');

			$this->db->where('id_pelanggan', $idP);
			$this->db->delete('mst_pelanggan');

			$jojon['flag'] 	= true;
			echo json_encode($jojon);
		}
		// Master pelanggan ENDS


		// Master Pemasok START
		public function masterpemasok()
		{
			$data['setting'] 	= $this->db->get('setup_general');
			$data['pemasok'] 	= $this->db->get('mst_pemasok');
			$data['unit'] 	= $this->db->query("SELECT * FROM mst_departemen");

			$content 	= $this->load->view('setup/master_pemasok', $data, true);
			echo $content;
		}

		public function simpanpemasok()
		{
			$data['nama_pemasok'] 	= $this->input->post('new_nama');
			$data['alamat_pemasok'] 	= $this->input->post('new_alamat');
			$data['no_telp_1'] 	= $this->input->post('new_telp');
			$data['no_telp_2'] 	= $this->input->post('new_telp_2');
			$data['email'] 	= $this->input->post('new_email');
			$data['id_unit'] 	= $this->input->post('new_unit');

			$this->db->insert('mst_pemasok', $data);
			$idkontak = $this->db->insert_id();
			$data['newID'] 	= $this->db->insert_id();
			$data['flag'] 	= true;



			echo json_encode($data);
			
			
			/*
			
			$getkodeakun = $this->db->query("SELECT * FROM mst_akun 
			WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2.1.1%'
			and mst_akun.level = 4");
			
			$kodeakun = $getkodeakun->num_rows() + 1;
			$kodeakun = str_pad($kodeakun, 2, "0",STR_PAD_LEFT);
			
			$akun['nama_akun'] =  $this->input->post('new_nama');
			$akun['kode_akun'] = $kodeakun;
			$akun['kode_induk'] = $getkodeakun->first_row()->kode_induk;
			$akun['id_induk'] = $getkodeakun->first_row()->id_induk;
			$akun['header'] = 0;
			$akun['saldo_normal'] = "K";
			$akun['level'] = 4;
			
			$this->db->insert("mst_akun", $akun);
			$idakun = $this->db->insert_id();

			
			
			$ref['id_kontak'] = $idkontak;
			$ref['id_akun'] = $idakun;
			$ref['tipe'] = "bkk";
			
			$this->db->insert("ref_link_piutang", $ref);

			echo json_encode($data);
			*/

		}

		public function simpanpemasokubah()
		{
			$idP 	= $this->input->post('edit_id_pemasok');
			$data['nama_pemasok'] 	= $this->input->post('edit_nama');
			$data['alamat_pemasok'] 	= $this->input->post('edit_alamat');
			$data['no_telp_1'] 	= $this->input->post('edit_telp');
			$data['no_telp_2'] 	= $this->input->post('edit_telp_2');
			$data['email'] 	= $this->input->post('edit_email');
			$data['id_unit'] 	= $this->input->post('edit_unit');			

			$this->db->where('id_pemasok', $idP);
			$this->db->update('mst_pemasok', $data);
			
			//$idakun = $this->db->query("SELECT * FROM ref_link_piutang 
			//WHERE ref_link_piutang.id_kontak = '".$idP."'")->first_row()->id_akun;
			
			//$this->db->where("id_akun", $idakun);
			//$this->db->where("mst_akun", array("nama_akun" => $this->input->post('edit_nama')));

			$data['newID'] 	= $idP;
			$data['flag'] 	= true;

			echo json_encode($data);
		}

		public function hapuspemasok()
		{
			$idP = $this->input->post('id_pemasok');

			$this->db->where('id_pemasok', $idP);
			$this->db->delete('mst_pemasok');

			$jojon['flag'] 	= true;
			echo json_encode($jojon);
		}
		// Master Pemasok ENDS


		// Master Ktegori Item START
		public function masterkategoriitem()
		{
			$data['kat'] 	= $this->db->get('mst_kategori_item');

			$content 	= $this->load->view('setup/master_kategoriitem', $data, true);
			echo $content;
		}

		public function simpankategori()
		{
			$data['nama_kategori'] 	= $this->input->post('new_nama');
			$data['deskripsi']  	= $this->input->post('new_deskripsi');

			$this->db->insert('mst_kategori_item', $data);
			$data['newID'] 	= $this->db->insert_id();
			$data['flag'] 	= true;

			echo json_encode($data);
		}

		public function simpankategoriubah()
		{
			$IDP 	= $this->input->post('edit_id_kategori');
			$data['nama_kategori'] 	= $this->input->post('edit_nama');
			$data['deskripsi']  	= $this->input->post('edit_deskripsi');

			$this->db->where('id_kategori_item', $IDP);
			$this->db->update('mst_kategori_item', $data);

			$data['newID'] 	= $IDP;
			$data['flag'] 	= true;

			echo json_encode($data);
		}

		public function hapuskategori()
		{
			$idP = $this->input->post('id_kategori');

			$this->db->where('id_kategori_item', $idP);
			$this->db->delete('mst_kategori_item');

			$jojon['flag'] 	= true;
			echo json_encode($jojon);
		}
		// Master Ktegori Item ENDS


		// Master Item START
		public function masteritem()
		{
			$data['kat']	= $this->db->get('mst_kategori_item');
			$data['tipe'] 	= $this->db->get('ref_tipe_item');

			$content 	= $this->load->view('setup/master_item', $data, true);
			echo $content;
		}

		public function simpanitem()
		{
			$data['id_ref_tipe_item'] 	= $this->input->post('new_tipe');
			$data['id_kategori_item'] 	= $this->input->post('new_kategori');
			$data['nama_item'] 	= $this->input->post('new_nama');
			$data['satuan'] 	= $this->input->post('new_satuan');
			$data['id_akun'] 	= $this->input->post('new_idakun');
			$data['harga_beli'] 	= str_replace(",","",$this->input->post('new_hargabeli'));
			$data['harga_jual'] 	= str_replace(",","",$this->input->post('new_hargajual'));

			$this->db->insert('mst_item', $data);
			$data['newID'] 	= $this->db->insert_id();
			$data['flag'] 	= true;

			$tipekat 	= $this->db->query("SELECT tipe.tipe_item, kate.nama_kategori 
				FROM mst_item item
				LEFT JOIN ref_tipe_item tipe ON tipe.id_ref_tipe_item = item.id_ref_tipe_item
				LEFT JOIN mst_kategori_item kate ON kate.id_kategori_item = item.id_kategori_item
				WHERE item.id_item = ".$data['newID']." ")->row();
			$data['tipe_item'] 	= 	$tipekat->tipe_item;
			$data['kategori'] 	= 	$tipekat->nama_kategori;

			echo json_encode($data);
		}

		public function simpanitemubah()
		{
			$idP 	= $this->input->post('edit_iditem');
			$data['id_ref_tipe_item'] 	= $this->input->post('edit_tipe');
			$data['id_kategori_item'] 	= $this->input->post('edit_kategori');
			$data['nama_item'] 	= $this->input->post('edit_nama');
			$data['satuan'] 	= $this->input->post('edit_satuan');
			$data['harga_beli'] 	= str_replace(",","",$this->input->post('edit_hargabeli'));
			$data['harga_jual'] 	= str_replace(",","",$this->input->post('edit_hargajual'));
			$data['id_akun'] 	= $this->input->post('edit_idakun');

			$this->db->where('id_item', $idP);
			$this->db->update('mst_item', $data);

			$tipekat 	= $this->db->query("SELECT tipe.tipe_item, kate.nama_kategori 
				FROM mst_item item
				LEFT JOIN ref_tipe_item tipe ON tipe.id_ref_tipe_item = item.id_ref_tipe_item
				LEFT JOIN mst_kategori_item kate ON kate.id_kategori_item = item.id_kategori_item
				WHERE item.id_item = ".$idP." ")->row();
			$data['tipe_item'] 	= $tipekat->tipe_item;
			$data['kategori'] 	= $tipekat->nama_kategori;
			$data['newID'] 		= $idP;
			$data['flag'] 		= true;

			echo json_encode($data);
		}

		public function hapusitem()
		{
			$idP 	= $this->input->post('id_item');

			$this->db->where('id_item', $idP);
			$this->db->delete('mst_item');

			$data['flag'] 	= true;
			echo json_encode($data);
		}
		// Master Item ENDS


		
		
		public function masterbank_()
		{
			$data['bank'] 	= $this->db->query("SELECT bank.*, CONCAT(mata.nama_mata_uang,' (',mata.negara,')') as nama_mata_uang,
				rek.nama_jenis_rekening FROM mst_bank bank
				LEFT JOIN mst_mata_uang mata ON mata.id_mata_uang = bank.id_mata_uang
				LEFT JOIN ref_jenis_rekening rek ON rek.id_ref_jenis_rekening = bank.id_jenis_rek");

			$data['rek'] 	= $this->db->get('ref_jenis_rekening');
			$data['mata'] 	= $this->db->get('mst_mata_uang');

			$content 	= $this->load->view('setup/master_bank', $data, true);
			echo $content;
		}

		public function simpanbank()
		{
			$data['id_mata_uang'] 	= $this->input->post('new_matauang');
			$data['id_jenis_rek'] 	= $this->input->post('new_jenis');
			$data['nama_bank'] 		= $this->input->post('new_namabank');
			$data['no_rekening'] 	= $this->input->post('new_norekening');

			$this->db->insert('mst_bank', $data);
			$data['newID'] 	= $this->db->insert_id();
			$data['flag'] 	= true;

			echo json_encode($data);
		}

		public function simpanbankubah()
		{
			$IDP 	= $this->input->post('edit_idbank');
			$data['id_mata_uang'] 	= $this->input->post('edit_matauang');
			$data['id_jenis_rek'] 	= $this->input->post('edit_jenis');
			$data['nama_bank'] 		= $this->input->post('edit_namabank');
			$data['no_rekening'] 	= $this->input->post('edit_norekening');

			$this->db->where('id_bank', $IDP);
			$this->db->update('mst_bank', $data);
			$data['newID'] 	= $IDP;
			$data['flag'] 	= true;

			echo json_encode($data);

		}

		public function hapusbank()
		{
			$idP = $this->input->post('id_bank');

			$this->db->where('id_bank', $idP);
			$this->db->delete('mst_bank');

			$jojon['flag'] 	= true;
			echo json_encode($jojon);
		}
		
		public function uploadFileMulti()
		{


			if(isset($_FILES[0]['error']))
			{
				$file = $_FILES[0]['name'];

				$ext = explode(".", $file);


				if(in_array($ext[1], array("jpg","png")))
				{
					$fileUpload = $_FILES[0]['name'];
					$tmpFile = $_FILES[0]['tmp_name'];
					$uploadDir = "upload/";

					move_uploaded_file($tmpFile, $uploadDir.$fileUpload);

					$flag = true;

				}
				else
				{
					$flag = false;
				}
			}
			else
			{
				$flag = false;
			}

			echo $flag;

		}
		// Master Bank ENDS
		
		function getlinkkodeakun()
		{
			$idtipe = $this->input->post("idtipe");
			$idakun = ($idtipe == 1) ? "5" : "4";
			
			$kodeakun = $this->db->query("SELECT * FROM mst_akun WHERE mst_akun.kode_akun LIKE '".$idakun."%'
				AND mst_akun.level = '1' AND mst_akun.header = '1'");
			
			echo $kodeakun->first_row()->id_akun;
		}
		
		function finishstep()
		{
			$iduser = $this->input->post("iduser");
			
			
			$this->db->where("id_user", $iduser);
			$this->db->update("sys_user", array("first_login" => 1));
		}
		
		function setunit($idunit)
		{
			$_SESSION['IDUnit'] = $idunit;
			
			redirect("main");
		}
		
		function getkabupaten()
		{
			$idprop = $this->input->post("idprov");
			$kodeprop = $this->input->post("kodeprov");
			
			$getkab = $this->db->query("SELECT * FROM mst_kabupaten WHERE mst_kabupaten.kode_propinsi ='".$kodeprop."'");
			
			
			foreach($getkab->result() as $row)
			{
				$json['html'][] = "<option value='".$row->id_kabupaten."#".$row->kode_kabupaten."'>".$row->nama_kabupaten."</option>";
			}
			
			echo json_encode($json);
		}
		
		public function settingGeneral()
		{
			$data['general'] = $this->db->query("SELECT * FROM setup_general");
			
			$content = $this->load->view("setting_general", $data, true);
			
			echo $content;
		}
		
		public function simpansetupgeneral()
		{
			$idS = $this->input->post("s_idsetting");
			$data['label_lembaga'] = $this->input->post("s_lembaga");
			$data['label_pemasok'] = $this->input->post("s_pemasok");
			$data['label_pelanggan'] = $this->input->post("s_pelanggan");
			$data['label_aplikasi'] = $this->input->post("s_aplikasi");
			$data['label_laporan'] = $this->input->post("s_laporan");
			$data['label_header'] = $this->input->post("s_header");
			$data['label_perubahanaset'] = $this->input->post("s_lpe");
			$data['label_tambahan'] = $this->input->post("s_tambahan");
			

			if($idS)
			{
				$this->db->where('id_setup_general', $idS);
				$this->db->update('setup_general', $data);
				$jojon['theID'] 	= $idS;
			}else{
				$this->db->update('setup_general', $data);
				$jojon['theID'] 	= $idS;
			}

			$json['flag'] = true;

			echo json_encode($json);
		}

		function generatehash()
		{
			$md5 = md5("demo");
			
			echo $md5;
		}

		public function account_setting()
	{
		
		$dbselect ="bumdes_kunci";
		$database="db_".$dbselect;
		setDatabase($database);

		$data['setting'] = $this->db->get('sys_user');

		$content = $this->load->view('setup/account_setting', $data, true);
		echo $content;
	}


		public function update_data_user()
		{
	
			$IDP 	= $this->input->post('IDP');
			$namauser = $this->input->post('nama_user');

			if($namauser == "")
		{ 
			$json['notif']='<div class="alert alert-warning alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong>Nama user tidak boleh kosong</strong>
			</div>';
		}

		else{ 

			$data['nama_user'] 	= $this->input->post('nama_user');
			$data['nama_lengkap'] = $this->input->post('nama_lengkap');
			$data['telp'] 	= $this->input->post('telp');
			$data['email'] 		= $this->input->post('email');

			$this->db->where('id_user', $IDP);
			$this->db->update('sys_user', $data);
			$data['newID'] 	= $IDP;
			$data['flag'] 	= true;

			$json['notif']='<div class="alert alert-success alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong>Data sudah berhasil diupdate</strong>
			</div>';

			}
			echo json_encode($json);


		}

		public function ganti_password_user()
		{
	
			$IDP 	= $this->input->post('IDP');
			$passwordx = $this->input->post("passwordx");
			$passwordy = $this->input->post("passwordy");

			if($passwordx == "" && $passwordy=="" )
		{ 
			$json['notif']='<div class="alert alert-warning alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong>Password tidak boleh kosong</strong>
			</div>';
		} 
		
			elseif ($passwordx == $passwordy) 
		{
			$data['password']  = md5($this->input->post("passwordx"));
	
			$this->db->where('id_user', $IDP);
			$this->db->update('sys_user', $data);
			$data['newID'] 	= $IDP;
			$data['flag'] 	= true;

			$json['notif']='<div class="alert alert-success alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong>Password sudah berhasil diganti</strong>
			</div>';		
		}
		else
		{
			$json['notif']='<div class="alert alert-warning alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong>Password harus sama</strong>
			</div>';	
		}

			

			echo json_encode($json);

		}

		
		

	}

	/* End of file user.php */
