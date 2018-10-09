<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class transaksi extends MY_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('func_helper');
		$this->load->helper('fungsidate');
		
		setDatabase($_SESSION['Database']);
	}

	//yudha
	public function penjualan()
	{
		

		if ($_SESSION['IDUnit'] == 1) {

			$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
			$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

			$data['penjualan'] = $this->db->query("SELECT *, COALESCE(SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item)) as total FROM trx_penjualan
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
				LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_penjualan.id_metode_bayar
				LEFT JOIN mst_bank ON mst_bank.id_bank = trx_penjualan.id_bank
				LEFT JOIN mst_pelanggan ON mst_pelanggan.id_pelanggan = trx_penjualan.id_pelanggan
				WHERE trx_penjualan.id_pelanggan <> 0 
				GROUP BY trx_penjualan_det.id_penjualan ");
		}else{
			$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
			$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

			$data['penjualan'] = $this->db->query("SELECT *, COALESCE(SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item)) as total FROM trx_penjualan
				LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
				LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_penjualan.id_metode_bayar
				LEFT JOIN mst_bank ON mst_bank.id_bank = trx_penjualan.id_bank
				LEFT JOIN mst_pelanggan ON mst_pelanggan.id_pelanggan = trx_penjualan.id_pelanggan
				WHERE trx_penjualan.id_pelanggan <> 0 AND trx_penjualan.id_unit =  '".$_SESSION['IDUnit']."'
				GROUP BY trx_penjualan_det.id_penjualan  ");
		}

		$content = $this->load->view("transaksi/penjualan",$data, true);

		echo $content;
	}

	//YUDHA WAS HERE
	public function penjualankredit()
	{
		
		$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
		$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

		$data['penjualan'] = $this->db->query("SELECT *, COALESCE(SUM(trx_penjualan_kredit_det.harga * trx_penjualan_kredit_det.jumlah_item)) as total FROM trx_penjualan_kredit
			LEFT JOIN trx_penjualan_kredit_det ON trx_penjualan_kredit_det.id_penjualan = trx_penjualan_kredit.id_penjualan
			LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_penjualan_kredit.id_metode_bayar
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_penjualan_kredit.id_bank
			LEFT JOIN mst_pelanggan ON mst_pelanggan.id_pelanggan = trx_penjualan_kredit.id_pelanggan
			WHERE trx_penjualan_kredit.id_pelanggan <> 0
			GROUP BY trx_penjualan_kredit_det.id_penjualan");

		$content = $this->load->view("transaksi/penjualan_kredit",$data, true);

		echo $content;
	} 

	public function caridatapenjualan()
	{

			// s_tanggalawal=08-03-2017&s_namapelanggan=pelanga&s_metodebayar=1&s_tanggalakhir=11-03-2017&s_notrans=12&s_bank=1
		$s_tglAwal 	= $this->input->post('s_tanggalawal');
		$s_tglAkhir 	= $this->input->post('s_tanggalakhir');
		$s_pelanggan 	= $this->input->post('s_namapelanggan');
		$s_metode 	= $this->input->post('s_metodebayar');
		$s_notrans 	= $this->input->post('s_notrans');
		$s_bank 	= $this->input->post('s_bank');

		$whereTglAwal 	= ($s_tglAwal) ? 'AND (trx.tanggal_penjualan >= "'.date("Y-m-d", strtotime($s_tglAwal)).'" AND trx.tanggal_penjualan <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'") ' : '';
			//$whereTglAkhir 	= ($s_tglAkhir) ? 'AND trx.tanggal_penjualan <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'" ' : '';
		$wherePelanggan = ($s_pelanggan != "") ? 'AND pel.nama_pelanggan LIKE "%'.$s_pelanggan.'%" ' : '';
		$whereMetode 	= ($s_metode != '-') ? 'AND trx.id_metode_bayar = '.$s_metode.' ' : '';
		$whereNotrans 	= ($s_notrans != "" ) ? 'AND trx.no_transaksi LIKE "%'.$s_notrans.'%" ' : '';
		$whereBank 		= ($s_bank != '-') ? 'AND trx.id_bank = '.$s_bank.' ' : '';

		$werewer = $whereTglAwal.$wherePelanggan.$whereMetode.$whereNotrans.$whereBank;
		$werewer = (substr($werewer, 0, 3) == 'AND') ? substr($werewer, 4) : $werewer;

		$idunit = ($_SESSION['IDUnit'] != 1) ? " and trx_penjualan.id_unit = '".$_SESSION['IDUnit']."'" : "";
		$idunit2 = ($_SESSION['IDUnit'] != 1) ? " and trx.id_unit = '".$_SESSION['IDUnit']."'" : "";

		if(!$werewer)
		{
			$kweri 	= "SELECT *, COALESCE(SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item)) as total FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_penjualan.id_metode_bayar
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_penjualan.id_bank
			LEFT JOIN mst_pelanggan ON mst_pelanggan.id_pelanggan = trx_penjualan.id_pelanggan
			WHERE trx_penjualan.id_pelanggan <> 0  $idunit
			GROUP BY trx_penjualan_det.id_penjualan";
		}
		else
		{
			$kweri 	= "SELECT *, COALESCE(SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item)) AS total FROM trx_penjualan trx 
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx.id_penjualan
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pelanggan pel ON pel.id_pelanggan = trx.id_pelanggan
			WHERE trx.id_pelanggan <> 0 and $werewer $idunit2 GROUP BY trx_penjualan_det.id_penjualan ";
		}

			//echo $kweri;

		$penjualan = $this->db->query($kweri);

		if($penjualan->num_rows() > 0)
		{
			foreach($penjualan->result() as $pen => $jualan)
			{
				$data['id_penjualan'] = $jualan->id_penjualan;
				$data['tanggal_penjualan'] 	= date("d-m-Y", strtotime($jualan->tanggal_penjualan));
				$data['nama_pelanggan'] 	= $jualan->nama_pelanggan;
				$data['no_transaksi'] 		= $jualan->no_transaksi;
				$data['nama_metode_bayar'] 	= $jualan->nama_metode_bayar;
				$data['nama_bank'] 			= $jualan->nama_bank;
				$data['keterangan'] 		= $jualan->keterangan;
				$data['total'] 				= number_format($jualan->total);
				$jojon['data'][] = $data;
			}

			$jojon['flag'] 	= true;
		}
		else
		{
			$jojon['flag'] 	= false;
		}
		$jojon['kopong'] 	= false;

		echo json_encode($jojon);

	}

	public function tambahpenjualan()
	{
		$data['setting'] = $this->db->query("SELECT * FROM setup_general");
		$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
		$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

		$data['pelanggan'] = $this->db->query("SELECT * FROM mst_pelanggan");
		$data['unit'] 	= $this->db->query("SELECT * FROM mst_departemen");

		
		$content = $this->load->view("transaksi/tambah_penjualan",$data, true);

		echo $content;

	}

	public function tambahpenjualankredit()
	{
		
		$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
		$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

		$data['pelanggan'] = $this->db->query("SELECT * FROM mst_pelanggan");
		
		$content = $this->load->view("transaksi/tambah_penjualan_kredit",$data, true);

		echo $content;

	}

	public function simpandatapemasok()
	{

		$data['nama_pemasok'] = $this->input->post("nama");
		$data['alamat_pemasok'] = $this->input->post("alamat");
		$data['no_telp_1'] = $this->input->post("telp1");
		$data['no_telp_2'] = $this->input->post("telp2");
		$data['email'] = $this->input->post("email");
		$data['id_unit'] 	= $this->input->post('new_unit');

		$this->db->insert("mst_pemasok", $data);
	}

	public function simpandatapelanggan()
	{

		$data['nama_pelanggan'] = $this->input->post("nama");
		$data['alamat_pelanggan'] = $this->input->post("alamat");
		$data['no_telp_1'] = $this->input->post("telp1");
		$data['no_telp_2'] = $this->input->post("telp2");
		$data['email'] = $this->input->post("email");
		$data['id_unit'] 	= $this->input->post('new_unit');

		$this->db->insert("mst_pelanggan", $data);
	}

	public function getpelanggan()
	{
		
		
		if($_SESSION['IDUnit'] != "1") 
		{
			$where = "WHERE mst_pelanggan.id_unit = '".$_SESSION['IDUnit']."'";
			
		}
		else
		{
			$where = "";
		}
		
		
		
		
		$pelanggan = $this->db->query("SELECT * FROM mst_pelanggan ".$where);

		if($pelanggan->num_rows() > 0)
		{
			foreach($pelanggan->result() as $row)
			{
				$data['idpelanggan'] = $row->id_pelanggan;
				$data['namapelanggan'] = $row->nama_pelanggan;
				$data['alamatpelanggan'] = $row->alamat_pelanggan;

				$json['pelanggan'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}
		echo json_encode($json);
	}
	
	public function getpelanggankredit()
	{
		$pelanggan = $this->db->query("SELECT * FROM ref_link_piutang
			LEFT JOIN mst_pelanggan ON mst_pelanggan.id_pelanggan = ref_link_piutang.id_kontak
			WHERE ref_link_piutang.tipe = 'bkm'");

		if($pelanggan->num_rows() > 0)
		{
			foreach($pelanggan->result() as $row)
			{
				$data['idpelanggan'] = $row->id_pelanggan;
				$data['namapelanggan'] = $row->nama_pelanggan;
				$data['alamatpelanggan'] = $row->alamat_pelanggan;

				$json['pelanggan'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}
		echo json_encode($json);
	}

		// tyas mirasih
	public function getnumbertransaksi()
	{
		$number = $this->db->query("SELECT no_transaksi FROM trx_penjualan
			WHERE trx_penjualan.id_pelanggan <> 0 and id_unit = '".$_SESSION['IDUnit']."'
			ORDER BY trx_penjualan.id_penjualan DESC  ");

		$bulan = convertBulanToRomawi(date("m"));
		$lastNombor = "";

		
		$unit = $this->db->query("SELECT * FROM mst_departemen where id_departemen = '".$_SESSION['IDUnit']."' ");

		$num=str_replace('Unit ', '', $unit->first_row()->nama_departemen);


		if($number->num_rows() == 0)
		{
			$data['unit'] = $unit->first_row()->nama_departemen;
			$data['bank'] = '-';
			$data['bulan'] = $bulan;
			$data['tahun'] = date("Y");
			$data['nourut'] = "0001";
			
			$result['notrans'] = $data;
			
			
			
		}
		else
		{
			$notransaksi = $number->first_row()->no_transaksi;
			$notrans = $notransaksi;
			$notrans = strrev($notrans);
			$notrans = substr($notrans, 0 ,4);
			$notrans = (int)strrev($notrans);
			$notrans = $notrans + 1;
			$notrans = STR_PAD($notrans, 4, "0", STR_PAD_LEFT);
			$notransaksi = $notrans;
			
			$data['unit'] = $unit->first_row()->nama_departemen;
			$data['bank'] = '-';
			$data['bulan'] = $bulan;
			$data['tahun'] = date("Y");
			$data['nourut'] = $notransaksi;
			
			$result['notrans'] = $data;
		}

		echo json_encode($result);
	}
	
	function getautonumberchangePenjualan()
	{
		$kasbank = $this->input->post("kasbank");
		$bulan = $this->input->post("bulan");
		$tahun = $this->input->post("tahun");
		
		$number = $this->db->query("SELECT no_transaksi FROM trx_penjualan
			WHERE trx_penjualan.id_pelanggan <> 0 and id_unit = '".$_SESSION['IDUnit']."'
			AND trx_penjualan.id_bank = '".$kasbank."' AND MONTH(trx_penjualan.tanggal_penjualan) = '".$bulan."'
			AND YEAR(trx_penjualan.tanggal_penjualan ) = '".$tahun."'
			ORDER BY trx_penjualan.id_penjualan DESC");
			
		$unit = $this->db->query("SELECT * FROM mst_departemen where id_departemen = '".$_SESSION['IDUnit']."' ");
		
		$kasbank = $this->db->query("SELECT * FROM mst_bank WHERE mst_bank.id_bank = '".$kasbank."'");
		
		if($number->num_rows() == 0)
		{
			$data['unit'] = $unit->first_row()->nama_departemen;
			$data['bank'] = $kasbank->first_row()->nama_bank;
			$data['bulan'] = convertBulanToRomawi($bulan);
			$data['tahun'] = date("Y");
			$data['nourut'] = "0001";
			
			$result['notrans'] = $data;
			
			
			
		}
		else
		{
			$notransaksi = $number->first_row()->no_transaksi;
			$notrans = $notransaksi;
			$notrans = strrev($notrans);
			$notrans = substr($notrans, 0 ,4);
			$notrans = (int)strrev($notrans);
			$notrans = $notrans + 1;
			$notrans = STR_PAD($notrans, 4, "0", STR_PAD_LEFT);
			$notransaksi = $notrans;
			
			$data['unit'] = $unit->first_row()->nama_departemen;
			$data['bank'] = $kasbank->first_row()->nama_bank;
			$data['bulan'] = convertBulanToRomawi($bulan);
			$data['tahun'] = date("Y");
			$data['nourut'] = $notransaksi;
			
			$result['notrans'] = $data;
		}

		echo json_encode($result);
	}
	
	function getautonumberchangePenjualanEdit()
	{
		$kasbank = $this->input->post("kasbank");
		$bulan = $this->input->post("bulan");
		$tahun = $this->input->post("tahun");
		$idpenjualan = $this->input->post("idpenjualan");
		
		$number = $this->db->query("SELECT trx_penjualan.no_transaksi
			FROM trx_penjualan
			WHERE trx_penjualan.id_pelanggan <> 0 
			and trx_penjualan.id_unit = (SELECT trx_penjualan.id_unit FROM trx_penjualan WHERE trx_penjualan.id_penjualan = '".$idpenjualan."')
			AND trx_penjualan.id_bank = '".$kasbank."' AND MONTH(trx_penjualan.tanggal_penjualan) = '".$bulan."'
			AND YEAR(trx_penjualan.tanggal_penjualan) = '".$tahun."'
			ORDER BY trx_penjualan.id_penjualan DESC");
			
		$unit = $this->db->query("SELECT * FROM trx_penjualan 
		LEFT JOIN mst_departemen ON mst_departemen.id_departemen = trx_penjualan.id_unit
		WHERE trx_penjualan.id_penjualan = '".$idpenjualan."'");
		
		$kasbank = $this->db->query("SELECT * FROM mst_bank WHERE mst_bank.id_bank = '".$kasbank."'");
		
		if($number->num_rows() == 0)
		{
			$data['unit'] = $unit->first_row()->nama_departemen;
			$data['bank'] = $kasbank->first_row()->nama_bank;
			$data['bulan'] = convertBulanToRomawi($bulan);
			$data['tahun'] = date("Y");
			$data['nourut'] = "0001";
			
			$result['notrans'] = $data;
			
			
			
		}
		else
		{
			
			
			$notransaksi = $number->first_row()->no_transaksi;
			$notrans = $notransaksi;
			$notrans = strrev($notrans);
			$notrans = substr($notrans, 0 ,4);
			$notrans = (int)strrev($notrans);
			$notrans = $notrans + 1;
			$notrans = STR_PAD($notrans, 4, "0", STR_PAD_LEFT);
			$notransaksi = $notrans;
			
			$data['unit'] = $unit->first_row()->nama_departemen;
			$data['bank'] = $kasbank->first_row()->nama_bank;
			$data['bulan'] = convertBulanToRomawi($bulan);
			$data['tahun'] = date("Y");
			$data['nourut'] = $notransaksi;
			
			$result['notrans'] = $data;
		}

		echo json_encode($result);
	}
	
	function getautonumberchangePengeluaran()
	{
		$kasbank = $this->input->post("kasbank");
		$bulan = $this->input->post("bulan");
		$tahun = $this->input->post("tahun");
		
		$number = $this->db->query("SELECT nomor_transaksi as no_transaksi FROM trx_pembelian_persediaan
			WHERE trx_pembelian_persediaan.id_pemasok <> 0 and trx_pembelian_persediaan.id_unit = '".$_SESSION['IDUnit']."'
			AND trx_pembelian_persediaan.id_bank = '".$kasbank."' AND MONTH(trx_pembelian_persediaan.tanggal) = '".$bulan."'
			AND YEAR(trx_pembelian_persediaan.tanggal ) = '".$tahun."'
			ORDER BY trx_pembelian_persediaan.id_pembelian DESC");
			
		$unit = $this->db->query("SELECT * FROM mst_departemen where id_departemen = '".$_SESSION['IDUnit']."' ");
		
		$kasbank = $this->db->query("SELECT * FROM mst_bank WHERE mst_bank.id_bank = '".$kasbank."'");
		
		if($number->num_rows() == 0)
		{
			$data['unit'] = $unit->first_row()->nama_departemen;
			$data['bank'] = $kasbank->first_row()->nama_bank;
			$data['bulan'] = convertBulanToRomawi($bulan);
			$data['tahun'] = date("Y");
			$data['nourut'] = "0001";
			
			$result['notrans'] = $data;
			
			
			
		}
		else
		{
			$notransaksi = $number->first_row()->no_transaksi;
			$notrans = $notransaksi;
			$notrans = strrev($notrans);
			$notrans = substr($notrans, 0 ,4);
			$notrans = (int)strrev($notrans);
			$notrans = $notrans + 1;
			$notrans = STR_PAD($notrans, 4, "0", STR_PAD_LEFT);
			$notransaksi = $notrans;
			
			$data['unit'] = $unit->first_row()->nama_departemen;
			$data['bank'] = $kasbank->first_row()->nama_bank;
			$data['bulan'] = convertBulanToRomawi($bulan);
			$data['tahun'] = date("Y");
			$data['nourut'] = $notransaksi;
			
			$result['notrans'] = $data;
		}

		echo json_encode($result);
	}

		// yudha
	public function getnumbertransaksikredit()
	{
		$number = $this->db->query("SELECT no_transaksi FROM trx_penjualan_kredit
			WHERE trx_penjualan_kredit.id_pelanggan <> 0
			ORDER BY trx_penjualan_kredit.id_penjualan DESC");

		$bulan = convertBulanToRomawi(date("m"));

		$lastNombor = "";

		if($number->num_rows() == 0)
		{
			$notransaksi = "kw.piutang/".$bulan."/".date("Y")."/0001";
		}
		else
		{
			$notransaksi = $number->first_row()->no_transaksi;
			$notrans = $notransaksi;
			$notrans = strrev($notrans);
			$notrans = substr($notrans, 0 ,4);
			$notrans = (int)strrev($notrans);
			$notrans = $notrans + 1;
			$notrans = STR_PAD($notrans, 4, "0", STR_PAD_LEFT);
			$notransaksi = "kw.piutang/".$bulan."/".date("Y")."/".$notrans;
		}

		echo $notransaksi;
	}

	function getitemlist()
	{
		$where= ($_SESSION['IDUnit']!=1) ? ' and mst_kategori_item.id_unit= "'.$_SESSION['IDUnit'].'"'  : ''; 
		$item = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			LEFT JOIN ref_tipe_item ON ref_tipe_item.id_ref_tipe_item = mst_item.id_ref_tipe_item
			LEFT join mst_departemen ON mst_kategori_item.id_unit = mst_departemen.id_departemen
			WHERE ref_tipe_item.id_ref_tipe_item = 2 ".$where);

		if($item->num_rows() > 0)
		{
			foreach($item->result() as $row)
			{
				$data['iditem'] = $row->id_item;
				$data['namaitem'] = $row->nama_item;
				$data['hargaitem'] = number_format($row->harga_jual);
				$data['satuan'] = $row->satuan;
				$data['kategori'] = $row->nama_kategori;
				$data['unit'] = $row->nama_departemen;
				$json['item'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}

		echo json_encode($json);
	}

	function getitemlistbiaya()
	{
		$item = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			LEFT JOIN ref_tipe_item ON ref_tipe_item.id_ref_tipe_item = mst_item.id_ref_tipe_item
			WHERE ref_tipe_item.id_ref_tipe_item = 1");

		if($item->num_rows() > 0)
		{
			foreach($item->result() as $row)
			{
				$data['iditem'] = $row->id_item;
				$data['namaitem'] = $row->nama_item;
				$data['hargaitem'] = number_format($row->harga_jual);
				$data['satuan'] = $row->satuan;
				$data['kategori'] = $row->nama_kategori;

				$json['item'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}

		echo json_encode($json);
	}

	function simpanitem()
	{
		$data['id_ref_tipe_item'] = $this->input->post("idtipeitem");
		$data['id_kategori_item'] = $this->input->post("idkategori");
		$data['nama_item'] = $this->input->post("namaitem");
		$data['satuan'] = $this->input->post("satuan");
		$data['id_akun'] = $this->input->post("idakun");
		$data['harga_beli'] = str_replace(",","",$this->input->post("hargabeli"));
		$data['harga_jual'] = str_replace(",","",$this->input->post("hargajual"));

		$this->db->insert("mst_item", $data);
	}

	function simpandatabkm()
	{
		$no=$this->input->post('notransaksi');
		$ceknomor = $this->db->query("SELECT no_transaksi FROM trx_penjualan where trx_penjualan.no_transaksi = '".$no."'");


		$this->form_validation->set_rules('idpelanggan', 'pelanggan', 'required'); 
		if (!$this->form_validation->run()) 
		{
			$json['notif']='<div class="alert alert-danger">
			<strong>Data Gagal Disimpan, cek kembali inputan anda!!</strong> 
			</div>';
		}
		elseif ($ceknomor->num_rows() == 0) 
		{
			parse_str($this->input->post("serialize"), $a);
			
			$transaksi = $this->input->post("namaunit")."/".$this->input->post("namabank")."/".$this->input->post("bulan")."/".$this->input->post("tahun")."/".$this->input->post("notransaksi");

			$parent['id_pelanggan'] = $this->input->post("idpelanggan");
			$parent['no_transaksi'] = $transaksi;
			$parent['tanggal_penjualan'] = date("Y-m-d", strtotime($this->input->post("tgltransaksi")));
			$parent['id_metode_bayar'] = $this->input->post("idmetodebayar");
			$parent['id_bank'] = $this->input->post("idbank");
			$parent['keterangan'] = $this->input->post("keterangan");
			$parent['id_unit'] = $_SESSION['IDUnit'];

			$this->db->insert("trx_penjualan", $parent);
			$idpenjualan = $this->db->insert_id();

			for($i = 0; $i < count($a['item']); $i++)
			{
				$child['id_penjualan'] = $idpenjualan;
				$child['id_item'] = $a['item'][$i];
				$child['jumlah_item'] = $a['jmlitem'][$i];
				$child['harga'] = str_replace(",","",$a['harga'][$i]);
				$child['potongan'] = str_replace(",","",$a['potongan'][$i]);
				$child['is_diskon'] = ($a['potongan'][$i] > 0) ? 1 : 0;
				$child['memo'] = str_replace(",","",$a['memo'][$i]);

				$this->db->insert("trx_penjualan_det", $child);
			}
			$json['notif']='<div class="alert alert-success alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong>Data Sudah Berhasil Disimpan</strong>
			</div>';

		}
		else
		{


			$json['notif']=' <div class="alert alert-warning alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong>Nomor Transaksi tidak boleh sama</strong>
			</div>';

		}

		echo json_encode($json);

	}

	
	//YUDHA WAS HERE
	function simpandatabkmkredit()
	{	
		parse_str($this->input->post("serialize"), $a);

		$parent['id_pelanggan'] = $this->input->post("idpelanggan");
		$parent['no_transaksi'] = $this->input->post("notransaksi");
		$parent['tanggal_penjualan'] = date("Y-m-d", strtotime($this->input->post("tgltransaksi")));
		$parent['id_metode_bayar'] = $this->input->post("idmetodebayar");
		$parent['id_bank'] = $this->input->post("idbank");
		$parent['keterangan'] = $this->input->post("keterangan");

		$this->db->insert("trx_penjualan_kredit", $parent);
		$idpenjualan = $this->db->insert_id();

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_penjualan'] = $idpenjualan;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah_item'] = $a['jmlitem'][$i];
			$child['harga'] = str_replace(",","",$a['harga'][$i]);
			$child['potongan'] = str_replace(",","",$a['potongan'][$i]);
			$child['is_diskon'] = ($a['potongan'][$i] > 0) ? 1 : 0;
			$child['memo'] = str_replace(",","",$a['memo'][$i]);

			$this->db->insert("trx_penjualan_kredit_det", $child);
		}

	}



function editdatabkm()
{
	$idpenjualan = $this->input->post("idpenjualan");

	$data['penjualan'] = $this->db->query("SELECT * FROM trx_penjualan
		LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
		LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_penjualan.id_metode_bayar
		LEFT JOIN mst_bank ON mst_bank.id_bank = trx_penjualan.id_bank
		LEFT JOIN mst_pelanggan ON mst_pelanggan.id_pelanggan = trx_penjualan.id_pelanggan
		LEFT JOIN mst_item ON mst_item.id_item = trx_penjualan_det.id_item
		LEFT JOIN mst_departemen ON mst_departemen.id_departemen = trx_penjualan.id_unit
		WHERE trx_penjualan_det.id_penjualan = '".$idpenjualan."'");

	$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
	$data['bank'] = $this->db->query("SELECT * FROM mst_bank");
	$data['setting'] = $this->db->query("SELECT * FROM setup_general");

	$data['pelanggan'] = $this->db->query("SELECT * FROM mst_pelanggan");
	$data['unit'] 	= $this->db->query("SELECT * FROM mst_departemen");

	$content = $this->load->view("transaksi/edit_penjualan", $data, true);

	echo $content;
}

	//YUDHA was here
	function editdatabkmkredit()
	{
		$idpenjualan = $this->input->post("idpenjualan");

		$data['penjualan'] = $this->db->query("SELECT * FROM trx_penjualan_kredit
			LEFT JOIN trx_penjualan_kredit_det ON trx_penjualan_kredit_det.id_penjualan = trx_penjualan_kredit.id_penjualan
			LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_penjualan_kredit.id_metode_bayar
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_penjualan_kredit.id_bank
			LEFT JOIN mst_pelanggan ON mst_pelanggan.id_pelanggan = trx_penjualan_kredit.id_pelanggan
			LEFT JOIN mst_item ON mst_item.id_item = trx_penjualan_kredit_det.id_item
			WHERE trx_penjualan_kredit_det.id_penjualan = '".$idpenjualan."'");

		$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
		$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

		$content = $this->load->view("transaksi/edit_penjualan_kredit", $data, true);

		echo $content;
	}

	function deletepenjualan()
	{
		$idP 	= $this->input->post('idpenjualan');

		$this->db->query("DELETE trx, det FROM trx_penjualan trx
			LEFT JOIN trx_penjualan_det det ON trx.id_penjualan = det.id_penjualan
			WHERE trx.id_penjualan = ".$idP." ");

		$data['flag'] 	= true;
		echo json_encode($data);
	}

	function simpaneditdatabkm()
	{

		parse_str($this->input->post("serialize"), $a);

		$transaksi = $this->input->post("namaunit")."/".$this->input->post("namabank")."/".$this->input->post("bulan")."/".$this->input->post("tahun")."/".$this->input->post("notransaksi");
		
		$parent['id_pelanggan'] = $this->input->post("idpelanggan");
		$parent['no_transaksi'] = $transaksi;
		$parent['tanggal_penjualan'] = date("Y-m-d", strtotime($this->input->post("tgltransaksi")));
		$parent['id_metode_bayar'] = $this->input->post("idmetodebayar");
		$parent['id_bank'] = $this->input->post("idbank");
		$parent['keterangan'] = $this->input->post("keterangan");

		$this->db->where("id_penjualan", $this->input->post("idpenjualan"));
		$this->db->update("trx_penjualan", $parent);


		$this->db->where("id_penjualan", $this->input->post("idpenjualan"));
		$this->db->delete("trx_penjualan_det");

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_penjualan'] = $this->input->post("idpenjualan");
			$child['id_item'] = $a['item'][$i];
			$child['jumlah_item'] = $a['jmlitem'][$i];
			$child['harga'] = str_replace(",","",$a['harga'][$i]);
			$child['potongan'] = str_replace(",","",$a['potongan'][$i]);
			$child['is_diskon'] = ($a['potongan'][$i] > 0) ? 1 : 0;
			$child['memo'] = str_replace(",","",$a['memo'][$i]);

			$this->db->insert("trx_penjualan_det", $child);
		}
	}

	//YUDHA 
	function simpaneditdatabkmkredit()
	{

		parse_str($this->input->post("serialize"), $a);

		$parent['id_pelanggan'] = $this->input->post("idpelanggan");
		$parent['no_transaksi'] = $this->input->post("notransaksi");
		$parent['tanggal_penjualan'] = date("Y-m-d", strtotime($this->input->post("tgltransaksi")));
		$parent['id_metode_bayar'] = $this->input->post("idmetodebayar");
		$parent['id_bank'] = $this->input->post("idbank");
		$parent['keterangan'] = $this->input->post("keterangan");

		$this->db->where("id_penjualan", $this->input->post("idpenjualan"));
		$this->db->update("trx_penjualan_kredit", $parent);


		$this->db->where("id_penjualan", $this->input->post("idpenjualan"));
		$this->db->delete("trx_penjualan_kredit_det");

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_penjualan'] = $this->input->post("idpenjualan");
			$child['id_item'] = $a['item'][$i];
			$child['jumlah_item'] = $a['jmlitem'][$i];
			$child['harga'] = str_replace(",","",$a['harga'][$i]);
			$child['potongan'] = str_replace(",","",$a['potongan'][$i]);
			$child['is_diskon'] = ($a['potongan'][$i] > 0) ? 1 : 0;
			$child['memo'] = str_replace(",","",$a['memo'][$i]);

			$this->db->insert("trx_penjualan_kredit_det", $child);
		}
	}

	function penerimaanlain()
	{

		$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
		$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

		$data['penerimaanlain'] = $this->db->query("SELECT *, COALESCE(SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item)) as total FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_penjualan.id_bank
			LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_penjualan.id_metode_bayar
			WHERE trx_penjualan.id_pelanggan = 0
			GROUP BY trx_penjualan_det.id_penjualan");

		$content = $this->load->view("transaksi/penerimaanlain", $data, true);

		echo $content;
	}

	function caridatapenerimaan()
	{
		$s_tglAwal 	= $this->input->post('s_tanggalawal');
		$s_tglAkhir 	= $this->input->post('s_tanggalakhir');
		$s_pelanggan 	= $this->input->post('s_namapelanggan');
		$s_metode 	= $this->input->post('s_metodebayar');
		$s_notrans 	= $this->input->post('s_notrans');
		$s_bank 	= $this->input->post('s_bank');

		$whereTglAwal 	= ($s_tglAwal) ? 'trx.tanggal_penjualan >= "'.date("Y-m-d", strtotime($s_tglAwal)).'" AND trx.tanggal_penjualan <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'" ' : '';
			//$whereTglAkhir 	= ($s_tglAkhir) ? 'AND trx.tanggal_penjualan <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'" ' : '';
		$wherePelanggan = ($s_pelanggan) ? 'AND pel.nama_pelanggan LIKE "%'.$s_pelanggan.'%" ' : '';
		$whereMetode 	= ($s_metode != '-') ? 'AND trx.id_metode_bayar = '.$s_metode.' ' : '';
		$whereNotrans 	= ($s_notrans) ? 'AND trx.no_transaksi LIKE "%'.$s_notrans.'%" ' : '';
		$whereBank 		= ($s_bank != '-') ? 'AND trx.id_bank = '.$s_bank.' ' : '';

		$werewer = $whereTglAwal.$wherePelanggan.$whereMetode.$whereNotrans.$whereBank;
		$werewer = (substr($werewer, 0, 3) == 'AND') ? substr($werewer, 4) : $werewer;

		if(!$werewer)
		{
			$kweri 	= "SELECT * FROM trx_penjualan trx
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pelanggan pel ON pel.id_pelanggan = trx.id_pelanggan
			WHERE trx_penjualan.id_pelanggan = 0 ";
		}
		else
		{
			$kweri 	= "SELECT *, COALESCE(SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item)) as total FROM trx_penjualan trx 
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar 
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx.id_penjualan
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pelanggan pel ON pel.id_pelanggan = trx.id_pelanggan 
			WHERE trx.id_pelanggan = 0 AND $werewer GROUP BY trx_penjualan_det.id_penjualan";
		}

			// echo $kweri;

		$penjualan = $this->db->query($kweri);

		if($penjualan->num_rows() > 0)
		{
			foreach($penjualan->result() as $pen => $jualan)
			{
				$data['id_penjualan'] = $jualan->id_penjualan;
				$data['tanggal_penjualan'] 	= date("d-m-Y", strtotime($jualan->tanggal_penjualan));
				$data['nama_pelanggan'] 	= $jualan->nama_pelanggan;
				$data['no_transaksi'] 		= $jualan->no_transaksi;
				$data['nama_metode_bayar'] 	= $jualan->nama_metode_bayar;
				$data['nama_bank'] 			= $jualan->nama_bank;
				$data['keterangan'] 		= $jualan->keterangan;
				$data['total'] 		= number_format($jualan->total);

				$jojon['data'][] = $data;
			}

			$jojon['flag'] 	= true;
		}
		else
		{
			$jojon['flag'] 	= false;
		}
		$jojon['kopong'] 	= false;

		echo json_encode($jojon);

	}

	function tambahpenerimaanlain()
	{
		$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
		$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

		$content = $this->load->view("transaksi/tambah_penerimaanlain", $data, true);

		echo $content;
	}

		// tyas mirasih
	function getnumbertransaksipenerimaan()
	{
		$aa= $_GET['haha'];
		echo $aa;


		$number = $this->db->query("SELECT no_transaksi FROM trx_penjualan
			WHERE trx_penjualan.id_pelanggan = 0
			ORDER BY trx_penjualan.id_penjualan DESC");

		$lastNombor = "";

		$bulan = convertBulanToRomawi(date("m"));

		if($number->num_rows() == 0)
		{
			$notransaksi = "kw.penerimaanlain/".$bulan."/".date("Y")."/0001";
		}
		elseif (!strpos($lastNombor, "kw.penerimaanlain/")) 
		{
			$lastNombor = $number->first_row()->no_transaksi;
			$notransaksi = GetNextNo($lastNombor);
		}
		else
		{
			$notransaksi = $number->first_row()->no_transaksi;
			$notrans = $notransaksi;
			$notrans = strrev($notrans);
			$notrans = substr($notrans, 0 ,4);
			$notrans = (int)strrev($notrans);
			$notrans = $notrans + 1;
			$notrans = STR_PAD($notrans, 4, "0", STR_PAD_LEFT);
			$notransaksi = "kw.penerimaanlain/".$bulan."/".date("Y")."/".$notrans;
		}

		echo $notransaksi;
	}

	function getnumbertransaksipenerimaankredit()
	{
		$number = $this->db->query("SELECT no_transaksi FROM trx_penjualan
			WHERE trx_penjualan.id_pelanggan = 0
			ORDER BY trx_penjualan.id_penjualan DESC");

		$lastNombor = "";

		$bulan = convertBulanToRomawi(date("m"));

		if($number->num_rows() == 0)
		{
			$notransaksi = "kw.penerimaanlain/".$bulan."/".date("Y")."/0001";
		}
		elseif (!strpos($lastNombor, "kw.penerimaanlain/")) 
		{
			$lastNombor = $number->first_row()->no_transaksi;
			$notransaksi = GetNextNo($lastNombor);
		}
		else
		{
			$notransaksi = $number->first_row()->no_transaksi;
			$notrans = $notransaksi;
			$notrans = strrev($notrans);
			$notrans = substr($notrans, 0 ,4);
			$notrans = (int)strrev($notrans);
			$notrans = $notrans + 1;
			$notrans = STR_PAD($notrans, 4, "0", STR_PAD_LEFT);
			$notransaksi = "kw.penerimaanlain/".$bulan."/".date("Y")."/".$notrans;
		}

		echo $notransaksi;
	}

	function simpandatapernerimaanlain()
	{

			//echo "<pre>";print_r($_POST);"</pre>";
			//exit();

		parse_str($this->input->post("serialize"), $a);

		$parent['no_transaksi'] = $this->input->post("notransaksi");
		$parent['tanggal_penjualan'] = date("Y-m-d", strtotime($this->input->post("tgltransaksi")));
		$parent['id_metode_bayar'] = $this->input->post("idmetodebayar");
		$parent['id_bank'] = $this->input->post("idbank");
		$parent['keterangan'] = $this->input->post("keterangan");

		$this->db->insert("trx_penjualan", $parent);
		$idpenerimaan = $this->db->insert_id();

		for($i = 0; $i < count($a['iditem']); $i++)
		{
			$harga = str_replace(",","",$a['harga'][$i]);
			$child['id_penjualan'] = $idpenerimaan;
			$child['id_item'] = $a['iditem'][$i];
			$child['jumlah_item'] = 1;
			$child['harga'] = $harga;
			$child['memo'] = $a['memo'][$i];

			$this->db->insert("trx_penjualan_det", $child);
		}
	}

	function editdatapenerimaanlain()
	{
		$idpenerimaan = $this->input->post("idpenerimaan");

		$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
		$data['bank'] = $this->db->query("SELECT * FROM mst_bank");
		$data['penerimaan'] = $this->db->query("SELECT * FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_penjualan.id_bank
			LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_penjualan.id_metode_bayar
			LEFT JOIN mst_item item ON trx_penjualan_det.id_item = item.id_item
			WHERE trx_penjualan.id_penjualan = '".$idpenerimaan."'");

		$content = $this->load->view("transaksi/edit_penerimaanlain",$data,true);

		echo $content;
	}

	public function simpandatapernerimaanlainubah()
	{
		parse_str($this->input->post("serialize"), $a);

		$idpenerimaan 	= $this->input->post('idpenerimaanlain');
		$parent['no_transaksi'] = $this->input->post("notransaksi");
		$parent['tanggal_penjualan'] = date("Y-m-d", strtotime($this->input->post("tgltransaksi")));
		$parent['id_metode_bayar'] = $this->input->post("idmetodebayar");
		$parent['id_bank'] = $this->input->post("idbank");
		$parent['keterangan'] = $this->input->post("keterangan");

		$this->db->where('id_penjualan', $idpenerimaan);
		$this->db->update("trx_penjualan", $parent);

			// delete det e sekk ndell
		$this->db->where('id_penjualan', $idpenerimaan);
		$this->db->delete("trx_penjualan_det");

		for($i = 0; $i < count($a['iditem']); $i++)
		{
			$child['id_penjualan'] = $idpenerimaan;
			$child['id_item'] = $a['iditem'][$i];
			$child['jumlah_item'] = 1;
			$child['harga'] = str_replace(",","",$a['harga'][$i]);
			$child['memo'] = $a['memo'][$i];

			$this->db->insert("trx_penjualan_det", $child);
		}
	}

	public function deletedatapenerimaanlain()
	{
		$idP 	= $this->input->post('idpenerimaanlain');

		$this->db->query("DELETE trx, det FROM trx_penjualan trx LEFT JOIN trx_penjualan_det det ON trx.id_penjualan = det.id_penjualan WHERE trx.id_penjualan = ".$idP." ");
		$data['flag'] 	= true;
		echo json_encode($data);
	}

	public function deleteallpenerimaanlain()
	{
		foreach ($_POST['idpenjualan'] as $id) {
			$this->db->where("id_penjualan", $id);
			$this->db->delete("trx_penjualan");
			$this->db->where("id_penjualan", $id);
			$this->db->delete("trx_penjualan_det");
		}
	}


	//yudha 
	public function deletedatapenjualan()
	{	

		$idP 	= $this->input->post('idpenjualan');
		$ni = $_GET['na'];
		echo $ni;

		if (isset($ni)) {
			$this->db->query("DELETE trx, det FROM trx_penjualan_kredit trx LEFT JOIN trx_penjualan_kredit_det det ON trx.id_penjualan = det.id_penjualan WHERE trx.id_penjualan = ".$idP." ");
			$data['flag'] 	= true;
			echo json_encode($data);

		}else
		{
			$this->db->query("DELETE trx, det FROM trx_penjualan trx LEFT JOIN trx_penjualan_det det ON trx.id_penjualan = det.id_penjualan WHERE trx.id_penjualan = ".$idP." ");
			$data['flag'] 	= true;
			echo json_encode($data);

		}
	}

	public function deleteall()
	{
		$ni = $_GET['na'];
		echo $ni;

		if (isset($ni)) {
			foreach ($_POST['idpenjualan'] as $id) {
				$this->db->where("id_penjualan", $id);
				$this->db->delete("trx_penjualan_kredit");
				$this->db->where("id_penjualan", $id);
				$this->db->delete("trx_penjualan_kredit_det");
			}
		}else{

			foreach ($_POST['idpenjualan'] as $id) {
				$this->db->where("id_penjualan", $id);
				$this->db->delete("trx_penjualan");
				$this->db->where("id_penjualan", $id);
				$this->db->delete("trx_penjualan_det");
			}

		}
	}

		// Penerimaan Lain OFF

		// Pengeluaran ndell
	public function pengeluaran()
	{
		if ($_SESSION['IDUnit']==1) {

			$this->db->where('id_ref_tipe_item', 1);
			$data['item'] = $this->db->get('ref_tipe_item');

			$data['metodebayar'] = $this->db->get('ref_metode_pembayaran');
			$data['bank'] 		 = $this->db->get('mst_bank');
			$data['pengeluaran'] = $this->db->query("SELECT *, 
				SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah) as total,
				trx.deskripsi as ket
				FROM trx_pembelian_persediaan trx
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx.id_pembelian
				LEFT JOIN ref_metode_pembayaran method ON method.id_metode_bayar = trx.id_metode_bayar
				LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
				LEFT JOIN mst_pemasok pema ON pema.id_pemasok = trx.id_pemasok
				WHERE trx.id_pemasok <> 0
				GROUP BY trx.id_pembelian");
		}else{
			$this->db->where('id_ref_tipe_item', 1);
			$data['item'] = $this->db->get('ref_tipe_item');

			$data['metodebayar'] = $this->db->get('ref_metode_pembayaran');
			$data['bank'] 		 = $this->db->get('mst_bank');
			$data['pengeluaran'] = $this->db->query("SELECT *, 
				SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah) as total,
				trx.deskripsi as ket
				FROM trx_pembelian_persediaan trx
				LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx.id_pembelian
				LEFT JOIN ref_metode_pembayaran method ON method.id_metode_bayar = trx.id_metode_bayar
				LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
				LEFT JOIN mst_pemasok pema ON pema.id_pemasok = trx.id_pemasok
				WHERE trx.id_pemasok <> 0 and trx.id_unit = '".$_SESSION['IDUnit']."'
				GROUP BY trx.id_pembelian");
		}

		$content =	$this->load->view('transaksi/pengeluaran', $data, true);
		echo $content;
	}
	//YUDHA
	public function pembeliankredit()
	{
		$this->db->where('id_ref_tipe_item', 1);
		$data['item'] = $this->db->get('ref_tipe_item');

		$data['metodebayar'] = $this->db->get('ref_metode_pembayaran');
		$data['bank'] 		 = $this->db->get('mst_bank');
		$data['pengeluaran'] = $this->db->query("SELECT *, 
			SUM(trx_pembelian_persediaan_kredit_det.harga * trx_pembelian_persediaan_kredit_det.jumlah) as total,
			trx.deskripsi as ket
			FROM trx_pembelian_persediaan_kredit trx
			LEFT JOIN trx_pembelian_persediaan_kredit_det ON trx_pembelian_persediaan_kredit_det.id_pembelian = trx.id_pembelian
			LEFT JOIN ref_metode_pembayaran method ON method.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pemasok pema ON pema.id_pemasok = trx.id_pemasok
			WHERE trx.id_pemasok <> 0
			GROUP BY trx.id_pembelian");

		$content =	$this->load->view('transaksi/pengeluarankredit', $data, true);
		echo $content;
	}


	public function caridatapengeluaran()
	{
		$s_tglAwal 	= $this->input->post('s_tanggalawal');
		$s_tglAkhir 	= $this->input->post('s_tanggalakhir');
		$s_pelanggan 	= $this->input->post('s_namapemasok');
		$s_metode 	= $this->input->post('s_metodebayar');
		$s_notrans 	= $this->input->post('s_notrans');
		$s_bank 	= $this->input->post('s_bank');

		$whereTglAwal 	= ($s_tglAwal) ? 'trx.tanggal >= "'.date("Y-m-d", strtotime($s_tglAwal)).'" ' : '';
		$whereTglAkhir 	= ($s_tglAkhir) ? 'AND trx.tanggal <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'" ' : '';
		$wherePelanggan = ($s_pelanggan) ? 'AND pem.nama_pemasok LIKE "%'.$s_pelanggan.'%" ' : '';
		$whereMetode 	= ($s_metode != '-') ? 'AND trx.id_metode_bayar = '.$s_metode.' ' : '';
		$whereNotrans 	= ($s_notrans) ? 'AND trx.nomor_transaksi LIKE "%'.$s_notrans.'%" ' : '';
		$whereBank 		= ($s_bank != '-') ? 'AND trx.id_bank = '.$s_bank.' ' : '';

		$werewer = $whereTglAwal.$whereTglAkhir.$wherePelanggan.$whereMetode.$whereNotrans.$whereBank;
		$werewer = (substr($werewer, 0, 3) == 'AND') ? substr($werewer, 4) : $werewer;
		$idunit = ($_SESSION['IDUnit'] != 1) ? " AND trx.id_unit='".$_SESSION['IDUnit']."'" : "";
		$idunit2 = ($_SESSION['IDUnit'] != 1) ? " AND trx.id_unit='".$_SESSION['IDUnit']."'" : "";


		if(!$werewer)
		{
			$kweri 	= "SELECT trx.deskripsi as ket, * FROM trx_pembelian_persediaan trx, 
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pemasok pem ON pem.id_pemasok = trx.id_pemasok
			WHERE $werewer $idunit";
		}
		else
		{
			$kweri 	= "SELECT *, 
			SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah) as total,
			trx.deskripsi as ket
			FROM trx_pembelian_persediaan trx
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx.id_pembelian
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pemasok pem ON pem.id_pemasok = trx.id_pemasok
			WHERE $werewer $idunit2 group BY trx.id_pembelian";
		}

			// echo $kweri;

		$pembelian = $this->db->query($kweri);

		if($pembelian->num_rows() > 0)
		{
			foreach($pembelian->result() as $pem => $belian)
			{
				$data['id_pembelian'] 	= $belian->id_pembelian;
				$data['tanggal'] 		= date("d-m-Y", strtotime($belian->tanggal));
				$data['nama_pemasok'] 		= $belian->nama_pemasok;
				$data['nomor_transaksi'] 	= $belian->nomor_transaksi;
				$data['nama_metode_bayar'] 	= $belian->nama_metode_bayar;
				$data['nama_bank'] 			= $belian->nama_bank;
				$data['ket'] 			= $belian->ket;
				$data['total'] 			= 	number_format($belian->total);


				$jojon['pel'][] = $data;
			}

			$jojon['flag'] 	= true;
		}
		else
		{
			$jojon['flag'] 	= false;
		}
		$jojon['kopong'] 	= false;

		echo json_encode($jojon);

	}

	public function tambahpengeluaran()
	{
		$data['setting'] = $this->db->query("SELECT * FROM setup_general");
		$data['metodebayar'] = $this->db->get("ref_metode_pembayaran");
		$data['bank'] = $this->db->get("mst_bank");

		$data['pemasok'] = $this->db->get("mst_pemasok");
		$data['unit'] 	= $this->db->query("SELECT * FROM mst_departemen");

		$content = $this->load->view("transaksi/tambah_pengeluaran",$data, true);

		echo $content;

	}

	public function tambahpengeluarankredit()
	{
		$data['metodebayar'] = $this->db->get("ref_metode_pembayaran");
		$data['bank'] = $this->db->get("mst_bank");

		$data['pemasok'] = $this->db->get("mst_pemasok");

		$content = $this->load->view("transaksi/tambah_pengeluaran_kredit",$data, true);

		echo $content;

	}


	public function getpemasok()
	{

		if($_SESSION['IDUnit'] != "1") 
		{
			$where = "WHERE mst_pemasok.id_unit = '".$_SESSION['IDUnit']."'";
			
		}
		else
		{
			$where = "";
		}
		
		$pemasok = $this->db->query("SELECT * FROM mst_pemasok ".$where);

		if($pemasok->num_rows() > 0)
		{
			foreach($pemasok->result() as $row)
			{
				$data['idpemasok'] = $row->id_pemasok;
				$data['namapemasok'] = $row->nama_pemasok;
				$data['alamatpemasok'] = $row->alamat_pemasok;

				$json['pemasok'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}
		echo json_encode($json);
	}

	public function getpemasokkredit()
	{
		$pemasok = $this->db->query("SELECT * FROM ref_link_piutang
			LEFT JOIN mst_pemasok ON mst_pemasok.id_pemasok = ref_link_piutang.id_kontak
			WHERE ref_link_piutang.tipe = 'bkk'");

		if($pemasok->num_rows() > 0)
		{
			foreach($pemasok->result() as $row)
			{
				$data['idpemasok'] = $row->id_pemasok;
				$data['namapemasok'] = $row->nama_pemasok;
				$data['alamatpemasok'] = $row->alamat_pemasok;

				$json['pemasok'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}
		echo json_encode($json);
	}

	public function simpandatapengeluaran()
	{
			// echo print_r($_POST); exit();
		$no=$this->input->post('notransaksi');
		$ceknomor = $this->db->query("SELECT nomor_transaksi FROM trx_pembelian_persediaan where trx_pembelian_persediaan.nomor_transaksi = '".$no."'");

		$this->form_validation->set_rules('idpemasok', 'pemasok', 'required'); 

		if (!$this->form_validation->run()) 

		{
			$json['notif']='<div class="alert alert-alert">Data gagal Disimpan, cek lagi inputan anda</div>';
		}	
		elseif ($ceknomor->num_rows() == 0) 
		{

			parse_str($this->input->post("serialize"), $a);

			$data['id_pemasok'] = $this->input->post('idpemasok');
			$data['tanggal'] 	= date("Y-m-d", strtotime($this->input->post('tgltransaksi')));
			$data['nomor_transaksi'] 	= $this->input->post('notransaksi');
			$data['deskripsi'] 	= $this->input->post('keterangan');
			$data['id_metode_bayar'] 	= $this->input->post('idmetodebayar');
			$data['id_bank'] 	= $this->input->post('idbank');
			$data['id_unit'] 	= $_SESSION['IDUnit'];

			$this->db->insert('trx_pembelian_persediaan', $data);
			$IDx 	= $this->db->insert_id();

			for($i = 0; $i < count($a['item']); $i++)
			{
				$child['id_pembelian'] = $IDx;
				$child['id_item'] = $a['item'][$i];
				$child['jumlah'] = $a['jmlitem'][$i];
				$child['harga'] = str_replace(',', '', $a['harga'][$i]);
				$child['potongan'] = str_replace(',', '', $a['potongan'][$i]);
				$child['memo'] = $a['memo'][$i];

				$sim=$this->db->insert("trx_pembelian_persediaan_det", $child);

			}
			$json['notif']=' <div class="alert alert-success alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong>Data Sudah Berhasil Disimpan</strong>
			</div>';

		}
		else
		{


			$json['notif']=' <div class="alert alert-warning alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			<strong>Nomor Transaksi tidak boleh sama</strong>
			</div>';

		}

		echo json_encode($json);
	}
	//YUDHA
	public function simpandatapengeluarankredit()
	{
			// echo print_r($_POST); exit();
		parse_str($this->input->post("serialize"), $a);

		$data['id_pemasok'] = $this->input->post('idpemasok');
		$data['tanggal'] 	= date("Y-m-d", strtotime($this->input->post('tgltransaksi')));
		$data['nomor_transaksi'] 	= $this->input->post('notransaksi');
		$data['deskripsi'] 	= $this->input->post('keterangan');
		$data['id_metode_bayar'] 	= $this->input->post('idmetodebayar');
		$data['id_bank'] 	= $this->input->post('idbank');

		$this->db->insert('trx_pembelian_persediaan_kredit', $data);
		$IDx 	= $this->db->insert_id();

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_pembelian'] = $IDx;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah'] = $a['jmlitem'][$i];
			$child['harga'] = str_replace(',', '', $a['harga'][$i]);
			$child['potongan'] = str_replace(',', '', $a['potongan'][$i]);
			$child['memo'] = $a['memo'][$i];

			$this->db->insert("trx_pembelian_persediaan_kredit_det", $child);
		}
	}


	function getitemlistbarang()
	{
		$where= ($_SESSION['IDUnit']!=1) ? ' and mst_kategori_item.id_unit= "'.$_SESSION['IDUnit'].'"'  : ''; 
		$item = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			LEFT JOIN ref_tipe_item ON ref_tipe_item.id_ref_tipe_item = mst_item.id_ref_tipe_item
			LEFT join mst_departemen ON mst_kategori_item.id_unit = mst_departemen.id_departemen
			WHERE ref_tipe_item.id_ref_tipe_item = 1 ".$where);

		if($item->num_rows() > 0)
		{
			foreach($item->result() as $row)
			{
				$data['iditem'] = $row->id_item;
				$data['namaitem'] = $row->nama_item;
				$data['hargaitem'] = number_format($row->harga_jual);
				$data['satuan'] = $row->satuan;
				$data['unit'] = $row->nama_departemen;
				$data['kategori'] = $row->nama_kategori;

				$json['item'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}

		echo json_encode($json);
	}

		// tyas mirasih
	public function getnumbertransaksipengeluaran()
	{
		$number = $this->db->query("SELECT nomor_transaksi as no_transaksi FROM trx_pembelian_persediaan trx
			WHERE trx.id_pemasok <> 0 and id_unit = '".$_SESSION['IDUnit']."'
			ORDER BY trx.id_pembelian DESC");

		$bulan = convertBulanToRomawi(date("m"));

		$lastNombor = "";

		$unit = $this->db->query("SELECT * FROM mst_departemen where id_departemen = '".$_SESSION['IDUnit']."' ");


		if($number->num_rows() == 0)
		{
			$data['unit'] = $unit->first_row()->nama_departemen;
			$data['bank'] = '-';
			$data['bulan'] = $bulan;
			$data['tahun'] = date("Y");
			$data['nourut'] = "0001";
			
			$result['notrans'] = $data;
			
			
			
		}
		else
		{
			$notransaksi = $number->first_row()->no_transaksi;
			$notrans = $notransaksi;
			$notrans = strrev($notrans);
			$notrans = substr($notrans, 0 ,4);
			$notrans = (int)strrev($notrans);
			$notrans = $notrans + 1;
			$notrans = STR_PAD($notrans, 4, "0", STR_PAD_LEFT);
			$notransaksi = $notrans;
			
			$data['unit'] = $unit->first_row()->nama_departemen;
			$data['bank'] = '-';
			$data['bulan'] = $bulan;
			$data['tahun'] = date("Y");
			$data['nourut'] = $notransaksi;
			
			$result['notrans'] = $data;
		}

		echo json_encode($result);
	}

	


	public function deletepengeluaran()
	{


		$idP 	= $this->input->post('idpengeluaran');

		$this->db->query("DELETE trx, det FROM trx_pembelian_persediaan trx
			LEFT JOIN trx_pembelian_persediaan_det det ON trx.id_pembelian = det.id_pembelian
			WHERE trx.id_pembelian = ".$idP." ");

		$data['flag'] 	= true;
		echo json_encode($data);
	}

	public function deletepengeluarankredit()
	{


		$idP 	= $this->input->post('idpengeluaran');

		$this->db->query("DELETE trx, det FROM trx_pembelian_persediaan_kredit trx
			LEFT JOIN trx_pembelian_persediaan_kredit_det det ON trx.id_pembelian = det.id_pembelian
			WHERE trx.id_pembelian = ".$idP." ");

		$data['flag'] 	= true;
		echo json_encode($data);
	}

	public function deletepengeluaranall()
	{
		$ni = $_GET['na'];

		if (isset($ni)) {
			foreach ($_POST['idpenjualan'] as $id) {
				$this->db->where("id_pembelian", $id);
				$this->db->delete("trx_pembelian_persediaan_kredit");
				$this->db->where("id_pembelian", $id);
				$this->db->delete("trx_pembelian_persediaan_kredit_det");
			}
		}else{
			foreach ($_POST['idpenjualan'] as $id) {
				$this->db->where("id_pembelian", $id);
				$this->db->delete("trx_pembelian_persediaan");
				$this->db->where("id_pembelian", $id);
				$this->db->delete("trx_pembelian_persediaan_det");
			}
		}

	}

	public function getdataeditpengeluaran()
	{
		$idP 	= $this->input->post('idpengeluaran');

		$data['pengeluaran'] 	= $this->db->query("SELECT trx.*, det.*, pema.nama_pemasok, item.nama_item,
			mst_departemen.*, mst_bank.*
			FROM trx_pembelian_persediaan trx
			LEFT JOIN trx_pembelian_persediaan_det det ON trx.id_pembelian = det.id_pembelian
			LEFT JOIN mst_pemasok pema ON pema.id_pemasok = trx.id_pemasok
			LEFT JOIN mst_item item ON item.id_item = det.id_item
			LEFT JOIN mst_departemen ON mst_departemen.id_departemen = trx.id_unit
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx.id_bank
			WHERE trx.id_pembelian = ".$idP." ");

		$data['metodebayar'] = $this->db->get("ref_metode_pembayaran");
		$data['bank'] = $this->db->get("mst_bank");
		$data['setting'] = $this->db->query("SELECT * FROM setup_general");

		$data['pemasok'] = $this->db->get("mst_pemasok");
		$data['unit'] 	= $this->db->query("SELECT * FROM mst_departemen");


		$content = $this->load->view("transaksi/edit_pengeluaran",$data, true);
		echo $content;
	}

	
	public function getdataeditpengeluarankredit()
	{
		$idP 	= $this->input->post('idpengeluaran');

		$data['pengeluaran'] 	= $this->db->query("SELECT trx.*, det.*, pema.nama_pemasok, item.nama_item
			FROM trx_pembelian_persediaan_kredit trx
			LEFT JOIN trx_pembelian_persediaan_kredit_det det ON trx.id_pembelian = det.id_pembelian
			LEFT JOIN mst_pemasok pema ON pema.id_pemasok = trx.id_pemasok
			LEFT JOIN mst_item item ON item.id_item = det.id_item
			WHERE trx.id_pembelian = ".$idP." ");

		$data['metodebayar'] = $this->db->get("ref_metode_pembayaran");
		$data['bank'] = $this->db->get("mst_bank");
		$data['pemasok'] = $this->db->get("mst_pemasok");

		$content = $this->load->view("transaksi/edit_pengeluaran_kredit",$data, true);
		echo $content;
	}


	public function simpandatapengeluaranubah()
	{
		parse_str($this->input->post("serialize"), $a);
		$idP 	= $this->input->post('id_pembelian');
		
		$transaksi = $this->input->post("namaunit")."/".$this->input->post("namabank")."/".$this->input->post("bulan")."/".$this->input->post("tahun")."/".$this->input->post("notransaksi");

		$data['id_pemasok'] = $this->input->post('idpemasok');
		$data['tanggal'] 	= date("Y-m-d", strtotime($this->input->post('tgltransaksi')));
		$data['nomor_transaksi'] 	= $transaksi;
		$data['deskripsi'] 	= $this->input->post('keterangan');
		$data['id_metode_bayar'] 	= $this->input->post('idmetodebayar');
		$data['id_bank'] 	= $this->input->post('idbank');

		$this->db->where('id_pembelian', $idP);
		$this->db->update('trx_pembelian_persediaan', $data);

			// delete det e sek...
		$this->db->where('id_pembelian', $idP);
		$this->db->delete('trx_pembelian_persediaan_det');

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_pembelian'] = $idP;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah'] = $a['jmlitem'][$i];
			$child['memo'] = $a['memo'][$i];
			$child['harga'] = str_replace(',', '', $a['harga'][$i]);
			$child['potongan'] = str_replace(',', '', $a['potongan'][$i]);

			$this->db->insert("trx_pembelian_persediaan_det", $child);
		}
	}

	//YUDHA
	public function simpandatapengeluaranubahkredit()
	{
		parse_str($this->input->post("serialize"), $a);
		$idP 	= $this->input->post('id_pembelian');

		$data['id_pemasok'] = $this->input->post('idpemasok');
		$data['tanggal'] 	= date("Y-m-d", strtotime($this->input->post('tgltransaksi')));
		$data['nomor_transaksi'] 	= $this->input->post('notransaksi');
		$data['deskripsi'] 	= $this->input->post('keterangan');
		$data['id_metode_bayar'] 	= $this->input->post('idmetodebayar');
		$data['id_bank'] 	= $this->input->post('idbank');

		$this->db->where('id_pembelian', $idP);
		$this->db->update('trx_pembelian_persediaan_kredit', $data);

			// delete det e sek...
		$this->db->where('id_pembelian', $idP);
		$this->db->delete('trx_pembelian_persediaan_kredit_det');

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_pembelian'] = $idP;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah'] = $a['jmlitem'][$i];
			$child['memo'] = $a['memo'][$i];
			$child['harga'] = str_replace(',', '', $a['harga'][$i]);
			$child['potongan'] = str_replace(',', '', $a['potongan'][$i]);

			$this->db->insert("trx_pembelian_persediaan_kredit_det", $child);
		}
	}

		// END pembelian pengeluaran


	function biaya()
	{

		$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
		$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

		$data['biaya'] = $this->db->query("SELECT trx_pembelian_persediaan.*, 
			ref_metode_pembayaran.nama_metode_bayar,
			mst_bank.nama_bank,
			SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_pembelian_persediaan.id_metode_bayar
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_pembelian_persediaan.id_bank
			WHERE trx_pembelian_persediaan.id_pemasok = 0
			GROUP BY trx_pembelian_persediaan.id_pembelian");

		$content = $this->load->view("transaksi/biaya", $data, true);

		echo $content;
	}

	function tambahbiaya()
	{
		$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
		$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

		$content = $this->load->view("transaksi/tambah_biaya", $data, true);

		echo $content;
	}

		// tyas mirasih
	public function getnumberbiaya()
	{
		$number = $this->db->query("SELECT nomor_transaksi FROM trx_pembelian_persediaan
			WHERE trx_pembelian_persediaan.id_pemasok = 0
			ORDER BY trx_pembelian_persediaan.id_pembelian DESC");

		$bulan = convertBulanToRomawi(date("m"));

		$lastNombor = "";

		if($number->num_rows() == 0)
		{
			$notransaksi = "kw.biaya/".$bulan."/".date("Y")."/0001";
		}
		elseif(!strpos($lastNombor, "kw.biaya/")){
			$lastNombor = $number->first_row()->nomor_transaksi;
			$notransaksi = GetNextNo($lastNombor);
		}
		else
		{
			$notransaksi = $number->first_row()->nomor_transaksi;
			$notrans = $notransaksi;
			$notrans = strrev($notrans);
			$notrans = substr($notrans, 0 ,4);
			$notrans = (int)strrev($notrans);
			$notrans = $notrans + 1;
			$notrans = STR_PAD($notrans, 4, "0", STR_PAD_LEFT);
			$notransaksi = "kw.biaya/".$bulan."/".date("Y")."/".$notrans;
		}

		echo $notransaksi;
	}


	function simpanbiaya()
	{
		parse_str($this->input->post("serialize"), $a);

		$data['tanggal'] 	= date("Y-m-d", strtotime($this->input->post('tgltransaksi')));
		$data['nomor_transaksi'] 	= $this->input->post('notransaksi');
		$data['deskripsi'] 	= $this->input->post('keterangan');
		$data['id_metode_bayar'] 	= $this->input->post('idmetodebayar');
		$data['id_bank'] 	= $this->input->post('idbank');

		$this->db->insert('trx_pembelian_persediaan', $data);
		$IDx 	= $this->db->insert_id();

		for($i = 0; $i < count($a['iditem']); $i++)
		{
			$child['id_pembelian'] = $IDx;
			$child['id_item'] = $a['iditem'][$i];
			$child['jumlah'] = 1;
			$child['harga'] = str_replace(',', '', $a['harga'][$i]);
			$child['memo'] = $a['memo'][$i];

			$this->db->insert("trx_pembelian_persediaan_det", $child);
		}
	}

	function editbiaya()
	{

		$idbayar = $this->input->post("idbiaya");

		$data['metodebayar'] = $this->db->query("SELECT * FROM ref_metode_pembayaran");
		$data['bank'] = $this->db->query("SELECT * FROM mst_bank");

		$data['biaya'] = $this->db->query("SELECT mst_item.nama_item, trx_pembelian_persediaan_det.id_item,trx_pembelian_persediaan_det.jumlah,trx_pembelian_persediaan_det.harga,trx_pembelian_persediaan_det.memo,trx_pembelian_persediaan.deskripsi, trx_pembelian_persediaan. *  FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
			LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_pembelian_persediaan.id_metode_bayar
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_pembelian_persediaan.id_bank
			LEFT JOIN mst_item ON mst_item.id_item = trx_pembelian_persediaan_det.id_item
			WHERE trx_pembelian_persediaan.id_pembelian = '".$idbayar."'");


		$content = $this->load->view("transaksi/edit_biaya", $data, true);

		echo $content;
	}

	function simpaneditdatabiaya()
	{

		parse_str($this->input->post("serialize"), $a);

		$idbiaya = $this->input->post("idbiaya");

		$data['tanggal'] 	= date("Y-m-d", strtotime($this->input->post('tgltransaksi')));
		$data['nomor_transaksi'] 	= $this->input->post('notransaksi');
		$data['deskripsi'] 	= $this->input->post('keterangan');
		$data['id_metode_bayar'] 	= $this->input->post('idmetodebayar');
		$data['id_bank'] 	= $this->input->post('idbank');

		$this->db->where("id_pembelian", $idbiaya);
		$this->db->update('trx_pembelian_persediaan', $data);

		$this->db->where("id_pembelian", $idbiaya);
		$this->db->delete("trx_pembelian_persediaan_det");


		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_pembelian'] = $idbiaya;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah'] = 1;
			$child['harga'] = str_replace(',', '', $a['harga'][$i]);
			$child['memo'] = $a['memo'][$i];

			$this->db->insert("trx_pembelian_persediaan_det", $child);
		}
	}
	function caridatamutasi()
	{
		$tanggalawal = $this->input->post("s_tanggalawal");
		$tanggalakhir = $this->input->post("s_tanggalakhir");
		$nmbukti = $this->input->post("s_nomorbukti");

		$wheres[]= "";
		if($tanggalawal != "" && $tanggalakhir != "")
		{
			$wheres[] .= " (trx_ju.tanggal >= '".date("Y-m-d", strtotime($tanggalawal))."' AND trx_ju.tanggal <= '".date("Y-m-d", strtotime($tanggalakhir))."')";
		}

		if($nmbukti != "")
		{
			$wheres[] .= " trx_ju.no_bukti LIKE '%".$nmbukti."%'";
		}
		$where=implode('AND', $wheres);
		$query="SELECT *,trx_ju.uraian AS uraian from trx_ju LEFT JOIN trx_judet ON trx_ju.id_ju=trx_judet.id_ju WHERE trx_ju.id_sumber_trans = (SELECT id_sumber_trans FROM ref_sumber_trans WHERE ref_sumber_trans.kode='MTS') AND trx_judet.debet > 0 ".$where;
		$sql=$this->db->query($query);

		if ($sql->num_rows>0) {
			foreach ($sql->result() as $row) {
				$data['id'] = $row->id_ju;
				$data['tanggal'] = date("d-m-Y", strtotime($row->tanggal));
				$data['nm'] = $row->no_bukti;
				$data['nmtok'] = $row->nomor;
				$data['uraian'] = $row->uraian;
				$data['total'] = number_format($row->debet);

				$json['data'][] = $data;
			}
			$json['flag']=true;	
		}else{
			$json['flag']=false;	
		}

		//echo $query;
		echo json_encode($json);
	}


	function caridatabiaya()
	{

			// tanggalawal=&&tanggalakhir=&&notrans=&&metodebayar=2&&bank=-
		$tanggalawal = $this->input->post("s_tanggalawal");
		$tanggalakhir = $this->input->post("s_tanggalakhir");
		$metodebayar = $this->input->post("s_metodebayar");
		$notransaksi = $this->input->post("s_notrans");
		$bank = $this->input->post("s_bank");

		if($tanggalawal != "" && $tanggalakhir != "")
		{
			$wheres[] = " (trx_pembelian_persediaan.tanggal >= '".date("Y-m-d", strtotime($tanggalawal))."' AND trx_pembelian_persediaan.tanggal <= '".date("Y-m-d", strtotime($tanggalakhir))."')";
		}

		if($metodebayar != "-")
		{
			$wheres[] = " trx_pembelian_persediaan.id_metode_bayar = '".$metodebayar."'";
		}

		if($notransaksi != "")
		{
			$wheres[] = " trx_pembelian_persediaan.nomor_transaksi LIKE '%".$notransaksi."%'";
		}

		if($bank != "-")
		{
			$wheres[] = " trx_pembelian_persediaan.id_bank = '".$bank."'";
		}
		$where = (isset($wheres)) ? implode(" AND ", $wheres) : "";
		$extend = (isset($wheres)) ? " AND " : "";

		$dataquery = $this->db->query("SELECT trx_pembelian_persediaan.*, 
			ref_metode_pembayaran.nama_metode_bayar, mst_bank.nama_bank,
			SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_pembelian_persediaan.id_metode_bayar
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_pembelian_persediaan.id_bank
			WHERE trx_pembelian_persediaan.id_pemasok = 0 
			$extend
			$where GROUP BY trx_pembelian_persediaan.id_pembelian");

		if($dataquery->num_rows() > 0)
		{
			foreach($dataquery->result() as $row)
			{
				$data['idbiaya'] = $row->id_pembelian;
				$data['tanggal'] = date("d-m-Y", strtotime($row->tanggal));
				$data['notrans'] = $row->nomor_transaksi;
				$data['metodebayar'] = $row->nama_metode_bayar;
				$data['bank'] = $row->nama_bank;
				$data['total'] = number_format($row->total);
				$data['keterangan'] = $row->deskripsi;

				$json['data'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}

		echo json_encode($json);
	}

	function deletedatabiaya()
	{
		$idbiaya = $this->input->post("idbiaya");

		$this->db->query("DELETE trx, det FROM trx_pembelian_persediaan trx LEFT JOIN trx_pembelian_persediaan_det det ON trx.id_pembelian = det.id_pembelian WHERE trx.id_pembelian = ".$idbiaya." ");
		$data['flag'] = true;
		echo json_encode($data);

	}
	function deletebiayaall()
	{
		foreach ($_POST['idpenjualan'] as $id) {
			$this->db->where("id_pembelian", $id);
			$this->db->delete("trx_pembelian_persediaan");
			$this->db->where("id_pembelian", $id);
			$this->db->delete("trx_pembelian_persediaan_det");
		}
	}

	function cetaksimpanpenerimaanlain()
	{
		parse_str($this->input->post("serialize"), $a);

		$parent['no_transaksi'] = $this->input->post("notransaksi");
		$parent['tanggal_penjualan'] = date("Y-m-d", strtotime($this->input->post("tgltransaksi")));
		$parent['id_metode_bayar'] = $this->input->post("idmetodebayar");
		$parent['id_bank'] = $this->input->post("idbank");
		$parent['keterangan'] = $this->input->post("keterangan");

		$this->db->insert("trx_penjualan", $parent);
		$idpenerimaan = $this->db->insert_id();

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_penjualan'] = $idpenerimaan;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah_item'] = 1;
			$child['harga'] = str_replace(",","",$a['harga'][$i]);
			$child['memo'] = $a['memo'][$i];

			$this->db->insert("trx_penjualan_det", $child);
		}

		$json['url'] = site_url("transaksi/printkuitansi/".$idpenerimaan);

		echo json_encode($json);
	}

	function cetaksimpanpemasukan()
	{
			//echo "<pre>";print_r($_POST);"</pre>";

		parse_str($this->input->post("serialize"), $a);
		
		$transaksi = $this->input->post("namaunit")."/".$this->input->post("namabank")."/".$this->input->post("bulan")."/".$this->input->post("tahun")."/".$this->input->post("notransaksi");

		$parent['id_pelanggan'] = $this->input->post("idpelanggan");
		$parent['no_transaksi'] = $transaksi;
		$parent['tanggal_penjualan'] = date("Y-m-d", strtotime($this->input->post("tgltransaksi")));
		$parent['id_metode_bayar'] = $this->input->post("idmetodebayar");
		$parent['id_bank'] = $this->input->post("idbank");
		$parent['keterangan'] = $this->input->post("keterangan");
		$parent['id_unit'] = $_SESSION['IDUnit'];

		$this->db->insert("trx_penjualan", $parent);
		$idpenjualan = $this->db->insert_id();


		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_penjualan'] = $idpenjualan;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah_item'] = $a['jmlitem'][$i];
			$child['harga'] = str_replace(",","",$a['harga'][$i]);
			$child['potongan'] = str_replace(",","",$a['potongan'][$i]);
			$child['is_diskon'] = ($a['potongan'][$i] > 0) ? 1 : 0;
			$child['memo'] = str_replace(",","",$a['memo'][$i]);

			$this->db->insert("trx_penjualan_det", $child);
		}

		$json['url'] = site_url("transaksi/printkuitansi/".$idpenjualan);

		echo json_encode($json);
	}

	function printkuitansi($idpenjualan)
	{

		$data['result'] = $this->db->query("SELECT *, COALESCE(SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item)) as total 
			FROM trx_penjualan
			LEFT JOIN trx_penjualan_det ON trx_penjualan.id_penjualan = trx_penjualan_det.id_penjualan
			LEFT JOIN mst_pelanggan ON mst_pelanggan.id_pelanggan = trx_penjualan.id_pelanggan
			WHERE trx_penjualan.id_penjualan = '".$idpenjualan."'");

		$content = $this->load->view("printkuitansi_bkm",$data, true);

		echo $content;
	}

	function printkuitansikredit($idpenjualan)
	{

		$data['result'] = $this->db->query("SELECT *, COALESCE(SUM(trx_penjualan_kredit_det.harga * trx_penjualan_kredit_det.jumlah_item)) as total 
			FROM trx_penjualan_kredit
			LEFT JOIN trx_penjualan_kredit_det ON trx_penjualan_kredit.id_penjualan = trx_penjualan_kredit_det.id_penjualan
			LEFT JOIN mst_pelanggan ON mst_pelanggan.id_pelanggan = trx_penjualan_kredit.id_pelanggan
			WHERE trx_penjualan_kredit.id_penjualan = '".$idpenjualan."'");

		$content = $this->load->view("printkuitansi_bkm",$data, true);

		echo $content;
	}

	function printkuitansi2($idpenjualan)
	{

		$data['result'] = $this->db->query("SELECT *, COALESCE(SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah)) as total 
			FROM trx_penjualan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan.id_pembelian = trx_pembelian_persediaan_det.id_pembelian
			LEFT JOIN mst_pemasok ON mst_pemasok.id_pemasok = trx_pembelian_persediaan.id_pemasok
			WHERE trx_pembelian_persediaan.id_pembelian = '".$idpenjualan."'");

		$content = $this->load->view("printkuitansi_bkm",$data, true);

		echo $content;
	}


	function editcetaksimpanpemasukan()
	{

		parse_str($this->input->post("serialize"), $a);

		$transaksi = $this->input->post("namaunit")."/".$this->input->post("namabank")."/".$this->input->post("bulan")."/".$this->input->post("tahun")."/".$this->input->post("notransaksi");
		
		$parent['id_pelanggan'] = $this->input->post("idpelanggan");
		$parent['no_transaksi'] = $transaksi;
		$parent['tanggal_penjualan'] = date("Y-m-d", strtotime($this->input->post("tgltransaksi")));
		$parent['id_metode_bayar'] = $this->input->post("idmetodebayar");
		$parent['id_bank'] = $this->input->post("idbank");
		$parent['keterangan'] = $this->input->post("keterangan");

		$this->db->where("id_penjualan", $this->input->post("idpenjualan"));
		$this->db->update("trx_penjualan", $parent);


		$this->db->where("id_penjualan", $this->input->post("idpenjualan"));
		$this->db->delete("trx_penjualan_det");

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_penjualan'] = $this->input->post("idpenjualan");
			$child['id_item'] = $a['item'][$i];
			$child['jumlah_item'] = $a['jmlitem'][$i];
			$child['harga'] = str_replace(",","",$a['harga'][$i]);
			$child['potongan'] = str_replace(",","",$a['potongan'][$i]);
			$child['is_diskon'] = ($a['potongan'][$i] > 0) ? 1 : 0;
			$child['memo'] = str_replace(",","",$a['memo'][$i]);

			$this->db->insert("trx_penjualan_det", $child);
		}

		$json['url'] = site_url("transaksi/printkuitansi/".$this->input->post("idpenjualan"));


		echo json_encode($json);
	}


	function editcetaksimpanpemasukanlain()
	{

		parse_str($this->input->post("serialize"), $a);

		$parent['no_transaksi'] = $this->input->post("notransaksi");
		$parent['tanggal_penjualan'] = date("Y-m-d", strtotime($this->input->post("tgltransaksi")));
		$parent['id_metode_bayar'] = $this->input->post("idmetodebayar");
		$parent['id_bank'] = $this->input->post("idbank");
		$parent['keterangan'] = $this->input->post("keterangan");

		$this->db->where("id_penjualan", $this->input->post("idpenjualan"));
		$this->db->update("trx_penjualan", $parent);


		$this->db->where("id_penjualan", $this->input->post("idpenjualan"));
		$this->db->delete("trx_penjualan_det");

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_penjualan'] = $this->input->post("idpenjualan");
			$child['jumlah_item'] = 1;
			$child['harga'] = str_replace(",","",$a['harga'][$i]);
			$child['potongan'] = 0;
			$child['is_diskon'] = 0;
			$child['memo'] = str_replace(",","",$a['memo'][$i]);

			$this->db->insert("trx_penjualan_det", $child);
		}

		$json['url'] = site_url("transaksi/printkuitansi/".$this->input->post("idpenjualan"));


		echo json_encode($json);
	}

	public function simpandancetak()
	{
			// echo print_r($_POST); exit();
		$this->load->library("fpdf/fpdf");
		parse_str($this->input->post("serialize"), $a);

		$data['id_pemasok'] = $this->input->post('idpemasok');
		$data['tanggal'] 	= date("Y-m-d", strtotime($this->input->post('tgltransaksi')));
		$data['nomor_transaksi'] 	= $this->input->post('notransaksi');
		$data['deskripsi'] 	= $this->input->post('keterangan');
		$data['id_metode_bayar'] 	= $this->input->post('idmetodebayar');
		$data['id_bank'] 	= $this->input->post('idbank');
		$data['id_unit'] = $_SESSION['IDUnit'];

		$this->db->insert('trx_pembelian_persediaan', $data);
		$IDx 	= $this->db->insert_id();

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_pembelian'] = $IDx;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah'] = $a['jmlitem'][$i];
			$child['harga'] = str_replace(',', '', $a['harga'][$i]);
			$child['potongan'] = str_replace(',', '', $a['potongan'][$i]);
			$child['memo'] = $a['memo'][$i];

			$this->db->insert("trx_pembelian_persediaan_det", $child);
		}
		$json['url']=site_url('transaksi/printkuitansiBKK/'.$IDx);
		echo json_encode($json);

	}

	function printkuitansiBKK($idpembelian)
	{	
		$data['perush'] = $this->db->query("SELECT * FROM sys_perusahaan 
			left join mst_kabupaten on mst_kabupaten.id_kabupaten=sys_perusahaan.id_kabupaten");

		$data['m'] = $this->db->query("SELECT *,COALESCE(SUM(trx_pembelian_persediaan_det.jumlah*trx_pembelian_persediaan_det.harga-IFNULL(trx_pembelian_persediaan_det.potongan,0))) AS total FROM  trx_pembelian_persediaan LEFT JOIN mst_pemasok on trx_pembelian_persediaan.id_pemasok = mst_pemasok.id_pemasok LEFT JOIN trx_pembelian_persediaan_det on trx_pembelian_persediaan.id_pembelian=trx_pembelian_persediaan_det.id_pembelian WHERE trx_pembelian_persediaan_det.id_pembelian='".$idpembelian."' ");

		$content=$this->load->view('printkuitansi_BKK', $data, TRUE);
		echo $content;
	}


	public function simpancetakpengeluaranubah()
	{
		parse_str($this->input->post("serialize"), $a);
		$idP 	= $this->input->post('id_pembelian');
		
		$transaksi = $this->input->post("namaunit")."/".$this->input->post("namabank")."/".$this->input->post("bulan")."/".$this->input->post("tahun")."/".$this->input->post("notransaksi");

		$data['id_pemasok'] = $this->input->post('idpemasok');
		$data['tanggal'] 	= date("Y-m-d", strtotime($this->input->post('tgltransaksi')));
		$data['nomor_transaksi'] 	= $transaksi;
		$data['deskripsi'] 	= $this->input->post('keterangan');
		$data['id_metode_bayar'] 	= $this->input->post('idmetodebayar');
		$data['id_bank'] 	= $this->input->post('idbank');

		$this->db->where('id_pembelian', $idP);
		$this->db->update('trx_pembelian_persediaan', $data);

			// delete det e sek...
		$this->db->where('id_pembelian', $idP);
		$this->db->delete('trx_pembelian_persediaan_det');

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_pembelian'] = $idP;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah'] = $a['jmlitem'][$i];
			$child['memo'] = $a['memo'][$i];
			$child['harga'] = str_replace(',', '', $a['harga'][$i]);
			$child['potongan'] = str_replace(',', '', $a['potongan'][$i]);

			$this->db->insert("trx_pembelian_persediaan_det", $child);
		}
		$json['url']=site_url('transaksi/printkuitansiBKK/'.$idP);
		echo json_encode($json);
	}


	function simpancetakbiaya()
	{
		parse_str($this->input->post("serialize"), $a);


		$data['tanggal'] 	= date("Y-m-d", strtotime($this->input->post('tgltransaksi')));
		$data['nomor_transaksi'] 	= $this->input->post('notransaksi');
		$data['deskripsi'] 	= $this->input->post('keterangan');
		$data['id_metode_bayar'] 	= $this->input->post('idmetodebayar');
		$data['id_bank'] 	= $this->input->post('idbank');

		$this->db->insert('trx_pembelian_persediaan', $data);
		$IDx 	= $this->db->insert_id();

		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_pembelian'] = $IDx;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah'] = 1;
			$child['harga'] = str_replace(',', '', $a['harga'][$i]);
			$child['memo'] = $a['memo'][$i];

			$this->db->insert("trx_pembelian_persediaan_det", $child);
		}
		$json['url']=site_url('transaksi/printkuitansiBKK/'.$IDx);
		echo json_encode($json);
	}

	function simpancetakbiayaubah()
	{

		parse_str($this->input->post("serialize"), $a);

		$idbiaya = $this->input->post("idbiaya");

		$data['tanggal'] 	= date("Y-m-d", strtotime($this->input->post('tgltransaksi')));
		$data['nomor_transaksi'] 	= $this->input->post('notransaksi');
		$data['deskripsi'] 	= $this->input->post('keterangan');
		$data['id_metode_bayar'] 	= $this->input->post('idmetodebayar');
		$data['id_bank'] 	= $this->input->post('idbank');

		$this->db->where("id_pembelian", $idbiaya);
		$this->db->update('trx_pembelian_persediaan', $data);

		$this->db->where("id_pembelian", $idbiaya);
		$this->db->delete("trx_pembelian_persediaan_det");


		for($i = 0; $i < count($a['item']); $i++)
		{
			$child['id_pembelian'] = $idbiaya;
			$child['id_item'] = $a['item'][$i];
			$child['jumlah'] = 1;
			$child['harga'] = str_replace(',', '', $a['harga'][$i]);
			$child['memo'] = $a['memo'][$i];

			$this->db->insert("trx_pembelian_persediaan_det", $child);
		}
		$json['url']=site_url('transaksi/printkuitansiBKK/'.$idbiaya);
		echo json_encode($json);
	}

	function printrekappenjualankredit()
	{
		// s_tanggalawal=08-03-2017&s_namapelanggan=pelanga&s_metodebayar=1&s_tanggalakhir=11-03-2017&s_notrans=12&s_bank=1
		$s_tglAwal 	= $_GET['tanggalawal'];
		$s_tglAkhir 	= $_GET['tanggalakhir'];
		$s_pelanggan 	= $_GET['pelanggan'];
		$s_metode 	= $_GET['metodebayar'];
		$s_notrans 	= $_GET['notrans'];
		$s_bank 	=  $_GET['bank'];


		$whereTglAwal 	= ($s_tglAwal) ? 'AND (trx.tanggal_penjualan >= "'.date("Y-m-d", strtotime($s_tglAwal)).'" AND trx.tanggal_penjualan <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'") ' : '';
		//$whereTglAkhir 	= ($s_tglAkhir) ? 'AND trx.tanggal_penjualan <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'" ' : '';
		$wherePelanggan = ($s_pelanggan != "") ? 'AND pel.nama_pelanggan LIKE "%'.$s_pelanggan.'%" ' : '';
		$whereMetode 	= ($s_metode != "-") ? 'AND trx.id_metode_bayar = '.$s_metode.' ' : '';
		$whereNotrans 	= ($s_notrans != "" ) ? 'AND trx.no_transaksi LIKE "%'.$s_notrans.'%" ' : '';
		$whereBank 		= ($s_bank != "-") ? 'AND trx.id_bank = '.$s_bank.' ' : '';

		$werewer = $whereTglAwal.$wherePelanggan.$whereMetode.$whereNotrans.$whereBank;
		$werewer = (substr($werewer, 0, 3) == 'AND') ? substr($werewer, 4) : $werewer;

		if(!$werewer)
		{
			$kweri 	= "SELECT *, SUM(trx_penjualan_kredit_det.harga * trx_penjualan_kredit_det.jumlah_item) as total FROM trx_penjualan_kredit trx
			LEFT JOIN trx_penjualan_kredit_det ON trx_penjualan_kredit_det.id_penjualan = trx.id_penjualan
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pelanggan pel ON pel.id_pelanggan = trx.id_pelanggan
			WHERE trx.id_pelanggan <> 0 and id_unit = '".$_SESSION['IDUnit']."'
			GROUP BY trx.id_penjualan";
		}
		else
		{
			$kweri 	= "SELECT *, SUM(trx_penjualan_kredit_det.harga * trx_penjualan_kredit_det.jumlah_item) as total FROM trx_penjualan_kredit trx
			LEFT JOIN trx_penjualan_kredit_det ON trx_penjualan_kredit_det.id_penjualan = trx.id_penjualan
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pelanggan pel ON pel.id_pelanggan = trx.id_pelanggan
			WHERE trx.id_pelanggan <> 0 and $werewer and id_unit = '".$_SESSION['IDUnit']."'
			GROUP BY trx.id_penjualan";
		}

			// echo $kweri;

		$penjualan = $this->db->query($kweri);

		if($penjualan->num_rows() > 0)
		{
			foreach($penjualan->result() as $pen => $jualan)
			{
				$data['id_penjualan'] = $jualan->id_penjualan;
				$data['tanggal_penjualan'] 	= date("d-m-Y", strtotime($jualan->tanggal_penjualan));
				$data['nama_pelanggan'] 	= $jualan->nama_pelanggan;
				$data['no_transaksi'] 		= $jualan->no_transaksi;
				$data['nama_metode_bayar'] 	= $jualan->nama_metode_bayar;
				$data['nama_bank'] 			= $jualan->nama_bank;
				$data['keterangan'] 		= $jualan->keterangan;
				$data['total'] 				= $jualan->total;

				$jojon['data'][] = $data;
			}

			$jojon['flag'] 	= true;
		}
		else
		{
			$jojon['flag'] 	= false;
		}


		$content = $this->load->view("printrekap_bkm", $jojon, true);

		echo $content;

	}

	function printrekappenjualan()
	{
		// s_tanggalawal=08-03-2017&s_namapelanggan=pelanga&s_metodebayar=1&s_tanggalakhir=11-03-2017&s_notrans=12&s_bank=1
		$s_tglAwal 	= $_GET['tanggalawal'];
		$s_tglAkhir 	= $_GET['tanggalakhir'];
		$s_pelanggan 	= $_GET['pelanggan'];
		$s_metode 	= $_GET['metodebayar'];
		$s_notrans 	= $_GET['notrans'];
		$s_bank 	=  $_GET['bank'];


		$whereTglAwal 	= ($s_tglAwal) ? 'AND (trx.tanggal_penjualan >= "'.date("Y-m-d", strtotime($s_tglAwal)).'" AND trx.tanggal_penjualan <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'") ' : '';
			//$whereTglAkhir 	= ($s_tglAkhir) ? 'AND trx.tanggal_penjualan <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'" ' : '';
		$wherePelanggan = ($s_pelanggan != "") ? 'AND pel.nama_pelanggan LIKE "%'.$s_pelanggan.'%" ' : '';
		$whereMetode 	= ($s_metode != "-") ? 'AND trx.id_metode_bayar = '.$s_metode.' ' : '';
		$whereNotrans 	= ($s_notrans != "" ) ? 'AND trx.no_transaksi LIKE "%'.$s_notrans.'%" ' : '';
		$whereBank 		= ($s_bank != "-") ? 'AND trx.id_bank = '.$s_bank.' ' : '';

		$werewer = $whereTglAwal.$wherePelanggan.$whereMetode.$whereNotrans.$whereBank;
		$werewer = (substr($werewer, 0, 3) == 'AND') ? substr($werewer, 4) : $werewer;
		
		$idunit = ($_SESSION['IDUnit'] != 1 ) ? "and trx.id_unit = '".$_SESSION['IDUnit']."'" : "";

		if(!$werewer)
		{
			$kweri 	= "SELECT *, SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item) as total FROM trx_penjualan trx
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx.id_penjualan
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pelanggan pel ON pel.id_pelanggan = trx.id_pelanggan
			WHERE trx.id_pelanggan <> 0  $idunit
			GROUP BY trx.id_penjualan";
		}
		else
		{
			$kweri 	= "SELECT *, SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item) as total FROM trx_penjualan trx
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx.id_penjualan
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pelanggan pel ON pel.id_pelanggan = trx.id_pelanggan
			WHERE trx.id_pelanggan <> 0 and $werewer $idunit
			GROUP BY trx.id_penjualan";
		}

		$penjualan = $this->db->query($kweri);

		if($penjualan->num_rows() > 0)
		{
			foreach($penjualan->result() as $pen => $jualan)
			{
				$data['id_penjualan'] = $jualan->id_penjualan;
				$data['tanggal_penjualan'] 	= date("d-m-Y", strtotime($jualan->tanggal_penjualan));
				$data['nama_pelanggan'] 	= $jualan->nama_pelanggan;
				$data['no_transaksi'] 		= $jualan->no_transaksi;
				$data['nama_metode_bayar'] 	= $jualan->nama_metode_bayar;
				$data['nama_bank'] 			= $jualan->nama_bank;
				$data['keterangan'] 		= $jualan->keterangan;
				$data['total'] 				= $jualan->total;

				$result[] = $data;
			}
			
			
			$jojon['data'] = $result;

			$jojon['flag'] 	= true;
		}
		else
		{
			$jojon['flag'] 	= false;
		}


		$content = $this->load->view("printrekap_bkm", $jojon, true);

		echo $content;

	}

	function printrekappenerimaanlain()
	{
		// s_tanggalawal=08-03-2017&s_namapelanggan=pelanga&s_metodebayar=1&s_tanggalakhir=11-03-2017&s_notrans=12&s_bank=1
		$s_tglAwal 	= $_GET['tanggalawal'];
		$s_tglAkhir 	= $_GET['tanggalakhir'];
		$s_pelanggan 	= $_GET['pelanggan'];
		$s_metode 	= $_GET['metodebayar'];
		$s_notrans 	= $_GET['notrans'];
		$s_bank 	=  $_GET['bank'];


		$whereTglAwal 	= ($s_tglAwal) ? 'AND (trx.tanggal_penjualan >= "'.date("Y-m-d", strtotime($s_tglAwal)).'" AND trx.tanggal_penjualan <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'") ' : '';
			//$whereTglAkhir 	= ($s_tglAkhir) ? 'AND trx.tanggal_penjualan <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'" ' : '';
		$wherePelanggan = ($s_pelanggan != "") ? 'AND pel.nama_pelanggan LIKE "%'.$s_pelanggan.'%" ' : '';
		$whereMetode 	= ($s_metode != "-") ? 'AND trx.id_metode_bayar = '.$s_metode.' ' : '';
		$whereNotrans 	= ($s_notrans != "" ) ? 'AND trx.no_transaksi LIKE "%'.$s_notrans.'%" ' : '';
		$whereBank 		= ($s_bank != "-") ? 'AND trx.id_bank = '.$s_bank.' ' : '';

		$werewer = $whereTglAwal.$wherePelanggan.$whereMetode.$whereNotrans.$whereBank;
		$werewer = (substr($werewer, 0, 3) == 'AND') ? substr($werewer, 4) : $werewer;

		if(!$werewer)
		{
			$kweri 	= "SELECT *, SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item) as total FROM trx_penjualan trx
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx.id_penjualan
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pelanggan pel ON pel.id_pelanggan = trx.id_pelanggan
			WHERE trx.id_pelanggan = 0 
			GROUP BY trx.id_penjualan";
		}
		else
		{
			$kweri 	= "SELECT *, SUM(trx_penjualan_det.harga * trx_penjualan_det.jumlah_item) as total FROM trx_penjualan trx
			LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx.id_penjualan
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pelanggan pel ON pel.id_pelanggan = trx.id_pelanggan
			WHERE trx.id_pelanggan = 0 and $werewer
			GROUP BY trx.id_penjualan";
		}

			// echo $kweri;

		$penjualan = $this->db->query($kweri);

		if($penjualan->num_rows() > 0)
		{
			foreach($penjualan->result() as $pen => $jualan)
			{
				$data['id_penjualan'] = $jualan->id_penjualan;
				$data['tanggal_penjualan'] 	= date("d-m-Y", strtotime($jualan->tanggal_penjualan));
				$data['nama_pelanggan'] 	= $jualan->nama_pelanggan;
				$data['no_transaksi'] 		= $jualan->no_transaksi;
				$data['nama_metode_bayar'] 	= $jualan->nama_metode_bayar;
				$data['nama_bank'] 			= $jualan->nama_bank;
				$data['keterangan'] 		= $jualan->keterangan;
				$data['total'] 				= $jualan->total;

				$jojon['data'][] = $data;
			}

			$jojon['flag'] 	= true;
		}
		else
		{
			$jojon['flag'] 	= false;
		}


		$content = $this->load->view("printrekap_bkm", $jojon, true);

		echo $content;
	}

	public function getComboKasBank()
	{

		$kodeakun = $this->input->post("kodeakun");

		$kasbank = $this->db->query("SELECT * FROM mst_bank
			WHERE mst_bank.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$kodeakun."%')");

		$option = "";
		foreach($kasbank->result() as $rowAkun)
		{
			$data['idbank'] = $rowAkun->id_bank;
			$data['namabank'] = $rowAkun->nama_bank;

			$option .= "<option value='".$rowAkun->id_bank."'>".$rowAkun->nama_bank."</option>";
		}

		echo $option;

	}

	public function cari_mustahiq()
	{
		$mustahiq = $this->input->post("mustahiq");

		$cari=$this->db->query("SELECT * FROM mst_pelanggan WHERE nama_pelanggan LIKE '".$mustahiq."%'");

		foreach ($cari->result() as $row) {
			$data['namapelanggan'] = $row->nama_pelanggan;
			$data['alamatpelanggan'] = $row->alamat_pelanggan;
			$data['idpelanggan'] = $row->id_pelanggan;

			$json['pelanggan'][]=$data; 

		}
		echo json_encode($json);
	}

	public function cari_mustahiq2()
	{
		$mustahiq = $this->input->post("mustahiq");

		$cari=$this->db->query("SELECT * FROM mst_pemasok WHERE nama_pemasok LIKE '".$mustahiq."%'");

		foreach ($cari->result() as $row) {
			$data['namapemasok'] = $row->nama_pemasok;
			$data['alamatpemasok'] = $row->alamat_pemasok;
			$data['idpemasok'] = $row->id_pemasok;

			$json['pemasok'][]=$data; 

		}
		echo json_encode($json);
	}

	function pilihkasbank(){
		$nilai = $this->input->post("id");
		$html= "";
		if ($nilai==1) 
		{
			$kas=$this->db->query("SELECT * FROM mst_bank 
				WHERE mst_bank.id_akun in (SELECT mst_akun.id_akun FROM mst_akun WHERE concat(mst_akun.kode_induk, '.',mst_akun.kode_akun) LIKE '1.1.1.01%')");
			$html.='<div class="form-group" id="changebank" style="">
			<label class="col-md-3 control-label">Kas</label>
			<div class="col-md-8">
			<select onchange="getkasbank()" class="form-control" id="idbank" name="id_bank">
			<option value="-">:: Pilih Kas ::</option>';

			foreach($kas->result() as $row){

				$html.='<option value="'. $row->id_bank.' ">'.$row->nama_bank.'</option>';

			}
			$html.='
			</select>
			</div>
			</div>';

		}else{
			$kas=$this->db->query("SELECT * FROM mst_bank 
				WHERE mst_bank.id_akun in (SELECT mst_akun.id_akun FROM mst_akun WHERE concat(mst_akun.kode_induk, '.',mst_akun.kode_akun) LIKE '1.1.1.02%')");
			$html.='<div class="form-group" id="changebank" style="">
			<label class="col-md-3 control-label">Bank</label>
			<div class="col-md-8">
			<select onchange="getkasbank()" class="form-control" id="idbank" name="id_bank">
			<option value="-">:: Pilih Bank ::</option>';

			foreach($kas->result() as $row){

				$html.='<option value="'. $row->id_bank.' ">'.$row->nama_bank.'</option>';

			}
			$html.='
			</select>
			</div>
			</div>';
		}
		echo $html;
	}


	function printrekappembelian()
	{
		$s_tglAwal 	= $_GET['tanggalawal'];
		$s_tglAkhir = $_GET['tanggalakhir'];
		$s_pelanggan  = $_GET['pemasok'];
		$s_metode 	= $_GET['metodebayar'];
		$s_notrans 	= $_GET['notrans'];
		$s_bank 	= $_GET['bank'];

		$whereTglAwal 	= ($s_tglAwal) ? 'AND trx.tanggal >= "'.date("Y-m-d", strtotime($s_tglAwal)).'" ' : '';
		$whereTglAkhir 	= ($s_tglAkhir) ? 'AND trx.tanggal <= "'.date("Y-m-d", strtotime($s_tglAkhir)).'" ' : '';
		$wherePelanggan = ($s_pelanggan) ? 'AND pem.nama_pemasok LIKE "%'.$s_pelanggan.'%" ' : '';
		$whereMetode 	= ($s_metode != '-') ? 'AND trx.id_metode_bayar = '.$s_metode.' ' : '';
		$whereNotrans 	= ($s_notrans) ? 'AND trx.nomor_transaksi LIKE "%'.$s_notrans.'%" ' : '';
		$whereBank 		= ($s_bank != '-') ? 'AND trx.id_bank = '.$s_bank.' ' : '';

		$werewer = $whereTglAwal.$whereTglAkhir.$wherePelanggan.$whereMetode.$whereNotrans.$whereBank;
			// $werewer = (substr($werewer, 0, 3) == 'AND') ? substr($werewer, 4) : $werewer;

		if(!$werewer)
		{
			$kweri 	= "SELECT *, SUM(det.harga * det.jumlah) as total
			FROM trx_pembelian_persediaan trx
			LEFT JOIN trx_pembelian_persediaan_det det ON trx.id_pembelian = det.id_pembelian
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pemasok pem ON pem.id_pemasok = trx.id_pemasok 
			WHERE trx.id_pemasok <> 0 GROUP BY trx.id_pembelian";
		}
		else
		{
			$kweri 	= "SELECT *, SUM(det.harga * det.jumlah) as total
			FROM trx_pembelian_persediaan trx
			LEFT JOIN trx_pembelian_persediaan_det det ON trx.id_pembelian = det.id_pembelian
			LEFT JOIN ref_metode_pembayaran bayar ON bayar.id_metode_bayar = trx.id_metode_bayar
			LEFT JOIN mst_bank bank ON bank.id_bank = trx.id_bank
			LEFT JOIN mst_pemasok pem ON pem.id_pemasok = trx.id_pemasok
			WHERE trx.id_pemasok <> 0 $werewer GROUP BY trx.id_pembelian";
		}

			// echo $kweri;

		$pembelian = $this->db->query($kweri);

		if($pembelian->num_rows() > 0)
		{
			foreach($pembelian->result() as $pem => $belian)
			{
				$data['id_pembelian'] 	= $belian->id_pembelian;
				$data['tanggal'] 		= date("d-m-Y", strtotime($belian->tanggal));
				$data['nama_pemasok'] 	= $belian->nama_pemasok;
				$data['nomor_transaksi']= $belian->nomor_transaksi;
				$data['nama_metode_bayar'] 	= $belian->nama_metode_bayar;
				$data['nama_bank'] 		= $belian->nama_bank;
				$data['total'] 			= $belian->total;
				$data['deskripsi'] 		= $belian->deskripsi;

				$jojon['pel'][] = $data;
			}

			$jojon['flag'] 	= true;
		}
		else
		{
			$jojon['flag'] 	= false;
		}

		$content = $this->load->view("printrekap_bkk", $jojon, true);

		echo $content;

	}

	function printdatabiaya()
	{
		$tanggalawal = $_GET["tanggalawal"];
		$tanggalakhir = $_GET["tanggalakhir"];
		$metodebayar = $_GET["metodebayar"];
		$notransaksi = $_GET["notrans"];
		$bank = $_GET["bank"];

		if($tanggalawal != "" && $tanggalakhir != "")
		{
			$wheres[] = " (trx_pembelian_persediaan.tanggal >= '".date("Y-m-d", strtotime($tanggalawal))."' AND trx_pembelian_persediaan.tanggal <= '".date("Y-m-d", strtotime($tanggalakhir))."')";
		}

		if($metodebayar != "-")
		{
			$wheres[] = " trx_pembelian_persediaan.id_metode_bayar = '".$metodebayar."'";
		}

		if($notransaksi != "")
		{
			$wheres[] = " trx_pembelian_persediaan.nomor_transaksi LIKE '%".$notransaksi."%'";
		}

		if($bank != "-")
		{
			$wheres[] = " trx_pembelian_persediaan.id_bank = '".$bank."'";
		}
		$where = (isset($wheres)) ? implode(" AND ", $wheres) : "";
		$extend = (isset($wheres)) ? " AND " : "";

		$dataquery = $this->db->query("SELECT trx_pembelian_persediaan.*, 
			ref_metode_pembayaran.nama_metode_bayar, mst_bank.nama_bank,
			SUM(trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah) as total 
			FROM trx_pembelian_persediaan
			LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
			LEFT JOIN ref_metode_pembayaran ON ref_metode_pembayaran.id_metode_bayar = trx_pembelian_persediaan.id_metode_bayar
			LEFT JOIN mst_bank ON mst_bank.id_bank = trx_pembelian_persediaan.id_bank
			WHERE trx_pembelian_persediaan.id_pemasok = 0 
			$extend
			$where GROUP BY trx_pembelian_persediaan.id_pembelian");

		if($dataquery->num_rows() > 0)
		{
			foreach($dataquery->result() as $row)
			{
				$data['idbiaya'] = $row->id_pembelian;
				$data['tanggal'] = date("d-m-Y", strtotime($row->tanggal));
				$data['notrans'] = $row->nomor_transaksi;
				$data['metodebayar'] = $row->nama_metode_bayar;
				$data['bank'] = $row->nama_bank;
				$data['total'] = $row->total;
				$data['keterangan'] = $row->deskripsi;

				$json['data'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}

		$konten = $this->load->view('printrekap_bkk-biaya', $json, true);
		echo $konten;
	}
	function printdatamutasi()
	{
		$tanggalawal = $_GET["tanggalawal"];
		$tanggalakhir = $_GET["tanggalakhir"];
		$nmbukti = $_GET["nmbukti"];

		$wheres[]= "";
		if($tanggalawal != "" && $tanggalakhir != "")
		{
			$wheres[] .= " (trx_ju.tanggal >= '".date("Y-m-d", strtotime($tanggalawal))."' AND trx_ju.tanggal <= '".date("Y-m-d", strtotime($tanggalakhir))."')";
		}

		if($nmbukti != "")
		{
			$wheres[] .= " trx_pembelian_persediaan.nomor_transaksi LIKE '%".$nmbukti."%'";
		}

		$where=implode('AND', $wheres);
		$query="SELECT *,trx_ju.uraian AS uraian from trx_ju LEFT JOIN trx_judet ON trx_ju.id_ju=trx_judet.id_ju WHERE trx_ju.id_sumber_trans = (SELECT id_sumber_trans FROM ref_sumber_trans WHERE ref_sumber_trans.kode='MTS') AND trx_judet.debet > 0 ".$where;
		$sql=$this->db->query($query);


		if ($sql->num_rows>0) {
			foreach ($sql->result() as $row) {
				$data['id'] = $row->id_ju;
				$data['tanggal'] = $row->tanggal;
				$data['nm'] = $row->no_bukti;
				$data['nmtok'] = $row->nomor;
				$data['uraian'] = $row->uraian;
				$data['total'] = $row->debet;

				$json['data'][] = $data;
			}
			$json['flag']=true;	
		}else{
			$json['flag']=false;	
		}

		$konten = $this->load->view('printrekap_mutasi', $json, true);
		echo $konten;
	}
	public function cariitempenjualan()
	{

		$nameitem = $this->input->post("item");

		$where= ($_SESSION['IDUnit']!=1) ? ' and mst_kategori_item.id_unit= "'.$_SESSION['IDUnit'].'"'  : ''; 
		$item = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			LEFT JOIN ref_tipe_item ON ref_tipe_item.id_ref_tipe_item = mst_item.id_ref_tipe_item
			LEFT join mst_departemen ON mst_kategori_item.id_unit = mst_departemen.id_departemen
			WHERE ref_tipe_item.id_ref_tipe_item = 2 ".$where." AND mst_item.nama_item LIKE '%".$nameitem."%'");

		if($item->num_rows() > 0)
		{
			foreach($item->result() as $row)
			{
				$data['iditem'] = $row->id_item;
				$data['namaitem'] = $row->nama_item;
				$data['hargaitem'] = number_format($row->harga_jual);
				$data['satuan'] = $row->satuan;
				$data['kategori'] = $row->nama_kategori;
				$data['unit'] = $row->nama_departemen;
				$json['item'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}

		echo json_encode($json);
	}

	public function cariitempengeluaran()
	{

		$nameitem = $this->input->post("item");

		$where= ($_SESSION['IDUnit']!=1) ? ' and mst_kategori_item.id_unit= "'.$_SESSION['IDUnit'].'"'  : ''; 
		$item = $this->db->query("SELECT * FROM mst_item
			LEFT JOIN mst_kategori_item ON mst_kategori_item.id_kategori_item = mst_item.id_kategori_item
			LEFT JOIN ref_tipe_item ON ref_tipe_item.id_ref_tipe_item = mst_item.id_ref_tipe_item
			LEFT join mst_departemen ON mst_kategori_item.id_unit = mst_departemen.id_departemen
			WHERE ref_tipe_item.id_ref_tipe_item = 1 ".$where." AND mst_item.nama_item LIKE '%".$nameitem."%'");

		if($item->num_rows() > 0)
		{
			foreach($item->result() as $row)
			{
				$data['iditem'] = $row->id_item;
				$data['namaitem'] = $row->nama_item;
				$data['hargaitem'] = number_format($row->harga_jual);
				$data['satuan'] = $row->satuan;
				$data['kategori'] = $row->nama_kategori;
				$data['unit'] = $row->nama_departemen;
				$json['item'][] = $data;
			}

			$json['flag'] = true;
		}
		else
		{
			$json['flag'] = false;
		}

		echo json_encode($json);
	}
	
	function convertbulan()
	{
		$bulan = $this->input->post("bulan");
		
		echo convertBulanToRomawi($bulan);
	}
}

/* End of file user.php */