<?php
	$tes = SanitizeParanoid($this->uri->segment(3));
	if($tes == "pdf")
	{
		ob_start();
		$periode = SanitizeParanoid($this->uri->segment(4));
		$nilai = SanitizeParanoid($this->uri->segment(5));
	}


?>
<style>
	<?php if($tes == 'pdf')
	{ ?>
		h4{
			margin: 0px 5px;
		}

		.tabelContent  {
			font-size:12px; font-family:arial, sans-serif;
			border-collapse:collapse; border-spacing:0;
			width: 100%;
		}
    
	    .tabelContent td
	    {
	      vertical-align: top;
	      overflow:hidden;word-break:normal;
	      padding: 5px;
	    }
		
		table{
			font-size:12px !important;
			width:100% !important;
		}

	    .tabelContent th{
	      text-align: center;
	      vertical-align: middle;
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
	<?php } ?>
</style>

<?php 
	//untuk sementara di bodon
	$startMonth = 1;
	
	if($periode == "Bulan")
	{
		$tanggalAkhir   = GetLastDateInMonth($nilai);
		
		$startDate = GetTahunPeriode()."-".$nilai."-01";
		$endDate = GetTahunPeriode()."-".$nilai."-".$tanggalAkhir;
	}
	elseif($periode == "Triwulan")
	{
		$startMonth = ($nilai == 1) ? 1 : 0; 
		$endMonth 	= ($nilai == 1) ? 3 : 0; 
		
		$startMonth = ($nilai == 2) ? 4 : $startMonth; 
		$endMonth 	= ($nilai == 2) ? 6 : $endMonth;

		$startMonth = ($nilai == 3) ? 7 : $startMonth; 
		$endMonth 	= ($nilai == 3) ? 9 : $endMonth; 
		
		$startMonth = ($nilai == 4) ? 10 : $startMonth; 
		$endMonth 	= ($nilai == 4) ? 12 : $endMonth; 
		
		$tanggalAkhir   = GetLastDateInMonth($endMonth);
		
		$startDate = GetTahunPeriode()."-".$startMonth."-01";
		$endDate = GetTahunPeriode()."-".$endMonth."-".$tanggalAkhir;
	}
	elseif($periode == "Semester")
	{
		$startMonth = ($nilai == 1) ? 1 : 0; 
		$endMonth 	= ($nilai == 1) ? 6 : 0; 
		
		$startMonth = ($nilai == 2) ? 7 : $startMonth; 
		$endMonth 	= ($nilai == 2) ? 12 : $endMonth;
		
		$tanggalAkhir   = GetLastDateInMonth($endMonth);
		
		$startDate = GetTahunPeriode()."-".$startMonth."-01";
		$endDate = GetTahunPeriode()."-".$endMonth."-".$tanggalAkhir;
	}
	elseif($periode == "Tahun")
	{
		$startDate = GetTahunPeriode()."-01-01";
		$endDate = GetTahunPeriode()."-12-31";
	}
	
	
?>

<div class="body-laporan">
	<div style="text-align:center; margin-bottom: 10px;">
		<h4>Laporan Perubahan Aset Kelolaan</h4>
		<h4><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
		<span>Per 31 Desember <?php echo GetTahunPeriode();?></span>
	</div>
	<div class="content-laporan">
		<table class="table table-bordered table-striped tabelContent" border="1">
			<col style="width: 28%">
		 	<col style="width: 12%">
		 	<col style="width: 12%">
		 	<col style="width: 12%">
		 	<col style="width: 12%">
		 	<col style="width: 12%">
		 	<col style="width: 12%">

			<tr>
				<th>Keterangan</th>
				<th>Saldo Awal (Rp.)</th>
				<th>Penambahan (Rp.)</th>
				<th>Pengurangan (Rp.)</th>
				<th>Penyisihan (Rp.)</th>
				<th>Akumulasi Penyusutan (Rp.)</th>
				<th>Saldo Akhir (Rp.)</th>
			</tr>
			<tr>
				<td style="font-weight:bold;">Dana Infak / sedekah - aset kelolaan lancar</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php 
			$seq = 1;
			$totalsaldoawalpiutang = 0;
			$totalpenambahanpiutang = 0;
			$totalpenguranganpiutang = 0;
			$totalsaldoakhirpiutang = 0;
			
			foreach($piutang->result_array() as $row){ 
				$saldoawal = $this->db->query("SELECT * FROM mst_saldo_awal
				WHERE mst_saldo_awal.id_akun = '".$row['idakun']."'");
			?>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $seq.". ".$row['namaakun']?></td>
					<td style="text-align:right;"><?php echo formatCurrency($saldoawal->first_row()->nominal)?></td>
					<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					<td style="text-align:right;"><?php echo formatCurrency($saldoawal->first_row()->nominal)?></td>
				</tr>
			<?php 
			
			$total = ($saldoawal->first_row()->nominal + 0) - 0;
			
			$totalsaldoawalpiutang += $saldoawal->first_row()->nominal;
			$totalpenambahanpiutang += 0;
			$totalpenguranganpiutang += 0;
			$totalsaldoakhirpiutang += $total;
				
			$seq++;
			}?>
			<tr>
				<td style="font-weight:bold;">Dana Infak / sedekah - aset kelolaan tidak lancar</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php 
			$seq = 1;
			$totalsaldoawalasettetap = 0;
			$totalpenambahanasettetap = 0;
			$totalpenguranganasettetap = 0;
			$totalsaldoakhirasettetap = 0;
			
			foreach($asettetap->result_array() as $row){ 
			
			
				$saldoawal = $this->db->query("SELECT * FROM mst_saldo_awal
				WHERE mst_saldo_awal.id_akun = '".$row['idakun']."'");
				
				$penambahan = $this->db->query("SELECT 
				COALESCE(SUM(trx_jurnal_det.debet_akhir), 0) AS total 
				FROM trx_jurnal 
				LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
				WHERE trx_jurnal_det.id_akun = '".$row['idakun']."'
				AND trx_jurnal.id_sumber_trans <> 6
				AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')");
				
				$pengurangan = $this->db->query("SELECT 
				COALESCE(SUM(trx_jurnal_det.kredit_akhir), 0) AS total 
				FROM trx_jurnal 
				LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
				WHERE trx_jurnal_det.id_akun = '".$row['idakun']."'
				AND trx_jurnal.id_sumber_trans <> 6
				AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')");
				
				$akumulasi = $this->db->query("SELECT COALESCE(SUM(trx_jurnal_det.debet_akhir) - SUM(trx_jurnal_det.kredit_akhir), 0 ) as total FROM trx_jurnal
				LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
				WHERE trx_jurnal_det.id_akun = (SELECT id_akun FROM mst_akun WHERE mst_akun.nama_akun LIKE 'akumulasi ".$row['namaakun']."')
				AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')");
				
				$total = (@$saldoawal->first_row()->nominal + @$penambahan->first_row()->total) - @$pengurangan->first_row()->total + $akumulasi->first_row()->total;
			
				$totalsaldoawalasettetap += @$saldoawal->first_row()->nominal;
				$totalpenambahanasettetap += $penambahan->first_row()->total;
				$totalpenguranganasettetap += $pengurangan->first_row()->total;
				$totalsaldoakhirasettetap += $total;
			
			?>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $seq.". ".$row['namaakun']?></td>
				<td style="text-align:right;"><?php echo formatCurrency(@$saldoawal->first_row()->nominal)?></td>
				<td style="text-align:right;"><?php echo formatCurrency($penambahan->first_row()->total)?></td>
				<td style="text-align:right;"><?php echo formatCurrency($pengurangan->first_row()->total)?></td>
				<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
				<td style="text-align:right;"><?php echo formatCurrency($akumulasi->first_row()->total)?></td>
				<td style="text-align:right;"><?php echo formatCurrency($total)?></td>
			</tr>
			<?php 
			$seq++;
			}?>
			<!-- ROWS 1 -->
			<?php
				$totalsaldoawal = $totalsaldoawalasettetap + $totalsaldoawalpiutang;
				$totalpenambahan = $totalpenambahanasettetap + $totalpenambahanpiutang;
				$totalpengurangan = $totalpenguranganasettetap + $totalpenguranganpiutang;
				$totalsaldoakhir = $totalsaldoakhirasettetap + $totalsaldoakhirpiutang;
			?>
			<tr style="font-weight:bold;">
				<td>Total</td>
				<td style="text-align:right;"><?php echo formatCurrency($totalsaldoawal)?></td>
				<td style="text-align:right;"><?php echo formatCurrency($totalpenambahan)?></td>
				<td style="text-align:right;"><?php echo formatCurrency($totalpengurangan)?></td>
				<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
				<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
				<td style="text-align:right;"><?php echo formatCurrency($totalsaldoakhir)?></td>
			</tr>
		</table>
	</div>
</div>


<?php
    
    function GetTotalAkunSaldoAwal()
    {
       $CI=&get_instance(); 
       

       $IDUnitKerja  = $_SESSION['IDSekolah'];
      
       $qryGetSaldo = $CI->db->query("SELECT COALESCE(id_akun,0) AS IDAkun, COALESCE(SUM(COALESCE(debet_akhir,0)),0) - COALESCE(SUM(COALESCE(kredit_akhir, 0)),0) as Total 
                                      FROM trx_jurnal trxJurnal
                                      LEFT JOIN trx_jurnal_det trxJurnalDet
                                      ON trxJurnal.id_jurnal  = trxJurnalDet.id_jurnal
                                      WHERE id_unit_kerja  = '".$IDUnitKerja."' 
                                      AND id_sumber_trans = (SELECT id_sumber_trans FROM ref_sumber_trans WHERE kode = 'SA')
                                      AND id_akun = (SELECT id_akun FROM ref_akunlink WHERE nama_akunlink = 'Saldo Awal') ");
     
      $IDAkun      	 = $qryGetSaldo->first_row()->IDAkun;
      $totalSaldo    = $qryGetSaldo->first_row()->Total;
      
      $data = $CI->db->query("select concat(kode_induk,'.',kode_akun) as KodeAkun from mst_akun where id_akun   = '".$IDAkun."' ");

      $kodeAkun = $data->num_rows() > 0 ? $data->first_row()->KodeAkun : 0;

      $totalSaldo  =  GetTotal($kodeAkun, $totalSaldo);

      return $totalSaldo;
    }

    function GetTotalAkunSDPeriodeLalu($kodeAkun, $tglAkhir = '', $tglAkhir2 = '')
    {
       $CI=&get_instance(); 

        $IDUnitKerja  = $_SESSION['IDSekolah'];
 
        $TotalSaldo = 0;


        $tglAwalPeriode = GetTahunPeriode()."-01-01";

        $qryGetSaldo = $CI->db->query("SELECT COALESCE(SUM( COALESCE(debet_akhir, 0) ) - SUM(COALESCE(kredit_akhir, 0)) , 0) AS Total  
                                      FROM trx_jurnal trxJurnal
                                      INNER JOIN  
                                      trx_jurnal_det trxJurnalDet
                                      ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                                      WHERE id_unit_kerja  = '".$IDUnitKerja."'  
                                      and id_akun in (select id_akun from mst_akun where concat(kode_induk,'.',kode_akun) =  '".$kodeAkun."'  ) 
                                      and id_sumber_trans = (select id_sumber_trans from ref_sumber_trans where kode = 'SA')  ");
        
        $totalSDPeriodeLalu = $qryGetSaldo->first_row()->Total;


        $qryGetSaldo = $CI->db->query("SELECT COALESCE(SUM( COALESCE(debet_akhir, 0) ) - SUM(COALESCE(kredit_akhir, 0)) , 0) AS Total  
                                      FROM trx_jurnal trxJurnal
                                      INNER JOIN  
                                      trx_jurnal_det trxJurnalDet
                                      ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                                      WHERE (tgl_jurnal >= '".$tglAwalPeriode." 00:00:00' and tgl_jurnal<'".$tglAkhir." 23:59:59') 
                                      AND id_unit_kerja  = '".$IDUnitKerja."'    
                                      AND id_akun in (select id_akun from mst_akun where 
                                          (concat(kode_induk,'.',kode_akun) LIKE '4%' or  concat(kode_induk,'.',kode_akun) LIKE '5%' or 
                                          concat(kode_induk,'.',kode_akun) LIKE '6%' or  
                                          concat(kode_induk,'.',kode_akun) LIKE '8%' or concat(kode_induk,'.',kode_akun) LIKE '9%') ) ");
      
        $totalSDPeriodeLalu = $totalSDPeriodeLalu + $qryGetSaldo->first_row()->Total;
        $totalEkuitasAwal = 0;

        if (in_array($kodeAkun, GetCOAAkunLinkExclude(array('Surplus/Defisit Periode Lalu')) )  )
        {
             $qryGetSaldo = $CI->db->query("SELECT  COALESCE( SUM(CASE WHEN mstAkun.saldo_normal = 'D' THEN COALESCE(debet_akhir, 0) - COALESCE(kredit_akhir, 0) ELSE COALESCE(kredit_akhir, 0) - COALESCE(debet_akhir,0) END), 0) AS Total 
                
                                            FROM trx_jurnal trxJurnal
                                            LEFT JOIN  
                                            trx_jurnal_det trxJurnalDet
                                            ON trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                                            LEFT JOIN
                                            mst_akun mstAkun
                                            ON mstAkun.id_akun = trxJurnalDet.id_akun  
                                            WHERE (tgl_jurnal >= '".$tglAkhir." 00:00:00' and tgl_jurnal<='".$tglAkhir2." 23:59:59') 
                                            AND id_unit_kerja  = '".$IDUnitKerja."'     
                                            AND trxJurnalDet.id_akun in (select id_akun from mst_akun where concat(kode_induk,'.',kode_akun) = '".$kodeAkun."'  ) 
                                            and id_sumber_trans not in (select id_sumber_trans from ref_sumber_trans where kode = 'SA') ");

            $totalEkuitasAwal =   $qryGetSaldo->num_rows() > 0 ? $qryGetSaldo->first_row()->Total : 0;
        }

        $totalSDPeriodeLalu = GetTotal($kodeAkun, $totalSDPeriodeLalu);
        $totalSDPeriodeLalu += $totalEkuitasAwal;

        return $totalSDPeriodeLalu;
    }
    
    function GetTotalAkunSDPeriodeBerjalan($kodeAkun, $tglAwal = '', $tglAkhir = '')
    {
       $CI=&get_instance(); 

        $IDUnitKerja  = $_SESSION['IDSekolah'];
  
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
                                            CONCAT(kode_induk,'.',kode_akun) LIKE '6%' OR  
                                            concat(kode_induk,'.',kode_akun) LIKE '8%' or concat(kode_induk,'.',kode_akun) LIKE '9%') )
                                     ");
    

        $totalSDPeriodeBerjalan = $qryGetSaldo->first_row()->Total;
 
        $totalSDPeriodeBerjalan = GetTotal($kodeAkun, $totalSDPeriodeBerjalan);

        return $totalSDPeriodeBerjalan;
    }

    function GetTotalSaldo($KodeAkun, $tglAkhir = '')
    {
       $CI=&get_instance();   

       $IDUnitKerja   = $_SESSION['IDSekolah'];
 
       $strIDAkun = '';
      
       $arrKodeAkunExclode = array('Surplus/Defisit Periode Berjalan', 'Saldo Awal');

       foreach (GetCOAAkunLinkExclude($arrKodeAkunExclode) as $value) 
       {
          $strIDAkun.= "'".$value."',";
       }


       $strIDAkun = substr($strIDAkun, 0 , strlen($strIDAkun) - 1);
       $TotalSaldo = 0;

       $tglAwalPeriode = GetTahunPeriode()."-01-01";

       $strQuery 	= "SELECT COALESCE(SUM( COALESCE(debet_akhir, 0) ) - SUM(COALESCE(kredit_akhir, 0)) , 0) as TotalSaldo  
                          FROM trx_jurnal trxJurnal
                          inner join  
                          trx_jurnal_det trxJurnalDet 
                          on trxJurnal.id_jurnal = trxJurnalDet.id_jurnal 
                          WHERE (tgl_jurnal >= '".$tglAwalPeriode." 00:00:00' and tgl_jurnal<='".$tglAkhir." 23:59:59') 
                          and id_akun not in (".$strIDAkun.")  
                          and id_unit_kerja  = '".$IDUnitKerja."'  
                          and id_akun in (select id_akun from mst_akun where concat(kode_induk,'.',kode_akun) like  '".$KodeAkun."%'  ) ";

        $qryGetSaldo = $CI->db->query($strQuery);

        $TotalSaldo2 = $qryGetSaldo->first_row()->TotalSaldo;

        $TotalSaldo  = GetTotal($KodeAkun, $TotalSaldo2);

       return $TotalSaldo;
    }

  
?>


<?php
  if($tes == 'pdf'){
    $content = ob_get_clean();
      // conversion HTML => PDF
    require_once 'assets/plugins/html2pdf_v4.03/html2pdf.class.php'; // arahkan ke folder html2pdf
    try
    {
    $html2pdf = new HTML2PDF('L','A4','fr', false, 'ISO-8859-15',array(5, 5, 5, 5)); //setting ukuran kertas dan margin pada dokumen anda
    // $html2pdf->setModeDebug(true);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Laporan_perubahan_aset.pdf');
    }
    catch(HTML2PDF_exception $e) { echo $e; } 
  }
?>