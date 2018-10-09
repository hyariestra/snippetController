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
			
			if(! $this->form_validation->run())
			{
				$errorMessage = "Silahkan periksa kembali inputan anda.";
                $messageData = ConstructMessageResponse($errorMessage , 'warning');
                echo $messageData;
                exit;
			}
			else
			{
				
				$userlogin = $this->db->query("SELECT * FROM sys_user 
				WHERE sys_user.nama_user = '".$username."' AND sys_user.password = '".$password."'");
				
				if($userlogin->num_rows() > 0)
				{
				
					$_SESSION['IDUser'] = $userlogin->first_row()->id_user;
					$_SESSION['IDSekolah'] = $userlogin->first_row()->id_sekolah;
					$_SESSION['NamaUser'] = $userlogin->first_row()->nama_user;
					$_SESSION['IDGroup'] = $userlogin->first_row()->id_group;
					$_SESSION['NamaLengkap'] = $userlogin->first_row()->nama_lengkap;
					$_SESSION['Email'] = $userlogin->first_row()->email;
					$_SESSION['databaseActive'] = "syncore_genio_1";
					
					
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
		

	}

/* End of file user.php */