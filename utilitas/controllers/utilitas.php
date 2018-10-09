<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class Utilitas extends MY_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('func_helper');
		setDatabase($_SESSION['Database']);
	}

	public function index()
	{	
		$this->load->view('login_view');	
	}

	public function user()

	{
		$data['user'] = $this->db->query("SELECT * FROM sys_user 
			LEFT JOIN sys_group ON sys_group.id_group = sys_user.id_group
			WHERE sys_user.id_sekolah = '".$_SESSION['IDSekolah']."'");
		$data['depar'] = $this->db->query("SELECT * from mst_departemen");
		$data['group'] = $this->db->query("SELECT * FROM sys_group");

		$content = $this->load->view("utilitas/user", $data, true);

		echo $content;
	}

	public function simpanuser()
	{	
	/*	print_r($_POST);
	exit();*/

	$data['id_sekolah'] = $_SESSION['IDSekolah'];
	$data['nama_user'] = $this->input->post("namauser");
	$data['nama_lengkap'] = $this->input->post("namalengkap");
	$data['id_group'] = $this->input->post("group");
	$data['id_unit']=$this->input->post("unit");
	$data['email'] = $this->input->post("email");
	$data['password'] = md5($this->input->post("password"));
	$data['isaktif'] = true;

	$this->db->insert("sys_user", $data);
}


public function hapususer()
{
	$idU = $this->input->post('iduser');

	$this->db->where('id_user', $idU );
	$this->db->delete('sys_user');

	$jojon['flag']=true;
	echo json_encode($jojon);	

}

public function deleteuserall()
{

	foreach ($_POST['idu'] as $id)

	{
		$this->db->where("id_user", $id);
		$this->db->delete("sys_user");
	}
	

}



public function simpanubahuser()
{

	$iduser = $this->input->post("iduser");
	$namauser = $this->input->post("namauser");
	$namalengkap = $this->input->post("namalengkap");
	$group = $this->input->post("group_ubah");
	$email = $this->input->post("email");
	$password = $this->input->post("password");
	$isaktif = $this->input->post("isaktif");



	if(isset($_POST['isaktif']))
	{ 
		$status = 1; 
	}
	else
	{ 
		$status = 0; 
	} 


	$data['id_sekolah'] = $_SESSION['IDSekolah'];
	$data['nama_user'] = $namauser;
	$data['nama_lengkap'] = $namalengkap;
	$data['id_group'] = $group;
	$data['email'] = $email;
	$data['isaktif'] = $status;

	if($password != "")
	{ 
		$data['password']  = md5($this->input->post("password")); 
	}

	$this->db->where("id_user", $iduser);
	$this->db->update("sys_user", $data);
}

public function settinguser()
{

	$data['user'] = $this->db->query("SELECT * FROM sys_user 
		WHERE sys_user.id_sekolah = '".$_SESSION['IDSekolah']."'");

	$data['modul'] = $this->db->query("SELECT * FROM sys_modul");

	$data['groupuser'] = $this->db->query("SELECT * FROM sys_group");

	$content = $this->load->view("utilitas/settinguser", $data, true);

	echo $content;

}

function getusergroup()
{

	$idgroup = $this->input->post("idgroup");

	$query = $this->db->query("SELECT * FROM sys_user
		WHERE sys_user.id_group = '".$idgroup."' AND sys_user.id_sekolah = '".$_SESSION['IDSekolah']."'");

	if($query->num_rows() > 0)
	{
		foreach($query->result() as $row)
		{
			$data['iduser'] = $row->id_user;
			$data['namauser'] = $row->nama_user;

			$json['user'][] = $data;
		}

		$json['flag_user'] = true;
	}
	else
	{
		$json['flag_user'] = false;
	}


	$modul = $this->db->query("SELECT * FROM  sys_group_modul
		LEFT JOIN sys_group ON sys_group_modul.id_group = sys_group.id_group
		WHERE sys_group_modul.id_group = '".$idgroup."' AND sys_group_modul.id_sekolah = '".$_SESSION['IDSekolah']."'");

	if($modul->num_rows() > 0)
	{
		foreach($modul->result() as $row)
		{
			$data2['idmodul'] = $row->id_modul;

			$json['modul'][] = $data2;
		}

		$json['flag'] = true;
	}
	else
	{
		$json['flag'] = false;
	}

	echo json_encode($json);
}

function simpandatauser()
{
	$idmodul = $this->input->post("module");
	$idgroup = $this->input->post("idgroup");
	$idsekolah = $_SESSION['IDSekolah'];


	$this->db->where("id_group", $idgroup);
	$this->db->where("id_sekolah", $idsekolah);
	$this->db->delete("sys_group_modul");


	foreach($idmodul as $modul)
	{	
		$data['id_group'] = $idgroup;
		$data['id_modul'] = $modul;
		$data['id_sekolah'] = $idsekolah;

		$this->db->insert("sys_group_modul", $data);
	}
}

function generateall()
{
	$sekolah = $this->db->query("SELECT * FROM mst_sekolah");

	foreach($sekolah->result() as $row)
	{
		$checkdata = $this->db->query("SELECT * FROM sys_group_modul WHERE sys_group_modul.id_sekolah = '".$row->id_sekolah."'");

		if($checkdata->num_rows() == 0)
		{
			$modul = $this->db->query("SELECT * FROM sys_modul");

			foreach($modul->result() as $mod)
			{
				$data['id_group'] = 1;
				$data['id_modul'] = $mod->id_modul;
				$data['id_sekolah'] = $row->id_sekolah;

				$this->db->insert("sys_group_modul", $data);
			}
		}
	}
}

function groupuser()
{
	$data['group'] = $this->db->query("SELECT * FROM sys_group");

	$content = $this->load->view("utilitas/groupuser", $data, true);

	echo $content;
}

function hapusgroupuser()
{
	$idP = $this->input->post('idgroup');

	$this->db->where('id_group', $idP);
	$this->db->delete('sys_group');

	$jojon['flag']=true;
	echo json_encode($jojon);
}


public function deletegroupall()
{
	foreach ($_POST['idg'] as $id)

	{
		$this->db->where("id_group", $id);
		$this->db->delete("sys_group");
	}
}


function simpangroup()
{
	$data['nama_group'] = $this->input->post("group"); 
	$data['deskripsi'] = $this->input->post("deskripsi"); 

	$this->db->insert("sys_group", $data);
}

function updategroup()
{
	$data['nama_group'] = $this->input->post("group_ubah"); 
	$data['deskripsi'] = $this->input->post("deskripsi_ubah"); 

	$this->db->where("id_group", $this->input->post("idgroup"));
	$this->db->update("sys_group", $data);
}

public function printdatauser()
{
	


	$queryuser =  $this->db->query("SELECT * FROM sys_user 
		LEFT JOIN sys_group ON sys_group.id_group = sys_user.id_group
		WHERE sys_user.id_sekolah = '".$_SESSION['IDSekolah']."'");
	$data['depar'] = $this->db->query("SELECT * from mst_departemen");
	$data['group'] = $this->db->query("SELECT * FROM sys_group");

	foreach($queryuser->result() as $pen => $user)
	{
		
		$data ['nama_user']= $user->nama_user;
		$data ['nama_lengkap']= $user->nama_lengkap;
		$data ['nama_group']= $user->nama_group;
		$data ['email']= $user->email;
		$data ['password']= $user->password;


		$jojon['data'][] = $data;
	}


	$content = $this->load->view("printrekapuser", $jojon, true);

	echo $content;
	
}

}



/* End of file user.php */