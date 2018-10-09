<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

	class User extends MY_Controller 
	{
	   	
	    public function __construct() 
	    {
	        parent::__construct();
	        $this->load->helper('func_helper');
	    }
	         
		public function index()
		{	
			$this->load->view('login_view');	
		}
		
		public function login()
		{
			
			$this->form_validation->set_rules('username', 'namaUser', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'kata sandi', 'trim|required|xss_clean');
			
			$username = $this->input->post("username");
			$password = md5($this->input->post("password"));
			$database = $this->input->post("database");
			
			if(! $this->form_validation->run())
			{
				$errorMessage = "Silahkan periksa kembali inputan anda.";
                $messageData = ConstructMessageResponse($errorMessage , 'warning');
                echo $messageData;
                exit;
			}
			else
			{
				
				$_SESSION['Database'] = $database;
				
				$databases = $_SESSION['Database'];
				
				setDatabase($databases);
				
				$userlogin = $this->db->query("SELECT * FROM sys_user 
				WHERE sys_user.nama_user = '".$username."' AND sys_user.password = '".$password."'");
				
				if($userlogin->num_rows() > 0)
				{
				
					$_SESSION['IDUser'] = $userlogin->first_row()->id_user;
					$_SESSION['IDSekolah'] = $userlogin->first_row()->id_sekolah;
					$_SESSION['NamaUser'] = $userlogin->first_row()->nama_user;
					$_SESSION['IDGroup'] = $userlogin->first_row()->id_group;
					$_SESSION['IDUnit'] = $userlogin->first_row()->id_unit;
					$_SESSION['NamaLengkap'] = $userlogin->first_row()->nama_lengkap;
					$_SESSION['Email'] = $userlogin->first_row()->email;
					$_SESSION['IDUnit'] = $userlogin->first_row()->id_unit;
					
					
					echo "<script>window.location='".base_url()."main';</script>";
				}
				else
				{
					$errorMessage = "Username dan password tidak cocok. Silahkan cek kembali.";
					$messageData = ConstructMessageResponse($errorMessage , 'warning');
					echo $messageData;
					exit;
				}
			}
		}

		// tinus : logout
		public function logout()
		{
				
			session_destroy();

			header('Location:'.base_url());
		}
		
		public function register()
		{
			//$this->load->database();
			//$dbselect = (isset($_GET['accesskey'])) ? base64_decode($_GET['accesskey']) : "";
			$dbselect = "bumdes";
			
			
			$database = "syncore_genio_".$dbselect;
			setDatabase($database);
			
			$data['provinsi'] = $this->db->query("SELECT * FROM mst_propinsi");
			
			$content = $this->load->view("user/register",$data, true);
			
			echo $content;
		}
		
		public function getkabupaten()
		{
			//$this->load->database();
			$dbselect = "bumdes";
			
			
			$database = "syncore_genio_".$dbselect;
			setDatabase($database);
			
			$idprop = $this->input->post("idprov");
			$kodeprop = $this->input->post("kodeprov");
			
			$getkab = $this->db->query("SELECT * FROM mst_kabupaten WHERE mst_kabupaten.kode_propinsi ='".$kodeprop."'");
			
			
			foreach($getkab->result() as $row)
			{
				$json['html'][] = "<option value='".$row->id_kabupaten."#".$row->kode_kabupaten."'>".$row->nama_kabupaten."</option>";
			}
			
			echo json_encode($json);
		}
		
		public function getnoreg()
		{
			//$this->load->database();
			$dbselect = "bumdes";
			
			
			$database = "syncore_genio_".$dbselect;
			setDatabase($database);
			
			$idprov = $this->input->post("idprov");
			$idkab = $this->input->post("idkab");
			
			$noreg = $this->db->query("SELECT * FROM sys_user WHERE sys_user.id_provinsi = '".$idprov."'
			AND sys_user.id_kabupaten = '".$idkab."'");
			
			$noreg = $noreg->num_rows() + 1;
			
			$noreg = str_pad($noreg, 4, "0", STR_PAD_LEFT);
			
			echo $noreg;
		}
		
		public function simpanregister()
		{
			//echo "<pre>";print_r($_POST);"</pre>";
			
			//$this->load->database();
			
			$dbselect = "bumdes";
			
			
			$database = "syncore_genio_".$dbselect;
			setDatabase($database);
			
			$idprov = $this->input->post("kodeprovinsi");
			$idkab = $this->input->post("kodekabupaten");
			$noreg = $this->input->post("noreg");
			
			$nomor = $idprov.".".$idkab.".".$noreg;
			
			$idperusahaan = $this->db->query("SELECT * FROM sys_perusahaan")->first_row()->id_perusahaan;
			
			$data['id_sekolah'] = $idperusahaan;
			$data['id_group'] = 1;
			$data['id_unit'] = 1;
			$data['nama_lembaga'] = $this->input->post("namalembaga");
			$data['nama_user'] = $this->input->post("username");
			$data['nama_lengkap'] = $this->input->post("namalengkap");
			$data['password'] = md5($this->input->post("password"));
			$data['alamat'] = $this->input->post("alamat");
			$data['email'] = $this->input->post("email");
			$data['telp'] = $this->input->post("telp");
			$data['noreg'] = $nomor;
			$data['isaktif'] = 1;
			$data['id_provinsi'] = $this->input->post("provinsi");
			$data['id_kabupaten'] = $this->input->post("kabupaten");
			
			$this->db->insert("sys_user", $data);
			
			//$this->db->query("INSERT INTO `register_akun`.`sys_user` (`nama_lembaga`, `nama_user`, `nama_lengkap`, `password`, `alamat`, `email`, `telp`, `noreg`, `id_propinsi`, `id_kabupaten`, `db_prefix`, `db_name`) VALUES ('".$data['nama_lembaga']."', '".$data['nama_user']."', '".$data['nama_lengkap']."', '".$data['password']."', '".$data['alamat']."', '".$data['email']."', '".$data['telp']."', '".$data['noreg']."', '".$data['id_propinsi']."', '".$data['id_kabupaten']."', '".$data['db_prefix']."', '".$data['db_name']."');");
		
			
		}
		

	}

/* End of file user.php */