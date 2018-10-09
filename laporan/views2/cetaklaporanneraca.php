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

<div class="row">
	<div class="col-md-12">
		<div style="text-align:center; margin-bottom: 10px" >
			<h4>Laporan Neraca</h4>
			<h4><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
			<span>Per 31 Desember <?php echo GetTahunPeriode();?></span>
		</div>
		
		<div class="body-laporan">
			<div class="content-laporan">
				<table class="table table-bordered table-striped tabelContent" border="1">
					<col style="width: 30%">
					<col style="width: 18%">
					<col style="width: 30%">
					<col style="width: 18">

					<tr>
						<th style="width: 30%">Keterangan</th>
						<th>Total (Rp.)</th>
						<th style="width: 30%">Keterangan</th>
						<th style="width: 20%">Total (Rp.)</th>
					</tr>
					<!-- ROWS 1 -->
					<tr>
						<th>Aset</th>
						<th></th>
						<th>Kewajiban</th>
						<th></th>
					</tr>
					<!-- ROWS 2 -->
					<tr>
						<th>Aset Lancar</th>
						<th></th>
						<th>Kewajiban Jangka Pendek</th>
						<th></th>
					</tr>
					<!-- ROWS 3 -->
					<tr>
						<!-- ASSET -->
						<td>
							<ul style="list-style: none;">
							<?php
								$asset = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.1%'
								AND mst_akun.`level` = 3");

								$totalAset = 0;

								foreach($asset->result() as $row)
								{
							?>
								
									<li><?php echo $row->nama_akun?></li>
								
							<?php
								}
							?>
							</ul>
						</td>
						<td>
							<ul style="list-style: none;">
							<?php
								
								$asset = $this->db->query("SELECT 
															CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
															mst_akun.nama_akun
															FROM mst_akun 
															WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.1%'
															AND mst_akun.`level` = 3");

								foreach($asset->result() as $row)
								{
									 
								/*	$saldo = $this->db->query("SELECT COALESCE(SUM(mst_saldo_awal.nominal)) as total FROM mst_saldo_awal
									LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
									WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$row->kodeakun."%'");*/
									$KodeAkun = $row->kodeakun;
									$total = GetTotalSaldo($KodeAkun, $endDate);
									$totalAset += $total;
							?>
								
									<li>Rp. <?php  echo formatCurrency($total);?></li>
								
							<?php
								}
							?>
							</ul>
						</td>
						
						<!-- KEWAJIBAN -->
						
						<td>
							<ul style="list-style: none;">
							<?php
								$kewajiban = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2.1.1%'
								AND mst_akun.`level` = 3");

								$totalKewajibanSaldoDana = 0;
								$totalKewajiban = 0;

								foreach($kewajiban->result() as $row)
								{
									
							?>
								
									<li><?php echo $row->nama_akun?></li>
								
							<?php
								}
							?>
							</ul>
						</td>
						<td>
							<ul style="list-style: none;">
							<?php
								$kewajiban = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2.1.1%'
								AND mst_akun.`level` = 3");
								foreach($kewajiban->result() as $row)
								{
									/*$saldo = $this->db->query("SELECT COALESCE(SUM(mst_saldo_awal.nominal)) as total FROM mst_saldo_awal
									LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
									WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$row->kodeakun."%'");*/
									$KodeAkun = $row->kodeakun;
									$total = GetTotalSaldo($KodeAkun, $endDate);
									$totalKewajibanSaldoDana += $total;
									$totalKewajiban += $total;
							?>
								
									<li>Rp. <?php echo formatCurrency($total)?></li>
								
							<?php
								}
							?>
							</ul>
						</td>
					</tr>
					
					<!-- ROWS 4 -->
					<tr>
						<th></th>
						<th></th>
						<th>Kewajiban Jangka Panjang</th>
						<th></th>
					</tr>
					
					<tr>
						<td></td>
						<td></td>
						<td>
							<ul style="list-style: none;">
							<?php
								$kewajiban = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2.1.2%'
								AND mst_akun.`level` = 3");

								$totalKewajibanSaldoDana = 0;
								$totalKewajiban = 0;

								foreach($kewajiban->result() as $row)
								{
									
							?>
								
									<li><?php echo $row->nama_akun?></li>
								
							<?php
								}
							?>
							</ul>
						</td>
						<td>
							<ul style="list-style: none;">
							<?php
								$kewajiban = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2.1.2%'
								AND mst_akun.`level` = 3");
								foreach($kewajiban->result() as $row)
								{
									/*$saldo = $this->db->query("SELECT COALESCE(SUM(mst_saldo_awal.nominal)) as total FROM mst_saldo_awal
									LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
									WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$row->kodeakun."%'");*/
									$KodeAkun = $row->kodeakun;
									$total = GetTotalSaldo($KodeAkun, $endDate);
									$totalKewajibanSaldoDana += $total;
									$totalKewajiban += $total;
							?>
								
									<li>Rp. <?php echo formatCurrency($total)?></li>
								
							<?php
								}
							?>
							</ul>
						</td>
					</tr>
					
					<!-- ROWS 5 -->
					<tr>
						<td></td>
						<td></td>
						<td>
							<ul style="list-style: none;">
								<?php
									$kewajibanJKPanjang = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
									mst_akun.nama_akun
									FROM mst_akun 
									WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2.2%'
									AND mst_akun.`level` = 3");


									foreach($kewajibanJKPanjang->result() as $row)
									{
								?>
										<li><?php echo $row->nama_akun?></li>
								<?php
									}
								?>
							</ul>
						</td>
						<td>
							<ul style="list-style: none;">
								<?php
									$kewajibanJKPanjang = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
									mst_akun.nama_akun
									FROM mst_akun 
									WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2.2%'
									AND mst_akun.`level` = 3");
									foreach($kewajibanJKPanjang->result() as $row)
									{
										/*$saldo = $this->db->query("SELECT COALESCE(SUM(mst_saldo_awal.nominal)) as total FROM mst_saldo_awal
										LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
										WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$row->kodeakun."%'");*/
										$KodeAkun = $row->kodeakun;
										$total = GetTotalSaldo($KodeAkun, $endDate);
										$totalKewajibanSaldoDana += $total;
										$totalKewajiban 		 += $total;
								?>
										<li>Rp. <?php echo formatCurrency($total)?></li>
								<?php
									}
								?>
							</ul>
						</td>
					</tr>
					
					<!-- rows 6 -->
					
					<tr>
						<th></th>
						<th></th>
						<th>Jumlah Kewajiban</th>
						<th style="text-align:right;"> Rp. 
							<?php
								/*$saldo = $this->db->query("SELECT COALESCE(SUM(mst_saldo_awal.nominal)) as total FROM mst_saldo_awal
										LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
										WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2%'");
								*/
								echo formatCurrency($totalKewajiban);
							?>
						</th>
					</tr>
					
					<!-- rows 7 -->
					<tr>
						<th>Aset Tidak Lancar</th>
						<th></th>
						<th>Saldo Dana</th>
						<th></th>
					</tr>
					
					<!-- rows 8 -->
					
					<tr>
						<td>
							<ul style="list-style: none;">
							<?php
								$asetTidakLancar = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.2%'
								AND mst_akun.`level` = 3");
								foreach($asetTidakLancar->result() as $row)
								{
								
							?>
								<li><?php echo $row->nama_akun?></li>
							<?php
								}
							?>
							</ul>
						</td>
						<td>
							<ul style="list-style: none;">
							<?php
								$asetTidakLancar = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.2%'
								AND mst_akun.`level` = 3");
								foreach($asetTidakLancar->result() as $row)
								{
								/*
								$saldo = $this->db->query("SELECT COALESCE(SUM(mst_saldo_awal.nominal)) as total FROM mst_saldo_awal
										LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
										WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$row->kodeakun."%'");*/

								$KodeAkun = $row->kodeakun;
								$total = GetTotalSaldo($KodeAkun, $endDate);
								$totalAset += $total;
								$saldo = $total;// ($saldo->first_row()->total < 0 ) ? "(".number_format($saldo->first_row()->total *-1) .")" : number_format($saldo->first_row()->total);
									
							?>
								<li>Rp. <?php echo formatCurrency($total);?></li>
							<?php
								}
							?>
							</ul>
						</td>
						<td>
							<ul style="list-style: none;">
							<?php
								$SaldoDana = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '3%'
								AND mst_akun.`level` = 4");

								$totalSaldoDana = 0;
								foreach($SaldoDana->result() as $row)
								{
							?>
								<li><?php echo $row->nama_akun?></li>
							<?php
								}
							?>
							</ul>
						</td>
						<td>
							<ul style="list-style: none;">
							<?php
								$SaldoDana = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '3%'
								AND mst_akun.`level` = 4");
								foreach($SaldoDana->result() as $row)
								{
								
								/*$saldo = $this->db->query("SELECT COALESCE(SUM(mst_saldo_awal.nominal)) as total FROM mst_saldo_awal
										LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
										WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$row->kodeakun."%'");*/

									$KodeAkun = $row->kodeakun;

									//hitung dan masukkan untuk saldo awalnya 
									if (in_array($KodeAkun, GetCOAAkunLinkExclude(array('Surplus/Defisit Periode Lalu')) )  )
									{

										$totalSDPeriodeLalu =  GetTotalAkunSDPeriodeLalu($KodeAkun, $startDate, $endDate);
										
										$totalSaldoDana += $totalSDPeriodeLalu;

										$totalKewajibanSaldoDana += $totalSDPeriodeLalu;

										echo '<li>Rp. '.formatCurrency($totalSDPeriodeLalu).'</li>';
									   
									}
									else if (in_array($KodeAkun, GetCOAAkunLinkExclude(array('Surplus/Defisit Periode Berjalan')) )  )
									{
				 
										$totalSDPeriodeBerjalan =  GetTotalAkunSDPeriodeBerjalan($KodeAkun, $startDate, $endDate);

										$totalSaldoDana += $totalSDPeriodeBerjalan;

										$totalKewajibanSaldoDana += $totalSDPeriodeBerjalan;

										echo '<li>Rp. '.formatCurrency($totalSDPeriodeBerjalan).'</li>';
							   
									}
									else if (in_array($KodeAkun, GetCOAAkunLinkExclude(array('Saldo Awal')) )  )
									{
				   
										$totalSaldoAwal   =  GetTotalAkunSaldoAwal();

										$totalSaldoDana += $totalSaldoAwal;

										$totalKewajibanSaldoDana += $totalSaldoAwal;

										echo '<li>Rp. '.formatCurrency($totalSaldoAwal).'</li>';
									}
									else
									{
									  
										$total   = GetTotalSaldo($KodeAkun, $endDate);
										
										$totalSaldoDana += $total;

										$totalKewajibanSaldoDana += $totalSaldoDana;

										echo '<li>Rp. '.formatCurrency($total).'</li>';
								   
									}  
							
								}
							?>
							</ul>
						</td>
					</tr>
					<!-- rows 9 -->
					
					<tr>
						<th></th>
						<th></th>
						<th>Jumlah Saldo Dana</th>
						<th style="text-align:right;">Rp. 
							<?php
								/*$saldo = $this->db->query("SELECT COALESCE(SUM(mst_saldo_awal.nominal)) as total FROM mst_saldo_awal
										LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
										WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '3%'");*/
										
								echo formatCurrency($totalSaldoDana);
							?>
						</th>
					</tr>
					
					<!-- rows 10 -->
					
					<tr>
						<th>Jumlah Aset</th>
						<th style="text-align:right;"> Rp.
						<?php
							/*$saldo = $this->db->query("SELECT ((SELECT COALESCE(SUM(mst_saldo_awal.nominal)) as total FROM mst_saldo_awal
										LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
										WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.1.1%')
										+
										(SELECT COALESCE(SUM(mst_saldo_awal.nominal)) as total FROM mst_saldo_awal
										LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
										WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.2%')) AS totalasset");
						*/
							echo formatCurrency($totalAset);
						?>
						</th>
						<th>Jumlah Kewajiban dan Saldo Dana</th>
						<th style="text-align:right;">Rp. <?php echo formatCurrency($totalKewajibanSaldoDana);?></th>
					</tr>
				</table>
			</div>
		</div>
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
    $html2pdf->Output('Laporan_neraca.pdf');
    }
    catch(HTML2PDF_exception $e) { echo $e; } 
  }
?>