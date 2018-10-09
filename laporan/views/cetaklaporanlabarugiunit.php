<?php

	//echo "<pre>";print_r(json_decode($pendapatan));"</pre>";
	//exit();
	
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

		ul {
			list-style: none !important;
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
		
		table tr th, table tr td{
			border: 1px solid #000;
			padding:5px;
			width:40%;
		}
		
		table{
			font-size:12px !important;
			width:100% !important;
			border:1px solid #000;
			border-collapse:collapse;
			width:100%;
		}

	    .tabelContent th{
	      vertical-align: middle;
	      padding: 10px;
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
	    padding-right: 10px;
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
		
		$endMonth = $nilai;
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
		
		$endMonth = 12;
	}
	
	switch ($periode) {
		case 'Bulan': $tglHeader = 'Sampai tanggal '.$tanggalAkhir." ".GetMonthName($nilai).' - '.GetTahunPeriode(); break;
		case 'Triwulan': $tglHeader = 'Sampai tanggal '.$tanggalAkhir." ".GetMonthName($endMonth).' - '.GetTahunPeriode(); break;
		case 'Semester': $tglHeader = 'Sampai tanggal '.$tanggalAkhir." ".GetMonthName($endMonth).' - '.GetTahunPeriode(); break;
		case 'Tahun': $tglHeader = 'Tahun '.$nilai; break;
	}
?>

<div class="row">
	<div class="col-md-12">
		<div style="text-align:center; margin-bottom: 15px !important;">
			<h4>Laporan Laba Rugi</h4>
			<h4><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
			<span><?php echo $tglHeader;?></span>
		</div>
		
		<div class="body-laporan">
			<div class="content-laporan">
				<table class="table table-bordered table-stripped">
					<tr>
						<th style="width:60%;">Nama Akun</th>
						<th><?php echo  GetMonthName($endMonth)." ".GetTahunPeriode() ?></th>
					</tr>
					<!-- PENDAPATAN -->
					
					<?php
						
						$total = 0;
						$kodeakun = json_decode($pendapatan);
						
						foreach($kodeakun as $akun)
						{
							if($akun->level <= 2 && $akun->kodePlainText != "4.1.99")
							{
								$space = getSpace($akun->level);
								
					?>
								<tr>
									<td style="<?php echo $space?>"><?php echo $akun->namaPlainText?></td>
									<td style="text-align:right;"></td>
								</tr>
					<?php
								if($akun->level == 2 && $akun->kodePlainText != "4.1.99")
								{
									$kategorunit = $this->db->query("SELECT 
									CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodePlainText,
									mst_akun.saldo_normal as saldoNormal,
									mst_akun.level as level,
									mst_akun.nama_akun as namaPlainText
									FROM mst_kategori_item
									LEFT JOIN mst_akun ON mst_akun.id_akun = mst_kategori_item.id_akun
									WHERE mst_kategori_item.id_unit = '".$_SESSION['IDUnit']."'
									AND mst_kategori_item.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$akun->kodePlainText."%')
									AND mst_kategori_item.id_akun NOT IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '4.1.99%')");
									
									foreach($kategorunit->result() as $rowkate)
									{
								
										$space = getSpace($rowkate->level);
										$nominal = formatCurrency(GetTotalAkun($rowkate->kodePlainText, $rowkate->saldoNormal, $rowkate->level, $startDate, $endDate));
					?>
										<tr>
											<td style="<?php echo $space?>"><?php echo $rowkate->namaPlainText?></td>
											<td style="text-align:right;"><?php echo $nominal?></td>
										</tr>
					<?php
										$total += GetTotalAkun($rowkate->kodePlainText, $rowkate->saldoNormal, $rowkate->level , $startDate, $endDate);
									}
								}
							}
						}
					?>
					<tr>
						<td><b>TOTAL PENDAPATAN USAHA</b></td>
						<td style="text-align:right; font-weight:bold;"><?php echo formatCurrency($total)?></td>
					</tr>
					
					<!-- BIAYA -->
					<?php
						
						$kodeakunBiaya = json_decode($biaya);
						$totalbiaya = 0;
						
						foreach($kodeakunBiaya as $akunBiaya)
						{
							if($akunBiaya->level <= 2  && $akunBiaya->kodePlainText != "5.1.99")
							{
								$space = getSpace($akunBiaya->level);
					?>
								<tr>
									<td style="<?php echo $space?>"><?php echo $akunBiaya->namaPlainText?></td>
									<td style="text-align:right;"></td>
								</tr>
					<?php
								
								if($akunBiaya->level == 2 && $akunBiaya->kodePlainText != "5.1.99")
								{
									$kategorunit = $this->db->query("SELECT 
									CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodePlainText,
									mst_akun.saldo_normal as saldoNormal,
									mst_akun.level as level,
									mst_akun.nama_akun as namaPlainText
									FROM mst_kategori_item
									LEFT JOIN mst_akun ON mst_akun.id_akun = mst_kategori_item.id_akun
									WHERE mst_kategori_item.id_unit = '".$_SESSION['IDUnit']."'
									AND mst_kategori_item.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$akunBiaya->kodePlainText."%')
									AND mst_kategori_item.id_akun NOT IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '5.1.99%')");
									
									foreach($kategorunit->result() as $rowkate)
									{
										$space = getSpace($rowkate->level);
										$nominal = formatCurrency(GetTotalAkun($rowkate->kodePlainText, $rowkate->saldoNormal, $rowkate->level, $startDate, $endDate));
								
					?>
										<tr>
											<td style="<?php echo $space?>"><?php echo $rowkate->namaPlainText?></td>
											<td style="text-align:right;"><?php echo $nominal?></td>
										</tr>
					<?php
										$totalbiaya += GetTotalAkun($rowkate->kodePlainText, $rowkate->saldoNormal, $rowkate->level, $startDate, $endDate);
									}
								}
							}
						}
					?>
					<tr>
						<td><b>TOTAL BIAYA USAHA</b></td>
						<td style="text-align:right; font-weight:bold;"><?php echo formatCurrency($totalbiaya) ?></td>
					</tr>
					<?php
						$surdefops = $total - $totalbiaya;
					?>
					<tr>
						<td><b>LABA/(RUGI) DARI KEGIATAN OPERASIONAL</b></td>
						<td style="text-align:right; font-weight:bold;"><?php echo formatCurrency($surdefops) ?></td>
					</tr>
					<!-- OPERASIONAL -->
					<tr>
						<td><b>PENDAPATAN NON OPERASIONAL</b></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left:20px"><b>PENDAPATAN NON OPERASIONAL</b></td>
						<td></td>
					</tr>
					<?php
						
						$nonops = json_decode($pendapatan);
						
						$totalnonops = 0;
						
						foreach($nonops as $akunnonops)
						{
							$space = getSpace($akunnonops->level);
							
							if($akunnonops->kodePlainText == "4.1.99")
							{
								
								$kategorunit = $this->db->query("SELECT 
								CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodePlainText,
								mst_akun.saldo_normal as saldoNormal,
								mst_akun.level as level,
								mst_akun.nama_akun as namaPlainText
								FROM mst_kategori_item
								LEFT JOIN mst_akun ON mst_akun.id_akun = mst_kategori_item.id_akun
								WHERE mst_kategori_item.id_unit = '".$_SESSION['IDUnit']."'
								AND mst_kategori_item.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$akunnonops->kodePlainText."%')");
								
								$space = getSpace($kategorunit->first_row()->level);
								$nominal = formatCurrency(GetTotalAkun($kategorunit->first_row()->kodePlainText, $kategorunit->first_row()->saldoNormal, $kategorunit->first_row()->level, $startDate, $endDate));
								
					?>
								<tr>
									<td style="<?php echo $space?>"><?php echo $kategorunit->first_row()->namaPlainText?></td>
									<td style="text-align:right;"><?php echo formatCurrency(GetTotalAkun($akunnonops->kodePlainText, $akunnonops->saldoNormal, $akunnonops->level, $startDate, $endDate)) ?></td>
								</tr>
					<?php
								$totalnonops += GetTotalAkun($akunnonops->kodePlainText, $akunnonops->saldoNormal, $akunnonops->level, $startDate, $endDate);
							}
						}
					?>
					
					<tr>
						<td><b>TOTAL PENDAPATAN NON OPERASIONAL</b></td>
						<td style="text-align:right; font-weight:bold;"><?php echo formatCurrency($totalnonops) ?></td>
					</tr>
					
					<!-- BIAYA NON OPERASIONAL -->
					<tr>
						<td><b>BIAYA NON OPERASIONAL</b></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left:20px"><b>BIAYA NON OPERASIONAL</b></td>
						<td></td>
					</tr>
					<?php
						
						$biayanonops = json_decode($biaya);
						
						$totalbiayanonops = 0;
						
						foreach($biayanonops as $akunbiayanonops)
						{
							$space = getSpace($akunbiayanonops->level);
							
							if($akunbiayanonops->kodePlainText == "5.1.99")
							{
								$kategorunit = $this->db->query("SELECT 
								CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodePlainText,
								mst_akun.saldo_normal as saldoNormal,
								mst_akun.level as level,
								mst_akun.nama_akun as namaPlainText
								FROM mst_kategori_item
								LEFT JOIN mst_akun ON mst_akun.id_akun = mst_kategori_item.id_akun
								WHERE mst_kategori_item.id_unit = '".$_SESSION['IDUnit']."'
								AND mst_kategori_item.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$akunbiayanonops->kodePlainText."%')");
								
								$space = getSpace(@$kategorunit->first_row()->level);
								$nominal = formatCurrency(GetTotalAkun(@$kategorunit->first_row()->kodePlainText, @$kategorunit->first_row()->saldoNormal, @$kategorunit->first_row()->level, $startDate, $endDate));
					?>
								<tr>
									<td style="<?php echo $space?>"><?php echo @$kategorunit->first_row()->namaPlainText?></td>
									<td style="text-align:right;"><?php echo formatCurrency(GetTotalAkun(@$kategorunit->first_row()->kodePlainText, @$kategorunit->first_row()->saldoNormal, @$kategorunit->first_row()->level, $startDate, $endDate)) ?></td>
								</tr>
					<?php
								$totalbiayanonops += GetTotalAkun(@$kategorunit->first_row()->kodePlainText, @$kategorunit->first_row()->saldoNormal, @$kategorunit->first_row()->level, $startDate, $endDate);
							}
						}
					?>
					<tr>
						<td><b>TOTAL BIAYA NON OPERASIONAL</b></td>
						<td style="text-align:right; font-weight:bold;"><?php echo formatCurrency($totalbiayanonops)?></td>
					</tr>
					<?php
						$surdefnonops = $totalnonops - $totalbiayanonops;
					?>
					<tr>
						<td><b>LABA/(RUBI) DARI KEGIATAN NON OPERASIONAL</b></td>
						<td style="text-align:right; font-weight:bold;"><?php echo formatCurrency($surdefnonops) ?></td>
					</tr>
					<?php
						$surdef = $surdefops - $surdefnonops;
					?>
					<tr>
						<td><b>LABA/(RUGI)</b></td>
						<td style="text-align:right; font-weight:bold;"><?php echo formatCurrency($surdef) ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<?php
    
	function getSpace($level)
	{
		$space = "";
		for($i = 1; $i <= $level; $i++);
		{
			$pad = 10 * $i;
			$space .= "padding-left:".$pad."px";
			
		}
		
		
		return $space;
	}
	
	function GetTotalAkun($kodeakun, $saldonormal, $level, $startDate, $endDate)
	{
		$CI=&get_instance(); 
		
		$query = $CI->db->query("SELECT 
		COALESCE(SUM(trx_jurnal_det.debet_akhir * trx_jurnal_det.nilai_tukar),0) as debet,
		COALESCE(SUM(trx_jurnal_det.kredit_akhir * trx_jurnal_det.nilai_tukar),0) as kredit		
		FROM trx_jurnal
		LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
		WHERE trx_jurnal_det.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$kodeakun."%')
		AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')");
		
		$debet = (isset($query->first_row()->debet)) ? $query->first_row()->debet : 0;
		$kredit = (isset($query->first_row()->kredit)) ? $query->first_row()->kredit : 0;
		
		$nominal = ($saldonormal == "D") ? $debet : $kredit;
		
		//$nominal = ($level == 3) ? $nominal : 0;
		
		return $nominal;
	}
	
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
    $html2pdf = new HTML2PDF('P','A4','fr', false, 'ISO-8859-15',array(5, 5, 5, 5)); //setting ukuran kertas dan margin pada dokumen anda
    // $html2pdf->setModeDebug(true);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Laporan_neraca.pdf');
    }
    catch(HTML2PDF_exception $e) { echo $e; } 
  }
?>