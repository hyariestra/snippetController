<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

    class User_model extends CI_Model{

        public function __construct() {
            parent::__construct();
            $this->IDUser = $_SESSION['IDUser'];
        }

        public function GetMemberProfile()
        {

        	$data = $this->db->query("select nama_user as NamaPengguna, nama_lengkap as NamaLengkap, email as Email 
        							 from sys_user 
        							 where id_user = '".$this->IDUser."' "); 
            return $data->result_array();
        }

        public function SetMemberProfile($data)
        {
            $this->nama         = $this->security->xss_clean($data['nama']);
            $this->username     = $this->security->xss_clean($data['username']);
            $this->email        = $this->security->xss_clean($data['email']);
            $this->katasandi    = $this->security->xss_clean($data['katasandi']);
    
            $this->katasandi    = (trim($this->katasandi) <> '') ? ",kata_sandi = '".md5($this->katasandi)."' " : "";

            //check username apakah sudah ada 
            $data = $this->db->query("select id_user from sys_user where nama_user = '".$this->username."' 
                                      and id_user <> '".$this->IDUser."' ");
        
            if ($data->num_rows() > 0)
            {
                $strMessage  = 'Nama pengguna sudah tercatat dan sudah digunakan oleh user yang lain';
                $messageData = ConstructMessageResponse($strMessage , 'danger');
                echo $messageData;
                exit;
            }

            //check email apakah sudah ada 
            $data = $this->db->query("select id_user from sys_user where email = '".$this->email."' 
                                      and id_user <> '".$this->IDUser."' ");

            if ($data->num_rows() > 0)
            {
                $strMessage  = 'Email sudah tercatat dan sudah digunakan oleh user yang lain';
                $messageData = ConstructMessageResponse($strMessage , 'danger');
                echo $messageData;
                exit;
            }

            $this->db->query("update sys_user set nama_user = '".$this->username."', nama_lengkap = '".$this->nama."', email = '".$this->email."' ".$this->katasandi."  
                               where id_user ='".$this->IDUser."' ");

            $strMessage  = 'Profil pengguna telah berhasil di update';
            $messageData = ConstructMessageResponse($strMessage , 'success');
            echo $messageData;;
       }
    }
?>