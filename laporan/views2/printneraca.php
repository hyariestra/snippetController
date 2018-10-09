<?php
	ob_start();

	// echo print_r($datane);
?>
<style>
	h4{
		margin-top: 0px;
		margin-bottom: 0px;
	}
	h5{
		margin-top: 0px;
	}
	.coll-td{
		padding:5px;
		font-size:12px;
	}
	th.coll-td{
		text-align: center;
	}
	th{
		text-align: center;
		padding: 2px 5px;
	}
	.ttd{
		text-align:right;
		padding-right: 50px;
		margin-top:50px;
	}
	.table tbody > tr > td.form-input{
		padding:3px 3px 3px 5px !important;
	}
	
	ul{
		list-style-type: none;
	}
	
	input[role='inputtext']{
		padding:3px !important;
		height:25px;
	}
	
	span[role='glyphicongroup']{
		padding:0px 12px !important;
		height:25px;
	}
	
	.nopadding{
		padding:0px 5px 0px 5px !important;
		margin-top:0px;
	}
	
	.labelinput{
		font-weight:bold;
		font-size:12px !important;
	}
	
	.customefooter{
		padding:5px !important;
		margin-top:0px !important;
		background:#787a93;
		color:#fff;
		font-size:12px;
	}
	
	.datepicker{
		z-index:9999 !important;
	}
	
	.content-laporan{
		border : 1px solid #ccc;
		padding : 10px;
	}

	.table{
		width: 595pt;
	}

	.tesst{
		type: none;
	}
	
	/*.body-laporan{
		margin-left : 10%;
		margin-right : 10%;
	}*/
</style>
<?php 
	//untuk sementara di bodon
	$startDate = GetTahunPeriode()."-01-01";
	$endDate = GetTahunPeriode()."-12-31";
?>
<page footer="page">
<div style="font-size:12px; float:left;position:absolute;">
	<img src="<?php echo base_url("upload/".$perusahaan->first_row()->gambar)?>" style="height:50px;width:50px;" />
</div>
<div style="font-size:12px; text-align:right; margin-bottom:30px;">
	<span><?php echo $perusahaan->first_row()->nama_perusahaan?></span>
	<br>
	<span><?php echo  $perusahaan->first_row()->alamat_perusahaan?></span>
	<br>
	<span><?php echo $perusahaan->first_row()->no_telp?> </span>
</div>

<hr>

<div class="widget-container" style="padding:10px;">
	<div class="row">
		<div class="col-md-12">
			<div style="text-align:center;">
				<h4>Laporan Neraca</h4>
				<h4><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
				<h5>Per 31 Desember <?php echo GetTahunPeriode();?></h5>
			</div>
			
			<div class="body-laporan">
				<table class="table" border="1" style="border-collapse:collapse;table-layout:fixed;width:100%;">
					<col style="width: 25%">
		          	<col style="width: 25%">
		          	<col style="width: 25%">
		          	<col style="width: 20%">

					<tr>
						<th>Keterangan</th>
						<th>Total (Rp.)</th>
						<th>Keterangan</th>
						<th>Total (Rp.)</th>
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
							<ul>
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
							<ul>
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
								
									<li style="list-style-type: none;">Rp. <?php  echo formatCurrency($total);?></li>
								
							<?php
								}
							?>
							</ul>
						</td>
						
						<!-- KEWAJIBAN -->
						
						<td>
							<ul>
							<?php
								$kewajiban = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2.1%'
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
							<ul>
							<?php
								$kewajiban = $this->db->query("SELECT CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun,
								mst_akun.nama_akun
								FROM mst_akun 
								WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '2.1%'
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
					
					<!-- ROWS 5 -->
					<tr>
						<td></td>
						<td></td>
						<td>
							<ul>
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
							<ul>
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
							<ul>
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
							<ul>
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
							<ul>
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
							<ul>
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
</page>
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
	$content = ob_get_clean();
	  // conversion HTML => PDF
  require_once 'assets/plugins/html2pdf_v4.03/html2pdf.class.php'; // arahkan ke folder html2pdf
  try
  {
	$html2pdf = new HTML2PDF('L','A4','fr', false, 'ISO-8859-15',array(5, 5, 5, 5)); //setting ukuran kertas dan margin pada dokumen anda
	//$html2pdf->setModeDebug();
	$html2pdf->pdf->SetDisplayMode('fullpage');
	$html2pdf->setDefaultFont('Arial');
	$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	$html2pdf->Output('rekappenerimaan.pdf');
  }
  catch(HTML2PDF_exception $e) { echo $e; } 
?>
