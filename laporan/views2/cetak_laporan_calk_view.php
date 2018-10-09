
<style type="text/css">


    .tabelContent  {font-size:12px;font-family:arial, sans-serif;border-collapse:collapse; border-spacing:0;}
    
    .tabelContent td
    {
      vertical-align: top;
      overflow:hidden;word-break:normal;
      padding-left: 15px;
    }
	
	table{
		font-size:12px !important;
		width:100% !important;
	}

    .tabelContent th{
     
      padding-top : 10px;
      padding-bottom : 10px;
    }

   .tabelItems  {font-size:12px;font-family:arial, sans-serif;border-collapse:collapse; border-spacing:0;margin-top:10px;}

   .tabelItems td
    {
      padding:5px 5px;
      border-style:none;border-width:1px;
      overflow:hidden;word-break:normal;
    }

    .tabelItems th{
      font-weight:bold;padding:5px 5px;
      border-style:solid;border-width:2px;overflow:hidden;
      word-break:normal;
      border-left: none;
      border-right: none;
      padding:10px;
      vertical-align: top;
   }

   .textLeft{
    text-align: left;
   }

   .textCenter{
    text-align: center;
   }
   
   .textRight{
    text-align: right;
   }

   .title{
      font-size:14px;
      padding : 3px;
      font-weight: bold;
      border-style: none;
   }

   .title2{
      font-size:14px;
      font-weight: bold;
      border-left:none;
      border-right:none;
      border-bottom: 1px solid black;
   }
   
   .bold{
      font-weight:bold;
   }
   
   .fontHeader1
   {
     font-family:Arial; 
     font-size:14px;
   }

   .fontHeader2
   {
     font-family:Arial; 
     font-size:13px;
   }

   .fontHeader3
   {
     font-family:Arial; 
     font-size:12px;
   }

   .header
   {
      border-style:none;
      text-align: center;
      font-weight: bold;
      margin-bottom:3px;
    }

    .redFont{
      color:red;
    }

    .indent
    {
      padding-left: 25px;
    }

    .footer1{font-family:arial;font-size:12px;text-align:center;width:31%;display:inline;margin-right:5px;}
    .marginTop{margin-top:5px}

   page{
      font-weight: 400; 
      line-height: 18px; 
      letter-spacing: normal; 
      text-align: start; 
      text-decoration: none; 
      text-indent: 0px; 
      text-rendering: optimizelegibility; 
      word-break: normal; 
      word-wrap: break-word; 
      word-spacing: 0px;
   }
</style>
<div class="content-header">   
	<h4>Laporan CALK</h4>
</div>
<div class="widget-container" style="padding:10px;">
	<div class="row" style="border-bottom:1px dashed #429489; padding-bottom:10px;">
		<div class="col-md-6">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-md-3 control-label">Periode</label>
					<div class="col-md-9">
						<select name="periode" class="form-control">
							<option value="Bulan">Bulan</option>
							<option value="Triwulan">Triwulan</option>
							<option value="Semster">Semester</option>
							<option value="Tahun">Tahun</option>
						</select>
					</div>
				</div>
				<div class="form-group" id="groupperiode">
					<label class="col-md-3 control-label">Bulan</label>
					<div class="col-md-9">
						<select name="periode" class="form-control">
						<?php for($i = 1; $i <=12; $i++){ ?>
							<option value="<?php echo $i?>"><?php echo convertBulan($i) ?></option>
						<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-9">
						<button class="btn btn-sm btn-warning">
							<span class="glyphicon glyphicon-search"></span>
							Print Preview
						</button>
						<button type="button" onclick="printdataneraca()" class="btn btn-sm btn-success">
							<span class="glyphicon glyphicon-print"></span>
							Print Data
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
<?php
      
        //$periode    =  SanitizeParanoid($this->uri->segment(3)); 
        //$nilai      =  SanitizeParanoid($this->uri->segment(4)); 
		
		$periode    =  "Tahun"; 
        $nilai      =  GetTahunPeriode(); 

        $this->IDUnitKerja    = $_SESSION['IDSekolah'];
        //$this->strValueUPTD   = GetSQLUPTDValue('select','value');

        $this->load->helper('inflector');

        //$arrHeaderReport    = GetHeaderReport(false);  
      
        //$namaInstansi = ($_SESSION['TipeLogin'] == 'Dinas') ? $arrHeaderReport['Organisasi'] : $arrHeaderReport['NamaUPTD'];
        //$alamat       = ($_SESSION['TipeLogin'] == 'Dinas') ? $arrHeaderReport['AlamatDinas'] : $arrHeaderReport['AlamatUPTD'];
        
        //$namaInstansi = ($_SESSION['TipeLogin'] == 'Dinas')  ? $namaInstansi : GetSettingValue('prefix_uptd')." ".$namaInstansi;

        $startDate = formatDateDB(RealDateTime('', false));
        $endDate   = formatDateDB(RealDateTime('', false));

        $startDateBefore = formatDateDB(RealDateTime('', false));
        $endDateBefore   = formatDateDB(RealDateTime('', false));

        $tahun = GetTahunPeriode();

        $startMonth = 1;
        $strPeriodeHeader = '';
        $strPeriode = '';
        $strPeriodePembanding = '';

        if ($periode == 'Tahun')
        {
            $startDate = $tahun.'-01-01';
            $endDate   = $tahun.'-12-31';

            $tanggal = GetLastDateInMonth(12, $nilai);

            //$strPeriodeHeader     = $tanggal." ".GetMonthName(12)." ".$nilai;
            $strPeriodeHeader       = 'Tahun '.$nilai;

            $strPeriode             = $strPeriodeHeader;//GetMonthName(12)." ".$nilai;

            $tahunSebelumnya        = $nilai-1;
            
            $startDateCompare       = $tahunSebelumnya.'-01-01';

            //$strPeriodePembanding   = GetMonthName(12)." ".$tahunSebelumnya;
            $nilaiPembanding        = $nilai - 1;
            $strPeriodePembanding   = 'Tahun '.$nilaiPembanding;

            $endDateCompare         = $tahunSebelumnya.'-12-31';
        }                 
        
        $nilai    =  ($periode == 'Bulan') ? GetMonthName($nilai) : $nilai;

        //$strPeriodeHeader = "Per ".$strPeriodeHeader;
        $strPeriodeHeader = $strPeriodeHeader;


        //$strPeriode = "Per ".$strPeriode;
        $strPeriode = $strPeriode;

        //$strPeriodePembanding = "Per ".$strPeriodePembanding;
        $strPeriodePembanding = $strPeriodePembanding;
    
        $this->load->model("laporan_model", "ModelLaporan");

        $dataTanggal = array("periodeAwal"        => $strPeriode,
                             "periodeAkhir"       => $strPeriodePembanding,
                             "tglAwal"            => $startDate,
                             "tglAkhir"           => $endDate,
                             "tglPembandingAwal"  => $startDateCompare,
                             "tglPembandingAkhir" => $endDateCompare);  


        $data = $this->db->query("select * from ref_narasi_calk");

        $pointA1 = '';
        $pointA2 = '';
        $pointBA = '';
        $pointBB = '';
        $pointBC = '';
        $pointBD = '';
        $pointBE = '';
        $pointBF = '';
        $pointBG = '';
        $pointD  = '';

        if ($data->num_rows() > 0 )
        {
          $pointA1 = $data->first_row()->point_a1;
          $pointA2 = $data->first_row()->point_a2;
          $pointBA = $data->first_row()->point_ba;
          $pointBB = $data->first_row()->point_bb;
          $pointBC = $data->first_row()->point_bc;
          $pointBD = $data->first_row()->point_bd;
          $pointBE = $data->first_row()->point_be;
          $pointBF = $data->first_row()->point_bf;
          $pointBG = $data->first_row()->point_bg;
          $pointD  = $data->first_row()->point_d;
        } 


        //$prefixUPTD   = ($_SESSION['TipeLogin'] == 'Dinas') ? '' : GetSettingValue('prefix_uptd');
        $namaInstansi = $this->db->query("SELECT * FROM sys_perusahaan")->first_row()->nama_perusahaan; 
      
    ?>
	
	

  <page footer="page">
     <table class="tabelContent">
        <col style="width: 100%">
        <tr><th>A. UMUM</th></tr>
        <tr><th style="padding-left:15px;">1. Pendirian Perusahaan dan Informasi Lainnya</th></tr>
        <tr><td style="padding-left:30px;"><?php echo $pointA1?></td></tr>
        <tr><th style="padding-left:15px;">2. Susunan Kepengurusan <?php echo strtoupper($namaInstansi) ?> pada tahun <?php echo GetTahunPeriode()?> sbb:</th></tr>
        <tr><td style="padding-left:30px;"><?php echo $pointA2?></td></tr>
     </table> 
  </page>

  <page footer="page">
     <table class="tabelContent">
        <col style="width: 100%">
        <tr><th>B. IKHTISAR KEBIJAKAN AKUNTANSI</th></tr>
        <tr><td>Berikut ini adalah kebijakan akuntansi yang diterapkan dalam penyusunan laporan keuangan Perusahaan, yang sesuai dengan standar akuntansi keuangan di Indonesia.</td></tr>
        <tr><th style="padding-left:15px;">a. Dasar Penyajian Laporan Keuangan</th></tr>
        <tr><td style="padding-left:30px;"><?php echo $pointBA?></td></tr>
        <tr><th style="padding-left:15px;">b. Setara Kas </th></tr>
        <tr><td style="padding-left:30px;"><?php echo $pointBB?></td></tr>
        <tr><th style="padding-left:15px;">c. Piutang Usaha </th></tr>
        <tr><td style="padding-left:30px;"><?php echo $pointBC?></td></tr>
        <tr><th style="padding-left:15px;">d. Pihak-Pihak Yang Mempunyai Hubungan Istimewa</th></tr>
        <tr><td style="padding-left:30px;"><?php echo $pointBD?></td></tr>
        <tr><th style="padding-left:15px;">e. Aset Tetap</th></tr>
        <tr><td style="padding-left:30px;"><?php echo $pointBE?></td></tr>
        <tr><th style="padding-left:15px;">f. Pengakuan Pendapatan dan Beban</th></tr>
        <tr><td style="padding-left:30px;"><?php echo $pointBF?></td></tr>
        <tr><th style="padding-left:15px;">g. Pajak Penghasilan</th></tr>
        <tr><td style="padding-left:30px;"><?php echo $pointBG?></td></tr>
     </table> 
  </page>

  <page footer="page">
     
     <table width="100%" border='0'>
      <col style="width: 100%">
      <tr>
          <td>
            <b>C. POS POS KEUANGAN</b>
          </td>
      </tr>
      <?php
       
       $i=1; 

        //KODE AKUN SEBELUM ASET TETAP
        $data = $this->db->query("SELECT  CONCAT(kode_induk,'.',kode_akun) AS kodeUrut, 
                                  CONCAT(kode_induk, '.', kode_akun) AS KodeAkun, nama_akun AS NamaAkun, header AS Header 
                                  FROM mst_akun WHERE LEVEL = 3 
                                  AND CONCAT(kode_induk,'.',kode_akun) < '1.3'
                                  ORDER BY kodeUrut ASC");

        foreach ($data->result_array() as $row) 
        {
           $namaAkun  = $row['NamaAkun']; 
           $kodeAkun  = $row['KodeAkun'];
           $isHeader  = $row['Header'];

           $strNamaAkun = ($isHeader) ? '<b>'.$i.'. '.$namaAkun.'</b>' : $namaAkun;

           $content = $this->ModelLaporan->GetCALKTreeView($dataTanggal, $nilai, array($kodeAkun), false);

           echo "<tr><td>".$strNamaAkun."</td></tr>";

           echo "<tr><td>".$content."</td></tr>";

           $i++;
        }  
         
        $content            = '';

        $periodeAwal        = $dataTanggal['periodeAwal'];
        $periodeAkhir       = $dataTanggal['periodeAkhir'];
        $tglAwal            = $dataTanggal['tglAwal'];
        $tglAkhir           = $dataTanggal['tglAkhir'];
        $tglPembandingAwal  = $dataTanggal['tglPembandingAwal'];
        $tglPembandingAkhir = $dataTanggal['tglPembandingAkhir'];

        
        $this->load->model("laporan_model", "ModelLaporan");

        echo "<tr><td><b>".$i.". Aset Tetap</b></td></tr>";

        $content =  "<table border='1' style='border-collapse:collapse; border-spacing:0;font-size:12px;font-family:arial, sans-serif;'>
                      <col style='width: 60%'>
                      <col style='width: 40%'>
                      <thead>
                      <tr><td colspan='2'>Akun ini terdiri dari : </td></tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>
                            <table border=0 style='border-collapse:collapse; border-spacing:0;'>
                              <col style='width: 30%'>
                              <col style='width: 30%'>
                              <col style='width: 30%'>
                              <col style='width: 30%'>
                              <tr><td colspan='4' class='textCenter'><b>".$periodeAwal."</b></td></tr>
                              <tr><td class='textCenter'>Saldo Awal</td><td  class='textCenter'>Penambahan</td><td  class='textCenter'>Pengurangan</td><td  class='textCenter'>Saldo Akhir</td></tr>
                            </table>
                        </td>
                      </tr>
                      <tr><td colspan='2'><b>Harga Perolehan</b></td></tr>
                      </thead>";
        
        $totalSaldoAwal   = 0;
        $totalPenambahan  = 0;
        $totalPengurangan = 0;
        $totalSaldoAkhir  = 0;

        //KODE AKUN ASET TETAP
        $data = $this->db->query("SELECT  CONCAT(kode_induk,'.',kode_akun) AS kodeUrut, 
                                  CONCAT(kode_induk, '.', kode_akun) AS KodeAkun, nama_akun AS NamaAkun, header AS Header 
                                  FROM mst_akun WHERE LEVEL = 3 
                                  AND CONCAT(kode_induk,'.',kode_akun) LIKE '1.3%'
                                  AND CONCAT(kode_induk,'.',kode_akun) <> '1.3.99' 
                                  ORDER BY kodeUrut ASC");

        $grandTotalSaldoAwal   = 0;
        $grandTotalPenambahan  = 0;
        $grandTotalPengurangan = 0;
        $grandTotalSaldoAkhir  = 0;

        foreach ($data->result_array() as $row) 
        {
           
           $strNamaAkun = $row['NamaAkun'] ;
           $concatKode  = $row['kodeUrut'];

           $totalSaldoAwal   = $this->ModelLaporan->GetTotalCALKSaldoAwal($concatKode, $tglAwal);
           $totalPenambahan  = $this->ModelLaporan->GetTotalCALKPenambahan($concatKode, $tglAwal, $tglAkhir);
           $totalPengurangan = $this->ModelLaporan->GetTotalCALKPengurangan($concatKode, $tglAwal, $tglAkhir);
           $totalSaldoAkhir  = ($totalSaldoAwal + $totalPenambahan) - $totalPengurangan;

           $content .="<tr>
                        <td>&nbsp;&nbsp;&nbsp;".$strNamaAkun."</td>
                        <td>
                            <table style='border-collapse:collapse; border-spacing:0;'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                              <tr><td class='textRight'>".formatCurrency($totalSaldoAwal)."</td><td class='textRight'>".formatCurrency($totalPenambahan)."</td><td class='textRight'>".formatCurrency($totalPengurangan)."</td><td class='textRight'>".formatCurrency($totalSaldoAkhir)."</td></tr>
                            </table>
                        </td>
                    </tr>";

          $grandTotalSaldoAwal   += $totalSaldoAwal;
          $grandTotalPenambahan  += $totalPenambahan;
          $grandTotalPengurangan += $totalPengurangan;
          $grandTotalSaldoAkhir  += $totalSaldoAkhir;

        }

        //TOTAL ASET TETAP
        $content .="<tr>
                        <td><b>Total</b></td>
                        <td>
                            <table style='border-collapse:collapse; border-spacing:0;'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                              <tr><td class='textRight'><b>".formatCurrency($grandTotalSaldoAwal)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPenambahan)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPengurangan)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalSaldoAkhir)."</b></td></tr>
                            </table>
                        </td>
                    </tr>";

        $content .="</table>";
        echo "<tr><td>".$content."</td></tr>";

        $i++;

        //KODE AKUN ASET TETAP (UTK PENYUSUTAN)
        $content =  "<table border='1' style='border-collapse:collapse; border-spacing:0;font-size:12px;font-family:arial, sans-serif;'>
                      <col style='width: 60%'>
                      <col style='width: 40%'>
                      <thead>
                      <tr><td colspan='2'><b>Akumulasi Penyusutan</b></td></tr>
                      </thead>";
        
        $totalSaldoAwal   = 0;
        $totalPenambahan  = 0;
        $totalPengurangan = 0;
        $totalSaldoAkhir  = 0;

        $grandTotalSaldoAwalPenyusutan   = 0;
        $grandTotalPenambahanPenyusutan  = 0;
        $grandTotalPenguranganPenyusutan = 0;
        $grandTotalSaldoAkhirPenyusutan  = 0;
        
        //KODE AKUN ASET TETAP (PENYUSUTAN)
        $data = $this->db->query("SELECT  CONCAT(kode_induk,'.',kode_akun) AS kodeUrut, 
                                  CONCAT(kode_induk, '.', kode_akun) AS KodeAkun, nama_akun AS NamaAkun, header AS Header 
                                  FROM mst_akun WHERE LEVEL = 4 
                                  AND CONCAT(kode_induk,'.',kode_akun) LIKE '1.3.99%'
                                  ORDER BY kodeUrut ASC");

        foreach ($data->result_array() as $row) 
        {
           
           $strNamaAkun = $row['NamaAkun'] ;
           $concatKode  = $row['kodeUrut'];

           $totalSaldoAwal   = $this->ModelLaporan->GetTotalCALKSaldoAwal($concatKode, $tglAwal);
           $totalPenambahan  = $this->ModelLaporan->GetTotalCALKPenambahan($concatKode, $tglAwal, $tglAkhir);
           $totalPengurangan = $this->ModelLaporan->GetTotalCALKPengurangan($concatKode, $tglAwal, $tglAkhir);
           $totalSaldoAkhir  = ($totalSaldoAwal + $totalPenambahan) - $totalPengurangan;

           $content .="<tr>
                        <td>&nbsp;&nbsp;&nbsp;".$strNamaAkun."</td>
                        <td>
                            <table style='border-collapse:collapse; border-spacing:0;'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                              <tr><td class='textRight'>".formatCurrency($totalSaldoAwal)."</td><td class='textRight'>".formatCurrency($totalPenambahan)."</td><td class='textRight'>".formatCurrency($totalPengurangan)."</td><td class='textRight'>".formatCurrency($totalSaldoAkhir)."</td></tr>
                            </table>
                        </td>
                    </tr>";

            $grandTotalSaldoAwalPenyusutan   += $totalSaldoAwal;
            $grandTotalPenambahanPenyusutan  += $totalPenambahan;
            $grandTotalPenguranganPenyusutan += $totalPengurangan;
            $grandTotalSaldoAkhirPenyusutan  += $totalSaldoAkhir;        

        }

        //TOTAL PENYUSUTAN
        $content .="<tr>
                        <td><b>Total</b></td>
                        <td>
                            <table style='border-collapse:collapse; border-spacing:0;'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                              <tr><td class='textRight'><b>".formatCurrency($grandTotalSaldoAwalPenyusutan)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPenambahanPenyusutan)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPenguranganPenyusutan)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalSaldoAkhirPenyusutan)."</b></td></tr>
                            </table>
                        </td>
                    </tr>";

        //NILAI BUKU 
        $grandTotalSaldoAwalNilaiBuku   = $grandTotalSaldoAwal    - $grandTotalSaldoAwalPenyusutan;
        $grandTotalPenambahanNilaiBuku  = $grandTotalPenambahan   - $grandTotalPenambahanPenyusutan;
        $grandTotalPenguranganNilaiBuku = $grandTotalPengurangan  - $grandTotalPenguranganPenyusutan;
        $grandTotalSaldoAkhirNilaiBuku  = $grandTotalSaldoAkhir   - $grandTotalSaldoAkhirPenyusutan;         

        $content .="<tr>
                        <td><b>Nilai Buku</b></td>
                        <td>
                            <table style='border-collapse:collapse; border-spacing:0;'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                              <tr><td class='textRight'><b>".formatCurrency($grandTotalSaldoAwalNilaiBuku)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPenambahanNilaiBuku)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPenguranganNilaiBuku)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalSaldoAkhirNilaiBuku)."</b></td></tr>
                            </table>
                        </td>
                    </tr>";
            

        $content .="</table>";
        echo "<tr><td>".$content."</td></tr>";


        //PEMBANDING
         $content =  "<table border='1' style='border-collapse:collapse; border-spacing:0;font-size:12px;font-family:arial, sans-serif;'>
                      <col style='width: 60%'>
                      <col style='width: 40%'>
                      <thead>
                      <tr><td colspan='2'>Akun ini terdiri dari : </td></tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>
                            <table border=0 style='border-collapse:collapse; border-spacing:0;'>
                              <col style='width: 30%'>
                              <col style='width: 30%'>
                              <col style='width: 30%'>
                              <col style='width: 30%'>
                              <tr><td colspan='4' class='textCenter'><b>".$periodeAkhir."</b></td></tr>
                              <tr><td class='textCenter'>Saldo Awal</td><td  class='textCenter'>Penambahan</td><td  class='textCenter'>Pengurangan</td><td  class='textCenter'>Saldo Akhir</td></tr>
                            </table>
                        </td>
                      </tr>
                      <tr><td colspan='2'><b>Harga Perolehan</b></td></tr>
                      </thead>";
        
        $totalSaldoAwal   = 0;
        $totalPenambahan  = 0;
        $totalPengurangan = 0;
        $totalSaldoAkhir  = 0;

        //KODE AKUN ASET TETAP
        $data = $this->db->query("SELECT  CONCAT(kode_induk,'.',kode_akun) AS kodeUrut, 
                                  CONCAT(kode_induk, '.', kode_akun) AS KodeAkun, nama_akun AS NamaAkun, header AS Header 
                                  FROM mst_akun WHERE LEVEL = 3 
                                  AND CONCAT(kode_induk,'.',kode_akun) LIKE '1.3%'
                                  AND CONCAT(kode_induk,'.',kode_akun) <> '1.3.99' 
                                  ORDER BY kodeUrut ASC");

        $grandTotalSaldoAwal   = 0;
        $grandTotalPenambahan  = 0;
        $grandTotalPengurangan = 0;
        $grandTotalSaldoAkhir  = 0;

        foreach ($data->result_array() as $row) 
        {
           
           $strNamaAkun = $row['NamaAkun'] ;
           $concatKode  = $row['kodeUrut'];

           $totalSaldoAwal   = $this->ModelLaporan->GetTotalCALKSaldoAwal($concatKode, $tglPembandingAwal);
           $totalPenambahan  = $this->ModelLaporan->GetTotalCALKPenambahan($concatKode, $tglPembandingAwal, $tglPembandingAkhir);
           $totalPengurangan = $this->ModelLaporan->GetTotalCALKPengurangan($concatKode, $tglPembandingAwal, $tglPembandingAkhir);
           $totalSaldoAkhir  = ($totalSaldoAwal + $totalPenambahan) - $totalPengurangan;

           $content .="<tr>
                        <td>&nbsp;&nbsp;&nbsp;".$strNamaAkun."</td>
                        <td>
                            <table style='border-collapse:collapse; border-spacing:0;'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                              <tr><td class='textRight'>".formatCurrency($totalSaldoAwal)."</td><td class='textRight'>".formatCurrency($totalPenambahan)."</td><td class='textRight'>".formatCurrency($totalPengurangan)."</td><td class='textRight'>".formatCurrency($totalSaldoAkhir)."</td></tr>
                            </table>
                        </td>
                    </tr>";

          $grandTotalSaldoAwal   += $totalSaldoAwal;
          $grandTotalPenambahan  += $totalPenambahan;
          $grandTotalPengurangan += $totalPengurangan;
          $grandTotalSaldoAkhir  += $totalSaldoAkhir;

        }

        //TOTAL ASET TETAP
        $content .="<tr>
                        <td><b>Total</b></td>
                        <td>
                            <table style='border-collapse:collapse; border-spacing:0;'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                              <tr><td class='textRight'><b>".formatCurrency($grandTotalSaldoAwal)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPenambahan)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPengurangan)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalSaldoAkhir)."</b></td></tr>
                            </table>
                        </td>
                    </tr>";

        $content .="</table>";
        echo "<tr><td>".$content."</td></tr>";


        //KODE AKUN ASET TETAP (UTK PENYUSUTAN)
        $content =  "<table border='1' style='border-collapse:collapse; border-spacing:0;font-size:12px;font-family:arial, sans-serif;'>
                      <col style='width: 60%'>
                      <col style='width: 40%'>
                      <thead>
                      <tr><td colspan='2'><b>Akumulasi Penyusutan</b></td></tr>
                      </thead>";
        
        $totalSaldoAwal   = 0;
        $totalPenambahan  = 0;
        $totalPengurangan = 0;
        $totalSaldoAkhir  = 0;

        $grandTotalSaldoAwalPenyusutan   = 0;
        $grandTotalPenambahanPenyusutan  = 0;
        $grandTotalPenguranganPenyusutan = 0;
        $grandTotalSaldoAkhirPenyusutan  = 0;
        
        //KODE AKUN ASET TETAP (PENYUSUTAN)
        $data = $this->db->query("SELECT  CONCAT(kode_induk,'.',kode_akun) AS kodeUrut, 
                                  CONCAT(kode_induk, '.', kode_akun) AS KodeAkun, nama_akun AS NamaAkun, header AS Header 
                                  FROM mst_akun WHERE LEVEL = 4 
                                  AND CONCAT(kode_induk,'.',kode_akun) LIKE '1.3.99%'
                                  ORDER BY kodeUrut ASC");

        foreach ($data->result_array() as $row) 
        {
           
           $strNamaAkun = $row['NamaAkun'] ;
           $concatKode  = $row['kodeUrut'];

           $totalSaldoAwal   = $this->ModelLaporan->GetTotalCALKSaldoAwal($concatKode, $tglPembandingAwal);
           $totalPenambahan  = $this->ModelLaporan->GetTotalCALKPenambahan($concatKode, $tglPembandingAwal, $tglPembandingAkhir);
           $totalPengurangan = $this->ModelLaporan->GetTotalCALKPengurangan($concatKode, $tglPembandingAwal, $tglPembandingAkhir);
           $totalSaldoAkhir  = ($totalSaldoAwal + $totalPenambahan) - $totalPengurangan;

           $content .="<tr>
                        <td>&nbsp;&nbsp;&nbsp;".$strNamaAkun."</td>
                        <td>
                            <table style='border-collapse:collapse; border-spacing:0;'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                              <tr><td class='textRight'>".formatCurrency($totalSaldoAwal)."</td><td class='textRight'>".formatCurrency($totalPenambahan)."</td><td class='textRight'>".formatCurrency($totalPengurangan)."</td><td class='textRight'>".formatCurrency($totalSaldoAkhir)."</td></tr>
                            </table>
                        </td>
                    </tr>";

            $grandTotalSaldoAwalPenyusutan   += $totalSaldoAwal;
            $grandTotalPenambahanPenyusutan  += $totalPenambahan;
            $grandTotalPenguranganPenyusutan += $totalPengurangan;
            $grandTotalSaldoAkhirPenyusutan  += $totalSaldoAkhir;        

        }

        //TOTAL PENYUSUTAN
        $content .="<tr>
                        <td><b>Total</b></td>
                        <td>
                            <table style='border-collapse:collapse; border-spacing:0;'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                              <tr><td class='textRight'><b>".formatCurrency($grandTotalSaldoAwalPenyusutan)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPenambahanPenyusutan)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPenguranganPenyusutan)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalSaldoAkhirPenyusutan)."</b></td></tr>
                            </table>
                        </td>
                    </tr>";

        //NILAI BUKU 
        $grandTotalSaldoAwalNilaiBuku   = $grandTotalSaldoAwal    - $grandTotalSaldoAwalPenyusutan;
        $grandTotalPenambahanNilaiBuku  = $grandTotalPenambahan   - $grandTotalPenambahanPenyusutan;
        $grandTotalPenguranganNilaiBuku = $grandTotalPengurangan  - $grandTotalPenguranganPenyusutan;
        $grandTotalSaldoAkhirNilaiBuku  = $grandTotalSaldoAkhir   - $grandTotalSaldoAkhirPenyusutan;         

        $content .="<tr>
                        <td><b>Nilai Buku</b></td>
                        <td>
                            <table style='border-collapse:collapse; border-spacing:0;'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                                 <col style='width: 30%'>
                              <tr><td class='textRight'><b>".formatCurrency($grandTotalSaldoAwalNilaiBuku)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPenambahanNilaiBuku)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalPenguranganNilaiBuku)."</b></td><td class='textRight'><b>".formatCurrency($grandTotalSaldoAkhirNilaiBuku)."</b></td></tr>
                            </table>
                        </td>
                    </tr>";
            

        $content .="</table>";
        echo "<tr><td>".$content."</td></tr>";



        //KODE AKUN EKUITAS
        $data = $this->db->query("SELECT  CONCAT(kode_induk,'.',kode_akun) AS kodeUrut, 
                                  CONCAT(kode_induk, '.', kode_akun) AS KodeAkun, nama_akun AS NamaAkun, header AS Header   
                                  FROM mst_akun WHERE LEVEL = 3 
                                  AND CONCAT(kode_induk,'.',kode_akun) like '3.1%'  
                                  ORDER BY kodeUrut ASC");
        
       
        foreach ($data->result_array() as $row) 
        {
           $namaAkun  = $row['NamaAkun']; 
           $kodeAkun  = $row['KodeAkun'];
           $isHeader  = $row['Header'];

           $strNamaAkun = ($isHeader) ? '<b>'.$i.'. '.$namaAkun.'</b>' : $namaAkun;

           $content = $this->ModelLaporan->GetCALKTreeView($dataTanggal, $nilai, array($kodeAkun), false);

           echo "<tr><td>".$strNamaAkun."</td></tr>";

           echo "<tr><td>".$content."</td></tr>";

           $i++;

        }

        //KODE AKUN SETELAH EKUITAS
        $data = $this->db->query("SELECT  CONCAT(kode_induk,'.',kode_akun) AS kodeUrut, 
                                  CONCAT(kode_induk, '.', kode_akun) AS KodeAkun, nama_akun AS NamaAkun, header AS Header   
                                  FROM mst_akun WHERE LEVEL = 1 AND CONCAT(kode_induk,'.',kode_akun) NOT LIKE '%7%' 
                                  AND CONCAT(kode_induk,'.',kode_akun) >= '0.4'  
                                  ORDER BY kodeUrut ASC");
        
       
        foreach ($data->result_array() as $row) 
        {
           $namaAkun  = $row['NamaAkun']; 
           $kodeAkun  = $row['KodeAkun'];
           $isHeader  = $row['Header'];

           $strNamaAkun = ($isHeader) ? '<b>'.$i.'. '.$namaAkun.'</b>' : $namaAkun;

           $content = $this->ModelLaporan->GetCALKTreeView($dataTanggal, $nilai, array($kodeAkun), false);

           echo "<tr><td>".$strNamaAkun."</td></tr>";

           echo "<tr><td>".$content."</td></tr>";

           $i++;

        }


      ?>


     
     </table> 
  </page>   

  <page footer="page">
     <table class="tabelContent">
        <col style="width: 100%">
        <tr><th>D. PENJELASAN ATAS INFORMASI-INFORMASI NON KEUANGAN</th></tr>
        <tr><td><?php echo $pointD?></td></tr>
     </table> 
  </page>

	</div>

</div>
<?php
/*
  $content = ob_get_clean();
  // conversion HTML => PDF
  require_once 'assets/plugins/html2pdf_v4.03/html2pdf.class.php'; // arahkan ke folder html2pdf
  try
  {
    $html2pdf = new HTML2PDF('P','Legal','en', false, 'ISO-8859-15',array(10, 10, 10, 5)); //setting ukuran kertas dan margin pada dokumen anda
    //$html2pdf->setModeDebug();
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Laporan_CALK.pdf');
  }
  catch(HTML2PDF_exception $e) { echo $e; } 
 */
?>

