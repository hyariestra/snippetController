<?php
	$tes = SanitizeParanoid($this->uri->segment(3));
	if($tes == "pdf")
	{
		ob_start();
		$periode = SanitizeParanoid($this->uri->segment(4));
		$nilai = SanitizeParanoid($this->uri->segment(5));
		$kasbank = SanitizeParanoid($this->uri->segment(6));
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

	    th{
	    	text-align: center;
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
	
	// echo $startDate." - ".$endDate;
	switch ($periode) {
		case 'Bulan': $tglHeader = 'Bulan '.GetMonthName($nilai).' - '.GetTahunPeriode(); break;
		case 'Triwulan': $tglHeader = 'Triwulan '.$nilai.' - '.GetTahunPeriode(); break;
		case 'Semester': $tglHeader = 'Semester '.$nilai.' - '.GetTahunPeriode(); break;
		case 'Tahun': $tglHeader = 'Tahun '.$nilai; break;
	}

	// echo "nilai ".$nilai;
?>

<div class="row">
	<div class="col-md-12">
	<div style="font-size:12px; text-align:right; margin-bottom:30px;">
				<span><?php echo $perusahaan->first_row()->nama_perusahaan?></span>
				<br>
				<span><?php echo  $perusahaan->first_row()->alamat_perusahaan?></span>
				<br>
				<span><?php echo $perusahaan->first_row()->no_telp?> </span>
			</div>
			<hr>
		<div style="text-align:center; margin-bottom: 15px !important;">
			<h4>Laporan Rekap Pengeluaran</h4>
			<h4><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
			<span><?php echo $tglHeader;?></span>
		</div>
		
		<div class="body-laporan">
			<div class="content-laporan">
				<table class="table table-bordered table-striped tabelContent" border="1" align="center">
					
					<?php $thead 	= ($tes != 'pdf') ? "background:#429489; color: #fff;" : "background:gray;"; ?>
					<thead style="<?php echo $thead ?>">
					<tr>
						<th style="width: 5%;text-align: center;">#</th>
						<th style="width: 10%;text-align: center;">Tanggal</th>
						<th style="width: 25%;text-align: center;">No.Bukti</th>
						<th style="width: 20%;text-align: center;">Keterangan</th>
								<!-- <th style="width: 15%;text-align: center;">Debet</th>-->
						<th style="width: 15%;text-align: center;">Pengurangan</th> 
						<th style="width: 20%;text-align: center;">Saldo Akhir</th>
					</tr>
					</thead>

					<!-- ThisISit ! -->
					<?php 
					
					
					if($kasbank != "-")
					{
						//$where = "jdet.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE mst_akun.id_akun = '".$kasbank."')";
						$where = " AND juu.id_bank = (SELECT id_bank FROM mst_bank WHERE mst_bank.id_akunbank = '".$kasbank."')";
						$whereSA = " AND mst_akun.id_akun = '".$kasbank."'";
					}
					else
					{
						//$where = "jdet.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.1.1%')";
						$where = "";
						$whereSA = "";
					}
					
					/*
					$bukas 	= $this->db->query("SELECT jur.tgl_jurnal AS Tanggal, 
						jur.no_bukti AS Nomor, jur.uraian AS Ket,
						SUM(jdet.kredit_akhir) AS Kredit, 
						SUM(jdet.debet_akhir) AS Debet
						FROM trx_jurnal jur
						LEFT JOIN trx_jurnal_det jdet ON jur.id_jurnal = jdet.id_jurnal
						LEFT JOIN mst_akun ak ON ak.id_akun = jdet.id_akun
						WHERE jur.id_jurnal IN (SELECT 
						jur.id_jurnal
						FROM trx_jurnal jur
						LEFT JOIN trx_jurnal_det jdet ON jur.id_jurnal = jdet.id_jurnal
						WHERE $where
						)
						AND jdet.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '5%')
						and NOT $where
						AND jur.tgl_jurnal BETWEEN '".$startDate."' AND '".$endDate."'
						GROUP BY jur.id_jurnal");
					*/
					
					$idunit		= ($_SESSION['IDUnit'] != 1 ) ? " AND juu.id_unit = '".$_SESSION['IDUnit']."'" : "";
					
					$bukas = $this->db->query("SELECT 
					juu.id_pembelian AS id, 
					juu.tanggal AS tgl, 
					juu.nomor_transaksi AS no_bukti,
					juu.deskripsi AS keterangan, 
					CONCAT(0) AS debet, 
					SUM(juudet.jumlah * juudet.harga) AS kredit 
					FROM trx_pembelian_persediaan juu
					LEFT JOIN trx_pembelian_persediaan_det juudet ON juu.id_pembelian = juudet.id_pembelian
					LEFT JOIN mst_item item ON juudet.id_item = item.id_item
					WHERE juu.tanggal >= '".$startDate."' AND juu.tanggal <= '".$endDate."'
					$where $idunit
					GROUP BY juu.id_pembelian");
				
					$SA = $this->db->query("SELECT SUM(nominal) AS nominal FROM mst_saldo_awal 
					LEFT JOIN mst_akun ON mst_akun.id_akun = mst_saldo_awal.id_akun
					WHERE CONCAT(mst_akun.kode_induk,'.', mst_akun.kode_akun) LIKE '1.1.1%'
					$whereSA");
					
					$pemasukan = $this->db->query("
					SELECT SUM(trx_jurnal_det.kredit_akhir) as pemasukan 
					FROM trx_jurnal
					LEFT JOIN trx_jurnal_det ON trx_jurnal.id_jurnal = trx_jurnal_det.id_jurnal
					WHERE (trx_jurnal.tgl_jurnal < '".$startDate."')
					AND trx_jurnal_det.id_akun IN (SELECT mst_akun.id_akun  FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.', mst_akun.kode_akun) LIKE  '4%')
					");
					
					$pengeluaran = $this->db->query("SELECT SUM(trx_jurnal_det.debet_akhir) as pengeluaran 
					FROM trx_jurnal
					LEFT JOIN trx_jurnal_det ON trx_jurnal.id_jurnal = trx_jurnal_det.id_jurnal
					WHERE (trx_jurnal.tgl_jurnal < '".$startDate."')
					AND trx_jurnal_det.id_akun IN (SELECT mst_akun.id_akun FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.', mst_akun.kode_akun) LIKE  '5%')
					");
					
					$slkhir = ($SA->first_row()->nominal + $pemasukan->first_row()->pemasukan) - $pengeluaran->first_row()->pengeluaran;
					?>
						
					<tr>
						<td></td>
						<td></td>
						<td>SA001</td>
						<td style="text-align:left; padding-right:5px;">Saldo Awal</td>
						<!-- <td style="text-align:right; padding-right:5px;"><?php echo formatCurrency(0) ?></td>-->
						<td style="text-align:right; padding-right:5px;"></td> 
						<td style="text-align:right; padding-right:5px;"><?php echo formatCurrency($slkhir) ?></td>
					</tr>	
					<?php
					foreach ($bukas->result() as $bu => $kas) 
					{ 
						//$slkhir = $slkhir + $kas->Kredit - $kas->Debet;
						$slkhir = $slkhir - $kas->kredit;
						?>
						<tr>
							<td><?php echo $bu+1?></td>
							<td><?php echo date('d-m-Y', strtotime($kas->tgl)) ?></td>
							<td><?php echo $kas->no_bukti ?></td>
							<td><?php echo $kas->keterangan ?></td>
							<!-- <td style="text-align:right; padding-right:5px;"><?php echo formatCurrency($kas->Debet) ?></td> -->
							<td style="text-align:right; padding-right:5px;"><?php echo formatCurrency($kas->kredit) ?></td> 
							<td style="text-align:right; padding-right:5px;"><?php echo formatCurrency($slkhir) ?></td>
						</tr>
					<?php 
					}
					?>
				</table>
			</div>
		</div>
	</div>
</div>

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
	    $html2pdf->Output('Laporan_bukukas_pengeluaran.pdf');
    }
    catch(HTML2PDF_exception $e) { echo $e; } 
  }
?>