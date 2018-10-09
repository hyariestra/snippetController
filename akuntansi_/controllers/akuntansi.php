<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

	class akuntansi extends MY_Controller 
	{
	   	
	    public function __construct() 
	    {
	        parent::__construct();
	        $this->load->helper('func_helper');
			setDatabase($_SESSION['Database']);
			$this->load->model("master_kode_akun_model", "ModelKodeAkun");
	    }
		
		public function daftarakun()
		{
			
			
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			
	        $content = $this->load->view('master_daftar_akun_view', $data, true);
			
			echo $content;

		}
	   
		
		public function settingkodeakun()
		{
			
			
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			
	        $content = $this->load->view('master_kode_akun_view', $data, true);
			
			echo $content;

		}
		
		public function settingkategoriakun()
		{
			
			
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			
	        $content = $this->load->view('master_kategori_akun_view', $data, true);
			
			echo $content;

		}
		
		public function settingitemakun()
		{
			
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			
	        $content = $this->load->view('master_item_akun_view', $data, true);
			
			echo $content;

		}
		
		// Master Bank START
		public function masterbank()
		{
			$data['rekening'] = $this->db->query("SELECT * FROM ref_jenis_rekening");
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '1');
			
			$content 	= $this->load->view('master_bank_akun', $data, true);
			echo $content;
		}
		
		public function getdatabank()
		{
			$idx = $this->input->post("idx");
			
			$bank = $this->db->query("SELECT * FROM mst_bank WHERE mst_bank.id_akunbank = '".$idx."'");
			
			$banks['id_bank'] = $bank->first_row()->id_bank;
			$banks['norek'] = $bank->first_row()->no_rekening;
			$banks['jenisrek'] = $bank->first_row()->id_jenis_rek;
			$banks['namabank'] = $bank->first_row()->nama_bank;
			
			echo json_encode($banks);
		}
		
		// Master Aset START
		public function settingaset()
		{
			
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '1');
			
			$content 	= $this->load->view('master_aset_akun', $data, true);
			echo $content;
		}
		
		
		public function settingsaldoawal()
		{
			$data['aset'] = $this->db->query("SELECT SUM(mst_saldo_awal.nominal) as total FROM mst_saldo_awal
			LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
			WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1%'");
			
			$data['kewajiban'] = $this->db->query("SELECT SUM(mst_saldo_awal.nominal) as total 
			FROM mst_saldo_awal
			LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
			WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2%'");
			
			$data['ekuitass'] = $this->db->query("SELECT SUM(mst_saldo_awal.nominal) as total 
			FROM mst_saldo_awal
			LEFT JOIN ref_akunlink ON ref_akunlink.id_akun = mst_saldo_awal.id_akun
			WHERE ref_akunlink.nama_akunlink = 'Surplus/Defisit Periode Lalu'");
			
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			
			$content = $this->load->view('master_saldo_awal', $data, true);
			
			echo $content;
		}
		
		public function simpansaldoawal()
		{	
			
			$this->db->truncate('mst_saldo_awal');
			
			$idakun = $this->input->post("id_akun");
			$saldoawal = $this->input->post("saldoawal");
			$totalsaldoawal = $this->input->post("totalsaldoawal");
			
			
			foreach($idakun as $keyAkun => $valAkun)
			{
				foreach($saldoawal as $keySaldo => $valSaldo)
				{
					if($keyAkun == $keySaldo)
					{
					
						$data['id_akun'] = $valAkun;
						$nominal = str_replace(",","",$valSaldo);
						
					
						
						if(substr($nominal, 0, 1) == "(")
						{
							$nominal = str_replace("(","",$nominal);
							$nominal = str_replace(")","",$nominal);
							$nominal = $nominal * -1;
						}
						else
						{
							$nominal = $nominal;
						}
						
						
						$data['nominal'] = $nominal;
						
						
						$this->db->insert("mst_saldo_awal", $data);
					}
				}
			}
			
			$SA = $this->db->query("SELECT * FROM ref_akunlink WHERE ref_akunlink.nama_akunlink = 'Saldo Awal'");
			
			$this->db->where("id_akun", $SA->first_row()->id_akun);
			$this->db->update("mst_saldo_awal", array("nominal" => $totalsaldoawal));
			
			
			
		}
		
		public function settinglinkakun()
		{
			$data['akun'] = $this->db->query("SELECT * FROM mst_akun WHERE mst_akun.kode_induk LIKE '3%'");
		
			$data['linkakun'] = $this->db->query("SELECT * FROM ref_akunlink
			LEFT JOIN mst_akun ON mst_akun.id_akun = ref_akunlink.id_akun");
			
			$content = $this->load->view("master_link_akun", $data, true);
			
			echo $content;
		}
		
		public function simpandatalinkakun()
		{
			$idrefakun = $this->input->post("id_ref_akun");
			$idakun = $this->input->post("id_akun");
			
			foreach($idrefakun as $keyref => $ref)
			{
				foreach($idakun as $keyakun => $akun)
				{
				
					if($keyref == $keyakun)
					{
						$this->db->where("id_akunlink", $ref);
						$this->db->update("ref_akunlink", array("id_akun" => $akun ));
					}
				}
			}
		}
		
		public function jurnalumum()
		{
		
			$data['ju'] = $this->db->query("SELECT *, 
			trx_ju.uraian as uraians, 
			SUM(trx_judet.debet) as total 
			FROM trx_ju
			LEFT JOIN trx_judet ON trx_judet.id_ju = trx_ju.id_ju
			WHERE trx_ju.id_sumber_trans = 1
			GROUP BY trx_judet.id_ju");
			
			$content = $this->load->view("jurnalumum", $data, true);
			
			echo $content;
		}
		
		public function tambahdata_jurnalumum()
		{
		
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			$data['kontak'] = $this->db->query("SELECT nama_pemasok as nama_kontak ,id_pemasok as idkontak, CONCAT(1) AS tipe_kontak, CONCAT('Pemasok') as nama_tipe FROM mst_pemasok
			union all
			SELECT  nama_pelanggan as nama_kontak , id_pelanggan as idkontak, CONCAT(2) AS tipe_kontak, CONCAT('Pelanggan') as nama_tipe FROM mst_pelanggan");
			
			$content = $this->load->view("tambahdata_jurnalumum", $data, true);
			
			echo $content;
		}
		
		public function carikodeakun()
		{
			$param = $this->input->post("akun");
			
			$dataakun = $this->ModelKodeAkun->CariDaftarKodeAkun($tipeAkun = '0', $param);
			
			echo "<pre>";print_r(json_decode($dataakun,true));"</pre>";
		}
		
		public function generateNumber()
		{
			$number = $this->db->query("SELECT * FROM trx_ju
			WHERE trx_ju.id_sumber_trans = 1
			ORDER BY trx_ju.nomor DESC");
			
			if($number->num_rows() == 0)
			{
				$number = "JU-0001";
			}
			else
			{
				$number = $number->first_row()->nomor;
				$number = explode("-", $number);
				$pattern = $number[0];
				$number = (int)$number[1];
				$number = $number + 1;
				$number = STR_PAD($number,4,"0", STR_PAD_LEFT);
				$number = $pattern."-".$number;
			}
			
			echo $number;
		}
		
		public function generateNumberBukti()
		{
			$number = $this->db->query("SELECT * FROM trx_ju
			WHERE trx_ju.id_sumber_trans = 1
			ORDER BY trx_ju.no_bukti DESC");
			$bulan = date("m");
			
			if($number->num_rows() == 0)
			{
				$number = "trx.jurnalumum/".convertBulanToRomawi($bulan)."/".date("Y")."/0001";
			}
			else
			{
				$number = $number->first_row()->no_bukti;
				$number = strrev($number);
				$number = explode("/",$number);
				$number = (int)$number[0];
				$number = strrev($number) + 1;
				$number = STR_PAD($number,4,"0", STR_PAD_LEFT);
				$number = "trx.jurnalumum/".convertBulanToRomawi($bulan)."/".date("Y")."/".$number;
			}
			
			echo $number;
		}
		
		public function simpandatajurnal()
		{
			
			parse_str($this->input->post("serialize"), $a);
			
			$ju['nomor'] = $this->input->post("nomor");
			$ju['uraian'] = $this->input->post("uraian");
			$ju['no_bukti'] = $this->input->post("nobukti");
			$ju['id_sumber_trans'] = 1;
			$ju['tanggal'] = date("Y-m-d", strtotime($this->input->post("tanggal")));
			
			$this->db->insert("trx_ju", $ju);
			$idju = $this->db->insert_id();
			
			
			foreach($a['idakun'] as $keyakun => $valakun)
			{
				foreach($a['debet'] as $keydebet => $valdebet)
				{
					foreach($a['kredit'] as $keykredit => $valkredit)
					{
						foreach($a['memo'] as $keymemo => $valmemo)
						{
							if($keyakun == $keydebet)
							{
								if($keydebet == $keykredit)
								{
									if($keykredit == $keymemo)
									{
										$judet['id_ju'] = $idju;
										$judet['id_akun'] = $valakun;
										$judet['debet'] = str_replace(",","",$valdebet);
										$judet['kredit'] = str_replace(",","",$valkredit);
										$judet['memo'] = $valmemo;
										
										$this->db->insert("trx_judet", $judet);
									}
								}
							}
						}
					}
				}
			}
		}
		
		public function editdata_jurnalumum()
		{
			
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			$data['kontak'] = $this->db->query("SELECT nama_pemasok as nama_kontak ,
			id_pemasok as idkontak, CONCAT(1) AS tipe_kontak, 
			CONCAT('Pemasok') as nama_tipe 
			FROM mst_pemasok
			union all
			SELECT  nama_pelanggan as nama_kontak , id_pelanggan as idkontak, CONCAT(2) AS tipe_kontak, CONCAT('Pelanggan') as nama_tipe FROM mst_pelanggan");
			
			
			$idju = $this->input->post("IDx");
			
			$data['ju'] = $this->db->query("SELECT *, 
			trx_ju.uraian as uraians,
			CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeakun,
			mst_akun.nama_akun as namaakun,
			mst_akun.id_akun as idakun
			FROM trx_ju
			LEFT JOIN trx_judet ON trx_judet.id_ju = trx_ju.id_ju
			LEFT JOIN mst_akun ON mst_akun.id_akun = trx_judet.id_akun
			WHERE trx_ju.id_ju = '".$idju."'");
			
			$content = $this->load->view("editdata_jurnalumum", $data, true);
			
			echo $content;
		}
		
		public function updatedatajurnal()
		{
			
			$idx = $this->input->post("IDx");
			
			$this->db->where("id_ju", $idx);
			$this->db->delete("trx_ju");
			
			$this->db->where("id_ju", $idx);
			$this->db->delete("trx_judet");
			
			parse_str($this->input->post("serialize"), $a);
			
			$ju['nomor'] = $this->input->post("nomor");
			$ju['uraian'] = $this->input->post("uraian");
			$ju['no_bukti'] = $this->input->post("nobukti");
			$ju['id_sumber_trans'] = 1;
			$ju['tanggal'] = date("Y-m-d", strtotime($this->input->post("tanggal")));
			
			$this->db->insert("trx_ju", $ju);
			$idju = $this->db->insert_id();
			
			
			foreach($a['idakun'] as $keyakun => $valakun)
			{
				foreach($a['debet'] as $keydebet => $valdebet)
				{
					foreach($a['kredit'] as $keykredit => $valkredit)
					{
						foreach($a['memo'] as $keymemo => $valmemo)
						{
							if($keyakun == $keydebet)
							{
								if($keydebet == $keykredit)
								{
									if($keykredit == $keymemo)
									{
										$judet['id_ju'] = $idju;
										$judet['id_akun'] = $valakun;
										$judet['debet'] = str_replace(",","",$valdebet);
										$judet['kredit'] = str_replace(",","",$valkredit);
										$judet['memo'] = $valmemo;
										
										$this->db->insert("trx_judet", $judet);
									}
								}
							}
						}
					}
				}
			}
		}
		
		public function posting()
		{
			$content = $this->load->view("posting","",true);
			
			echo $content;
		}
		
		public function simpanjudul_()
		{

			$ind = get_kode_akun_($this->input->post('simpanIdakun'))->id_akun;

			$k_induk = get_kode_akun_($this->input->post('simpanIdakun'))->kode_induk;

			$level = get_kode_akun_($this->input->post('simpanIdakun'))->level;

			$k_induka = get_kode_akun_($this->input->post('simpanIdakun'))->kode_akun;

			$k_induka_ = get_kode_akun__($this->input->post('simpanIdakun'))->kode_akun;

			$jum_ = get_kode_akun_jum($this->input->post('simpanIdakun'));

			$indukkke = $level > 0 ? $k_induka_ : $k_induka;

			$jum_kod_akun = "1000000000".$indukkke + 1;

			$pus = str_replace("1000000000", "", $jum_kod_akun);

			$kod_aku = $jum_ == 0 ? ( $level > 1 ? '01': '1') : $pus;

			$data['id_induk'] 		= $ind;
			$data['kode_akun'] 		= $kod_aku;
			$data['level'] 			= $level + 1;
			$data['kode_induk'] 	= $level > 1 ? $k_induk.'.'.$k_induka : $k_induka;
			$data['nama_akun'] 		= $this->input->post('new_namajudul');

			$this->db->insert('mst_akun', $data);
			$data['newID'] 	= $this->db->insert_id();
			$data['flag'] 	= true;

			echo json_encode($data);
		}
		
		public function simpanjudul($param = "")
		{
		
			$idinduk = $this->input->post("simpanIdakun");
			
			$kodeinduk = $this->db->query("SELECT 
			CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeinduk, 
			mst_akun.level ,
			mst_akun.saldo_normal
			FROM mst_akun 
			WHERE mst_akun.id_akun = '".$idinduk."'");
			
			$kodeakun = $this->db->query("SELECT * FROM mst_akun 
			WHERE mst_akun.kode_induk = '".$kodeinduk->first_row()->kodeinduk."'
			ORDER BY mst_akun.kode_akun DESC");
			
			$kodeakun = $kodeakun->num_rows() + 1;
			$kodeakun = STR_PAD($kodeakun, 2, "0", STR_PAD_LEFT);
			
			
			$data['id_induk'] 		= $idinduk;
			$data['kode_induk']		= $kodeinduk->first_row()->kodeinduk;
			$data['level'] 			= $kodeinduk->first_row()->level + 1;
			$data['kode_akun']		= $kodeakun;
			$data['saldo_normal']	= $kodeinduk->first_row()->saldo_normal;
			$data['nama_akun'] 		= $this->input->post('new_namajudul');

			$this->db->insert('mst_akun', $data);
			$idakun = $this->db->insert_id();
			
			if($param == "kategori")
			{
				$kategori['nama_kategori'] = $this->input->post("new_namajudul"); 
				$kategori['id_akun'] = $idakun;
				
				$this->db->insert("mst_kategori_item", $kategori);
			}
			elseif($param == "item")
			{
				$akun = $this->db->query("SELECT concat(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeakun,
				mst_akun.saldo_normal
				FROM mst_akun 
				WHERE mst_akun.id_akun = '".$idinduk."'");
				
				$idkategori = $this->db->query("SELECT * FROM mst_kategori_item WHERE mst_kategori_item.id_akun = '".$idinduk."'");
				
				$idreftipeitem = (substr($akun->first_row()->kodeakun,0,1) == 4) ? 2 : 1;
				
				$item['id_ref_tipe_item'] = $idreftipeitem;
				$item['id_kategori_item'] = $idkategori->first_row()->id_kategori_item;
				$item['nama_item'] = $this->input->post('new_namajudul');
				$item['id_akunitem'] = $idakun;
				$item['satuan'] = $this->input->post('new_satuan');
				$item['harga_beli'] = str_replace(",","",$this->input->post('new_beli'));
				$item['harga_jual'] = str_replace(",","",$this->input->post('new_jual'));
				
				$this->db->insert("mst_item", $item);
			}
			elseif($param == "bank")
			{
				$bank['nama_bank'] = $this->input->post("new_namajudul");
				$bank['id_mata_uang'] = 1;
				$bank['id_jenis_rek'] = $this->input->post("new_jenisrek");
				$bank['no_rekening'] = $this->input->post("new_norekening");
				$bank['id_akun'] = $idinduk;
				$bank['id_akunbank'] = $idakun;
				
				$this->db->insert("mst_bank", $bank);
			}
			elseif($param == "akumulasi")
			{
				$asettetap = $this->db->query("SELECT 
				CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeinduk,
				mst_akun.id_akun as idinduk,
				mst_akun.level as level
				FROM mst_akun 
				WHERE mst_akun.id_akun = '".$idinduk."'");
					
				if($asettetap->first_row()->kodeinduk == '1.2.1')
				{
					$akmulpenyusutan = $this->db->query("SELECT 
					CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeinduk,
					mst_akun.id_akun as idinduk,
					mst_akun.level as level,
					mst_akun.saldo_normal
					FROM mst_akun 
					WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '1.2.99'");
					
					$getchildakmulpenyusutan = $this->db->query("SELECT 
					CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeinduk,
					mst_akun.id_akun as idinduk,
					mst_akun.level as level
					FROM mst_akun 
					WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.2.99%'
					AND mst_akun.level = 4");
					
					$kodeakun = $getchildakmulpenyusutan->num_rows() + 1;
					$kodeakun = STR_PAD($kodeakun, 2, "0", STR_PAD_LEFT);
					
					$akumulasi['id_induk'] 		= $akmulpenyusutan->first_row()->idinduk;
					$akumulasi['kode_induk']	= $akmulpenyusutan->first_row()->kodeinduk;
					$akumulasi['level'] 		= $akmulpenyusutan->first_row()->level + 1;
					$akumulasi['kode_akun']		= $kodeakun;
					$akumulasi['saldo_normal']	= $akmulpenyusutan->first_row()->saldo_normal;
					$akumulasi['nama_akun'] 	= "Akumulasi penyusutan ".$this->input->post('new_namajudul');

					$this->db->insert('mst_akun', $akumulasi);
				}
			}
			
			$json['flag'] = true;
			$json['nama_akun'] = $this->input->post('new_namajudul');
			
			echo json_encode($json);
		}
		
		public function simpanjudulubah($param = "")
		{
			$IDP 	= $this->input->post('edit_idjudul');
			$data['nama_akun'] 		= $this->input->post('edit_namajudul');

			$this->db->where('id_akun', $IDP);
			$this->db->update('mst_akun', $data);
			
			$data['newID'] 	= $IDP;
			$data['flag'] 	= true;
			
			if($param == "kategori")
			{
				$this->db->where("id_akun", $IDP);
				$this->db->update("mst_kategori_item", array("nama_kategori" => $this->input->post('edit_namajudul')));
			}
			elseif($param == "akun")
			{
				$edititem['nama_item'] = $this->input->post('edit_namajudul');
				$edititem['satuan'] = $this->input->post('edit_satuan');
				$edititem['harga_beli'] = str_replace(",","",$this->input->post('edit_beli'));
				$edititem['harga_jual'] = str_replace(",","",$this->input->post('edit_jual'));
				
				$this->db->where("id_akunitem", $IDP);
				$this->db->update("mst_item", $edititem);
			}
			elseif($param == "bank")
			{
				$bank['nama_bank'] = $this->input->post("edit_namajudul");
				$bank['id_mata_uang'] = 1;
				$bank['id_jenis_rek'] = $this->input->post("edit_jenisrek");
				$bank['no_rekening'] = $this->input->post("edit_norekening");
				
				$this->db->where("id_akunbank", $IDP);
				$this->db->update("mst_bank", $bank);
			}
			

			echo json_encode($data);

		}

		public function hapusjudul($param = "")
		{
			$idP = $this->input->post('id_judul');
			$cekJurnal = $this->db->query("SELECT * FROM trx_jurnal_det WHERE id_akun = '".$idP."'")->num_rows();

			if($cekJurnal > 0)
			{
				$jojon['flag'] 	= false;				
			}
			else
			{
				$this->db->where('id_akun', $idP);
				$this->db->delete('mst_akun');
				
				if($param == "kategori")
				{
					$this->db->where('id_akun', $idP);
					$this->db->delete('mst_kategori_item');
				}
				elseif($param == "akun")
				{
					$this->db->where('id_akunitem', $idP);
					$this->db->delete('mst_item');
				}
				elseif($param == "bank")
				{
					$this->db->where('id_akunbank', $idP);
					$this->db->delete('mst_bank');
				}
				elseif($param == "akumulasi")
				{
					$qry = $this->db->query("SELECT * FROM mst_akun WHERE mst_akun.id_akun = '".$idP."'");
					$kodeakun = $qry->first_row()->kode_akun;
					
					$qryakml = $this->db->query("SELECT * FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_skun.kode_akun) = '1.2.99.".$kodeakun."'");
					$idakun = $qryakml->first_row()->id_akun;
					
					
					$this->db->where("id_akun", $idakun);
					$this->db->delete("mst_akun");
				}
				
				$jojon['flag'] 	= true;
			}

			echo json_encode($jojon);
		}
		
		
		public function simpanposting()
		{
			
			$this->db->truncate('trx_jurnal');
			$this->db->truncate('trx_jurnal_det');
			
			// INSERT SALDO AWAL //
			
			$saldoawal = $this->db->query("SELECT * FROM mst_saldo_awal
			LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun");
			
			$sa['id_unit_kerja'] = $_SESSION['IDSekolah'];
			$sa['nomor'] = "SA001";
			$sa['no_bukti'] = "SA001";
			$sa['uraian'] = "Jurnal Umum Saldo Awal";
			$sa['id_sumber_trans'] = 6;
			$sa['tgl_jurnal'] = date("Y-m-d", strtotime("01-01-2017"));
			$sa['date_entry'] = date("Y-m-d H:i:s");
			
			$this->db->insert("trx_jurnal", $sa);
			$idjurnal = $this->db->insert_id();
			
			foreach($saldoawal->result() as $row)
			{
			
				if($row->saldo_normal == "D")
				{
					$debet = $row->nominal;
					$kredit = 0;
				}
				else
				{
					$kredit = $row->nominal;
					$debet = 0;
				}
				
				$saDet['id_jurnal'] = $idjurnal;
				$saDet['id_akun'] = $row->id_akun;
				$saDet['id_kategori_item'] = 0;
				$saDet['debet_awal'] = $debet;
				$saDet['kredit_awal'] = $kredit;
				$saDet['nilai_tukar'] = 1;
				$saDet['debet_akhir'] = $debet;
				$saDet['kredit_akhir'] = $kredit;
				$saDet['memo'] = "Saldo Awal";
				$saDet['id_sumber_dana'] = 0;
				
				$this->db->insert("trx_jurnal_det", $saDet);
				
			}
			
			$idSA = $this->db->query("SELECT * FROM trx_jurnal
			LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
			WHERE trx_jurnal_det.id_akun = (SELECT id_akun FROM ref_akunlink WHERE ref_akunlink.nama_akunlink = 'Saldo Awal')");
				
			$nominal = $this->db->query("SELECT 
			SUM(trx_jurnal_det.debet_akhir) as debet, 
			SUM(trx_jurnal_det.kredit_akhir) as kredit 
			FROM trx_jurnal
			LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
			WHERE trx_jurnal_det.id_akun <> (SELECT id_akun FROM ref_akunlink WHERE ref_akunlink.nama_akunlink = 'Saldo Awal')");
			
			$this->db->where("id_jurnal_det", $idSA->first_row()->id_jurnal_det);
			$this->db->update("trx_jurnal_det", array("debet_awal" => $nominal->first_row()->kredit, "kredit_awal" => $nominal->first_row()->debet, "debet_akhir" => $nominal->first_row()->kredit, "kredit_akhir" => $nominal->first_row()->debet));
			
			// INSERT PEMASUKAN //
			
			$trxPenjualan = $this->db->query("SELECT * FROM trx_penjualan
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_penjualan.id_bank
			LEFT JOIN mst_akun ON mst_akun.id_akun = mst_bank.id_akun");
			
			foreach($trxPenjualan->result() as $rowPenjualan)
			{
				$penjualan['id_unit_kerja'] = $_SESSION['IDSekolah'];
				$penjualan['nomor'] = $rowPenjualan->no_transaksi;
				$penjualan['no_bukti'] = $rowPenjualan->no_transaksi;
				$penjualan['uraian'] = $rowPenjualan->keterangan;
				$penjualan['id_sumber_trans'] = 4;
				$penjualan['tgl_jurnal'] = date("Y-m-d", strtotime($rowPenjualan->tanggal_penjualan));
				$penjualan['date_entry'] = date("Y-m-d H:i:s");
				
				$this->db->insert("trx_jurnal", $penjualan);
				$idjurnal = $this->db->insert_id();
				
				$trxPenjualanDet = $this->db->query("SELECT 
				*, 
				SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item) as total 
				FROM trx_penjualan_det
				LEFT JOIN mst_item ON mst_item.id_item = trx_penjualan_det.id_item
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
				WHERE trx_penjualan_det.id_penjualan = '".$rowPenjualan->id_penjualan."'
				GROUP BY trx_penjualan_det.id_penjualan_det");
				
				$BankTotal = 0;
				
				foreach($trxPenjualanDet->result() as $rowPenjualanDet)
				{
				
					$penjDet['id_jurnal'] = $idjurnal;
					$penjDet['id_akun'] = $rowPenjualanDet->id_akun;
					$penjDet['id_kategori_item'] = 0;
					$penjDet['debet_awal'] = 0;
					$penjDet['kredit_awal'] = $rowPenjualanDet->total;
					$penjDet['nilai_tukar'] = 1;
					$penjDet['debet_akhir'] = 0;
					$penjDet['kredit_akhir'] = $rowPenjualanDet->total;
					$penjDet['memo'] =  $rowPenjualanDet->memo;
					$penjDet['id_sumber_dana'] =  0;
					
					$this->db->insert("trx_jurnal_det", $penjDet);
					
					$BankTotal += $rowPenjualanDet->total;
				}
				
					$penjDetBank['id_jurnal'] = $idjurnal;
					$penjDetBank['id_akun'] = $rowPenjualan->id_akunbank;
					$penjDetBank['id_kategori_item'] = 0;
					$penjDetBank['debet_awal'] = $BankTotal;
					$penjDetBank['kredit_awal'] = 0;
					$penjDetBank['nilai_tukar'] = 1;
					$penjDetBank['debet_akhir'] = $BankTotal;
					$penjDetBank['kredit_akhir'] = 0;
					$penjDetBank['memo'] =  "Penerimaan Akun Kas/Bank";
					$penjDetBank['id_sumber_dana'] =  0;
					$this->db->insert("trx_jurnal_det", $penjDetBank);
			}
			
			
			// INSERT PEMBELIAN //
			
			$trxPembelian = $this->db->query("SELECT *, 
			trx_pembelian_persediaan.deskripsi as deskTPP 
			FROM trx_pembelian_persediaan
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_pembelian_persediaan.id_bank
			LEFT JOIN mst_akun ON mst_akun.id_akun = mst_bank.id_akun");
			
			foreach($trxPembelian->result() as $rowPembelian)
			{
				$pembelian['id_unit_kerja'] = $_SESSION['IDSekolah'];
				$pembelian['nomor'] = $rowPembelian->nomor_transaksi;
				$pembelian['no_bukti'] = $rowPembelian->nomor_transaksi;
				$pembelian['uraian'] = $rowPembelian->deskTPP;
				$pembelian['id_sumber_trans'] = 5;
				$pembelian['tgl_jurnal'] = date("Y-m-d", strtotime($rowPembelian->tanggal));
				$pembelian['date_entry'] = date("Y-m-d H:i:s");
				
				$this->db->insert("trx_jurnal", $pembelian);
				$idjurnal = $this->db->insert_id();
				
				$trxPembelianDet = $this->db->query("SELECT *, SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah) as total 
				FROM trx_pembelian_persediaan_det
				LEFT JOIN mst_item ON mst_item.id_item = trx_pembelian_persediaan_det.id_item
				LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
				WHERE trx_pembelian_persediaan_det.id_pembelian = '".$rowPembelian->id_pembelian."'
				GROUP BY trx_pembelian_persediaan_det.id_pembelian_det");
				
				$BankTotal = 0;
				
				foreach($trxPembelianDet->result() as $rowPembelianDet)
				{
				
					$pembDet['id_jurnal'] = $idjurnal;
					$pembDet['id_akun'] = $rowPembelianDet->id_akun;
					$pembDet['id_kategori_item'] = 0;
					$pembDet['debet_awal'] = $rowPembelianDet->total;
					$pembDet['kredit_awal'] = 0;
					$pembDet['nilai_tukar'] = 1;
					$pembDet['debet_akhir'] = $rowPembelianDet->total;
					$pembDet['kredit_akhir'] = 0;
					$pembDet['memo'] =  $rowPembelianDet->memo;
					$pembDet['id_sumber_dana'] =  0;
					
					$this->db->insert("trx_jurnal_det", $pembDet);
					
					$BankTotal += $rowPembelianDet->total;
				}
				
					$penjDetBank['id_jurnal'] = $idjurnal;
					$penjDetBank['id_akun'] = $rowPembelian->id_akunbank;
					$penjDetBank['id_kategori_item'] = 0;
					$penjDetBank['debet_awal'] = 0;
					$penjDetBank['kredit_awal'] = $BankTotal;
					$penjDetBank['nilai_tukar'] = 1;
					$penjDetBank['debet_akhir'] = 0;
					$penjDetBank['kredit_akhir'] = $BankTotal;
					$penjDetBank['memo'] =  "Pengeluaran Akun Kas/Bank";
					$penjDetBank['id_sumber_dana'] =  0;
					$this->db->insert("trx_jurnal_det", $penjDetBank);
			}
			
			
			// INSERT JU //
			$trxJU = $this->db->query("SELECT * FROM trx_ju");
			foreach($trxJU->result() as $rowJU)
			{
				$ju['id_unit_kerja'] = $_SESSION['IDSekolah'];
				$ju['nomor'] = $rowJU->nomor;
				$ju['no_bukti'] = $rowJU->no_bukti;
				$ju['uraian'] = $rowJU->uraian;
				$ju['id_sumber_trans'] = 1;
				$ju['tgl_jurnal'] = date("Y-m-d", strtotime($rowJU->tanggal));
				$ju['date_entry'] = date("Y-m-d H:i:s");
				
				$this->db->insert("trx_jurnal", $ju);
				$idjurnal = $this->db->insert_id();
				
				$trxJUDet = $this->db->query("SELECT * FROM trx_judet
				WHERE trx_judet.id_ju = '".$rowJU->id_ju."'");
				
				foreach($trxJUDet->result() as $rowJUDet)
				{
					$juDet['id_jurnal'] = $idjurnal;
					$juDet['id_akun'] = $rowJUDet->id_akun;
					$juDet['id_kategori_item'] = 0;
					$juDet['debet_awal'] = $rowJUDet->debet;
					$juDet['kredit_awal'] = $rowJUDet->kredit;
					$juDet['nilai_tukar'] = 1;
					$juDet['debet_akhir'] = $rowJUDet->debet;
					$juDet['kredit_akhir'] = $rowJUDet->kredit;
					$juDet['memo'] = "Jurnal Umum";
					$juDet['id_sumber_dana'] = 0;
					
					$this->db->insert("trx_jurnal_det", $juDet);
				}
			}
		}
		
		
		function linkitem()
		{
		
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			$data['kategori'] = $this->db->query("SELECT * FROM mst_kategori_item");
			
			$content = $this->load->view("master_link_item", $data, true);
			
			echo $content;
		}
		
		public function simpandatalinkitem()
		{
			
			$iditem = $this->input->post("id_item");
			$idakun = $this->input->post("id_akun");
			
			foreach($iditem as $keyItem => $valItem)
			{
				foreach($idakun as $keyAkun => $valAkun)
				{
					if($keyItem == $keyAkun)
					{
						$this->db->where("id_item", $valItem);
						$this->db->update("mst_item", array("id_akun" => $valAkun));
					}
				}
			}
		}
		
		public function deletejurnalumum()
		{
			$idju = $this->input->post("idju");
			
			$this->db->where("id_ju", $idju);
			$this->db->delete("trx_ju");
			
			$this->db->where("id_ju", $idju);
			$this->db->delete("trx_judet");
		}
		
		public function linkbank()
		{
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '0');
			$data['linkbank'] = $this->db->query("SELECT *, mst_bank.nama_bank as bank FROM mst_bank
			LEFT JOIN mst_akun ON mst_akun.id_akun = mst_bank.id_akunbank");
			
			$content = $this->load->view("master_link_bank", $data, true);
			
			echo $content;
		}
		
		public function simpandatalinkbank()
		{
			
			
			$idakunref = $this->input->post("id_ref_akun");
			$idakun = $this->input->post("id_akun");
			
			foreach($idakunref as $keyakunref => $akunref)
			{
				foreach($idakun as $keyakun => $akun)
				{
					if($keyakunref == $keyakun)
					{
						$this->db->where("id_bank", $akunref);
						$this->db->update("mst_bank", array("id_akun" =>$akun));
					}
				}
			}
		}
		
		
		public function mutasibank()
		{
			$data['mutasi'] = $this->db->query("SELECT trx_ju.* FROM trx_ju 
			WHERE trx_ju.id_sumber_trans = 8");
			
			$content = $this->load->view("mutasibank",$data,true);
			
			echo $content;
		}
		
		public function tambahdata_mutasibank()
		{
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '1');
			$content = $this->load->view("tambahdata_mutasibank",$data,true);
			
			echo $content;
		}
		
		public function getNumberMutasiBank()
		{
			$number = $this->db->query("SELECT * FROM trx_ju
			WHERE trx_ju.id_sumber_trans = 8
			ORDER BY trx_ju.nomor DESC");
			
			if($number->num_rows() == 0)
			{
				$number = "MTS-001";
			}
			else
			{
				$number = $number->first_row()->nomor;
				$number = explode("-", $number);
				$pattern = $number[0];
				$number = (int)$number[1];
				$number = $number + 1;
				$number = STR_PAD($number,4,"0", STR_PAD_LEFT);
				$number = $pattern."-".$number;
			}
			
			echo $number;
		}
		
		public function generateNumberBuktiMutasiBank()
		{
			$number = $this->db->query("SELECT * FROM trx_ju
			WHERE trx_ju.id_sumber_trans = 8
			ORDER BY trx_ju.no_bukti DESC");
			$bulan = date("m");
			
			if($number->num_rows() == 0)
			{
				$number = "trx.mutasibank/".convertBulanToRomawi($bulan)."/".date("Y")."/0001";
			}
			else
			{
				$number = $number->first_row()->no_bukti;
				$number = strrev($number);
				$number = explode("/",$number);
				$number = (int)$number[0];
				$number = strrev($number) + 1;
				$number = STR_PAD($number,4,"0", STR_PAD_LEFT);
				$number = "trx.mutasibank/".convertBulanToRomawi($bulan)."/".date("Y")."/".$number;
			}
			
			echo $number;
		}
		
		public function simpandataMutasiBank()
		{
			
			$ju['nomor'] = $this->input->post("nomor");
			$ju['uraian'] = $this->input->post("uraian");
			$ju['no_bukti'] = $this->input->post("nobukti");
			$ju['id_sumber_trans'] = 8;
			$ju['tanggal'] = date("Y-m-d", strtotime($this->input->post("tanggal")));
			
			$this->db->insert("trx_ju", $ju);
			$idju = $this->db->insert_id();
			
			$judet['id_ju'] = $idju;
			$judet['id_akun'] = $this->input->post('idakun_dari');
			$judet['debet'] = 0;
			$judet['kredit'] = str_replace(",","",$this->input->post("nominal"));
			
			$this->db->insert("trx_judet", $judet);
			
			$judet['id_ju'] = $idju;
			$judet['id_akun'] = $this->input->post('idakun_ke');
			$judet['debet'] = str_replace(",","",$this->input->post("nominal"));
			$judet['kredit'] = 0;
			
			$this->db->insert("trx_judet", $judet);
			
		}
		
		public function editdata_mutasibank()
		{
			
			$data['akun'] = $this->ModelKodeAkun->GetDaftarKodeAkun($tipeAkun = '1');
			
			$data['idju'] = $this->input->post("IDx");
			
			$idju = $this->input->post("IDx");
			
			$data['ju'] = $this->db->query("SELECT *, 
			trx_ju.uraian as uraians,
			CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeakun,
			mst_akun.nama_akun as namaakun,
			mst_akun.id_akun as idakun
			FROM trx_ju
			LEFT JOIN trx_judet ON trx_judet.id_ju = trx_ju.id_ju
			LEFT JOIN mst_akun ON mst_akun.id_akun = trx_judet.id_akun
			WHERE trx_ju.id_ju = '".$idju."'");
			
			
			$data['dari'] = $this->db->query("SELECT *, 
			trx_ju.uraian as uraians,
			CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeakun,
			mst_akun.nama_akun as namaakun,
			mst_akun.id_akun as idakun,
			trx_judet.kredit as kredit
			FROM trx_ju
			LEFT JOIN trx_judet ON trx_judet.id_ju = trx_ju.id_ju
			LEFT JOIN mst_akun ON mst_akun.id_akun = trx_judet.id_akun
			WHERE trx_ju.id_ju = '".$idju."'
			AND trx_judet.kredit > 0");
			
			$data['ke'] = $this->db->query("SELECT *, 
			trx_ju.uraian as uraians,
			CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) AS kodeakun,
			mst_akun.nama_akun as namaakun,
			mst_akun.id_akun as idakun,
			trx_judet.debet as debet
			FROM trx_ju
			LEFT JOIN trx_judet ON trx_judet.id_ju = trx_ju.id_ju
			LEFT JOIN mst_akun ON mst_akun.id_akun = trx_judet.id_akun
			WHERE trx_ju.id_ju = '".$idju."'
			AND trx_judet.debet > 0");
			
			$content = $this->load->view("editdata_mutasibank", $data, true);
			
			echo $content;
		}
		
		public function updatedatamutasibank()
		{
			
			$idx = $this->input->post("IDx");
			
			$this->db->where("id_ju", $idx);
			$this->db->delete("trx_ju");
			
			$this->db->where("id_ju", $idx);
			$this->db->delete("trx_judet");
			
			$ju['nomor'] = $this->input->post("nomor");
			$ju['uraian'] = $this->input->post("uraian");
			$ju['no_bukti'] = $this->input->post("nobukti");
			$ju['id_sumber_trans'] = 8;
			$ju['tanggal'] = date("Y-m-d", strtotime($this->input->post("tanggal")));
			
			$this->db->insert("trx_ju", $ju);
			$idju = $this->db->insert_id();
			
			$judet['id_ju'] = $idju;
			$judet['id_akun'] = $this->input->post('idakun_dari');
			$judet['debet'] = 0;
			$judet['kredit'] = str_replace(",","",$this->input->post("nominal"));
			
			$this->db->insert("trx_judet", $judet);
			
			$judet['id_ju'] = $idju;
			$judet['id_akun'] = $this->input->post('idakun_ke');
			$judet['debet'] = str_replace(",","",$this->input->post("nominal"));
			$judet['kredit'] = 0;
			
			$this->db->insert("trx_judet", $judet);
		}
		
		function getedititemakun()
		{
			$idakun = $this->input->post("idx");
			
			$getitem = $this->db->query("SELECT * FROM mst_item WHERE mst_item.id_akunitem = '".$idakun."'");
			
			$json['namaitem'] = $getitem->first_row()->nama_item;
			$json['satuan'] = $getitem->first_row()->satuan;
			$json['hargabeli'] = number_format($getitem->first_row()->harga_beli);
			$json['hargajual'] = number_format($getitem->first_row()->harga_jual);
			
			echo json_encode($json);
			
		}
		
		
	}

/* End of file user.php */