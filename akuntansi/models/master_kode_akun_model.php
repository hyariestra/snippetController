<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

    class Master_kode_akun_model extends CI_Model{

      public function GetDaftarKodeAkun($tipeAkun)
      {
         
       return $this->treeDataKodeAkun($tipeAkun);

      }

	public function CariDaftarKodeAkun($tipeAkun, $param)
      {
         
       return $this->treeDataKodeAkunCari($tipeAkun, $param);

      }    

      public function GetDaftarAkunFilter($arrAkun, $refTipeAkun = '')
      {
         
       echo $this->treeDataKodeAkunFilter($arrAkun, $refTipeAkun);

      }   

      public function GetDaftarAkunFilterNone($refTipeAkun = '')
      {

       echo $this->treeDataKodeAkunFilterNone($refTipeAkun);

      }    

      public function GetDaftarKodeAkunCetak($tipeAkun, $kodeRekeningShow = 'notShow')
      {
         
       return $this->treeDataKodeAkunCetak($tipeAkun, $kodeRekeningShow);

      }    
      
      public function GetDaftarAkunAjax($arrData)
      {

        $this->IDAkun       = SanitizeParanoid($arrData['id']);
        $this->IDInduk      = SanitizeParanoid($arrData['IDInduk']);
        $this->IDAkunKel    = SanitizeParanoid($arrData['IDAkunKel']);
        $this->IDAkunTipe   = SanitizeParanoid($arrData['IDAkunTipe']);
        $this->kodeInduk    = SanitizeParanoid($arrData['kodeInduk']);
        $this->kodeAkun     = SanitizeParanoid($arrData['kodeAkun']);
        $this->IDLevel      = SanitizeParanoid($arrData['level']);
        $this->byPass       = SanitizeParanoid($arrData['byPass']);

        //check kode akun apakah sudah dipakai untuk transaksi
        $this->arrData = array();
        $this->arrData[] = array("tableName" => "trx_anggaran_pendapatan_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
        $this->arrData[] = array("tableName" => "trx_anggaran_pendapatan_det_perubahan", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
        $this->arrData[] = array("tableName" => "trx_anggaran_biaya_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
        $this->arrData[] = array("tableName" => "trx_anggaran_biaya_det_perubahan", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
        $this->arrData[] = array("tableName" => "trx_judet", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");

        if (!isCheckTransactionOK($this->arrData))
        {
          if ($this->byPass == 'false')
          {
            return "inTransaction";
            exit;
          }

          if ($this->byPass == 'true')
          {
            writeCRUDLog('Penambahan child node untuk kode akun '.$this->kodeInduk.'.'.$this->kodeAkun.' (yang terdapat beberapa transaksi) ', 'MasterKodeAkun', 'TambahData');
          }
        }

        $this->kodeInduk    = ( $this->IDLevel > 1) ? $this->kodeInduk.'.'.$this->kodeAkun : $this->kodeInduk; 

        echo '<input type="hidden" id="levelHidden" name="levelHidden" value="'.$this->IDLevel.'"/><input type="hidden" id="kodeIndukHidden" name="kodeIndukHidden" value="'.$this->kodeInduk.'"/><input type="hidden" id="indukHidden" name="indukHidden" value="'.$this->IDAkun.'"/>';

        return $this->getComponent($this->kodeInduk, $this->IDLevel, $this->IDAkunTipe, $this->IDAkunKel,'', 'Tambah');
      }  

      public function GetDaftarAkunUbahAjax($arrData)
      {

        $this->IDAkun       = SanitizeParanoid($arrData['id']);
        $this->IDInduk      = SanitizeParanoid($arrData['IDInduk']);
        $this->kodeInduk    = SanitizeParanoid($arrData['kodeInduk']);
        $this->IDAkunTipe   = SanitizeParanoid($arrData['IDAkunTipe']);
        $this->IDAkunKel    = SanitizeParanoid($arrData['IDAkunKel']);
        $this->IDLevel      = SanitizeParanoid($arrData['level']);
        $this->kodeAkun     = SanitizeParanoid($arrData['kodeAkun']);
        $this->saldoNormal  = SanitizeParanoid($arrData['saldoNormal']);

        echo '<input type="hidden" id="levelHidden" name="levelHidden" value="'.$this->IDLevel.'"/><input type="hidden" id="kodeIndukHidden" name="kodeIndukHidden" value="'.$this->kodeInduk.'"/><input type="hidden" id="indukHidden" name="indukHidden" value="'.$this->IDAkun.'"/>';

        return $this->getComponent($this->kodeInduk, $this->IDLevel, $this->IDAkunTipe, $this->IDAkunKel,  $this->saldoNormal, 'Ubah');
      }    

      public function GetDaftarKasBankUbahAjax($arrData)
      {

        $this->IDAkun       = SanitizeParanoid($arrData['IDAkun']);
        $this->IDInduk      = SanitizeParanoid($arrData['IDInduk']);
        $this->Level        = SanitizeParanoid($arrData['Level']);

        
        $data = $this->db->query("select id_akun, id_tipekasbank as IDTipeKasBank, nama_bendahara as NamaBendahara, 
                                 nama_bank as NamaBank, norek_bank as NoRekBank, nip_bendahara as NipBendahara   
                                 from mst_akun where id_akun  = '".$this->IDAkun."' and 
                                 id_akun_tipe = (select id from ref_akuntipe where nama = 'Kas/Bank' ) and header = 0 limit 1 ");
       
        $isAkunKasBank = ($data->num_rows() > 0);
        
        $strHTMLOut = '';

        if ($isAkunKasBank)
        { 
                $IDTipeKasBank = $data->first_row()->IDTipeKasBank;
                $NamaBendahara = $data->first_row()->NamaBendahara;
                $NIPBendahara  = $data->first_row()->NipBendahara;
                $NamaBank      = $data->first_row()->NamaBank;
                $NoRekBank     = $data->first_row()->NoRekBank;

                $dataKasBank = $this->db->query("select id_tipekasbank as IDTipeKasBank, nama as Nama  
                                                 from ref_tipe_kasbank");

                $strHTMLOut .= '<div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" >&nbsp;</label>
                                    <div class="col-sm-9">
                                        <div class="badge">Data Kas/Bank Dibawah Ini :</div>
                                    </div>
                                </div>
                                <div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" for="tipeKasBankUbah" style="text-decoration:underline;">Tipe Kas/Bank</label>
                                    <div class="col-sm-9">
                                        <select role="AjaxKasBankInfo" class="form-control" name="tipeKasBankUbah" id="tipeKasBankUbah">';

                foreach ($dataKasBank->result_array() as $rowKasBank) 
                {
                    $strSelected = ($rowKasBank['IDTipeKasBank'] == $IDTipeKasBank) ? 'selected' : '';

                    $strHTMLOut .= "<option value='".$rowKasBank['IDTipeKasBank']."' ".$strSelected.">".$rowKasBank['Nama']."</option>";
                }

                $strHTMLOut .= '</select></div></div>';

                $strHTMLOut .= '<div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" for="namaBendaharaUbah">Nama Bendahara</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="'.$NamaBendahara.'" class="form-control" name="namaBendaharaUbah" id="namaBendaharaUbah">
                                    </div>
                                </div>
                                 <div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" for="nipBendahara">NIP Bendahara</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="'.$NIPBendahara.'" class="form-control" name="nipBendaharaUbah" id="nipBendaharaUbah">
                                    </div>
                                </div>
                                <div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" for="namaBankUbah">Nama Bank</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="'.$NamaBank.'" class="form-control" name="namaBankUbah" id="namaBankUbah">
                                    </div>
                                </div>
                                <div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" for="NoRekBankUbah">No Rekening Bank</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="'.$NoRekBank.'" class="form-control" name="NoRekBankUbah" id="NoRekBankUbah">
                                    </div>
                                </div>';
        }

          return $strHTMLOut;
      }    


      public function GetDaftarKasBankAjax($arrData)
      {

        $this->IDAkun       = SanitizeParanoid($arrData['IDAkun']);
        $this->IDInduk      = SanitizeParanoid($arrData['IDInduk']);
        $this->Level        = SanitizeParanoid($arrData['Level']);

        
        $data = $this->db->query("select id_akun, id_tipekasbank as IDTipeKasBank, 
                                 nama_bendahara as NamaBendahara, nip_bendahara as NIPBendahara, 
                                 nama_bank as NamaBank, norek_bank as NoRekBank  
                                 from mst_akun where id_akun  = '".$this->IDAkun."' and 
                                 id_akun_tipe = (select id from ref_akuntipe where nama = 'Kas/Bank' ) limit 1 ");

        $isAkunKasBank = ($data->num_rows() > 0);
        
        $strHTMLOut = '';

        if ($isAkunKasBank)
        { 
                $IDTipeKasBank = $data->first_row()->IDTipeKasBank;
                $NamaBendahara = $data->first_row()->NamaBendahara;
                $NIPBendahara  = $data->first_row()->NIPBendahara;
                $NamaBank      = $data->first_row()->NamaBank;
                $NoRekBank     = $data->first_row()->NoRekBank;

                $dataKasBank = $this->db->query("select id_tipekasbank as IDTipeKasBank, nama as Nama  
                                                 from ref_tipe_kasbank");

                $strHTMLOut .= '<div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" >&nbsp;</label>
                                    <div class="col-sm-9">
                                        <div class="badge">Data Kas/Bank Dibawah Ini :</div>
                                    </div>
                                </div>
                                <div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" for="tipeKasBank" style="text-decoration:underline;">Tipe Kas/Bank</label>
                                    <div class="col-sm-9">
                                        <select role="AjaxKasBankInfo" class="form-control" name="tipeKasBank" id="tipeKasBank">';

                foreach ($dataKasBank->result_array() as $rowKasBank) 
                {
                    $strSelected = ($rowKasBank['IDTipeKasBank'] == $IDTipeKasBank) ? 'selected' : '';

                    $strHTMLOut .= "<option value='".$rowKasBank['IDTipeKasBank']."' ".$strSelected.">".$rowKasBank['Nama']."</option>";
                }

                $strHTMLOut .= '</select></div></div>';

                $strHTMLOut .= '<div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" for="namaBendahara">Nama Bendahara</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="'.$NamaBendahara.'" class="form-control" name="namaBendahara" id="namaBendahara">
                                    </div>
                                </div>
                                <div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" for="nipBendahara">NIP Bendahara</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="'.$NIPBendahara.'" class="form-control" name="nipBendahara" id="nipBendahara">
                                    </div>
                                </div>
                                <div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" for="namaBank">Nama Bank</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="'.$NamaBank.'" class="form-control" name="namaBank" id="namaBank">
                                    </div>
                                </div>
                                <div class="form-group sr-only" role="AjaxKasBankInfo">
                                    <label class="col-sm-3 control-label" for="NoRekBank">No Rekening Bank</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="'.$NoRekBank.'" class="form-control" name="NoRekBank" id="NoRekBank">
                                    </div>
                                </div>';
        }

          return $strHTMLOut;
      }    

    
      private function getComponent($kodeInduk, $level, $IDAkunTipe, $IDAkunKel, $saldoNormal, $type)
      {

          $arrKodeInduk = explode('.', $kodeInduk);

          $i=1;  
          $lastKodeInduk = '';
          $lastWhere = '';

          $strTest = '';
          $IDInduk = '';

          $prevKodeInduk = '';
          $strHTMLOut = '';

          foreach ($arrKodeInduk as $key => $value) {
            

            if ($i==1){
              $this->selectQuery = $this->db->query("select id_akun as IDAkun from mst_akun where level = 1 and kode_akun = '".$value."' ");  
              $this->arrSelectQuery = ( $this->selectQuery->num_rows() > 0 ) ? $this->selectQuery->row_array() : array("IDAkun" => '0');
              $prevKodeInduk =  $this->arrSelectQuery['IDAkun'];
            }

            if ($i==1) {
              $lastKodeInduk =  "0";
              $firstKode     = $value; 
            }else if ($i == 2){
              $lastKodeInduk = $value;
            }else if ($i>2) {
              $lastKodeInduk.= '.'.$value;
            }
            

             if ($i>1){
              $this->selectQuery = $this->db->query("select id_akun as IDAkun from mst_akun 
                                                      where id_induk = '".$prevKodeInduk."' and kode_akun ='".$value."' and level='".$i."' ");

              $this->arrSelectQuery = ( $this->selectQuery->num_rows() > 0 ) ? $this->selectQuery->row_array() : array("IDAkun" => '0');
              $prevKodeInduk =  $this->arrSelectQuery['IDAkun'];
            }

            
            $lastWhere = "and id_akun = '".$prevKodeInduk."'";

            $this->strSelectQuery = "SELECT id_akun as IDAkun, kode_akun AS KodeAkun, id_akun_tipe as IDAkunTipe, id_akun_kel as IDAkunKel, header as Header, 
                                     nama_akun AS NamaAkun, id_induk as Induk, level as Level  
                                     FROM mst_akun 
                                     WHERE kode_akun ='".$value."' and level='".$i."' ".$lastWhere; 

            $this->selectQuery = $this->db->query($this->strSelectQuery);
            $this->arrSelectQuery = ($this->selectQuery->num_rows() > 0 ) ? $this->selectQuery->row_array() : array();

            if ($this->selectQuery->num_rows() > 0)
            {
            
              $IDAkun         = $this->arrSelectQuery['IDAkun'];
              $kodeInduk      = $this->arrSelectQuery['Induk'];
              $header         = $this->arrSelectQuery['Header'];
              $kodePlainText  = $this->arrSelectQuery['KodeAkun'];
              $namaPlainText  = $this->arrSelectQuery['NamaAkun']; 
              $level          = $this->arrSelectQuery['Level'];
              $IDTipeAkun     = $this->arrSelectQuery['IDAkunTipe']; 
              $strTitle       = '';

              if ($level == 1) $strTitle = 'Tipe Akun';
              if ($level == 2) $strTitle = 'Kelompok';
              if ($level == 3) $strTitle = 'Jenis';
              if ($level == 4) $strTitle = 'Objek';
              if ($level == 5) $strTitle = 'Rincian Objek';
              if ($level > 5)  $strTitle = 'Sub Objek';

              $strField  = ($type == 'Tambah') ? 'id_akun' : 'id_induk';

              $strHTMLOut .= '<div class="form-group" role="ajaxGetDaftarAkun">';  
              $strHTMLOut .= '<label for="" role="notEmpty" class="col-sm-3 control-label">'.$strTitle.'</label>';
              $strHTMLOut .= '<div class="col-sm-9">';            
              $strHTMLOut .='<select id="ajaxCombo'.$level.$type.'" name="ajaxCombo'.$level.$type.'" class="form-control" role="ajaxComboAkun">';

              $strHTMLOut.='<option value="'.$kodePlainText.'">'.$kodePlainText.' - '.$namaPlainText.'</option>';
    
              $strHTMLOut.='</select></div></div>';
              
              $i++;
            } //if ($this->selectQuery->num_rows() > 0)

          }

    
          if ($level >= 2)
          {
              $IDAkunTipe = ($type == 'Tambah') ? $IDAkunTipe :  $IDTipeAkun;
               
              $strAkunTipe = ($level > 2) ? " and id ='".$IDAkunTipe."' " : "";
              $strArusKas  = ($level > 2) ? " and id_aruskas_kel ='".$IDAkunTipe."' " : "";

              $strHTMLOut .= '<div class="form-group" role="ajaxGetDaftarAkunTipe"> ';
              $strHTMLOut .= '<label for="akunTipe" class="col-sm-3 control-label">Sub Tipe</label> ';
              $strHTMLOut .= '<div class="col-sm-9"> ';
              $strHTMLOut .= '<select id="akunTipe'.$type.'" name="akunTipe'.$type.'" class="form-control" role="ajaxComboAkunTipe"> ';
              $strHTMLOut .= '<option value="0">:: Tanpa Sub Tipe ::</option> ';

              $data = $this->db->query("select id as IDAkunTipe, kode as KodeAkunTipe, nama as NamaAkunTipe from ref_akuntipe where id_akunkel ='".$IDAkunKel."' ".$strAkunTipe);
              
              foreach ($data->result_array() as $row) 
              {
                $strHTMLOut .="<option value='".$row['IDAkunTipe']."'>".$row['KodeAkunTipe'].' - '.$row['NamaAkunTipe']."</option>";
              }

              $strHTMLOut .= '</select>';
              $strHTMLOut .= '</div>';
              $strHTMLOut .= '</div>';
              $strHTMLOut .= '<div class="form-group" role="ajaxGetDaftarArusKas">';
              $strHTMLOut .= '<label for="arusKas" class="col-sm-3 control-label">Arus Kas</label>';
              $strHTMLOut .= '<div class="col-sm-9">';
              $strHTMLOut .=  '<select id="arusKas'.$type.'" name="arusKas'.$type.'" class="form-control" role="ajaxComboArusKas">';
              $strHTMLOut .= '<option value="0">:: Tanpa Arus Kas ::</option>';

              $data = $this->db->query("select id_aruskas_kel as IDArusKas, kode as KodeArusKas, nama as NamaArusKas from ref_aruskas_kel");
              foreach ($data->result_array() as $row) 
              {
                $strHTMLOut .= "<option value='".$row['IDArusKas']."'>".$row['KodeArusKas'].' - '.$row['NamaArusKas']."</option>";
              }

              $strHTMLOut .= '</select> ';
              $strHTMLOut .= '</div> ';
              $strHTMLOut .= '</div> ';

          }  

          if ($level == 2) 
          {

              $strHTMLOut .= '<div class="form-group" role="ajaxGetDaftarAkun">';  
              $strHTMLOut .= '<label for="" role="notEmpty" class="col-sm-3 control-label">Saldo Normal</label>';
              $strHTMLOut .= '<div class="col-sm-9">';            
              $strHTMLOut .='<select id="ajaxComboSaldoNormal'.$type.'" name="ajaxComboSaldoNormal'.$type.'" class="form-control" role="ajaxComboAkun">';

              $saldoNormalSelected = ($type == 'Ubah') ? $saldoNormal : '';

              $debetType  = ($saldoNormalSelected == 'D') ? 'selected' : '';
              $creditType = ($saldoNormalSelected == 'K') ? 'selected' : '';

              $strHTMLOut.='<option value="D" '.$debetType.'>(D) Debet</option>';
              $strHTMLOut.='<option value="K" '.$creditType.'>(K) Kredit</option>';

              $strHTMLOut.='</select></div></div>';
          }

          return $strHTMLOut;

      }

      public function UbahAkun($arrData)
      {

        $this->IDAkun           = SanitizeParanoid($arrData['IDAkun']);
        $this->IDInduk          = SanitizeParanoid($arrData['IDInduk']);
        $this->kodeInduk        = SanitizeParanoid($arrData['kodeInduk']);
        $this->kodeAkun         = SanitizeParanoid($arrData['kodeAkun']);
        $this->namaAkun         = SanitizeParanoid($arrData['namaAkun']);
        $this->arusKas          = SanitizeParanoid($arrData['arusKas']);
        $this->akunTipe         = SanitizeParanoid($arrData['akunTipe']);
        $this->level            = SanitizeParanoid($arrData['level']);
        $this->saldoNormal      = SanitizeParanoid($arrData['saldoNormal']);
        $this->deskripsi        = SanitizeParanoid($arrData['deskripsi']);
        $this->tipeAkunNavigasi = SanitizeParanoid($arrData['tipeAkunNavigasi']);
        $this->TipeKasBank      = SanitizeParanoid($arrData['TipeKasBank']);
     /*   $this->NamaBendahara    = SanitizeParanoid($arrData['namaBendaharaUbah']); 
        $this->NipBendahara     = SanitizeParanoid($arrData['nipBendaharaUbah']); 
        $this->NamaBank         = SanitizeParanoid($arrData['namaBankUbah']);
        $this->NoRekBank        = SanitizeParanoid($arrData['NoRekBankUbah']);

        $this->TipeKasBank      = (trim($this->TipeKasBank) == '') ? 0 : $this->TipeKasBank;*/

        $this->lastUpdate       = RealDateTime();
        $this->userUpdate       = $_SESSION['IDUser'];
        $this->IDUnitKerja      = $_SESSION['IDUnitKerja'];

        $this->arusKas          = ($this->arusKas  == '')  ? '0' : $this->arusKas;
        $this->akunTipe         = ($this->akunTipe == '')  ? '0' : $this->akunTipe;

        //check kode Akun
        $this->selectQuery = $this->db->query("select id_akun, kode_akun as KodeAkun from mst_akun  
                                               where kode_akun ='".$this->kodeAkun."' 
                                               and level = '".$this->level."' 
                                               and id_induk = '".$this->IDInduk."'  
                                               and id_akun <> '".$this->IDAkun."' limit 1");

        if ($this->selectQuery->num_rows() > 0)
        {
            $strMessage  = 'Kode Akun sudah tercatat di sistem database';
            $messageData = ConstructMessageResponse($strMessage , 'danger');
            echo $messageData;    
            exit;
        }

        //saldo normal untuk level <> 3 menyesuaikan induknya
        if ($this->level <> 3) 
        {
            $this->selectQuery = $this->db->query("SELECT saldo_normal as saldoNormal 
                                                   FROM mst_akun 
                                                   WHERE id_akun = '".$this->IDInduk."' ");

            $this->arrSelectQuery = ($this->selectQuery->num_rows() > 0) ? $this->selectQuery->row_array() : array('saldoNormal' => '');
            $this->saldoNormal = $this->arrSelectQuery['saldoNormal'];
        } 

        $this->db->trans_begin();

        //ambil kode akun untuk referensi kode akun biaya investasi
        $this->selectQuery = $this->db->query("select kode_akun as KodeAkun from mst_akun  
                                              where id_akun = '".$this->IDAkun."'");
         
        $this->KodeAkunReferensi = $this->selectQuery->first_row()->KodeAkun;

        $strUpdateLevel1 = "update mst_akun set kode_akun = '".$this->kodeAkun."', nama_akun ='".$this->namaAkun."', deskripsi = '".$this->deskripsi."' where id_akun = '".$this->IDAkun."' ";
        
      /*  $strUpdateNonLevel1 = "update mst_akun set kode_akun = '".$this->kodeAkun."', 
                                id_akun_tipe = '".$this->akunTipe."', 
                                id_aruskas_kel = '".$this->arusKas."',
                                nama_akun ='".$this->namaAkun."',
                                saldo_normal = '".$this->saldoNormal."', 
                                deskripsi = '".$this->deskripsi."',
                                id_tipekasbank = '".$this->TipeKasBank."', 
                                nama_bendahara = '".$this->NamaBendahara."',
                                nip_bendahara = '".$this->NipBendahara."', 
                                nama_bank = '".$this->NamaBank."',
                                norek_bank = '".$this->NoRekBank."'   
                                where id_akun = '".$this->IDAkun."' ";
*/

          $strUpdateNonLevel1 = "update mst_akun set kode_akun = '".$this->kodeAkun."', 
                                id_akun_tipe = '".$this->akunTipe."', 
                                id_aruskas_kel = '".$this->arusKas."',
                                nama_akun ='".$this->namaAkun."',
                                saldo_normal = '".$this->saldoNormal."', 
                                deskripsi = '".$this->deskripsi."' 
                                where id_akun = '".$this->IDAkun."' ";


        $this->strUpdateQuery1 = ($this->level < 3) ? $strUpdateLevel1 : $strUpdateNonLevel1 ;

        $this->db->query($this->strUpdateQuery1);
      

        $dataSumberDana = $this->db->query("select id_sumber_dana from mst_sumber_dana where id_akun_link = '".$this->IDAkun."' ");

        $strUpdateSumberDana = '';

        if ($dataSumberDana->num_rows() > 0)
        {
          $strUpdateSumberDana = "update mst_sumber_dana set nama_sumber_dana = '".$this->namaAkun."' where id_akun_link  = '".$this->IDAkun."' "; 
          $this->db->query($strUpdateSumberDana);
        } 
        
        $this->strUpdateQuery1 .=  "\n\r".$strUpdateSumberDana;

        $this->strUpdateQuery2 = '';

        //update juga anaknya
        if ($this->level == 3)
        {
          $this->strUpdateQuery2 = "update mst_akun set saldo_normal = '".$this->saldoNormal."'  
                                    where kode_induk like '".$this->kodeInduk.'.'.$this->kodeAkun."%' ";
      
          $this->db->query($this->strUpdateQuery2);
        }

         $this->strUpdateQuery3 = '';
         $this->strUpdateQuery4 = '';
        
        //kondisi khusus : untuk COA kepala 1.3 ada update revisi juga ke COA 7 (investasi) //link auto
        if (substr($this->kodeInduk, 0, 3) == '1.3')
        {

           $strKodeIndukInvestasi = '7'.substr($this->kodeInduk, 1, strlen($this->kodeInduk));
           $strkodeAkunInvestasi  = $strKodeIndukInvestasi.'.'.$this->KodeAkunReferensi;

           //ambil ID Akun Investasi 
           $data = $this->db->query("select id_akun as IDAkun from mst_akun where concat (kode_induk,'.',kode_akun) = '".$strkodeAkunInvestasi."'  ");
           
           $this->IDAkun  = $data->first_row()->IDAkun;

           $this->strUpdateQuery3 = "update mst_akun set kode_akun = '".$this->kodeAkun."', nama_akun ='".$this->namaAkun."', deskripsi = '".$this->deskripsi."' where id_akun = '".$this->IDAkun."' ";

           $this->db->query($this->strUpdateQuery3);

           $strKodeIndukPenyusutan = '1.3.99.'.substr($this->kodeInduk, 4, strlen($this->kodeInduk));
           $strKodeAkunPenyusutan  = $strKodeIndukPenyusutan.'.'.$this->KodeAkunReferensi;

           //ambil ID Akun induk investasi
           $data = $this->db->query("select id_akun as IDAkun, id_akun_tipe as IDAkunTipe, id_akun_kel as IDAkunKel, id_aruskas_kel as IDArusKasKel,
                                     saldo_normal as SaldoNormal    
                                     from mst_akun where concat (kode_induk,'.',kode_akun) = '".$strKodeIndukPenyusutan."'  ");
          
           //ambil ID Akun Investasi 
           $data = $this->db->query("select id_akun as IDAkun from mst_akun where concat (kode_induk,'.',kode_akun) = '".$strKodeAkunPenyusutan."'  ");
          
           $this->IDAkun  = $data->first_row()->IDAkun;

           $this->strUpdateQuery4 = "update mst_akun set kode_akun = '".$this->kodeAkun."', nama_akun ='".$this->namaAkun."', deskripsi = '".$this->deskripsi."' where id_akun = '".$this->IDAkun."' ";

           $this->db->query($this->strUpdateQuery4);

        }

        //update juga anaknya
        //$this->recursiveUpdateKodeAkun($this->IDAkun, $this->kodeInduk, $this->kodeAkun);

        if ($this->db->trans_status() === FALSE)
        {
          
          $this->db->trans_rollback();

          writeCRUDLog('Ubah data [Tidak Berhasil] : '.$this->strUpdateQuery1."\n".$this->strUpdateQuery2."\n".$this->strUpdateQuery3."\n".$this->strUpdateQuery4, 'MasterKodeAkun', 'UbahData');

          $strMessage  = 'Transaksi tidak berhasil, ubah kode akun dibatalkan';
          $messageData = ConstructMessageResponse($strMessage , 'danger');

          echo $messageData;
  
        }  
        else
        {
          
          $this->db->trans_commit();

          writeCRUDLog('Ubah data telah berhasil : '.$this->strUpdateQuery1."\n".$this->strUpdateQuery2."\n".$this->strUpdateQuery3."\n".$this->strUpdateQuery4, 'MasterKodeAkun', 'UbahData');

          $strMessage  = 'Ubah data akun telah berhasil';
          $messageData = ConstructMessageResponse($strMessage , 'success');

          echo $messageData."<script>alert('".$strMessage."');window.resetForm();dialogFormUbahAkunClose();loadGridData('".$this->tipeAkunNavigasi."');</script>";

        }  //  if ($this->db->trans_status() === FALSE)

      }

      public function HapusAkun($arrData)
      {
          $this->IDAkun = SanitizeParanoid($arrData['IDAkun']);
          $this->TipeAkunNavigasi = SanitizeParanoid($arrData['TipeAkunNavigasi']);

          //check dulu sebelum dihapus
          $this->selectQuery =  $this->db->query("select id_akun from mst_akun where id_induk = '".$this->IDAkun."' and level > 1");
       
          if ($this->selectQuery->num_rows() > 0)
          {
              $strMessage  = 'Kode Akun tidak dapat dihapus, ada beberapa kode Akun dengan induk Akun yang sama';
              $messageData = ConstructMessageResponse($strMessage , 'danger');
              echo $messageData;    
              exit;
          }

          //check dulu sebelum dihapus
          $this->selectQuery =  $this->db->query("select level as Level from mst_akun where id_akun = '".$this->IDAkun."' and level=1 ");
             
          if ($this->selectQuery->num_rows() > 0)
          {
              $strMessage  = 'Kode Akun induk tidak dapat dihapus';
              $messageData = ConstructMessageResponse($strMessage , 'danger');
              echo $messageData;    
              exit;
          }

          //check dulu sebelum dihapus
          $this->selectQuery =  $this->db->query("select id_induk as IDInduk from mst_akun where id_akun = '".$this->IDAkun."' ");

          $this->arrSelectQuery = ($this->selectQuery->num_rows() > 0) ? $this->selectQuery->row_array() : array("IDInduk" => "");

          $this->IDInduk = $this->arrSelectQuery['IDInduk'];

          $this->selectQuery =  $this->db->query("select id_akun as IDAkun from mst_akun where id_induk = '".$this->IDInduk."' and id_akun <> '".$this->IDAkun."' ");

          //cek di tabeL2 tertentu yang berhubungan dengan proses ini
          $this->arrData = array();
          $this->arrData[] = array("tableName" => "trx_anggaran_pendapatan_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
          $this->arrData[] = array("tableName" => "trx_anggaran_biaya_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
          $this->arrData[] = array("tableName" => "trx_anggaran_pendapatan_det_perubahan", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
          $this->arrData[] = array("tableName" => "trx_anggaran_biaya_det_perubahan", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
          $this->arrData[] = array("tableName" => "trx_judet", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
          $this->arrData[] = array("tableName" => "mst_pelanggan", "SQLWhere" => "id_akun_piutang = '".$this->IDAkun."' ");
          $this->arrData[] = array("tableName" => "mst_pemasok", "SQLWhere" => "id_akun_hutang = '".$this->IDAkun."' ");
          $this->arrData[] = array("tableName" => "trx_hutang_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
          $this->arrData[] = array("tableName" => "trx_inv_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
          $this->arrData[] = array("tableName" => "trx_spj_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
          $this->arrData[] = array("tableName" => "ref_akunlink", "SQLWhere" => "id_akun  = '".$this->IDAkun."' ");

          if (!isCheckTransactionOK($this->arrData))
          {
              $strMessage  = 'Kode Akun tidak dapat dihapus, ada beberapa transaksi dengan referensi yang sama';
              $messageData = ConstructMessageResponse($strMessage , 'danger');
              echo $messageData;    
              exit;
          }  
          
          $this->arrData[] = array("tableName" => "mst_sumber_dana", "SQLWhere" => "id_akun_link  = '".$this->IDAkun."' ");
          if (!isCheckTransactionOK($this->arrData))
          {
              $strMessage  = 'Kode akun terhubung dengan sumber dana, proses dibatalkan';
              $messageData = ConstructMessageResponse($strMessage , 'warning');
              echo $messageData;    
              exit;
          } 

          $this->db->trans_begin();
          
          $this->strDeleteQuery1 = '';

          if ($this->selectQuery->num_rows() == 0)
          {
            //check levelnya 
            $this->selectQuery =  $this->db->query("select level as Level from mst_akun where id_akun = '".$this->IDInduk."' ");
            
            $strBold = ($this->selectQuery->first_row()->Level <= 3) ? '1' : '0'; //level <=3 set selalu bold walaupun tidak punya anak

            $this->strDeleteQuery1 = "update mst_akun set header = '".$strBold."' where id_akun = '".$this->IDInduk."' ";
            $this->selectQuery =  $this->db->query($this->strDeleteQuery1);
          }
        
          
          $this->strDeleteQuery3 = '';
          $this->strDeleteQuery4 = '';

          //ambil kode akun untuk referensi kode akun biaya investasi
          $this->selectQuery = $this->db->query("select kode_induk as KodeInduk, kode_akun as KodeAkun from mst_akun  
                                              where id_akun = '".$this->IDAkun."'");

          $this->KodeIndukReferensi = $this->selectQuery->first_row()->KodeInduk;
          $this->KodeAkunReferensi  = $this->selectQuery->first_row()->KodeAkun;

          $this->strDeleteQuery2 = "delete from mst_akun where id_akun = '".$this->IDAkun."'";
          $this->db->query($this->strDeleteQuery2);
          
          //kondisi khusus : untuk COA kepala 1.3 ada update revisi juga ke COA 7 (investasi) //link auto
          if (substr($this->KodeIndukReferensi, 0, 3) == '1.3')
          {

             //hapus juga akun link di biaya investasi
             $strKodeIndukInvestasi = '7'.substr($this->KodeIndukReferensi, 1, strlen($this->KodeIndukReferensi));
             $strkodeAkunInvestasi  = $strKodeIndukInvestasi.'.'.$this->KodeAkunReferensi;

             //ambil ID Akun Investasi 
             $data = $this->db->query("select id_akun as IDAkun from mst_akun where concat (kode_induk,'.',kode_akun) = '".$strkodeAkunInvestasi."'  ");
             
             $this->IDAkun  = $data->first_row()->IDAkun;

              //cek di tabeL2 tertentu yang berhubungan dengan proses ini (untuk biaya investasi)
              $this->arrData = array();
              $this->arrData[] = array("tableName" => "trx_anggaran_pendapatan_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
              $this->arrData[] = array("tableName" => "trx_anggaran_biaya_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
              $this->arrData[] = array("tableName" => "trx_anggaran_pendapatan_det_perubahan", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
              $this->arrData[] = array("tableName" => "trx_anggaran_biaya_det_perubahan", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
              $this->arrData[] = array("tableName" => "trx_judet", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");

              if (!isCheckTransactionOK($this->arrData))
              {
                  $strMessage  = 'Kode Akun tidak dapat dihapus, ada beberapa transaksi dengan referensi yang sama';
                  $messageData = ConstructMessageResponse($strMessage , 'danger');
                  echo $messageData;    
                  exit;
              }  

               //check dulu sebelum dihapus
              $this->selectQuery =  $this->db->query("select id_induk as IDInduk from mst_akun where id_akun = '".$this->IDAkun."' ");
              $this->arrSelectQuery = ($this->selectQuery->num_rows() > 0) ? $this->selectQuery->row_array() : array("IDInduk" => "");

              $this->IDInduk = $this->arrSelectQuery['IDInduk'];

              $this->selectQuery =  $this->db->query("select id_akun as IDAkun from mst_akun where id_induk = '".$this->IDInduk."' and id_akun <> '".$this->IDAkun."' ");


              if ($this->selectQuery->num_rows() == 0)
              {
                //check levelnya 
                $this->selectQuery =  $this->db->query("select level as Level from mst_akun where id_akun = '".$this->IDInduk."' ");
                
                $strBold = ($this->selectQuery->first_row()->Level <= 3) ? '1' : '0'; //level <=3 set selalu bold walaupun tidak punya anak

                $this->strDeleteQuery1 = "update mst_akun set header = '".$strBold."' where id_akun = '".$this->IDInduk."' ";
                $this->selectQuery =  $this->db->query($this->strDeleteQuery1);

              }

              $this->strDeleteQuery3 = "delete from mst_akun where id_akun = '".$this->IDAkun."'";
              $this->db->query($this->strDeleteQuery3);

               //hapus juga akun link di akumulasi penyusutan
               $strKodeIndukPenyusutan = '1.3.99.'.substr($this->KodeIndukReferensi, 4, strlen($this->KodeIndukReferensi));
               $strKodeIndukPenyusutan = (strlen(trim($this->KodeIndukReferensi)) == 3) ? '1.3.99' : $strKodeIndukPenyusutan;
               $strKodeAkunPenyusutan  = $strKodeIndukPenyusutan.'.'.$this->KodeAkunReferensi;

               //ambil ID Akun Investasi 
               $data = $this->db->query("select id_akun as IDAkun from mst_akun where concat (kode_induk,'.',kode_akun) = '".$strKodeAkunPenyusutan."'  ");
               
               $this->IDAkun  = $data->first_row()->IDAkun;

                //cek di tabeL2 tertentu yang berhubungan dengan proses ini (untuk biaya investasi)
                $this->arrData = array();
                $this->arrData[] = array("tableName" => "trx_anggaran_pendapatan_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
                $this->arrData[] = array("tableName" => "trx_anggaran_biaya_det", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
                $this->arrData[] = array("tableName" => "trx_anggaran_pendapatan_det_perubahan", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
                $this->arrData[] = array("tableName" => "trx_anggaran_biaya_det_perubahan", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");
                $this->arrData[] = array("tableName" => "trx_judet", "SQLWhere" => "id_akun = '".$this->IDAkun."' ");

                if (!isCheckTransactionOK($this->arrData))
                {
                    $strMessage  = 'Kode Akun tidak dapat dihapus, ada beberapa transaksi dengan referensi yang sama';
                    $messageData = ConstructMessageResponse($strMessage , 'danger');
                    echo $messageData;    
                    exit;
                }  

                //check dulu sebelum dihapus
                $this->selectQuery =  $this->db->query("select id_induk as IDInduk from mst_akun where id_akun = '".$this->IDAkun."' ");
                $this->arrSelectQuery = ($this->selectQuery->num_rows() > 0) ? $this->selectQuery->row_array() : array("IDInduk" => "");

                $this->IDInduk = $this->arrSelectQuery['IDInduk'];
            
                $this->selectQuery =  $this->db->query("select id_akun as IDAkun from mst_akun where id_induk = '".$this->IDInduk."' and id_akun <> '".$this->IDAkun."' ");


                if ($this->selectQuery->num_rows() == 0)
                {
                   //check levelnya 
                   $this->selectQuery =  $this->db->query("select level as Level from mst_akun where id_akun = '".$this->IDInduk."' ");
            
                  $strBold = ($this->selectQuery->first_row()->Level <= 4) ? '1' : '0'; //level <=3 set selalu bold walaupun tidak punya anak

                  $this->strDeleteQuery4 = "update mst_akun set header = '".$strBold."' where id_akun = '".$this->IDInduk."' ";
                  $this->selectQuery =  $this->db->query($this->strDeleteQuery4);

                }


                $this->strDeleteQuery4 = "delete from mst_akun where id_akun = '".$this->IDAkun."'";
                $this->db->query($this->strDeleteQuery4);
          }



          if ($this->db->trans_status() === FALSE)
          {
            
            $this->db->trans_rollback();

            writeCRUDLog('Hapus data [Tidak Berhasil] : '.$this->strDeleteQuery1."\n".$this->strDeleteQuery2."\n".$this->strDeleteQuery3."\n".$this->strDeleteQuery4, 'MasterKodeAkun', 'HapusData');
          
            $strMessage  = 'Transaksi tidak berhasil, hapus data akun dibatalkan';
            $messageData = ConstructMessageResponse($strMessage , 'danger');

            echo $messageData;
    
          }  
          else
          {
            
            $this->db->trans_commit();

            writeCRUDLog('Hapus data telah berhasil : '.$this->strDeleteQuery1."\n".$this->strDeleteQuery2."\n".$this->strDeleteQuery3."\n".$this->strDeleteQuery4, 'MasterKodeAkun', 'HapusData');
          
            $strMessage  = 'Hapus data Akun telah berhasil';
            $messageData = ConstructMessageResponse($strMessage , 'success');

            echo $messageData."<script>loadGridData('".$this->TipeAkunNavigasi."');</script>";
          }

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
										  id_unit as IDUnit,
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
           $IDUnit       	= $row['IDUnit']; 

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
                            "IDTipeKasBank"   : "'.$IDTipeKasBank.'",
                            "NamaBendahara"   : "'.$NamaBendahara.'",
                            "NamaBank"        : "'.$NamaBank.'",
                            "NoRekBank"       : "'.$NoRekBank.'",
                            "IDUnit"			: "'.$IDUnit.'",
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
										   id_unit as IDUnit,
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
           $IDUnit = $row['IDUnit'];
          
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
					  "IDUnit"			: "'.$IDUnit.'",
                      "action"          : "'.$actionList.'"},';

           $hasil = $this->recursiveDataKodeAkun($row['IDAkun'], $hasil);
          }  //foreach( $selectQuery->result_array() as $row )

          return $hasil;
      } 
           
      public function TambahAkun($arrData)
      {  

        $this->IDInduk          = SanitizeParanoid($arrData['IDInduk']);
        $this->kodeInduk        = SanitizeParanoid($arrData['kodeInduk']);
        $this->akunKel          = SanitizeParanoid($arrData['akunKel']);
        $this->kodeAkun         = SanitizeParanoid($arrData['kodeAkun']);
        $this->namaAkun         = SanitizeParanoid($arrData['namaAkun']);
        $this->arusKas          = SanitizeParanoid($arrData['arusKas']);
        $this->akunTipe         = SanitizeParanoid($arrData['akunTipe']);
        $this->deskripsi        = SanitizeParanoid($arrData['deskripsi']);  
        $this->level            = SanitizeParanoid($arrData['level']);
        $this->saldoNormal      = SanitizeParanoid($arrData['saldoNormal']);
        $this->tipeAkunNavigasi = SanitizeParanoid($arrData['tipeAkunNavigasi']);
     /*   $this->tipeKasBank      = SanitizeParanoid($arrData['tipeKasBank']);
        $this->namaBendahara    = SanitizeParanoid($arrData['namaBendahara']);
        $this->namaBank         = SanitizeParanoid($arrData['namaBank']);
        $this->NoRekBank        = SanitizeParanoid($arrData['NoRekBank']);

        $this->tipeKasBank      = (trim($this->tipeKasBank) == '') ? 0 : $this->tipeKasBank;*/
        
        $this->arusKas          = ($this->arusKas  == '')  ? '0' : $this->arusKas;
        $this->akunTipe         = ($this->akunTipe == '')  ? '0' : $this->akunTipe;
        $this->akunKel          = ($this->akunKel == '')  ? '0' : $this->akunKel;

        $this->dateEntry    = RealDateTime(); 
        $this->userEntry    = $_SESSION['IDUser'];
        $this->IDUnitKerja  = $_SESSION['IDUnitKerja'];
        $this->header       = 0;
        
        $this->level        = $this->level + 1;

        //saldo normal untuk level <> 3 menyesuaikan induknya
        if ($this->level <> 3) 
        {
            $this->selectQuery = $this->db->query("SELECT saldo_normal as saldoNormal 
                                                   FROM mst_akun 
                                                   WHERE id_akun = '".$this->IDInduk."' ");

            $this->arrSelectQuery = ($this->selectQuery->num_rows() > 0) ? $this->selectQuery->row_array() : array('saldoNormal' => '');
            $this->saldoNormal = $this->arrSelectQuery['saldoNormal'];

        }

        //check max level akun
        $this->selectQuery  =  $this->db->query("SELECT set_setting_value as SetSettingValue  
                                                 FROM set_setting 
                                                 WHERE set_setting_nama = 'max_akun_level' ");
        
        $this->arrSelectQuery = ($this->selectQuery->num_rows() > 0) ? $this->selectQuery->row_array() : array('SetSettingValue' => ''); 
        $this->maxAkunLevel   = $this->arrSelectQuery['SetSettingValue'];

        if ($this->level > $this->maxAkunLevel)
        {
          $strMessage  = 'Maksimal level akun yang diperbolehkan adalah level '.$this->maxAkunLevel;
          $messageData = ConstructMessageResponse($strMessage , 'warning');

          echo $messageData;     
          exit;
        }

        //check kode akun apakah masih tersedia
        $this->selectQuery  =  $this->db->query("SELECT id_akun as IDAkun   
                                                 FROM mst_akun
                                                 WHERE kode_akun = '".$this->kodeAkun."' 
                                                 AND kode_induk='".$this->kodeInduk."' ");
        
        if ($this->selectQuery->num_rows() > 0 )
        {
          $strMessage  = 'Kode akun sudah tercatat di database, silahkan gunakan kode yang lain ';
          $messageData = ConstructMessageResponse($strMessage , 'warning');

          echo $messageData;     
          exit;
        }

        $this->selectQuery  =  $this->db->query("SELECT id_akun_kel as IDAkunKel, id_akun_tipe as IDAkunTipe, 
                                                id_aruskas_kel as IDArusKasKel, saldo_normal as SaldoNormal 
                                                FROM mst_akun 
                                                WHERE id_akun = '".$this->IDInduk ."' ");
       
        $this->arrSelectQuery = ($this->selectQuery->num_rows () > 0) ?  $this->selectQuery->row_array() : array('SaldoNormal' => '') ;

        $this->saldoNormal  = ( $this->level > 1) ? $this->saldoNormal : $this->arrSelectQuery['SaldoNormal'];
        $this->akunKel      = $this->arrSelectQuery['IDAkunKel'];
        $this->arusKas      = ( $this->level == 2) ? $this->arrSelectQuery['IDArusKasKel']  : $this->arusKas;
        $this->akunTipe     = ( $this->level == 2) ? $this->arrSelectQuery['IDAkunTipe']  : $this->akunTipe;

        $this->db->trans_begin();
/*
        $this->strSelectQuery1 = "insert into mst_akun (id_induk, id_akun_kel, id_akun_tipe, id_aruskas_kel, 
                                  kode_induk, id_tipekasbank, nama_bendahara, nama_bank, norek_bank,
                                  kode_akun, nama_akun, level, header, saldo_normal, deskripsi, date_entry, user_entry, last_update, user_update)   
                                  
                                  values ('".$this->IDInduk."','".$this->akunKel."','".$this->akunTipe."','".$this->arusKas."',
                                        '".$this->kodeInduk."', '".$this->tipeKasBank."', '".$this->namaBendahara."', '".$this->namaBank."', '".$this->NoRekBank."' ,
                                        '".$this->kodeAkun."', '".$this->namaAkun."', 
                                        '".$this->level."', '".$this->header."', '".$this->saldoNormal."', '".$this->deskripsi."', '".$this->dateEntry."', '".$this->userEntry."', '', '0')";*/

        
        $this->strSelectQuery1 = "insert into mst_akun (id_induk, id_akun_kel, id_akun_tipe, id_aruskas_kel, 
                                  kode_induk, 
                                  kode_akun, nama_akun, level, header, saldo_normal, deskripsi, date_entry, user_entry, last_update, user_update)   
                                  
                                  values ('".$this->IDInduk."','".$this->akunKel."','".$this->akunTipe."','".$this->arusKas."',
                                        '".$this->kodeInduk."', '".$this->kodeAkun."', '".$this->namaAkun."', 
                                        '".$this->level."', '".$this->header."', '".$this->saldoNormal."', '".$this->deskripsi."', '".$this->dateEntry."', '".$this->userEntry."', '', '0')";

        $this->db->query($this->strSelectQuery1);

        $this->db->query("update mst_akun set header = '1' where id_akun = '".$this->IDInduk."' ");

         //ambil ID terakhir yang diinsert
         $data = $this->db->query("select id_akun as IDAkun, kode_induk as KodeInduk, level as Level 
                                  from mst_akun order by id_akun desc limit 1");


         $lastIDAkun = $data->first_row()->IDAkun;

         $kodeIndukCheck = substr($data->first_row()->KodeInduk, 0, 1);
         $levelCheck     = $data->first_row()->Level;

         //check auto insert di tabel sumber dana (untuk akun pendapatan dan level = 3)
         if ($kodeIndukCheck == '4' && $levelCheck == '3')
         {
           $this->db->query("insert into mst_sumber_dana (kode_sumber_dana, nama_sumber_dana, id_akun_link, tipe_group, date_entry, user_entry, last_update, user_update) 
                            values( '".$kodeIndukCheck.".".$lastIDAkun.".".$levelCheck."', '".$this->namaAkun."', '".$lastIDAkun."', 'NON PNPB', '".$this->dateEntry."', '".$this->userEntry."', '', '0' )");
         }


         //check levelnya 
        $this->selectQuery =  $this->db->query("select level as Level from mst_akun where id_akun = '".$lastIDAkun."' ");
        
        $strBold = ($this->selectQuery->first_row()->Level <= 3) ? '1' : '0'; //level <=3 set selalu bold walaupun tidak punya anak

        $this->db->query("update mst_akun set header = '".$strBold."' where id_akun = '".$lastIDAkun."' ");

        $this->strSelectQuery3 = '';
        $this->strSelectQuery4 = '';
        
        //kondisi khusus : untuk COA kepala 1.3 ada insert simpan juga ke COA 7 (investasi) //link auto
        if (substr($this->kodeInduk, 0, 3) == '1.3')
        {

           $strKodeIndukInvestasi = '7'.substr($this->kodeInduk, 1, strlen($this->kodeInduk));

           $data = $this->db->query("select id_akun as IDAkun, id_akun_tipe as IDAkunTipe, id_akun_kel as IDAkunKel, id_aruskas_kel as IDArusKasKel,
                                     saldo_normal as SaldoNormal    
                                    from mst_akun where concat (kode_induk,'.',kode_akun) = '".$strKodeIndukInvestasi."'  ");
           
           $this->IDIndukInvestasi  = $data->first_row()->IDAkun;
           $this->akunKel           = $data->first_row()->IDAkunKel;
           $this->akunTipe          = $data->first_row()->IDAkunTipe;
           $this->akunKel           = $data->first_row()->IDArusKasKel;
           $thid->saldoNormal       = $data->first_row()->SaldoNormal;
           
           $this->db->query("update mst_akun set header = '1' where id_akun = '".$this->IDIndukInvestasi."' ");

           $this->strSelectQuery3 = "insert into mst_akun (id_induk, id_akun_kel, id_akun_tipe, id_aruskas_kel, kode_induk, kode_akun, nama_akun, level, header, saldo_normal, deskripsi, date_entry, user_entry, last_update, user_update)   
                                     values ('".$this->IDIndukInvestasi."','".$this->akunKel."','".$this->akunTipe."','".$this->arusKas."','".$strKodeIndukInvestasi."','".$this->kodeAkun."', '".$this->namaAkun."', 
                                     '".$this->level."', '".$this->header."', '".$this->saldoNormal."', '".$this->deskripsi."', '".$this->dateEntry."', '".$this->userEntry."', '', '0')";
            
           $this->db->query($this->strSelectQuery3);

          //ambil ID terakhir yang diinsert
           $data = $this->db->query("select id_akun as IDAkun from mst_akun order by id_akun desc limit 1");
           $lastIDAkun = $data->first_row()->IDAkun;

           //check levelnya 
          $this->selectQuery =  $this->db->query("select level as Level from mst_akun where id_akun = '".$lastIDAkun."' ");
          
          $strBold = ($this->selectQuery->first_row()->Level <= 3) ? '1' : '0'; //level <=3 set selalu bold walaupun tidak punya anak

          $this->db->query("update mst_akun set header = '".$strBold."' where id_akun = '".$lastIDAkun."' ");

           $strKodeIndukPenyusutan = '1.3.99.'.substr($this->kodeInduk, 4, strlen($this->kodeInduk));
           $strKodeIndukPenyusutan = (strlen(trim($this->kodeInduk)) == 3) ? '1.3.99' : $strKodeIndukPenyusutan;
           
           $data = $this->db->query("select id_akun as IDAkun, id_akun_tipe as IDAkunTipe, id_akun_kel as IDAkunKel, id_aruskas_kel as IDArusKasKel,
                                     saldo_normal as SaldoNormal, level as LevelInduk     
                                    from mst_akun where concat (kode_induk,'.',kode_akun) = '".$strKodeIndukPenyusutan."'  ");
         
           $this->IDIndukPenyusutan  = $data->first_row()->IDAkun;
           $this->akunKel            = $data->first_row()->IDAkunKel;
           $this->akunTipe           = $data->first_row()->IDAkunTipe;
           $this->akunKel            = $data->first_row()->IDArusKasKel;
           $thid->saldoNormal        = $data->first_row()->SaldoNormal;
           $this->levelPenyusutan    = $data->first_row()->LevelInduk+1;

           $this->db->query("update mst_akun set header = '1' where id_akun = '".$this->IDIndukPenyusutan."' ");
          
           //untuk aset tanah tidak diinsert ke penyusutan
           
           if (substr($this->kodeInduk, 0, 5) <> '1.3.1'  && $strKodeIndukPenyusutan <> 0)
           {
             $this->strSelectQuery4 = "insert into mst_akun (id_induk, id_akun_kel, id_akun_tipe, id_aruskas_kel, kode_induk, kode_akun, nama_akun, level, header, saldo_normal, deskripsi, date_entry, user_entry, last_update, user_update)   
                                       values ('".$this->IDIndukPenyusutan."','".$this->akunKel."','".$this->akunTipe."','".$this->arusKas."','".$strKodeIndukPenyusutan."','".$this->kodeAkun."', '".$this->namaAkun."', 
                                       '".$this->levelPenyusutan."', '".$this->header."', '".$this->saldoNormal."', '".$this->deskripsi."', '".$this->dateEntry."', '".$this->userEntry."', '', '0')";
          
            $this->db->query($this->strSelectQuery4);

          }

           //ambil ID terakhir yang diinsert
           $data = $this->db->query("select id_akun as IDAkun from mst_akun order by id_akun desc limit 1");
           $lastIDAkun = $data->first_row()->IDAkun;

          //check levelnya 
          $this->selectQuery =  $this->db->query("select level as Level from mst_akun where id_akun = '".$lastIDAkun."' ");
          
          $strBold = ($this->selectQuery->first_row()->Level <= 3) ? '1' : '0'; //level <=3 set selalu bold walaupun tidak punya anak

          $this->db->query("update mst_akun set header = '".$strBold."' where id_akun = '".$lastIDAkun."' ");

        }
      
        //check kode akun apakah sudah dipakai untuk transaksi
        $this->arrData = array();
        $this->arrData[] = array("tableName" => "trx_anggaran_pendapatan_det", "SQLWhere" => "id_akun = '".$this->IDInduk."' ");
        $this->arrData[] = array("tableName" => "trx_anggaran_biaya_det", "SQLWhere" => "id_akun = '".$this->IDInduk."' ");
        $this->arrData[] = array("tableName" => "trx_anggaran_pendapatan_det_perubahan", "SQLWhere" => "id_akun = '".$this->IDInduk."' ");
        $this->arrData[] = array("tableName" => "trx_anggaran_biaya_det_perubahan", "SQLWhere" => "id_akun = '".$this->IDInduk."' ");
        $this->arrData[] = array("tableName" => "trx_judet", "SQLWhere" => "id_akun = '".$this->IDInduk."' ");
        $this->arrData[] = array("tableName" => "trx_jurnal_det", "SQLWhere" => "id_akun = '".$this->IDInduk."' ");
        $this->arrData[] = array("tableName" => "trx_inv_det", "SQLWhere" => "id_akun = '".$this->IDInduk."' ");
        $this->arrData[] = array("tableName" => "trx_hutang_det", "SQLWhere" => "id_akun = '".$this->IDInduk."' ");
        
        $this->strSelectQuery2 = '';

        $this->tipeKasBank      = SanitizeParanoid($arrData['tipeKasBank']);
        $this->namaBendahara    = SanitizeParanoid($arrData['namaBendahara']);
        $this->namaBank         = SanitizeParanoid($arrData['namaBank']);
        $this->NoRekBank        = SanitizeParanoid($arrData['NoRekBank']);


        if (!isCheckTransactionOK($this->arrData))
        {
          
          $this->kodeAkun = $this->kodeAkun + 1;  

          //buat akun yang sama diinsert sebagai anak (untuk menghindari konflik)
          $this->strSelectQuery2 = "INSERT INTO mst_akun (kode_akun, id_akun_kel, id_akun_tipe, id_aruskas_kel, nama_akun, id_induk, kode_induk, level, header, 
                            builtin, id_tipekasbank, nama_bendahara, nama_bank, norek_bank, saldo_normal, deskripsi, date_entry, user_entry, last_update, user_update)

                            SELECT ".$this->kodeAkun.", id_akun_kel, id_akun_tipe, id_aruskas_kel, nama_akun, id_akun, CONCAT(kode_induk,'.',kode_akun) , level+1, 0, 
                            builtin, id_tipekasbank, nama_bendahara, nama_bank, norek_bank, saldo_normal, deskripsi, date_entry, user_entry, last_update, user_update
                            FROM mst_akun WHERE id_akun='".$this->IDInduk."'";

          $this->db->query($this->strSelectQuery2);

          $data = $this->db->query("select id_akun as IDAkun from mst_akun order by id_akun desc limit 1");
          $lastIDAkun = $data->first_row()->IDAkun;
        
          //update id akun di transaksi, sesuaikan dengan data akun yang baru 
          $this->db->query("update trx_anggaran_biaya_det set id_akun ='".$lastIDAkun."' where id_akun ='".$this->IDInduk."' ");
          $this->db->query("update trx_anggaran_biaya_det_perubahan set id_akun ='".$lastIDAkun."' where id_akun ='".$this->IDInduk."' ");
          $this->db->query("update trx_anggaran_pendapatan_det set id_akun ='".$lastIDAkun."' where id_akun ='".$this->IDInduk."' ");
          $this->db->query("update trx_anggaran_pendapatan_det_perubahan set id_akun ='".$lastIDAkun."' where id_akun ='".$this->IDInduk."' ");
          $this->db->query("update trx_judet set id_akun ='".$lastIDAkun."' where id_akun ='".$this->IDInduk."' ");
          $this->db->query("update trx_jurnal_det set id_akun ='".$lastIDAkun."' where id_akun ='".$this->IDInduk."' ");
          $this->db->query("update trx_inv_det set id_akun ='".$lastIDAkun."' where id_akun ='".$this->IDInduk."' ");
          $this->db->query("update trx_hutang_det set id_akun ='".$lastIDAkun."' where id_akun ='".$this->IDInduk."' ");
        }

        if ($this->db->trans_status() === FALSE)
        {
          
          $this->db->trans_rollback();

          writeCRUDLog('Tambah data [tidak Berhasil] : '.$this->strSelectQuery1."\n".$this->strSelectQuery2."\n".$this->strSelectQuery3."\n".$this->strSelectQuery4, 'MasterKodeAkun', 'TambahData');

          $strMessage  = 'Transaksi tidak berhasil, tambah akun dibatalkan';
          $messageData = ConstructMessageResponse($strMessage , 'danger');

          echo $messageData;
  
        }  
        else
        {
          
          $this->db->trans_commit();
          
          writeCRUDLog('Tambah data telah berhasil : '.$this->strSelectQuery1."\n".$this->strSelectQuery2."\n".$this->strSelectQuery3."\n".$this->strSelectQuery4, 'MasterKodeAkun', 'TambahData');

          $strMessage  = 'Tambah data akun telah berhasil';
          $messageData = ConstructMessageResponse($strMessage , 'success');

           echo $messageData."<script>alert('".$strMessage."');dialogFormTambahAkunClose();loadGridData('".$this->tipeAkunNavigasi."');</script>";

        }  //  if ($this->db->trans_status() === FALSE)

      }


      private function recursiveUpdateKodeAkun($parent = 0, $kodeIndukUbah, $kodeAkunUbah, $prevKodeInduk = '')
      {

          $selectQuery = $this->db->query("SELECT id_akun as IDAkun, id_induk as IDInduk , kode_akun as KodeAkun, kode_induk as KodeInduk   
                                           FROM mst_akun 
                                           WHERE id_induk='".$parent."'");

          foreach( $selectQuery->result_array() as $row )
          {

           $IDAkunLoop    = $row['IDAkun'];
           $IDIndukLoop   = $row['IDInduk'];
       
           $selectQuery2 = $this->db->query("SELECT kode_akun as KodeAkun, kode_induk as KodeInduk     
                                              FROM mst_akun 
                                              WHERE id_akun='".$IDAkunLoop."'");
           $kodeAkun  = $row['KodeAkun'];
           $kodeInduk = $row['KodeInduk'];
           
           $kodeIndukUbah2 = $kodeIndukUbah.'.'.$kodeAkunUbah;
           //8.4.1.2.1.1
           $kodeIndukUbah3 = $kodeInduk.'.'.$kodeAkun;
           $kodeIndukUbah4 = substr($kodeIndukUbah3,  strlen($kodeIndukUbah2)+1, (strlen($kodeIndukUbah3)  - strlen(substr($kodeIndukUbah3,0, strlen($kodeIndukUbah2)))) - strlen($kodeAkun)+1 );// $kodeInduk.'.'.$kodeAkun;
           
           $kodeIndukUbah5 = $kodeIndukUbah.'.'.$kodeAkunUbah.'.'.$kodeIndukUbah4;//.substr($kodeIndukUbah3, strlen($kodeIndukUbah2), strlen($kodeIndukUbah3));
           //log_message('error', $kodeIndukUbah3.' EMPAT '.$kodeIndukUbah4);
           //log_message('error', "update mst_akun set kode_induk = '".$kodeIndukUbah5."'  where id_induk = '".$parent."' and id_akun = '".$IDAkunLoop."' ");
           //$this->db->query( "update mst_akun set kode_induk = '".$kodeIndukUbah5."'  where id_induk = '".$parent."' and id_akun = '".$IDAkunLoop."' ");
           $prevKodeInduk='';//.=$kodeAkun;
           $this->recursiveUpdateKodeAkun($IDAkunLoop, $kodeIndukUbah, $kodeAkunUbah, $prevKodeInduk);
          }  //foreach( $selectQuery->result_array() as $row )
      } 

      public  function treeDataKodeAkunCetak($arrDataAkun = array(), $kodeRekeningShow = 'NotShow')
      {
            
            $strHTMLOut = ''; 
    
            $loop = 0;

            $arrHTMLOut = array();

            //foreach ($arrData as $value) {
            for($i=0;$i<count($arrDataAkun);$i++) 
            {
              
              $value = $arrDataAkun[$i]; 

              $arrHTMLOut[$loop] = '';
              
              $selectQuery = $this->db->query("SELECT kode_akun AS kodeAkun, header as Header, (select nama from ref_akuntipe where id =mst_akun.id_akun_tipe) as NamaAkunTipe, 
                                               nama_akun AS NamaKodering, kode_induk as KodeInduk, level as Level, 
                                               saldo_normal as SaldoNormal, 
                                               (SELECT CONCAT(kode_induk,'.',kode_kodering,' (',nama_kodering,')')  FROM mst_kodering WHERE  id_kodering = mst_akun.id_kodering )  AS concatKodeRekening    
                                               FROM mst_akun 
                                               WHERE kode_akun = '".$value."' AND kode_induk = '0' AND level = 1  ");
             
              $row = $selectQuery->row_array();

               $kodeInduk      = $row['KodeInduk'];
               $header         = $row['Header'];
               $kodePlainText  = $row['kodeAkun'];
               $namaPlainText  = $row['NamaKodering']; 
               $saldoNormal    = $row['SaldoNormal'];
               $level          = $row['Level'];
               $kodeInduk      = ($kodeInduk == '0') ? $row['kodeAkun'] : $kodeInduk; 
               $kodePlainText  = ($level == 1) ? $kodePlainText : $kodeInduk.'.'.$kodePlainText;
               $kodeWithFormat = ($header == 1 ) ? "<b>".$kodePlainText."</b>" : $kodePlainText;
               $namaWithFormat = ($header == 1 ) ? "<b>".$namaPlainText."</b>" : $namaPlainText;
               $kodeAkun       = $row['kodeAkun'];
               $NamaAkunTipe   = $row['NamaAkunTipe'];
               $kodeRekeningWithFormat  = $row['concatKodeRekening'];
          
               //setting khusus kode akun untuk jenis industri DESA
               $settingValue  = GetSettingValue('kelompok_industri');

               if ($settingValue == 'Desa')
               {
                
                 $prefixAkun = substr($kodePlainText,0,1);
                 $prefixAkun = ($prefixAkun == '4') ? '1' : $prefixAkun;
                 $prefixAkun = ($prefixAkun == '5') ? '2' : $prefixAkun;
                 $prefixAkun = ($prefixAkun == '6') ? '3' : $prefixAkun;

                 $strNewKodeAkun = $prefixAkun.substr($kodePlainText,1,strlen($kodePlainText));
                 $kodeWithFormat =  ($header == 1 ) ? "<b>".$strNewKodeAkun."</b>" : $strNewKodeAkun;

                }

               $strKodeRekeningShow = ($kodeRekeningShow == 'Show') ? "<td class='textLeft'>".$kodeRekeningWithFormat."</td>" : "";

               $hasil = "<tr><td class='textLeft'>".$kodeWithFormat."</td><td class='textLeft'>".$namaWithFormat."</td>".$strKodeRekeningShow."<td class='textLeft'>".$NamaAkunTipe."</td><td class='textCenter'>".$saldoNormal."</td></tr> ";          

               $arrHTMLOut[$loop] = $hasil;
               $strHTMLOut.=  $arrHTMLOut[$loop];
               $loop++;
               $arrHTMLOut[$loop] = '';

              $this->selectQuery = $this->db->query("SELECT kode_akun as KodeAkun 
                                                    FROM mst_akun
                                                    WHERE kode_akun = '".$value."' AND level = 1 LIMIT 1 ");
             
              $this->arrSelectQuery = ($this->selectQuery->num_rows() > 0) ? $this->selectQuery->row_array() : array("KodeAkun" => "0");
              $this->KodeInduk      =  $this->arrSelectQuery['KodeAkun']; 
               
              $arrHTMLOut[$loop] = $this->recursiveDataKodeAkunCetak($this->KodeInduk, $arrHTMLOut[$loop], $kodeRekeningShow);
              $strHTMLOut.=  $arrHTMLOut[$loop];

              $loop++;
            }
             
            return  $strHTMLOut;
        }

        private function recursiveDataKodeAkunCetak($parent=0, $hasil, $kodeRekeningShow = 'NotShow')
        {

          $selectQuery = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, kode_akun AS kodeAkun, header as Header, concat(kode_induk, '.', kode_akun) as ConcatKode,
                                           (select nama from ref_akuntipe where id =mst_akun.id_akun_tipe) as NamaAkunTipe,  
                                           concat(kode_induk,'.',kode_akun) as ConcatKode, 
                                           nama_akun AS NamaAkun, kode_induk as KodeInduk, level as Level, 
                                           saldo_normal as SaldoNormal, 
                                           (SELECT CONCAT(kode_induk,'.',kode_kodering,' (',nama_kodering,')')  FROM mst_kodering WHERE  id_kodering = mst_akun.id_kodering )  AS concatKodeRekening   
                                           FROM  mst_akun 
                                           WHERE kode_induk = '".$parent."'
                                           ORDER BY kodeUrut ASC"); 
        
          foreach( $selectQuery->result_array() as $row )
          {

           $kodeInduk      = $row['KodeInduk'];
           $header         = $row['Header'];
           $kodePlainText  = $row['kodeAkun'];
           $namaPlainText  = $row['NamaAkun']; 
           $saldoNormal    = $row['SaldoNormal'];
           $level          = $row['Level'];
           $kodeInduk      = ($kodeInduk == '0') ? $row['kodeAkun'] : $kodeInduk; 
     
           $namaWithFormat = ($header == 1 ) ? "<b>".$namaPlainText."</b>" : $namaPlainText;
           $kodeWithFormat = $kodeInduk.'.'.$kodePlainText;
           $kodeWithFormat = ($header == 1 ) ? "<b>".$kodeWithFormat."</b>" : $kodeWithFormat;
           
           $kodeRekeningWithFormat  = $row['concatKodeRekening'];

           $namaAkunTipe  = $row['NamaAkunTipe'];

           //setting khusus kode akun untuk jenis industri DESA
           $settingValue  = GetSettingValue('kelompok_industri');

           if ($settingValue == 'Desa')
           {
            
             $strConcatCode = $row['ConcatKode'];
             $prefixAkun = substr($strConcatCode,0,1);
             $prefixAkun = ($prefixAkun == '4') ? '1' : $prefixAkun;
             $prefixAkun = ($prefixAkun == '5') ? '2' : $prefixAkun;
             $prefixAkun = ($prefixAkun == '6') ? '3' : $prefixAkun;

             $strNewKodeAkun = $prefixAkun.substr($strConcatCode,1,strlen($strConcatCode));
             $kodeWithFormat =  ($header == 1 ) ? "<b>".$strNewKodeAkun."</b>" : $strNewKodeAkun;

            }
          
           $strKodeRekeningShow = ($kodeRekeningShow == 'Show') ? "<td class='textLeft'>".$kodeRekeningWithFormat."</td>" : "";

           $hasil .= "<tr><td class='textLeft'>".$kodeWithFormat."</td><td class='textLeft'>".$namaWithFormat."</td>".$strKodeRekeningShow."<td class='textLeft'>".$namaAkunTipe."</td><td class='textCenter'>".$saldoNormal."</td></tr> ";  
  
           $hasil = $this->recursiveDataKodeAkunCetak($row['ConcatKode'], $hasil, $kodeRekeningShow);

    
          }  //foreach( $selectQuery->result_array() as $row )

          return $hasil;
      } 

      public function isSuperAdminAccess()
      {
          $IDUser = $_SESSION['IDUser'];
          $data = $this->db->query("select id_group as IDGroup from sys_user where id_user='".$IDUser."' ");

          return $data->first_row()->IDGroup;

      } 


      public function SetKoderingInMstAkun($IDAkun = '0', $IDKodering = '0')
      {
        $IDAkun     = SanitizeParanoid($IDAkun);
        $IDKodering = SanitizeParanoid($IDKodering);

        $this->db->trans_begin();
        
        $strUpdateQuery1 = "update mst_akun set id_kodering = '".$IDKodering."' where id_akun = '".$IDAkun."'  ";
    
        $this->db->query($strUpdateQuery1);

        if ($this->db->trans_status() === FALSE)
        {
          
          $this->db->trans_rollback();

          writeCRUDLog('Ubah data [Tidak Berhasil] : '.$this->strUpdateQuery1, 'MasterKodeAkun', 'UbahDataKodering');

          $strMessage  = 'Transaksi tidak berhasil, update kodering dibatalkan';

        }  
        else
        {
          
          $this->db->trans_commit();

          writeCRUDLog('Ubah data telah berhasil : '.$this->strUpdateQuery1, 'MasterKodeAkun', 'UbahDataKodering');

          $strMessage  = 'update kodering telah berhasil';

        }  //  if ($this->db->trans_status() === FALSE)

         echo $strMessage;

      }

      private  function treeDataKodeAkunFilter($arrInduk = array(), $refTipeAkun = '')
      {
         
          $strJsonData = '['; 
         
          $i=0;
          $arrStrJsonData = array();

          foreach ($arrInduk as $value) {
             
            $arrStrJsonData[$i] = '';

            $selectQuery = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, id_akun as IDAkun, id_akun_kel as IDAkunKel, id_akun_tipe as IDAkunTipe, 
                                          (SELECT nama from ref_akuntipe where id = IDAkunTipe) as NamaAkunTipe,
                                          id_aruskas_kel as IDArusKas, kode_akun AS KodeAkun, header as Header, 
                                          nama_akun AS NamaAkun, id_induk as IDInduk, kode_induk as KodeInduk, level as Level, 
                                          saldo_normal as SaldoNormal, builtin as Builtin,
                                          id_tipekasbank as IDTipeKasBank, nama_bendahara as NamaBendahara,
                                          nama_bank as NamaBank, norek_bank as NoRekBank,
                                          (SELECT CONCAT(kode_induk,'.',kode_kodering,' (',nama_kodering,')')  FROM mst_kodering WHERE  id_kodering = mst_akun.id_kodering )  AS concatKodeRekening   
                                          FROM mst_akun 
                                          WHERE concat(kode_induk,'.',kode_akun) = '".$value."' 
                                          order by kodeUrut");
            
  
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
             $settingValue  = GetSettingValue('kelompok_industri');

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

             $btnTambah   = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkun();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';  
             $btnUbah     = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-warning\' onclick=\'UbahAkun();\'><span class=\'glyphicon glyphicon-pencil\' aria-hidden=\'true\'></button>&nbsp;';
             $btnHapus    = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-danger\' onclick=\'HapusAkun();\'><span class=\'glyphicon glyphicon-trash\' aria-hidden=\'true\'></button>&nbsp;';  
            
             $kelompokRekening = ($level == 1) ? substr($concatCode,2,1) : substr($concatCode,0,1);

             $btnKodering = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'BrowseKodering('.$kelompokRekening.', this)\'><span class=\'glyphicon glyphicon-folder-open\' aria-hidden=\'true\'></button>'; 

             $isSuperAdmin = $this->isSuperAdminAccess() == 1;

             $btnAdd   = (!$isSuperAdmin && ($level == 1 || $level == 2 ) || ($concatCode == '3.1.1.1'  || $concatCode == '3.1.1.2' || $concatCode == '3.1.1.3')) ? '' : $btnTambah;
            
             $btnKodering = ($header == '0') ? $btnKodering : '';

             $btnDelete = ($builtin == 1) ? '' :  $btnHapus; 

             //hitung dan masukkan untuk saldo awalnya
             $isAkunLink = (substr($concatCode,0,5) == '3.1.1') && in_array($IDAkun, GetCOAAkunLinkExclude(array('Saldo Awal', 'Surplus/Defisit Periode Lalu', 'Surplus/Defisit Periode Berjalan')) );

             $btnDelete = ($level >= 4) ? $btnHapus : '';
             $btnDelete = ($isAkunLink) ? '' : $btnDelete;
             $btnDelete = ($isSuperAdmin) ? $btnHapus : $btnDelete;

             $actionList = $btnAdd.$btnUbah.$btnDelete; 

             $actionList = $isSuperAdmin ? $btnAdd.$btnUbah.$btnDelete : $actionList;
             
             $data2 = $this->db->query("SELECT id_akunlink as IDAkunLink FROM ref_akunlink WHERE id_akun = '".$IDAkun."' ");
             $isKodeAkunLink = $data2->num_rows() > 0;

             $actionList = (substr($concatCode, 0, 1) == '7')? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
             $actionList = (substr($concatCode, 0, 6) == '1.3.99') ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
             $actionList = ($isAkunLink) ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
            
             $actionList = ($isKodeAkunLink) ? $btnUbah : $actionList;
      

             $hasil = '{"id"             : "'.$IDAkun .'", 
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
                        "action"          : "'.$actionList.'"},';

              $arrStrJsonData[$i] .= $this->recursiveDataFilter($value, $hasil, $refTipeAkun);
              $strJsonData.=  $arrStrJsonData[$i];

            }

            $i++;
        } 

        $strJsonData = substr($strJsonData, 0, strlen($strJsonData) - 2);
        $strJsonData .= '}]';

        return $strJsonData;
      }


      private function recursiveDataFilter($parent=0, $hasil, $refTipeAkun = '')
      {

          $selectQuery = $this->db->query("select id as IDTipe from ref_akuntipe where  nama  = '".$refTipeAkun."' ");
          
          $IDRefAkunTipe = $selectQuery->first_row()->IDTipe;

          $selectQuery = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, id_akun as IDAkun, id_akun_kel as IDAkunKel, id_akun_tipe as IDAkunTipe, 
                                          (SELECT nama from ref_akuntipe where id = IDAkunTipe) as NamaAkunTipe,
                                          concat(kode_induk,'.',kode_akun) as ConcatCode,
                                           id_aruskas_kel as IDArusKas, kode_akun AS KodeAkun, header as Header, 
                                           nama_akun AS NamaAkun, id_induk as IDInduk, kode_induk as KodeInduk, level as Level, 
                                           saldo_normal as SaldoNormal, builtin as Builtin,
                                            (SELECT CONCAT(kode_induk,'.',kode_kodering,' (',nama_kodering,')') FROM mst_kodering WHERE  id_kodering = mst_akun.id_kodering )  AS concatKodeRekening     
                                           FROM mst_akun 
                                           WHERE kode_induk = '".$parent."' and id_akun_tipe = '".$IDRefAkunTipe."'
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
           $settingValue  = GetSettingValue('kelompok_industri');

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

           $btnTambah   = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkun();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';  
           $btnUbah     = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-warning\' onclick=\'UbahAkun();\'><span class=\'glyphicon glyphicon-pencil\' aria-hidden=\'true\'></button>&nbsp;';
           $btnHapus    = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-danger\' onclick=\'HapusAkun();\'><span class=\'glyphicon glyphicon-trash\' aria-hidden=\'true\'></button>&nbsp;';  
          
           $kelompokRekening = ($level == 1) ? substr($concatCode,2,1) : substr($concatCode,0,1);

           $btnKodering = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'BrowseKodering('.$kelompokRekening.', this)\'><span class=\'glyphicon glyphicon-folder-open\' aria-hidden=\'true\'></button>'; 

           $isSuperAdmin = $this->isSuperAdminAccess() == 1;

           $btnAdd   = (!$isSuperAdmin && ($level == 1 || $level == 2 ) || ($concatCode == '3.1.1.1'  || $concatCode == '3.1.1.2' || $concatCode == '3.1.1.3')) ? '' : $btnTambah;
          
           $btnKodering = ($header == '0') ? $btnKodering : '';

           $btnDelete = ($builtin == 1) ? '' :  $btnHapus; 

           //hitung dan masukkan untuk saldo awalnya
           $isAkunLink = (substr($concatCode,0,5) == '3.1.1') && in_array($IDAkun, GetCOAAkunLinkExclude(array('Saldo Awal', 'Surplus/Defisit Periode Lalu', 'Surplus/Defisit Periode Berjalan')) );

           $btnDelete = ($level >= 4) ? $btnHapus : '';
           $btnDelete = ($isAkunLink) ? '' : $btnDelete;
           $btnDelete = ($isSuperAdmin) ? $btnHapus : $btnDelete;

           $actionList = $btnAdd.$btnUbah.$btnDelete; 

           $actionList = $isSuperAdmin ? $btnAdd.$btnUbah.$btnDelete : $actionList;
           
           $data2 = $this->db->query("SELECT id_akunlink as IDAkunLink FROM ref_akunlink WHERE id_akun = '".$IDAkun."' ");
           $isKodeAkunLink = $data2->num_rows() > 0;

           $actionList = (substr($concatCode, 0, 1) == '7')? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
           $actionList = (substr($concatCode, 0, 6) == '1.3.99') ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
           $actionList = ($isAkunLink) ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
          
           $actionList = ($isKodeAkunLink) ? $btnUbah : $actionList;


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
                      "action"          : "'.$actionList.'"},';

           $hasil = $this->recursiveDataFilter($concatCode, $hasil, $refTipeAkun);
          }  //foreach( $selectQuery->result_array() as $row )

          return $hasil;
      } 


      private  function treeDataKodeAkunFilterNone($refTipeAkun = '')
      {
         
         $strJsonData = '['; 
         
          $i=0;
          $arrStrJsonData = array();
          

          $selectQuery = $this->db->query("select id as IDTipe from ref_akuntipe where  nama  = '".$refTipeAkun."' ");
         
          
          $IDRefAkunTipe = $selectQuery->first_row()->IDTipe;

          $selectQuery1 = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, id_akun as IDAkun, id_akun_kel as IDAkunKel, id_akun_tipe as IDAkunTipe, 
                                          (SELECT nama from ref_akuntipe where id = IDAkunTipe) as NamaAkunTipe,
                                          id_aruskas_kel as IDArusKas, kode_akun AS KodeAkun, header as Header, 
                                          nama_akun AS NamaAkun, id_induk as IDInduk, kode_induk as KodeInduk, level as Level, 
                                          saldo_normal as SaldoNormal, builtin as Builtin,
                                          id_tipekasbank as IDTipeKasBank, nama_bendahara as NamaBendahara,
                                          nama_bank as NamaBank, norek_bank as NoRekBank,
                                          (SELECT CONCAT(kode_induk,'.',kode_kodering,' (',nama_kodering,')')  FROM mst_kodering WHERE  id_kodering = mst_akun.id_kodering )  AS concatKodeRekening   
                                          FROM mst_akun 
                                          WHERE id_akun_tipe = '".$IDRefAkunTipe."' AND header = 1 and level = 3
                                          order by kodeUrut");

          foreach ($selectQuery1->result_array() as $row) 
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
             $kodePlainText  = $kodeInduk.'.'.$kodePlainText;
             $kodeWithFormat = "<b>".$kodePlainText."</b>";
             $namaWithFormat = "<b>".$namaPlainText."</b>";
             $kodeAkun       = $row['KodeAkun'];
             $kodeRekeningWithFormat = $row['concatKodeRekening'];
            
             //setting khusus kode akun untuk jenis industri DESA
             $settingValue  = GetSettingValue('kelompok_industri');

             if ($settingValue == 'Desa')
             {

               $prefixAkun = substr($kodePlainText,0,1);
               $prefixAkun = ($prefixAkun == '4') ? '1' : $prefixAkun;
               $prefixAkun = ($prefixAkun == '5') ? '2' : $prefixAkun;
               $prefixAkun = ($prefixAkun == '6') ? '3' : $prefixAkun;

               $strNewKodeAkun = $prefixAkun.substr($kodePlainText,1,strlen($kodePlainText));
               $kodeWithFormat =  "<b>".$strNewKodeAkun."</b>";

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

             $btnTambah   = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkun();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';  
             $btnUbah     = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-warning\' onclick=\'UbahAkun();\'><span class=\'glyphicon glyphicon-pencil\' aria-hidden=\'true\'></button>&nbsp;';
             $btnHapus    = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-danger\' onclick=\'HapusAkun();\'><span class=\'glyphicon glyphicon-trash\' aria-hidden=\'true\'></button>&nbsp;';  
            
             $kelompokRekening = ($level == 1) ? substr($concatCode,2,1) : substr($concatCode,0,1);

             $btnKodering = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'BrowseKodering('.$kelompokRekening.', this)\'><span class=\'glyphicon glyphicon-folder-open\' aria-hidden=\'true\'></button>'; 

             $isSuperAdmin = $this->isSuperAdminAccess() == 1;

             $btnAdd   = (!$isSuperAdmin && ($level == 1 || $level == 2 ) || ($concatCode == '3.1.1.1'  || $concatCode == '3.1.1.2' || $concatCode == '3.1.1.3')) ? '' : $btnTambah;
            
             $btnKodering = ($header == '0') ? $btnKodering : '';

             $btnDelete = ($builtin == 1) ? '' :  $btnHapus; 

             //hitung dan masukkan untuk saldo awalnya
             $isAkunLink = (substr($concatCode,0,5) == '3.1.1') && in_array($IDAkun, GetCOAAkunLinkExclude(array('Saldo Awal', 'Surplus/Defisit Periode Lalu', 'Surplus/Defisit Periode Berjalan')) );

             $btnDelete = ($level >= 4) ? $btnHapus : '';
             $btnDelete = ($isAkunLink) ? '' : $btnDelete;
             $btnDelete = ($isSuperAdmin) ? $btnHapus : $btnDelete;

             $actionList = $btnAdd.$btnUbah.$btnDelete; 

             $actionList = $isSuperAdmin ? $btnAdd.$btnUbah.$btnDelete : $actionList;
             
             $data2 = $this->db->query("SELECT id_akunlink as IDAkunLink FROM ref_akunlink WHERE id_akun = '".$IDAkun."' ");
             $isKodeAkunLink = $data2->num_rows() > 0;

             $actionList = (substr($concatCode, 0, 1) == '7')? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
             $actionList = (substr($concatCode, 0, 6) == '1.3.99') ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
             $actionList = ($isAkunLink) ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
            
             $actionList = ($isKodeAkunLink) ? $btnUbah : $actionList;

              $hasil = '{"id"             : "'.$IDAkun .'", 
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
                        "action"          : "'.$actionList.'"},';

              $arrStrJsonData[$i] = $this->recursiveDataFilterNone($concatCode, $hasil, $refTipeAkun);
              $strJsonData.=  $arrStrJsonData[$i];

            $i++;
        } 

        $strJsonData = substr($strJsonData, 0, strlen($strJsonData) - 2);
        $strJsonData .= '}]';

        return $strJsonData;
      }


      private function recursiveDataFilterNone($parent=0, $hasil, $refTipeAkun = '')
      {
          $hasil2 = '';
          $selectQuery = $this->db->query("select id as IDTipe from ref_akuntipe where  nama  = '".$refTipeAkun."' ");
          
          $IDRefAkunTipe = $selectQuery->first_row()->IDTipe;

          $selectQuery = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, id_akun as IDAkun, id_akun_kel as IDAkunKel, id_akun_tipe as IDAkunTipe, 
                                          (SELECT nama from ref_akuntipe where id = IDAkunTipe) as NamaAkunTipe,
                                          concat(kode_induk,'.',kode_akun) as ConcatCode,
                                           id_aruskas_kel as IDArusKas, kode_akun AS KodeAkun, header as Header, 
                                           nama_akun AS NamaAkun, id_induk as IDInduk, kode_induk as KodeInduk, level as Level, 
                                           saldo_normal as SaldoNormal, builtin as Builtin,
                                            (SELECT CONCAT(kode_induk,'.',kode_kodering,' (',nama_kodering,')') FROM mst_kodering WHERE  id_kodering = mst_akun.id_kodering )  AS concatKodeRekening     
                                           FROM mst_akun 
                                           WHERE kode_induk = '".$parent."' and id_akun_tipe = '".$IDRefAkunTipe."'
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
           $settingValue  = GetSettingValue('kelompok_industri');

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

           $btnTambah   = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkun();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';  
           $btnUbah     = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-warning\' onclick=\'UbahAkun();\'><span class=\'glyphicon glyphicon-pencil\' aria-hidden=\'true\'></button>&nbsp;';
           $btnHapus    = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-danger\' onclick=\'HapusAkun();\'><span class=\'glyphicon glyphicon-trash\' aria-hidden=\'true\'></button>&nbsp;';  
          
           $kelompokRekening = ($level == 1) ? substr($concatCode,2,1) : substr($concatCode,0,1);

           $btnKodering = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'BrowseKodering('.$kelompokRekening.', this)\'><span class=\'glyphicon glyphicon-folder-open\' aria-hidden=\'true\'></button>'; 

           $isSuperAdmin = $this->isSuperAdminAccess() == 1;

           $btnAdd   = (!$isSuperAdmin && ($level == 1 || $level == 2 ) || ($concatCode == '3.1.1.1'  || $concatCode == '3.1.1.2' || $concatCode == '3.1.1.3')) ? '' : $btnTambah;
          
           $btnKodering = ($header == '0') ? $btnKodering : '';

           $btnDelete = ($builtin == 1) ? '' :  $btnHapus; 

           //hitung dan masukkan untuk saldo awalnya
           $isAkunLink = (substr($concatCode,0,5) == '3.1.1') && in_array($IDAkun, GetCOAAkunLinkExclude(array('Saldo Awal', 'Surplus/Defisit Periode Lalu', 'Surplus/Defisit Periode Berjalan')) );

           $btnDelete = ($level >= 4) ? $btnHapus : '';
           $btnDelete = ($isAkunLink) ? '' : $btnDelete;
           $btnDelete = ($isSuperAdmin) ? $btnHapus : $btnDelete;

           $actionList = $btnAdd.$btnUbah.$btnDelete; 

           $actionList = $isSuperAdmin ? $btnAdd.$btnUbah.$btnDelete : $actionList;
           
           $data2 = $this->db->query("SELECT id_akunlink as IDAkunLink FROM ref_akunlink WHERE id_akun = '".$IDAkun."' ");
           $isKodeAkunLink = $data2->num_rows() > 0;

           $actionList = (substr($concatCode, 0, 1) == '7')? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
           $actionList = (substr($concatCode, 0, 6) == '1.3.99') ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
           $actionList = ($isAkunLink) ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
          
           $actionList = ($isKodeAkunLink) ? $btnUbah : $actionList;


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
                      "action"          : "'.$actionList.'"},';

           $hasil = $this->recursiveDataFilterNone($concatCode, $hasil, $refTipeAkun);


          }  //foreach( $selectQuery->result_array() as $row )

          $hasil2 = '';

          return $hasil;
      }
	  
	//====================== CARI DATA KODE AKUN RYZVIE =========================//
	
	private  function treeDataKodeAkunCari($tipeAkun, $param)
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
                                          WHERE id_induk=0 and level = 1 and kode_akun='".$tipeAkun."' and  (CONCAT(kode_induk,'.',kode_akun) LIKE '%".$param."%' OR nama_akun LIKE '%".$param."%')
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
            
             $btnTambah   = '<button buttonType=\'actionItems\' buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkun();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';  
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
                            "IDTipeKasBank"   : "'.$IDTipeKasBank.'",
                            "NamaBendahara"   : "'.$NamaBendahara.'",
                            "NamaBank"        : "'.$NamaBank.'",
                            "NoRekBank"       : "'.$NoRekBank.'",
                            "action"          : "'.$actionList.'"},';
							
							
          }
		  
		 

           $strJsonData .= $this->recursiveDataKodeAkunCari($this->IDTipeAkun, $strJsonData, $param);
           $strJsonData = substr($strJsonData, 2, strlen($strJsonData) - 2);
           $strJsonData = $strHeader.$strJsonData;
           $strJsonData = substr($strJsonData, 0, strlen($strJsonData) - 2);
           $strJsonData .= '}]';

        }  
        else
        {
        
          $strJsonData .= $this->recursiveDataKodeAkunCari($this->IDTipeAkun, $strJsonData, $param);
          $strJsonData = substr($strJsonData, 1, strlen($strJsonData) - 2);
          $strJsonData .= ']';
		  
		
        }

        return $strJsonData;
      }
    
      private function recursiveDataKodeAkunCari($parent=0, $hasil, $param)
      {

          $selectQuery = $this->db->query("SELECT (SELECT CAST(CONCAT(REPLACE(kode_induk,'.',''), kode_akun) AS UNSIGNED) ) AS kodeUrut, id_akun as IDAkun, id_akun_kel as IDAkunKel, id_akun_tipe as IDAkunTipe, 
                                          (SELECT nama from ref_akuntipe where id = IDAkunTipe) as NamaAkunTipe,
                                          concat(kode_induk,'.',kode_akun) as ConcatCode,
                                           id_aruskas_kel as IDArusKas, kode_akun AS KodeAkun, header as Header, 
                                           nama_akun AS NamaAkun, id_induk as IDInduk, kode_induk as KodeInduk, level as Level, 
                                           saldo_normal as SaldoNormal, builtin as Builtin,
                                            (SELECT CONCAT(kode_induk,'.',kode_kodering,' (',nama_kodering,')') FROM mst_kodering WHERE  id_kodering = mst_akun.id_kodering )  AS concatKodeRekening     
                                           FROM mst_akun 
                                           WHERE (CONCAT(kode_induk,'.',kode_akun) LIKE '%".$param."%' OR nama_akun LIKE '%".$param."%')
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

           $btnTambah   = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'TambahAkun();\'><span class=\'glyphicon glyphicon-plus-sign\' aria-hidden=\'true\'></button>&nbsp;';  
           $btnUbah     = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-warning\' onclick=\'UbahAkun();\'><span class=\'glyphicon glyphicon-pencil\' aria-hidden=\'true\'></button>&nbsp;';
           $btnHapus    = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-danger\' onclick=\'HapusAkun();\'><span class=\'glyphicon glyphicon-trash\' aria-hidden=\'true\'></button>&nbsp;';  
          
           $kelompokRekening = ($level == 1) ? substr($concatCode,2,1) : substr($concatCode,0,1);

           $btnKodering = '<button buttonType=\'actionItems\' class= \'btn btn-xs btn-success\' onclick=\'BrowseKodering('.$kelompokRekening.', this)\'><span class=\'glyphicon glyphicon-folder-open\' aria-hidden=\'true\'></button>'; 

           $isSuperAdmin = $this->isSuperAdminAccess() == 1;

           $btnAdd   = (!$isSuperAdmin && ($level == 1 || $level == 2 ) || ($concatCode == '3.1.1.1'  || $concatCode == '3.1.1.2' || $concatCode == '3.1.1.3')) ? '' : $btnTambah;
          
           $btnKodering = ($header == '0') ? $btnKodering : '';

           $btnDelete = ($builtin == 1) ? '' :  $btnHapus; 

           //hitung dan masukkan untuk saldo awalnya
           $isAkunLink = (substr($concatCode,0,5) == '3.1.1') && in_array($IDAkun, GetCOAAkunLinkExclude(array('Saldo Awal', 'Surplus/Defisit Periode Lalu', 'Surplus/Defisit Periode Berjalan')) );

           $btnDelete = ($level >= 4) ? $btnHapus : '';
           $btnDelete = ($isAkunLink) ? '' : $btnDelete;
           $btnDelete = ($isSuperAdmin) ? $btnHapus : $btnDelete;

           $actionList = $btnAdd.$btnUbah.$btnDelete; 

           $actionList = $isSuperAdmin ? $btnAdd.$btnUbah.$btnDelete : $actionList;
           
           $data2 = $this->db->query("SELECT id_akunlink as IDAkunLink FROM ref_akunlink WHERE id_akun = '".$IDAkun."' ");
           $isKodeAkunLink = $data2->num_rows() > 0;

           $actionList = (substr($concatCode, 0, 1) == '7')? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
           $actionList = (substr($concatCode, 0, 6) == '1.3.99') ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
           $actionList = ($isAkunLink) ? '<span class=\'label label-success\'><b>Akun TerLink</b></span>' : $actionList;
          
           $actionList = ($isKodeAkunLink) ? $btnUbah : $actionList;
              
           $actionList .= '&nbsp;'.$btnKodering;

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
                      "action"          : "'.$actionList.'"},';

           //$hasil = $this->recursiveDataKodeAkunCari($row['IDAkun'], $hasil, $param);
          }  //foreach( $selectQuery->result_array() as $row )

          return $hasil;
      } 
}       