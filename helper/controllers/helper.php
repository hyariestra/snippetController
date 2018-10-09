<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class helper extends MY_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('func_helper');
		setDatabase($_SESSION['Database']);
	}

	
		public function informasi_bumdes()
		{
			
			$content = $this->load->view("h_informasi_bumdes",true);
			
			echo $content;
		}
		
		public function periode_transaksi()
		{
			$content = $this->load->view("h_periode_transaksi",true);
			
			echo $content;
		}
		
		public function mata_uang()
		{
			$content = $this->load->view("h_mata_uang",true);
			
			echo $content;
		}
		
		public function pelanggan()
		{
			$content = $this->load->view("h_pelanggan",true);
			
			echo $content;
		}

		public function pemasok()
		{
			$content = $this->load->view("h_pemasok",true);

			echo $content;
		}

		public function setup_general()
		{
			$content = $this->load->view("helper/h_setup_general",true);

			echo $content;
		}

		public function kode_akun()
		{
			$content = $this->load->view("helper/h_kode_akun",true);

			echo $content;
		}

		public function unit()
		{
			$content = $this->load->view("helper/h_unit",true);

			echo $content;
		}

		public function kategori_item()
		{
			$content = $this->load->view("helper/h_kategori_item",true);

			echo $content;
		}
		
		public function item()
		{
			$content = $this->load->view("helper/h_item",true);

			echo $content;
		}

		public function kas_bank()
		{
			$content = $this->load->view("helper/h_kas_bank",true);

			echo $content;
		}
		
		public function aset()
		{
			$content = $this->load->view("helper/h_aset",true);

			echo $content;
		}

		public function persediaan()
		{
			$content = $this->load->view("helper/h_persediaan",true);

			echo $content;
		}

		public function daftar_akun()
		{
			$content = $this->load->view("helper/h_daftar_akun",true);

			echo $content;
		}

		public function saldo_awal()
		{
			$content = $this->load->view("helper/h_saldo_awal",true);

			echo $content;
		}


		
		public function tambah_penerimaan()
		{
			$content = $this->load->view("helper/h_tambah_penerimaan",true);

			echo $content;
		}

		public function daftar_bkm()
		{
			$content = $this->load->view("helper/h_daftar_bkm",true);

			echo $content;
		}

		public function cari_bkm()
		{
			$content = $this->load->view("helper/h_cari_bkm",true);

			echo $content;
		}

		public function tambah_pengeluaran()
		{
			$content = $this->load->view("helper/h_tambah_pengeluaran",true);

			echo $content;
		}

		public function daftar_bkk()
		{
			$content = $this->load->view("helper/h_daftar_bkk",true);

			echo $content;
		}

		public function cari_bkk()
		{
			$content = $this->load->view("helper/h_cari_bkk",true);

			echo $content;
		}

		public function jurnal_umum()
		{
			$content = $this->load->view("helper/h_jurnal_umum",true);

			echo $content;
		}

		public function tambah_jurnal_umum()
		{
			$content = $this->load->view("helper/h_tambah_jurnal_umum",true);

			echo $content;
		}

		public function daftar_jurnal_umum()
		{
			$content = $this->load->view("helper/h_daftar_jurnal_umum",true);

			echo $content;
		}

		public function tambah_mutasi_bank()
		{
			$content = $this->load->view("helper/h_tambah_mutasi_bank",true);

			echo $content;
		}

		public function daftar_mutasi_bank()
		{
			$content = $this->load->view("helper/h_daftar_mutasi_bank",true);

			echo $content;
		}

		public function posting_jurnal()
		{
			$content = $this->load->view("helper/h_posting_jurnal",true);

			echo $content;
		}

		public function buku_jurnal()
		{
			$content = $this->load->view("helper/h_buku_jurnal",true);

			echo $content;
		}

		public function neraca_saldo()
		{
			$content = $this->load->view("helper/h_neraca_saldo",true);

			echo $content;
		}

		public function buku_kas_penerimaan()
		{
			$content = $this->load->view("helper/h_buku_kas_penerimaan",true);

			echo $content;
		}

		public function buku_kas_pengeluaran()
		{
			$content = $this->load->view("helper/h_buku_kas_pengeluaran",true);

			echo $content;
		}

		public function buku_kas_harian()
		{
			$content = $this->load->view("helper/h_buku_kas_harian",true);

			echo $content;
		}

		public function laporan_neraca()
		{
			$content = $this->load->view("h_laporan_neraca",true);

			echo $content;
		}

		public function laporan_operasional()
		{
			$content = $this->load->view("helper/h_laporan_operasional",true);

			echo $content;
		}

		public function arus_kas()
		{
			$content = $this->load->view("helper/h_arus_kas",true);

			echo $content;
		}

		public function narasi_calk()
		{
			$content = $this->load->view("helper/h_narasi_calk",true);

			echo $content;
		}

		public function calk()
		{
			$content = $this->load->view("helper/h_calk",true);

			echo $content;
		}

		public function laporan_laba_rugi_per_unit()
		{
			$content = $this->load->view("helper/h_laporan_laba_rugi_per_unit",true);

			echo $content;
		}

		public function group_user()
		{
			$content = $this->load->view("helper/h_group_user",true);

			echo $content;
		}

		public function user()
		{
			$content = $this->load->view("helper/h_user",true);

			echo $content;
		}

		public function setting_user()
		{
			$content = $this->load->view("helper/h_setting_user",true);

			echo $content;
		}

	}

	/* End of file user.php */
