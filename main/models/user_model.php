<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

    class User_model extends CI_Model{

        public function __construct() {
            parent::__construct();
            //$this->IDUser = $_SESSION['IDUser'];
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

       public function treeDataKodeAkun($tipeAkun = '')
	   {

        $selectQuery = $this->db->query("SELECT id_akun as IDAkun FROM mst_akun
                                        WHERE kode_induk = 0 and level = 1 and kode_akun = '".$tipeAkun."' ");
		
		
        $arrSelectQuery = ( $selectQuery->num_rows() > 0 ) ? $selectQuery->row_array() : array("IDAkun" => 0 );

        $this->IDTipeAkun = ($tipeAkun <> '0') ? $arrSelectQuery['IDAkun'] : '0';

		$strJsonData = '{ "data" :[';
        $strHeader = '';

        if ($tipeAkun > 0)
        {
          $strHeader = '';
          //$strJsonData = '';

          $selectQuery = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, id_akun as IDAkun, id_akun_kel as IDAkunKel, id_akun_tipe as IDAkunTipe,
                                          (SELECT nama from ref_akuntipe where id = IDAkunTipe) as NamaAkunTipe,
                                          id_aruskas_kel as IDArusKas, kode_akun AS KodeAkun, header as Header,
                                          nama_akun AS NamaAkun, id_induk as IDInduk, kode_induk as KodeInduk, level as Level,
                                          saldo_normal as SaldoNormal,
                                          id_tipekasbank as IDTipeKasBank, nama_bendahara as NamaBendahara,
                                          nama_bank as NamaBank, norek_bank as NoRekBank,
                                          (SELECT CONCAT(kode_induk,'.',kode_kodering,' (',nama_kodering,')')  FROM mst_kodering WHERE  id_kodering = mst_akun.id_kodering )  AS concatKodeRekening
                                          FROM mst_akun
                                          WHERE id_induk=0 and level = 1 and kode_akun='".$tipeAkun."'
                                          order by kodeUrut");

          foreach( $selectQuery->result_array() as $row )
          {

           $IDAkun         = $row['IDAkun'];
           $IDInduk        = $row['IDInduk'];
           $IDAkunTipe     = $row['IDAkunTipe'];
           $IDAkunKel      = $row['IDAkunKel'];
           $NamaAkunTipe   = $row['NamaAkunTipe'];
           $IDArusKas      = $row['IDArusKas'];
           $kodeInduk      = $row['KodeInduk'];
           $header         = $row['Header'];
           $kodePlainText  = $row['KodeAkun'];
           $namaPlainText  = $row['NamaAkun'];
           $saldoNormal    = $row['SaldoNormal'];
           $level          = $row['Level'];
           $kodeInduk      = $row['KodeAkun'];
           $kodePlainText  = $kodePlainText;
           $kodeWithFormat = "<b>".$kodePlainText."</b>";
           $namaWithFormat = "<b>".$namaPlainText."</b>";
           $kodeAkun       = $row['KodeAkun'];
           $kodeRekeningWithFormat = $row['concatKodeRekening'];
           $IDTipeKasBank   = $row['IDTipeKasBank'];
           $NamaBendahara   = $row['NamaBendahara'];
           $NamaBank        = $row['NamaBank'];
           $NoRekBank       = $row['NoRekBank'];

           $concatCode     = $row['KodeInduk'].'.'.$row['KodeAkun'];

           $IDArusKas      = ($IDArusKas == '') ? '1' : $IDArusKas;

           $strSpace = '';

             for($i = 1; $i <= $level ; $i++)
             {
              $strSpace .= "<img src='assets/images/space.png'/>";
             }

             $imgRoot   = "<span class='glyphicon glyphicon-home'></span>".$strSpace;

             /*setting khusus kode akun untuk jenis industri DESA
             $settingValue  = GetSettingValue('kelompok_industri');

             if ($settingValue == 'Desa')
             {
               $prefixAkun = substr($kodePlainText,0,1);
               $prefixAkun = ($prefixAkun == '4') ? '1' : $prefixAkun;
               $prefixAkun = ($prefixAkun == '5') ? '2' : $prefixAkun;
               $prefixAkun = ($prefixAkun == '6') ? '3' : $prefixAkun;

               $strNewKodeAkun = $prefixAkun.substr($kodePlainText,1,strlen($kodePlainText));
               $kodeWithFormat =  "<b>".$strNewKodeAkun."</b>";

            }*/

             $kodeWithFormat = $imgRoot.$kodeWithFormat;

             $btnTambah   = '<button buttonType=\'actionItems\' buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkun();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';
             $btnUbah     = '<button buttonType=\'actionItems\' buttonType=\'actionItems\' class= \'btn btn-xs btn-warning\' onclick=\'UbahAkun();\'><span class=\'glyphicon glyphicon-pencil\' aria-hidden=\'true\'></button>&nbsp;';

             $kelompokRekening = ($level == 1) ? substr($concatCode,2,1) : substr($concatCode,0,1);

             $btnKodering = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'BrowseKodering('.$kelompokRekening.', this)\'><span class=\'glyphicon glyphicon-folder-open\' aria-hidden=\'true\'></button>&nbsp;';

             //$isSuperAdmin = $this->isSuperAdminAccess() == 1;

             //$btnAdd   = (!$isSuperAdmin && ($level == 1 || $level == 2 ) ) ? '' : $btnTambah;

             $btnKodering = ($header == '0') ? $btnKodering : '';

             $actionList = $btnAdd.$btnUbah.$btnKodering;

             $actionList = (substr($concatCode, 0, 3) == '0.7') ? '' : $actionList;

             $strHeader = '{"id"             : "'.$IDAkun.'",
                            "IDAkunTipe"      : "'.$IDAkunTipe.'",
                            "IDArusKas"       : "'.$IDArusKas.'",
                            "IDAkunKel"       : "'.$IDAkunKel.'",
                            "kodePlainText"   : "'.$kodePlainText.'",
                            "kodeWithFormat"  : "'.$kodeWithFormat.'",
                            "namaPlainText"   : "'.$namaPlainText.'",
                            "namaWithFormat"  : "'.$namaWithFormat.'",
                            "kodeRekeningWithFormat" : "'.$kodeRekeningWithFormat.'",
                            "namaAkunTipe"    : "'.$NamaAkunTipe.'",
                            "saldoNormal"     : "'.$saldoNormal.'",
                            "level"           : "'.$level.'",
                            "induk"           : "'.$IDInduk.'",
                            "indukKode"       : "'.$kodeInduk.'",
                            "AkunKode"        : "'.$kodeAkun.'",
                            "IDTipeKasBank"   : "'.$IDTipeKasBank.'",
                            "NamaBendahara"   : "'.$NamaBendahara.'",
                            "NamaBank"        : "'.$NamaBank.'",
                            "NoRekBank"       : "'.$NoRekBank.'",
                            "action"          : "'.$actionList.'"},';
          }

           $strJsonData .= $this->recursiveDataKodeAkun($this->IDTipeAkun, $strJsonData);
           $strJsonData = substr($strJsonData, 2, strlen($strJsonData) - 2);
           $strJsonData = $strHeader.$strJsonData;
           $strJsonData = substr($strJsonData, 0, strlen($strJsonData) - 2);
           $strJsonData .= ']}';

        }
        else
        {

          $strJsonData .= $this->recursiveDataKodeAkun($this->IDTipeAkun, $strJsonData);
          $strJsonData = substr($strJsonData, 11, strlen($strJsonData) - 12);
          $strJsonData .= ']}';
        }

        return $strJsonData;
      }

       public function recursiveDataKodeAkun($parent=0, $hasil)
      {

          $selectQuery = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, id_akun as IDAkun, id_akun_kel as IDAkunKel, id_akun_tipe as IDAkunTipe,
                                          (SELECT nama from ref_akuntipe where id = IDAkunTipe) as NamaAkunTipe,
                                          concat(kode_induk,'.',kode_akun) as ConcatCode,
                                           id_aruskas_kel as IDArusKas, kode_akun AS KodeAkun, header as Header,
                                           nama_akun AS NamaAkun, id_induk as IDInduk, kode_induk as KodeInduk, level as Level,
                                           saldo_normal as SaldoNormal, builtin as Builtin,
                                            (SELECT CONCAT(kode_induk,'.',kode_kodering,' (',nama_kodering,')') FROM mst_kodering WHERE  id_kodering = mst_akun.id_kodering )  AS concatKodeRekening
                                           FROM mst_akun
                                           WHERE id_induk='".$parent."'
                                           ORDER BY kodeUrut ASC ");

          foreach( $selectQuery->result_array() as $row )
          {

           $IDAkun         = $row['IDAkun'];
           $IDInduk        = $row['IDInduk'];
           $IDAkunKel      = $row['IDAkunKel'];
           $IDAkunTipe     = $row['IDAkunTipe'];
           $IDArusKas      = $row['IDArusKas'];
           $kodeInduk      = $row['KodeInduk'];
           $header         = $row['Header'];
           $kodePlainText  = $row['KodeAkun'];
           $namaAkunTipe   = $row['NamaAkunTipe'];
           $namaPlainText  = $row['NamaAkun'];
           $saldoNormal    = $row['SaldoNormal'];
           $builtin        = $row['Builtin'];
           $level          = $row['Level'];
           $kodeInduk      = ($kodeInduk == '0') ? $row['KodeAkun'] : $kodeInduk;
           $kodePlainText  = ($level == 1) ? $kodePlainText : $kodeInduk.'.'.$kodePlainText;
           $kodeWithFormat = ($header == 1 ) ? "<b>".$kodePlainText."</b>" : $kodePlainText;
           $namaWithFormat = ($header == 1 ) ? "<b>".$namaPlainText."</b>" : $namaPlainText;
           $kodeAkun       = $row['KodeAkun'];
           $kodeRekeningWithFormat = $row['concatKodeRekening'];

           //setting khusus kode akun untuk jenis industri DESA
           /*$settingValue  = GetSettingValue('kelompok_industri');

           if ($settingValue == 'Desa')
           {

             $prefixAkun = substr($kodePlainText,0,1);
             $prefixAkun = ($prefixAkun == '4') ? '1' : $prefixAkun;
             $prefixAkun = ($prefixAkun == '5') ? '2' : $prefixAkun;
             $prefixAkun = ($prefixAkun == '6') ? '3' : $prefixAkun;

             $strNewKodeAkun = $prefixAkun.substr($kodePlainText,1,strlen($kodePlainText));
             $kodeWithFormat =  ($header == 1 ) ? "<b>".$strNewKodeAkun."</b>" : $strNewKodeAkun;

           }*/

           $concatCode     = $row['KodeInduk'].'.'.$row['KodeAkun'];

           $IDArusKas      = ($IDArusKas == '') ? '1' : $IDArusKas;

           $strSpace = '';

           for($i = 1; $i <= $level ; $i++)
           {
            $strSpace .= "<img src='assets/images/space.png'/>";
          }

           $selectQuery = $this->db->query("SELECT count(id_akun) as CountAkun
                                            FROM mst_akun
                                            WHERE id_induk = '".$IDAkun."' ");

           $arrSelectQuery = ( $selectQuery->num_rows() > 0 ) ? $selectQuery->row_array() : array("CountAkun" => 0 );

           $CountAkun  = $arrSelectQuery['CountAkun'];

           $imgRoot   = "<span class='glyphicon glyphicon-home'></span>".$strSpace;
           $imgFolder = $strSpace."<span class='glyphicon glyphicon-folder-open'></span><img src='assets/images/space.png'/>";
           $imgItem   = $strSpace."<span class='glyphicon glyphicon-folder-close'></span><img src='assets/images/space.png'/>";

           $imgChild  = ( $CountAkun > 0 ) ? $imgFolder : $imgItem;

           $kodeWithFormat = ($level == 1) ? $imgRoot.$kodeWithFormat : $kodeWithFormat;
           $kodeWithFormat = ($level > 1) ?  $imgChild.$kodeWithFormat : $kodeWithFormat;

           $btnTambah   = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkun();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';
           $btnUbah     = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-warning\' onclick=\'UbahAkun();\'><span class=\'glyphicon glyphicon-pencil\' aria-hidden=\'true\'></button>&nbsp;';
           $btnHapus    = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-danger\' onclick=\'HapusAkun();\'><span class=\'glyphicon glyphicon-trash\' aria-hidden=\'true\'></button>&nbsp;';

           $kelompokRekening = ($level == 1) ? substr($concatCode,2,1) : substr($concatCode,0,1);

           $btnKodering = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'BrowseKodering('.$kelompokRekening.', this)\'><span class=\'glyphicon glyphicon-folder-open\' aria-hidden=\'true\'></button>';

           //$isSuperAdmin = $this->isSuperAdminAccess() == 1;

           //$btnAdd   = (!$isSuperAdmin && ($level == 1 || $level == 2 ) || ($concatCode == '3.1.1.1'  || $concatCode == '3.1.1.2' || $concatCode == '3.1.1.3')) ? '' : $btnTambah;

           $btnKodering = ($header == '0') ? $btnKodering : '';

           $btnDelete = ($builtin == 1) ? '' :  $btnHapus;

           //hitung dan masukkan untuk saldo awalnya
           $isAkunLink = (substr($concatCode,0,5) == '3.1.1') && in_array($IDAkun, GetCOAAkunLinkExclude(array('Saldo Awal', 'Surplus/Defisit Periode Lalu', 'Surplus/Defisit Periode Berjalan')) );

           $btnDelete = ($level >= 4) ? $btnHapus : '';
           $btnDelete = ($isAkunLink) ? '' : $btnDelete;
           //$btnDelete = ($isSuperAdmin) ? $btnHapus : $btnDelete;

           //$actionList = $btnAdd.$btnUbah.$btnDelete;

           //$actionList = $isSuperAdmin ? $btnAdd.$btnUbah.$btnDelete : $actionList;

           $data2 = $this->db->query("SELECT id_akunlink as IDAkunLink FROM ref_akunlink WHERE id_akun = '".$IDAkun."' ");
           $isKodeAkunLink = $data2->num_rows() > 0;

           //$actionList = (substr($concatCode, 0, 1) == '7')? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
           //$actionList = (substr($concatCode, 0, 6) == '1.3.99') ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
           //$actionList = ($isAkunLink) ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;

           //$actionList = ($isKodeAkunLink) ? $btnUbah : $actionList;

           //$actionList .= '&nbsp;'.$btnKodering;

           $hasil .= '{"id"             : "'.$IDAkun .'",
                      "IDAkunKel"       : "'.$IDAkunKel.'",
                      "IDAkunTipe"      : "'.$IDAkunTipe .'",
                      "IDArusKas"       : "'.$IDArusKas .'",
                      "kodePlainText"   : "'.$kodePlainText.'",
                      "kodeWithFormat"  : "'.$kodeWithFormat.'",
                      "namaPlainText"   : "'.$namaPlainText.'",
                      "namaAkunTipe"    : "'.$namaAkunTipe.'",
                      "namaWithFormat"  : "'.$namaWithFormat.'",
                      "kodeRekeningWithFormat" : "'.$kodeRekeningWithFormat.'",
                      "saldoNormal"     : "'.$saldoNormal.'",
                      "level"           : "'.$level.'",
                      "induk"           : "'.$IDInduk.'",
                      "indukKode"       : "'.$kodeInduk.'",
                      "AkunKode"        : "'.$kodeAkun.'",
                      "action"          : "aaaa"},';

           $hasil = $this->recursiveDataKodeAkun($row['IDAkun'], $hasil);
          }

          return $hasil;
      }

      

    }
?>
