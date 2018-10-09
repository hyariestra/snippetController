<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

    class Laporan_model extends CI_Model{

	    public function __construct() {
	        parent::__construct();
	    }



	    public function GetOperasionalTreeView($periode, $nilai)
	    {
	    	return $this->treeDataKodeAkun(array("periode" => $periode, "nilai" => $nilai, "akunInduk" => array('4','5','6','8','9')) );

	    }

      public function GetCALKTreeView($periode, $nilai, $arrAkunInduk, $formatAsetTetap = false)
      {
        return $this->treeDataKodeAkunCALK(array("periode" => $periode, "nilai" => $nilai, "akunInduk" => $arrAkunInduk, "formatAssetTetap" => $formatAsetTetap));

      }

	private function treeDataKodeAkun($tipeAkun)
      {
        
        $selectQuery = $this->db->query("SELECT id_akun as IDAkun FROM mst_akun 
                                        WHERE kode_induk = 0 and level = 1 and kode_akun = '".$tipeAkun."' ");

        $arrSelectQuery = ( $selectQuery->num_rows() > 0 ) ? $selectQuery->row_array() : array("IDAkun" => 0 ); 
           
        $this->IDTipeAkun = ($tipeAkun <> '0') ? $arrSelectQuery['IDAkun'] : '0';

        $strJsonData = '[';
        $strHeader = '';

        if ($tipeAkun > 0)
        {
          $strHeader = '';
          $strJsonData = '[';

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
              $strSpace .= "<img src='assets/images/icons/space.png'/>";
             }

             $imgRoot   = "<span class='glyphicon glyphicon-home'></span>".$strSpace;
            
             //setting khusus kode akun untuk jenis industri DESA
             $settingValue  = "";

             if ($settingValue == 'Desa')
             {
               $prefixAkun = substr($kodePlainText,0,1);
               $prefixAkun = ($prefixAkun == '4') ? '1' : $prefixAkun;
               $prefixAkun = ($prefixAkun == '5') ? '2' : $prefixAkun;
               $prefixAkun = ($prefixAkun == '6') ? '3' : $prefixAkun;

               $strNewKodeAkun = $prefixAkun.substr($kodePlainText,1,strlen($kodePlainText));
               $kodeWithFormat =  "<b>".$strNewKodeAkun."</b>";

            }

             $kodeWithFormat = $imgRoot.$kodeWithFormat;
            
             //$btnTambah   = '<button buttonType=\'actionItems\' buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkun();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';  
             $btnTambah   = '<button buttonType=\'actionItems\' buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkunBaru();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';  
             $btnUbah     = '<button buttonType=\'actionItems\' buttonType=\'actionItems\' class= \'btn btn-xs btn-warning\' onclick=\'UbahAkun();\'><span class=\'glyphicon glyphicon-pencil\' aria-hidden=\'true\'></button>&nbsp;';
             
             $kelompokRekening = ($level == 1) ? substr($concatCode,2,1) : substr($concatCode,0,1);

             $btnKodering = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'BrowseKodering('.$kelompokRekening.', this)\'><span class=\'glyphicon glyphicon-folder-open\' aria-hidden=\'true\'></button>&nbsp;'; 

             $isSuperAdmin = $this->isSuperAdminAccess() == 1;

             $btnAdd   = (!$isSuperAdmin && ($level == 1 || $level == 2 ) ) ? '' : $btnTambah;
             
             $btnKodering = ($header == '0') ? $btnKodering : '';

             $actionList = $btnAdd.$btnUbah.$btnKodering;
             
             $actionList = (substr($concatCode, 0, 3) == '0.7') ? '' : $actionList;
          
             $strHeader = '[{"id"             : "'.$IDAkun.'", 
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
                            "Header"    	  : "'.$header.'", 
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
           $strJsonData .= '}]';

        }  
        else
        {
        
          $strJsonData .= $this->recursiveDataKodeAkun($this->IDTipeAkun, $strJsonData);
          $strJsonData = substr($strJsonData, 1, strlen($strJsonData) - 2);
          $strJsonData .= ']';
        }

        return $strJsonData;
      }
    
      private function recursiveDataKodeAkun($parent=0, $hasil)
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
           $settingValue  = "";

           if ($settingValue == 'Desa')
           {

             $prefixAkun = substr($kodePlainText,0,1);
             $prefixAkun = ($prefixAkun == '4') ? '1' : $prefixAkun;
             $prefixAkun = ($prefixAkun == '5') ? '2' : $prefixAkun;
             $prefixAkun = ($prefixAkun == '6') ? '3' : $prefixAkun;

             $strNewKodeAkun = $prefixAkun.substr($kodePlainText,1,strlen($kodePlainText));
             $kodeWithFormat =  ($header == 1 ) ? "<b>".$strNewKodeAkun."</b>" : $strNewKodeAkun;

            }

           $concatCode     = $row['KodeInduk'].'.'.$row['KodeAkun'];

           $IDArusKas      = ($IDArusKas == '') ? '1' : $IDArusKas;

           $strSpace = '';

           for($i = 1; $i <= $level ; $i++)
           {
            $strSpace .= "<img src='assets/images/icons/space.png'/>";
           }

           $selectQuery = $this->db->query("SELECT count(id_akun) as CountAkun 
                                            FROM mst_akun 
                                            WHERE id_induk = '".$IDAkun."' ");

           $arrSelectQuery = ( $selectQuery->num_rows() > 0 ) ? $selectQuery->row_array() : array("CountAkun" => 0 ); 
           
           $CountAkun  = $arrSelectQuery['CountAkun'];

           $imgRoot   = "<span class='glyphicon glyphicon-home'></span>".$strSpace;
           $imgFolder = $strSpace."<span class='glyphicon glyphicon-folder-open'></span><img src='assets/images/icons/space.png'/>";
           $imgItem   = $strSpace."<span class='glyphicon glyphicon-folder-close'></span><img src='assets/images/icons/space.png'/>";
           
           $imgChild  = ( $CountAkun > 0 ) ? $imgFolder : $imgItem;

           $kodeWithFormat = ($level == 1) ? $imgRoot.$kodeWithFormat : $kodeWithFormat;
           $kodeWithFormat = ($level > 1) ?  $imgChild.$kodeWithFormat : $kodeWithFormat;

           //$btnTambah   = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkun();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';  
           $btnTambah   = '<button buttonType=\'actionItems\' buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkunBaru(this, '.$IDAkun.');\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';  
           //$btnUbah     = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-warning\' onclick=\'UbahAkun();\'><span class=\'glyphicon glyphicon-pencil\' aria-hidden=\'true\'></button>&nbsp;';
           $btnUbah     = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-warning\' onclick=\'UbahAkunBaru(this, '.$IDAkun.');\'><span class=\'glyphicon glyphicon-pencil\' aria-hidden=\'true\'></button>&nbsp;';
           $btnHapus    = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-danger\' onclick=\'HapusAkun(this, '.$IDAkun.');\'><span class=\'glyphicon glyphicon-trash\' aria-hidden=\'true\'></button>&nbsp;';  
          
           

           $kelompokRekening = ($level == 1) ? substr($concatCode,2,1) : substr($concatCode,0,1);

           $btnKodering = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'BrowseKodering('.$kelompokRekening.', this)\'><span class=\'glyphicon glyphicon-folder-open\' aria-hidden=\'true\'></button>'; 

           $isSuperAdmin = $this->isSuperAdminAccess() == 1;

           $btnAdd   = (!$isSuperAdmin && ($level == 1 || $level == 2 ) || ($concatCode == '3.1.1.1'  || $concatCode == '3.1.1.2' || $concatCode == '3.1.1.3' || $level == 3)) ? '' : $btnTambah;
          
           $btnKodering = ($header == '0' ) ? $btnKodering : '';

           $btnDelete = ($builtin == 1) ? '' :  $btnHapus; 

           //hitung dan masukkan untuk saldo awalnya
           $isAkunLink = (substr($concatCode,0,5) == '3.1.1') && in_array($IDAkun, GetCOAAkunLinkExclude(array('Saldo Awal', 'Surplus/Defisit Periode Lalu', 'Surplus/Defisit Periode Berjalan')) );

           $btnDelete = ($level >= 4) ? $btnHapus : '';
           $btnDelete = ($isAkunLink) ? '' : $btnDelete;
           $btnDelete = ($isSuperAdmin) ? $btnHapus : $btnDelete;

           $actionList_ = $btnAdd.$btnUbah.$btnDelete; 

           $actionList = $concatCode == '0.1' ? '' : $actionList_;

           $actionList = $isSuperAdmin ? ($concatCode == '0.1' ? '' : $btnAdd.$btnUbah.$btnDelete ) : $actionList;
           
           $data2 = $this->db->query("SELECT id_akunlink as IDAkunLink FROM ref_akunlink WHERE id_akun = '".$IDAkun."' ");
           $isKodeAkunLink = $data2->num_rows() > 0;

           $actionList = (substr($concatCode, 0, 1) == '7')? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
           $actionList = (substr($concatCode, 0, 6) == '1.3.99') ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
           $actionList = ($isAkunLink) ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
          
           $actionList = ($isKodeAkunLink) ? $btnUbah : $actionList;
              
           $actionList .= ($concatCode == '0.1' ? '' : '&nbsp;'.$btnKodering) ;

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
                      "Header"	        : "'.$header.'", 
                      "action"          : "'.$actionList.'"},';

           $hasil = $this->recursiveDataKodeAkun($row['IDAkun'], $hasil);
          }  //foreach( $selectQuery->result_array() as $row )

          return $hasil;
      } 

      private function GetTotalSurplusDefisit($tglAwal, $tglAkhir)
      {
          
          $IDUnitKerja      = $_SESSION['IDUnitKerja'];
          //$strValueUPTD       = GetSQLUPTDValue('select','value');

          $data = $this->db->query("SELECT COALESCE(SUM(COALESCE(debet_akhir,0)),0) - COALESCE(SUM(COALESCE(kredit_akhir, 0)),0) as Total  
                                  FROM trx_jurnal trxJurnal
                                  INNER JOIN trx_jurnal_det trxJurnalDet  
                                  ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal
                                  WHERE id_unit_kerja  = '".$IDUnitKerja."' AND ".$strValueUPTD."
                                  AND (tgl_jurnal >= '".$tglAwal." 00:00:00' and tgl_jurnal <= '".$tglAkhir." 23:59:59') 

                                  AND id_akun in (select id_akun from trx_ns  where 
                                          (concat(kode_induk,'.',kode_akun) LIKE '4%' or concat(kode_induk,'.',kode_akun) LIKE '5%' or 
                                            concat(kode_induk,'.',kode_akun) LIKE '6%' or  
                                            concat(kode_induk,'.',kode_akun) LIKE '8%' or concat(kode_induk,'.',kode_akun) LIKE '9%') ) ");

                                 // AND (kode_akun LIKE '4%' OR kode_akun LIKE '5%' OR kode_akun LIKE '6%' OR kode_akun LIKE '8%' OR kode_akun LIKE '9%') ");
       
          $total = $data->first_row()->Total;
          $total = $total * -1;

          return $total;
      }

      private function GetTotalOperasional($kodeAkun, $tglAwal, $tglAkhir)
      {
          
          $IDUnitKerja      = $_SESSION['IDUnitKerja'];
          //$strValueUPTD       = GetSQLUPTDValue('select','value');

          $data = $this->db->query("SELECT COALESCE(SUM(COALESCE(debet_akhir,0)),0) - COALESCE(SUM(COALESCE(kredit_akhir, 0)),0) as Total  
                                  FROM trx_jurnal trxJurnal
                                  INNER JOIN trx_jurnal_det trxJurnalDet  
                                  ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal
                                  WHERE id_unit_kerja  = '".$IDUnitKerja."' AND ".$strValueUPTD."
                                  AND (tgl_jurnal >= '".$tglAwal." 00:00:00' and tgl_jurnal <= '".$tglAkhir." 23:59:59')
                                  AND id_akun in (select id_akun from trx_ns  where concat(kode_induk,'.',kode_akun) like  '".$kodeAkun."%'  ) ");
        

          $total = $data->first_row()->Total;
          
          $total = GetTotal($kodeAkun, $total);

          return $total;
      }

      private function GetSubTotalOperasional($kodeAkun, $tglAwal, $tglAkhir)
      {
          
          $IDUnitKerja      = $_SESSION['IDUnitKerja'];
          //$strValueUPTD       = GetSQLUPTDValue('select','value');

          $data = $this->db->query("SELECT COALESCE(SUM(COALESCE(debet_akhir,0)),0) - COALESCE(SUM(COALESCE(kredit_akhir, 0)),0) as Total  
                                  FROM trx_jurnal trxJurnal
                                  INNER JOIN trx_jurnal_det trxJurnalDet  
                                  ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal
                                  WHERE id_unit_kerja  = '".$IDUnitKerja."' AND ".$strValueUPTD."
                                  AND (tgl_jurnal >= '".$tglAwal." 00:00:00' and tgl_jurnal <= '".$tglAkhir." 23:59:59')
                                  AND id_akun in (select id_akun from trx_ns  where 
                                          concat(kode_induk,'.',kode_akun) LIKE '".$kodeAkun."%') ");
         
          $total = $data->first_row()->Total;
          
          $total = GetTotal($kodeAkun, $total);

          return $total;
      }
	  
	  public function GetTotalCALKSaldoAwal($kodeAkun, $tglAwal = '')
      {
         $CI=&get_instance(); 

          $IDUnitKerja  = $_SESSION['IDSekolah'];
          //$strValueUPTD   = GetSQLUPTDValue('select','value');

          $totalSaldoAwal = 0;

          $qryGetSaldo = $CI->db->query("SELECT COALESCE(SUM( COALESCE(debet_akhir, 0) ) - SUM(COALESCE(kredit_akhir, 0)) , 0) AS Total  
                                        FROM trx_jurnal trxJurnal
                                        INNER JOIN  
                                        trx_jurnal_det trxJurnalDet
                                        ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                                        WHERE id_unit_kerja  = '".$IDUnitKerja."'
                                        and id_akun in (select id_akun from mst_akun where concat(kode_induk,'.',kode_akun) like  '".$kodeAkun."%'  ) 
                                        and id_sumber_trans = (select id_sumber_trans from ref_sumber_trans where kode = 'SA')  ");
        
          $totalSaldoAwal = $qryGetSaldo->first_row()->Total;

          $qryGetSaldo = $CI->db->query("SELECT COALESCE(SUM( COALESCE(debet_akhir, 0) ) - SUM(COALESCE(kredit_akhir, 0)) , 0) AS Total  
                                        FROM trx_jurnal trxJurnal
                                        INNER JOIN  
                                        trx_jurnal_det trxJurnalDet
                                        ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                                        WHERE (tgl_jurnal < '".$tglAwal." 23:59:59') 
                                        AND id_unit_kerja  = '".$IDUnitKerja."'  
                                        and id_sumber_trans not in (select id_sumber_trans from ref_sumber_trans where kode = 'SA') 
                                        AND id_akun in (select id_akun from mst_akun where 
                                            (concat(kode_induk,'.',kode_akun) LIKE '".$kodeAkun."%') ) ");
        
          $totalSaldoAwal = $totalSaldoAwal + $qryGetSaldo->first_row()->Total;
   
          $totalSaldoAwal = GetTotal($kodeAkun, $totalSaldoAwal);

          return $totalSaldoAwal;
      }

      public function GetTotalCALKPenambahan($kodeAkun, $tglAwal = '', $tglAkhir = '')
      {
          $CI=&get_instance(); 

          $IDUnitKerja  = $_SESSION['IDSekolah'];
          //$strValueUPTD   = GetSQLUPTDValue('select','value');

          $TotalSaldo = 0;

          $strPenambahan = (GetSaldoNormal($kodeAkun) == 'D') ? " COALESCE(SUM( COALESCE(debet_akhir, 0) ) ) " : " COALESCE(SUM( COALESCE(kredit_akhir, 0) ) ) ";

          $kodeAkun = (substr($kodeAkun, 0, 1) == '0') ? substr($kodeAkun, 2, strlen($kodeAkun) ) : $kodeAkun;

          $qryGetSaldo = $CI->db->query("SELECT ".$strPenambahan." AS Total  
                                        FROM trx_jurnal trxJurnal
                                        INNER JOIN  
                                        trx_jurnal_det trxJurnalDet
                                        ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                                        WHERE (tgl_jurnal >= '".$tglAwal." 00:00:00' and tgl_jurnal<'".$tglAkhir." 23:59:59') 
                                        AND id_unit_kerja  = '".$IDUnitKerja."' 
                                        and id_sumber_trans not in (select id_sumber_trans from ref_sumber_trans where kode = 'SA') 
                                        AND id_akun in (select id_akun from mst_akun where 
                                        (concat(kode_induk,'.',kode_akun) LIKE '".$kodeAkun."%') ) ");
        
          $TotalSaldo = $qryGetSaldo->first_row()->Total;
   
          $TotalSaldo = GetTotal($kodeAkun, $TotalSaldo);

          return $TotalSaldo;
      }

      public function GetTotalCALKPengurangan($kodeAkun, $tglAwal = '', $tglAkhir = '')
      {
         $CI=&get_instance(); 

          $IDUnitKerja  = $_SESSION['IDSekolah'];
          //$strValueUPTD   = GetSQLUPTDValue('select','value');

          $TotalSaldo = 0;
          
          $kodeAkun = (substr($kodeAkun, 0, 1) == '0') ? substr($kodeAkun, 2, strlen($kodeAkun) ) : $kodeAkun;

          $strPengurangan = (GetSaldoNormal($kodeAkun) == 'D') ? " COALESCE(SUM( COALESCE(kredit_akhir, 0) ) ) " : " COALESCE(SUM( COALESCE(debet_akhir, 0) ) ) ";

          $qryGetSaldo = $CI->db->query("SELECT ".$strPengurangan." AS Total  
                                        FROM trx_jurnal trxJurnal
                                        INNER JOIN  
                                        trx_jurnal_det trxJurnalDet
                                        ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                                        WHERE (tgl_jurnal >= '".$tglAwal." 00:00:00' and tgl_jurnal<'".$tglAkhir." 23:59:59') 
                                        AND id_unit_kerja  = '".$IDUnitKerja."'
                                        and id_sumber_trans not in (select id_sumber_trans from ref_sumber_trans where kode = 'SA') 
                                        AND id_akun in (select id_akun from mst_akun where 
                                            (concat(kode_induk,'.',kode_akun) LIKE '".$kodeAkun."%') ) ");
        
          $TotalSaldo = $qryGetSaldo->first_row()->Total;
   
          $TotalSaldo = GetTotal($kodeAkun, $TotalSaldo);

          return $TotalSaldo;
      }

      private function GetTotalAkunCALKPeriodeAwal($kodeAkun, $tglAwal = '', $tglAkhir = '')
      {
          $CI=&get_instance(); 

          $IDUnitKerja  = $_SESSION['IDSekolah'];
          //$strValueUPTD   = GetSQLUPTDValue('select','value');

          $TotalSaldo = 0;

          $kodeAkun = (substr($kodeAkun, 0, 1) == '0') ? substr($kodeAkun, 2, strlen($kodeAkun) ) : $kodeAkun;

          $qryGetSaldo = $CI->db->query("SELECT COALESCE(SUM( COALESCE(debet_akhir, 0) ) - SUM(COALESCE(kredit_akhir, 0)) , 0) AS Total  
                                        FROM trx_jurnal trxJurnal
                                        INNER JOIN  
                                        trx_jurnal_det trxJurnalDet
                                        ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                                        WHERE id_unit_kerja  = '".$IDUnitKerja."'    
                                        and id_akun in (select id_akun from mst_akun where concat(kode_induk,'.',kode_akun) LIKE  '".$kodeAkun."%'  ) 
                                        and id_sumber_trans = (select id_sumber_trans from ref_sumber_trans where kode = 'SA')  ");
        
          $totalSDPeriodeLalu = $qryGetSaldo->first_row()->Total;

          $qryGetSaldo = $CI->db->query("SELECT COALESCE(SUM( COALESCE(debet_akhir, 0) ) - SUM(COALESCE(kredit_akhir, 0)) , 0) AS Total  
                                        FROM trx_jurnal trxJurnal
                                        INNER JOIN  
                                        trx_jurnal_det trxJurnalDet
                                        ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                                        WHERE (tgl_jurnal >= '".$tglAwal." 00:00:00' and tgl_jurnal<'".$tglAkhir." 23:59:59') 
                                        AND id_unit_kerja  = '".$IDUnitKerja."' 
                                        and id_sumber_trans not in (select id_sumber_trans from ref_sumber_trans where kode = 'SA') 
                                        AND id_akun in (select id_akun from mst_akun where 
                                            (concat(kode_induk,'.',kode_akun) LIKE '".$kodeAkun."%') ) ");
        
          $totalSDPeriodeLalu = $totalSDPeriodeLalu + $qryGetSaldo->first_row()->Total;
   
          $totalSDPeriodeLalu = GetTotal($kodeAkun, $totalSDPeriodeLalu);

          return $totalSDPeriodeLalu;
      }

      private function GetTotalAkunCALKPeriodeAkhir($kodeAkun, $tglAwal = '', $tglAkhir = '')
      {
         $CI=&get_instance(); 

          $IDUnitKerja  = $_SESSION['IDUnitKerja'];
          //$strValueUPTD = GetSQLUPTDValue('select','value');

          $TotalSaldo = 0;
         
          $qryGetSaldo = $CI->db->query("SELECT COALESCE(SUM( COALESCE(debet_akhir, 0) ) - SUM(COALESCE(kredit_akhir, 0)) , 0) AS Total  
                                        FROM trx_jurnal trxJurnal
                                        INNER JOIN  
                                        trx_jurnal_det trxJurnalDet
                                        ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                                        WHERE (tgl_jurnal >= '".$tglAwal." 00:00:00' and tgl_jurnal<='".$tglAkhir." 23:59:59') 
                                        AND id_unit_kerja  = '".$IDUnitKerja."' 

                                        AND id_akun in (select id_akun from mst_akun where 
                                            (concat(kode_induk,'.',kode_akun) LIKE '4%' or concat(kode_induk,'.',kode_akun) LIKE '5%' or 
                                             concat(kode_induk,'.',kode_akun) LIKE '6%' or 
                                             concat(kode_induk,'.',kode_akun) LIKE '8%' or concat(kode_induk,'.',kode_akun) LIKE '9%') )
                                       ");
        
       
          $totalSDPeriodeBerjalan = $qryGetSaldo->first_row()->Total;
   
          $totalSDPeriodeBerjalan = GetTotal($kodeAkun, $totalSDPeriodeBerjalan);

          return $totalSDPeriodeBerjalan;
      }
	  
	  private  function treeDataKodeAkunCALK($arrData = array())
      {
          
           
            $loop=0;

            $arrDataAkun        = $arrData['akunInduk'];
            $arrDataTanggal     = $arrData['periode'];
            
            $formatAssetTetap   = $arrData['formatAssetTetap'];  
            $periodeAwal        = $arrDataTanggal['periodeAwal'];
            $periodeAkhir       = $arrDataTanggal['periodeAkhir'];
            $tglAwal            = $arrDataTanggal['tglAwal'];
            $tglAkhir           = $arrDataTanggal['tglAkhir'];
            $tglPembandingAwal  = $arrDataTanggal['tglPembandingAwal'];
            $tglPembandingAkhir = $arrDataTanggal['tglPembandingAkhir'];

            $strHTMLOut =  "<table border='1' style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif; width:100%'>
                            <col style='width: 60%'>
                            <col style='width: 40%'>
                            <thead>
                            <tr><td colspan='2'>Akun ini terdiri dari : </td></tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>
                                  <table border=0 style='border-collapse:collapse; border-spacing:0; width:100%'>
                                    <col style='width: 50%'>
                                    <col style='width: 50%'>
                                    <tr><td class='textCenter' style='border-style:none;' ><b>".$periodeAwal."</b></td><td  class='textCenter'><b>".$periodeAkhir."</b></td></tr>
                                    <tr><td class='textCenter' style=>Rp</td><td  class='textCenter'>Rp</td></tr>
                                  </table>
                              </td>
                            </tr>
                            </thead>";

            if ($formatAssetTetap)
            {
              $strHTMLOut = "<table border='0' style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                              <col style='width: 60%'>
                              <col style='width: 40%'>
                              <thead>
                              <tr><td colspan='2'>Akun ini terdiri dari : </td></tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <table border='0' style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                      <tr><td colspan='4' class='textCenter'><b>".$periodeAwal."</b></td></tr>
                                      <tr><td class='textCenter'>Saldo Awal</td><td  class='textCenter'>Penambahan</td><td  class='textCenter'>Pengurangan</td><td  class='textCenter'>Saldo Akhir</td></tr>
                                    </table>
                                </td>
                              </tr>
                              </thead>";
            }                

            $arrHTMLOut = array();

            //foreach ($arrData as $value) {
            for($i=0;$i<count($arrDataAkun);$i++) 
            {
              
              $value = $arrDataAkun[$i]; 

              $arrHTMLOut[$loop] = '';
              
              $selectQuery = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, kode_akun AS kodeAkun, header as Header,   
                                               nama_akun AS NamaAkun, kode_induk as KodeInduk, level as Level, saldo_normal as SaldoNormal  
                                               FROM mst_akun 
                                               WHERE concat(kode_induk,'.',kode_akun) = '".$value."' 
                                               ORDER BY kodeUrut ASC ");

              $row = $selectQuery->row_array();

               $kodeInduk      = $row['KodeInduk'];
               $header         = $row['Header'];
               $kodePlainText  = $row['kodeAkun'];
               $namaPlainText  = $row['NamaAkun']; 
               $saldoNormal    = $row['SaldoNormal'];
               $level          = $row['Level'];
               $kodeInduk      = ($kodeInduk == '0') ? $row['kodeAkun'] : $kodeInduk; 
               $kodePlainText  = ($level == 1) ? $kodePlainText : $kodeInduk.'.'.$kodePlainText;
               $kodeWithFormat = ($header == 1 ) ? "<b>".$kodePlainText."</b>" : $kodePlainText;
               $namaWithFormat = ($header == 1 ) ? "<b>".$namaPlainText."</b>" : $namaPlainText;
               $kodeAkun       = $row['kodeAkun'];

               $concatKode  = $row['KodeInduk'].'.'.$row['kodeAkun'];
               $strNamaAkun = $namaWithFormat;         
               

               $hasil =  "<tr>
                              <td>".$strNamaAkun."</td>
                              <td>
                                  <table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                    <tr><td style='width:150px' class='textCenter'>&nbsp;</td><td style='width:150px' class='textCenter'>&nbsp;</td></tr>
                                  </table>
                              </td>
                          </tr>";

               if ($formatAssetTetap)
               {
                   /* $hasil =  "<tr>
                          <td>".$strNamaAkun."</td>
                          <td>
                              <table style='border-collapse:collapse; border-spacing:0;'>
                                <col style='width: 30%'>
                                <col style='width: 30%'>
                                <col style='width: 30%'>
                                <col style='width: 30%'>
                                <tr><td class='textRight'>&nbsp;</td><td class='textRight'>&nbsp;</td><td class='textRight'>&nbsp;</td><td class='textRight'>&nbsp;</td></tr>
                              </table>
                          </td>
                      </tr>";*/
               }          

               $arrHTMLOut[$loop] = $hasil;
               $strHTMLOut.=  $arrHTMLOut[$loop];
               $loop++;
               $arrHTMLOut[$loop] = '';

              $arrHTMLOut[$loop] = $this->recursiveDataKodeAkunCALK($concatKode, $arrHTMLOut[$loop], $arrDataTanggal, $formatAssetTetap, false);
              $strHTMLOut.=  $arrHTMLOut[$loop];
              
              $strNamaAkun = '<b>Jumlah </b>'.$strNamaAkun;

               if ($formatAssetTetap)
               {
                
                    $totalSaldoAwal   = $this->GetTotalCALKSaldoAwal($concatKode, $tglAwal);
                    $totalPenambahan  = $this->GetTotalCALKPenambahan($concatKode, $tglAwal, $tglAkhir);
                    $totalPengurangan = $this->GetTotalCALKPengurangan($concatKode, $tglAwal, $tglAkhir);
                    $totalSaldoAkhir  = ($totalSaldoAwal + $totalPenambahan) - $totalPengurangan;

                    $hasil =  "<tr>
                          <td>".$strNamaAkun."</td>
                          <td>
                              <table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                   <col style='width: 34%'>
                                   <col style='width: 34%'>
                                   <col style='width: 34%'>
                                   <col style='width: 34%'>
                                <tr><td class='textRight'><b>".formatCurrency($totalSaldoAwal)."</b></td><td class='textRight'><b>".formatCurrency($totalPenambahan)."</b></td><td class='textRight'><b>".formatCurrency($totalPengurangan)."</b></td><td class='textRight'><b>".formatCurrency($totalSaldoAkhir)."</b></td></tr>
                              </table>
                          </td>
                      </tr>";
               }else
               {
               
                  $totalPeriodeAwal            = $this->GetTotalAkunCALKPeriodeAwal($concatKode, $tglAwal, $tglAkhir);
                  $totalPeriodeAwalPembanding  = $this->GetTotalAkunCALKPeriodeAwal($concatKode, $tglPembandingAwal, $tglPembandingAkhir);
              
                  $hasil =  "<tr>
                              <td>".$strNamaAkun."</td>
                              <td>
                                  <table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                    <tr><td style='width:150px' class='textRight'><b>".formatCurrency($totalPeriodeAwal)."</b></td><td style='width:150px' class='textRight'><b>".formatCurrency($totalPeriodeAwalPembanding)."</b></td></tr>
                                  </table>
                              </td>
                          </tr>";
               }     

               $strHTMLOut .=  $hasil;   
              
              $loop++;
            }

            $strHTMLOut .= "</table>"; 

           if ($formatAssetTetap)
           {

              $strHTMLOut .= "<br><table border='0' style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                <col style='width: 60%'>
                                <col style='width: 40%'>
                                <thead>
                                <tr><td colspan='2'>Akun ini terdiri dari : </td></tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td>
                                      <table border=1 style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                        <col style='width: 34%'>
                                        <col style='width: 34%'>
                                        <col style='width: 34%'>
                                        <col style='width: 34%'>
                                        <tr><td colspan='4' class='textCenter'><b>".$periodeAkhir."</b></td></tr>
                                        <tr><td class='textCenter'>Saldo Awal</td><td  class='textCenter'>Penambahan</td><td  class='textCenter'>Pengurangan</td><td  class='textCenter'>Saldo Akhir</td></tr>
                                      </table>
                                  </td>
                                </tr>
                                </thead>";

                $arrHTMLOut = array();

                //foreach ($arrData as $value) {
                for($i=0;$i<count($arrDataAkun);$i++) 
                {
                  
                  $value = $arrDataAkun[$i]; 

                  $arrHTMLOut[$loop] = '';
                  
                  $selectQuery = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, kode_akun AS kodeAkun, header as Header,   
                                                   nama_akun AS NamaAkun, kode_induk as KodeInduk, level as Level, saldo_normal as SaldoNormal  
                                                   FROM mst_akun 
                                                   WHERE concat(kode_induk,'.',kode_akun) = '".$value."' ");

                  $row = $selectQuery->row_array();

                   $kodeInduk      = $row['KodeInduk'];
                   $header         = $row['Header'];
                   $kodePlainText  = $row['kodeAkun'];
                   $namaPlainText  = $row['NamaAkun']; 
                   $saldoNormal    = $row['SaldoNormal'];
                   $level          = $row['Level'];
                   $kodeInduk      = ($kodeInduk == '0') ? $row['kodeAkun'] : $kodeInduk; 
                   $kodePlainText  = ($level == 1) ? $kodePlainText : $kodeInduk.'.'.$kodePlainText;
                   $kodeWithFormat = ($header == 1 ) ? "<b>".$kodePlainText."</b>" : $kodePlainText;
                   $namaWithFormat = ($header == 1 ) ? "<b>".$namaPlainText."</b>" : $namaPlainText;
                   $kodeAkun       = $row['kodeAkun'];

                   $concatKode  = $row['KodeInduk'].'.'.$row['kodeAkun'];
                   $strNamaAkun = $namaWithFormat;         
                   

                   if ($formatAssetTetap)
                   {
                        $hasil =  "<tr>
                              <td>".$strNamaAkun."</td>
                              <td>
                                  <table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                    <tr><td class='textRight'>&nbsp;</td><td class='textRight'>&nbsp;</td><td class='textRight'>&nbsp;</td><td class='textRight'>&nbsp;</td></tr>
                                  </table>
                              </td>
                          </tr>";
                   }
                   else
                   {

                     $totalPeriodeAwal            = $this->GetTotalAkunCALKPeriodeAwal($concatKode, $tglAwal, $tglAkhir);
                     $totalPeriodeAwalPembanding  = $this->GetTotalAkunCALKPeriodeAwal($concatKode, $tglPembandingAwal, $tglPembandingAkhir);

                     $strTotalPeriodeAwal   = ($header == 1) ? '<b>'.formatCurrency($totalPeriodeAwal).'</b>' : formatCurrency($totalPeriodeAwal);
                     $strTotalPeriodeAkhir  = ($header == 1) ? '<b>'.formatCurrency($totalPeriodeAwalPembanding).'</b>' : formatCurrency($totalPeriodeAwalPembanding);

                     $hasil =  "<tr>
                                  <td>".$strNamaAkun."</td>
                                  <td>
                                      <table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                        <tr><td style='width:150px' class='textCenter'>".$strTotalPeriodeAwal."</td><td style='width:150px' class='textCenter'>".$strTotalPeriodeAkhir."</td></tr>
                                      </table>
                                  </td>
                              </tr>";
                   }          

                   $arrHTMLOut[$loop] = $hasil;
                   $strHTMLOut.=  $arrHTMLOut[$loop];
                   $loop++;
                   $arrHTMLOut[$loop] = '';

                  $arrHTMLOut[$loop] = $this->recursiveDataKodeAkunCALK($concatKode, $arrHTMLOut[$loop], $arrDataTanggal, $formatAssetTetap, true);
                  $strHTMLOut.=  $arrHTMLOut[$loop];
                  
                  $strNamaAkun = '<b>Jumlah </b>'.$strNamaAkun;

                  $hasil =  "<tr>
                                  <td>".$strNamaAkun."</td>
                                  <td>
                                      <table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                        <tr><td style='width:150px' class='textCenter'>&nbsp;</td><td style='width:150px' class='textCenter'>&nbsp;</td></tr>
                                      </table>
                                  </td>
                              </tr>";

                   if ($formatAssetTetap)
                   {
                      $totalSaldoAwal   = $this->GetTotalCALKSaldoAwal($concatKode, $tglPembandingAwal);
                      $totalPenambahan  = $this->GetTotalCALKPenambahan($concatKode, $tglPembandingAwal, $tglPembandingAkhir);
                      $totalPengurangan = $this->GetTotalCALKPengurangan($concatKode, $tglPembandingAwal, $tglPembandingAkhir);
                      $totalSaldoAkhir  = ($totalSaldoAwal + $totalPenambahan) - $totalPengurangan;

                        $hasil =  "<tr>
                              <td>".$strNamaAkun."</td>
                              <td>
                                  <table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                    <tr><td class='textRight'><b>".formatCurrency($totalSaldoAwal)."</b></td><td class='textRight'><b>".formatCurrency($totalPenambahan)."</b></td><td class='textRight'><b>".formatCurrency($totalPengurangan)."</b></td><td class='textRight'><b>".formatCurrency($totalSaldoAkhir)."</b></td></tr>
                                  </table>
                              </td>
                          </tr>";
                   }     

                   $strHTMLOut .=  $hasil;   
                  
                  $loop++;
                }


              $strHTMLOut .= "</table>"; 

            }

            return $strHTMLOut;
        }
		
		
		private function recursiveDataKodeAkunCALK($parent=0, $hasil, $arrDataTanggal, $formatAssetTetap = false, $pembandingAsetTetap = false)
        {

          $parent = substr($parent, 0, 1) == '0' ? substr($parent, 2, strlen($parent)) : $parent;

          $selectQuery = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, concat(kode_induk, '.', kode_akun) AS kodeAkun, header as Header, 
                                           nama_akun AS NamaAkun, concat(kode_induk, '.', kode_akun) as KodeInduk, level as Level, saldo_normal as SaldoNormal  
                                           FROM  mst_akun 
                                           WHERE kode_induk = '".$parent."'
                                           ORDER BY kodeUrut ASC"); 
         
          $tglAwal            = $arrDataTanggal['tglAwal'];
          $tglAkhir           = $arrDataTanggal['tglAkhir'];
          $tglPembandingAwal  = $arrDataTanggal['tglPembandingAwal'];
          $tglPembandingAkhir = $arrDataTanggal['tglPembandingAkhir'];

          $total = 0;

          foreach( $selectQuery->result_array() as $row )
          {

           $kodeInduk      = $row['KodeInduk'];
           $header         = $row['Header'];
           $kodePlainText  = $row['kodeAkun'];
           $namaPlainText  = $row['NamaAkun']; 
           $saldoNormal    = $row['SaldoNormal'];
           $level          = $row['Level'];
           $kodeInduk      = ($kodeInduk == '0') ? $row['kodeAkun'] : $kodeInduk; 
           $kodeWithFormat = ($header == 1 ) ? "<b>".$kodePlainText."</b>" : $kodePlainText;
           $namaWithFormat = ($header == 1 ) ? "<b>".$namaPlainText."</b>" : $namaPlainText;
           
           $concatKode      = $row['KodeInduk'];

           $strSpace = '';

           for($i = 1; $i <= $level ; $i++)
           {
             $strSpace .= "&nbsp;&nbsp;";
           }

           if ($header == 1)
           {
             $headerAkunLoop = $kodeInduk;
           }

            $kodeWithFormat = $strSpace.$kodeWithFormat;

            $strNamaAkun =   $strSpace.$namaWithFormat;  

            $subTotalSaldoAwal          = 0;
            $subTotalPenambahan         = 0;
            $subTotalPengurangan        = 0;
            $subTotalSaldoAkhir         = 0;

            $subTotalPeriodeAwal        = 0;
            $subTotalPeriodePembanding  = 0;
            $totalPeriodeAwal           = 0;
            $totalPeriodeAkhir          = 0;

           if ($formatAssetTetap)
           {
              /*  $totalSaldoAwal   = 0;
                $totalPenambahan  = 0;
                $totalPengurangan = 0;
                $totalSaldoAkhir  = 0;
                
                $tglAwal  = ($pembandingAsetTetap) ? $tglPembandingAwal : $tglAwal;
                $tglAkhir = ($pembandingAsetTetap) ? $tglPembandingAkhir : $tglAkhir;

                $totalSaldoAwal   = $this->GetTotalCALKSaldoAwal($concatKode, $tglAwal);
                $totalPenambahan  = $this->GetTotalCALKPenambahan($concatKode, $tglAwal, $tglAkhir);
                $totalPengurangan = $this->GetTotalCALKPengurangan($concatKode, $tglAwal, $tglAkhir);
                $totalSaldoAkhir  = ($totalSaldoAwal + $totalPenambahan) - $totalPengurangan;

                $subTotalSaldoAwal    += $totalSaldoAwal;
                $subTotalPenambahan   += $totalPenambahan; 
                $subTotalPengurangan  += $totalPengurangan; 
                $subTotalSaldoAkhir   += $totalSaldoAkhir; 


                $totalSaldoAwal   = ($header) ? '' : $totalSaldoAwal;
                $totalPenambahan  = ($header) ? '' : $totalPenambahan;
                $totalPengurangan = ($header) ? '' : $totalPengurangan;
                $totalSaldoAkhir  = ($header) ? '' : $totalSaldoAkhir;

                $hasil .=  "<tr>
                          <td>".$strNamaAkun."</td>
                          <td>
                              <table style='border-collapse:collapse; border-spacing:0;'>
                                <col style='width: 34%'>
                                <col style='width: 34%'>
                                <col style='width: 34%'>
                                <col style='width: 34%'>
                                <tr><td class='textRight'>".formatCurrency($totalSaldoAwal)."</td><td class='textRight'>".formatCurrency($totalPenambahan)."</td><td class='textRight'>".formatCurrency($totalPengurangan)."</td><td class='textRight'>".formatCurrency($totalSaldoAkhir)."</td></tr>
                              </table>
                          </td>
                      </tr>";*/
           }            
           else
           {
				$totalPeriodeAwal            = $this->GetTotalAkunCALKPeriodeAwal($concatKode, $tglAwal, $tglAkhir);
				$totalPeriodeAwalPembanding  = $this->GetTotalAkunCALKPeriodeAwal($concatKode, $tglPembandingAwal, $tglPembandingAkhir);

				$strTotalPeriodeAwal        = ($header == 0) ? formatCurrency($totalPeriodeAwal) : '';
				$strTotalPeriodePembanding  = ($header == 0) ? formatCurrency( $totalPeriodeAwalPembanding) : '';

				$subTotalPeriodeAwal       += ($header == 1) ? $totalPeriodeAwal : 0;
				$subTotalPeriodePembanding += ($header == 1) ? $totalPeriodeAwalPembanding : 0;

				//tampilkan selain dari ekuitas yang dilink
				if (!in_array($concatKode, GetCOAAkunLinkExclude(array('Surplus/Defisit Periode Lalu', 'Surplus/Defisit Periode Berjalan', 'Saldo Awal')) ))
				{
					
								
					if(substr($kodePlainText,0,1) == 4 ||  substr($kodePlainText,0,1) == 5)
					{
						if($level <= 3)
						{
							$hasil .=  "<tr>
									<td>".$strNamaAkun."</td>
									<td>
										<table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
										  <tr><td style='width:150px' class='textRight'></td><td style='width:150px' class='textRight'></td></tr>
										</table>
									</td>
								</tr>";
								
							
						}
						elseif($level == 4)
						{
							
							$hasil .=  "<tr>
									<td>".$strNamaAkun."</td>
									<td>
										<table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
										  <tr><td style='width:150px' class='textRight'></td><td style='width:150px' class='textRight'></td></tr>
										</table>
									</td>
								</tr>";
								
							$idakunKategori = $this->db->query("SELECT 
							mst_akun.id_akun as idakun
							FROM mst_akun 
							WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '".$kodePlainText."'");
							
							
							$itemCALK = $this->db->query("SELECT mst_item.* FROM mst_kategori_item
							LEFT JOIN mst_item ON mst_item.id_kategori_item = mst_kategori_item.id_kategori_item
							WHERE mst_kategori_item.id_akun = '".$idakunKategori->first_row()->idakun."'");
							
							
							foreach($itemCALK->result_array() as $row)
							{
								
								if(substr($kodePlainText,0,1) == 4)
								{
							
									$strTotalPeriodeAwal = $this->db->query("SELECT 
									SUM(COALESCE((trx_penjualan_det.harga * trx_penjualan_det.jumlah_item),0) - COALESCE(trx_penjualan_det.potongan,0)) as total 
									FROM trx_penjualan
									LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
									WHERE trx_penjualan_det.id_item = '".$row['id_item']."'");
									
									$nominalJu = $this->db->query("SELECT 
									COALESCE(SUM(trx_jurnal_det.debet_akhir) - SUM(trx_jurnal_det.kredit_akhir), 0 ) as total,
									mst_akun.saldo_normal
									FROM trx_jurnal
									LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
									LEFT JOIN mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
									WHERE trx_jurnal_det.id_akun = '".$row['id_akunitem']."'");
									
									$nominalJUs = ($nominalJu->first_row()->saldo_normal == "D") ? $nominalJu->first_row()->total : $nominalJu->first_row()->total *-1;
									
								
								}
								elseif(substr($kodePlainText,0,1) == 5)
								{
									$strTotalPeriodeAwal = $this->db->query("SELECT 
									SUM(COALESCE((trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah),0) - COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total 
									FROM trx_pembelian_persediaan
									LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
									WHERE trx_pembelian_persediaan_det.id_item = '".$row['id_item']."'");
									
									$nominalJu = $this->db->query("SELECT 
									COALESCE(SUM(trx_jurnal_det.debet_akhir) - SUM(trx_jurnal_det.kredit_akhir), 0 ) as total, 
									mst_akun.saldo_normal
									FROM trx_jurnal
									LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
									LEFT JOIN mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
									WHERE trx_jurnal_det.id_akun = '".$row['id_akunitem']."'");
									$nominalJUs = ($nominalJu->first_row()->saldo_normal == "D") ? $nominalJu->first_row()->total : $nominalJu->first_row()->total *-1;
								}
								
								$nominal = @$strTotalPeriodeAwal->first_row()->total + @$nominalJUs;
								
								$hasil .=  "<tr>
									<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$row['nama_item']."</td>
									<td>
										<table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
										  <tr><td style='width:150px' class='textRight'>".formatCurrency($nominal)."</td><td style='width:150px' class='textRight'>".$strTotalPeriodePembanding."</td></tr>
										</table>
									</td>
								</tr>";
							}
						}
						
						
					}
					else
					{
						$hasil .=  "<tr>
									<td>".$strNamaAkun."</td>
									<td>
										<table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
										  <tr><td style='width:150px' class='textRight'>".$strTotalPeriodeAwal."</td><td style='width:150px' class='textRight'>".$strTotalPeriodePembanding."</td></tr>
										</table>
									</td>
								</tr>";
					}
					
				}            
           }   

            $hasil = $this->recursiveDataKodeAkunCALK($concatKode, $hasil, $arrDataTanggal, $formatAssetTetap, $pembandingAsetTetap);
            
              //SET TOTAL

              if ($header == 1)
              {  

                 if ($formatAssetTetap)
                 {
                      
                      $totalSaldoAwal   = $this->GetTotalCALKSaldoAwal($concatKode, $tglAwal);
                      $totalPenambahan  = $this->GetTotalCALKPenambahan($concatKode, $tglAwal, $tglAkhir);
                      $totalPengurangan = $this->GetTotalCALKPengurangan($concatKode, $tglAwal, $tglAkhir);
                      $totalSaldoAkhir  = ($totalSaldoAwal + $totalPenambahan) - $totalPengurangan;
                      
                      $hasil .=  "<tr>
                                <td>Level".$level." ".$kodePlainText.' '.$strNamaAkun."</td>
                                <td>
                                    <table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                      <col style='width: 34%'>
                                      <tr><td class='textRight'><b>".formatCurrency($totalSaldoAwal)."</b></td><td class='textRight'><b>".formatCurrency($totalPenambahan)."</b></td><td class='textRight'><b>".formatCurrency($totalPengurangan)."</b></td><td class='textRight'><b>".formatCurrency($totalSaldoAkhir)."</b></td></tr>
                                    </table>
                                </td>
                            </tr>";
                 }            
                 else
                 {

                  //spasi
                  $strSpace = '';

                  for($i = 1; $i <= $level ; $i++)
                  {
                     $strSpace .= "&nbsp;&nbsp;";
                  }

                   $strNamaAkun = "<b>Jumlah ".$namaPlainText."</b>";

                   $hasil .=  "<tr>
                                  <td>".$strSpace.$strNamaAkun."</td>
                                  <td>
                                      <table style='border-collapse:collapse; border-spacing:0;font-size:10px;font-family:arial, sans-serif;'>
                                        <tr><td style='width:150px' class='textRight'><b>".formatCurrency($subTotalPeriodeAwal)."</b></td><td style='width:150px' class='textRight'><b>".formatCurrency($subTotalPeriodePembanding)."</b></td></tr>
                                      </table>
                                  </td>
                              </tr>";
                 }   //if ($formatAssetTetap)
            }   //if ($header == 1)

          }  //foreach( $selectQuery->result_array() as $row )

          return $hasil;
      } 
	  
	  public function GetDaftarKodeAkun($tipeAkun)
      {
         
       return $this->treeDataKodeAkun($tipeAkun);

      }
	  
	  public function isSuperAdminAccess()
      {
          $IDUser = $_SESSION['IDUser'];
          $data = $this->db->query("select id_group as IDGroup from sys_user where id_user='".$IDUser."' ");

          return $data->first_row()->IDGroup;

      } 

    }  
?>
