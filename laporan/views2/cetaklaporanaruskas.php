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

<div class="row">
	<div class="col-md-12">
		<div style="text-align:center; margin-bottom: 10px;">
			<h4>Laporan Arus Kas</h4>
			<h4><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
			<span>Per 31 Desember <?php echo GetTahunPeriode();?></span>
		</div>
		
		<div class="body-laporan">
			<div class="content-laporan">
				<table class="table table-bordered table-striped tabelContent" border="1">
					<col style="width: 60%">
					<col style="width: 20%">
					<col style="width: 20%">
					
					<tr>
						<th>Uraian</th>
						<th>Tahun <?php echo GetTahunPeriode();?></th>
						<th>Tahun <?php echo GetTahunPeriode() - 1;?></th>
					</tr>
					
					<!-- AKTIVITAS OPERASIONAL AKUN 4-->
					<tr>
						<td style="font-weight:bold;">Arus Kas Dari Aktvitias Opersional</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td style="font-weight:bold; padding-left : 10px;">Arus Kas Masuk</td>
						<td></td>
						<td></td>
					</tr>
					<?php
						$totalArusMasuk = 0;
						$nominalJuMasuk = 0;
						
						$pendapatan = $this->db->query("SELECT 
						tablepemasukan.nama_item,
						tablepemasukan.id_akunitem,
						SUM(tablepemasukan.total) as total
						FROM (SELECT 
						mst_item.nama_item,
						mst_item.id_akunitem,
						SUM(COALESCE(trx_penjualan_det.jumlah_item,0) * COALESCE(trx_penjualan_det.harga,0)) - SUM(COALESCE(trx_penjualan_det.potongan)) as total
						FROM trx_penjualan
						LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
						LEFT JOIN mst_item ON mst_item.id_item = trx_penjualan_det.id_item
						WHERE (trx_penjualan.tanggal_penjualan >= '".$startDate."' AND trx_penjualan.tanggal_penjualan <= '".$endDate."')
						GROUP BY mst_item.id_akunitem
						UNION ALL
						SELECT 		
						mst_akun.nama_akun as nama_item,
						mst_akun.id_akun as id_akunitem,
						COALESCE(SUM(trx_jurnal_det.kredit_akhir), 0) as total
						FROM trx_jurnal
						LEFT JOIN trx_jurnal_det ON trx_jurnal.id_jurnal = trx_jurnal_det.id_jurnal
						LEFT JOIN mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
						WHERE trx_jurnal.id_jurnal IN (
																SELECT 		
																trx_jurnal.id_jurnal
																FROM trx_jurnal
																LEFT JOIN trx_jurnal_det ON trx_jurnal.id_jurnal = trx_jurnal_det.id_jurnal
																LEFT JOIN mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
																WHERE (trx_jurnal_det.id_akun in (SELECT mst_akun.id_akun FROM mst_akun WHERE concat(mst_akun.kode_induk,'.',mst_akun.kode_induk) LIKE '1.1.1%'))
																AND trx_jurnal.id_sumber_trans = 1 
																)
						AND NOT (trx_jurnal_det.id_akun in (SELECT mst_akun.id_akun FROM mst_akun WHERE concat(mst_akun.kode_induk,'.',mst_akun.kode_induk) LIKE '1.1.1%'))
						AND trx_jurnal.id_sumber_trans = 1 
						AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')
						AND concat(mst_akun.kode_induk,'.',mst_akun.kode_induk) LIKE '4%'
						GROUP BY mst_akun.id_akun
						HAVING COALESCE(SUM(trx_jurnal_det.kredit_akhir), 0) > 0)
						as tablepemasukan
						GROUP BY tablepemasukan.id_akunitem");
			
						foreach($pendapatan->result() as $row){
						
						
						
						
						$nominal = $row->total;
					?>
					<tr>
						<td style="padding-left : 15px;"><?php echo $row->nama_item?></td>
						<td style="text-align:right;"><?php echo formatCurrency($nominal)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					<?php
					
						$totalArusMasuk += $nominal;
						}
					?>
					<tr style="font-weight:bold;">
						<td >Jumlah Arus Masuk</td>
						<td style="text-align:right;"><?php echo formatCurrency($totalArusMasuk)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					
					<!-- AKTIVITAS OPERASIONAL AKUN 5-->
					<tr>
						<td style="font-weight:bold; padding-left:10px;">Arus Kas Keluar</td>
						<td></td>
						<td></td>
					</tr>
					<?php
						$totalArusKeluar = 0;
						$nominalJuKeluar = 0;
			
						$pengeluaran = $this->db->query("SELECT 
						tablepegeluaran.nama_item,
						tablepegeluaran.id_akunitem,
						SUM(tablepegeluaran.total) as total
						FROM (SELECT 
						mst_item.nama_item,
						mst_item.id_akunitem,
						SUM(COALESCE(trx_pembelian_persediaan_det.jumlah,0) * COALESCE(trx_pembelian_persediaan_det.harga,0)) - SUM(COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total
						FROM trx_pembelian_persediaan
						LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
						LEFT JOIN mst_item ON mst_item.id_item = trx_pembelian_persediaan_det.id_item
						WHERE (trx_pembelian_persediaan.tanggal >= '".$startDate."' AND trx_pembelian_persediaan.tanggal <= '".$endDate."')
						GROUP BY mst_item.id_akunitem
						UNION ALL
						SELECT 		
						mst_akun.nama_akun as nama_item,
						mst_akun.id_akun as id_akunitem,
						SUM(COALESCE(trx_jurnal_det.debet_akhir,0)) as total
						FROM trx_jurnal
						LEFT JOIN trx_jurnal_det ON trx_jurnal.id_jurnal = trx_jurnal_det.id_jurnal
						LEFT JOIN mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
						WHERE trx_jurnal.id_jurnal IN (
														SELECT 		
														trx_jurnal.id_jurnal
														FROM trx_jurnal
														LEFT JOIN trx_jurnal_det ON trx_jurnal.id_jurnal = trx_jurnal_det.id_jurnal
														LEFT JOIN mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
														WHERE (trx_jurnal_det.id_akun in (SELECT mst_akun.id_akun FROM mst_akun WHERE concat(mst_akun.kode_induk,'.',mst_akun.kode_induk) LIKE '1.1.1%'))
														AND trx_jurnal.id_sumber_trans = 1 
														)
						AND NOT (trx_jurnal_det.id_akun in (SELECT mst_akun.id_akun FROM mst_akun WHERE concat(mst_akun.kode_induk,'.',mst_akun.kode_induk) LIKE '1.1.1%'))
						AND trx_jurnal.id_sumber_trans = 1 
						AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')
						AND concat(mst_akun.kode_induk,'.',mst_akun.kode_induk) LIKE '5%'
						GROUP BY mst_akun.id_akun
						HAVING COALESCE(SUM(trx_jurnal_det.debet_akhir), 0) > 0
						) as tablepegeluaran
						GROUP BY tablepegeluaran.id_akunitem");
						
						foreach($pengeluaran->result() as $row){
						
						$nominal = $row->total;
					?>
					<tr>
						<td style="padding-left : 15px;"><?php echo $row->nama_item?></td>
						<td style="text-align:right;"><?php echo formatCurrency($nominal)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					<?php
						$totalArusKeluar += $nominal;
						}
					?>
					<tr style="font-weight:bold;">
						<td>Jumlah Arus Keluar</td>
						<td style="text-align:right;"><?php echo formatCurrency($totalArusKeluar)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					
					<?php
						$totalaruskas = $totalArusMasuk - $totalArusKeluar
					?>
					<tr>
						<td style="font-weight:bold;">Total Arus Kas Dari Aktvitias Opersional</td>
						<td style="text-align:right;"><?php echo formatCurrency($totalaruskas)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					<!-- ARUS KAS INVESTASI -->
					<tr>
						<td style="font-weight:bold;">Arus Kas Dari Aktvitias Investasi</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td style="font-weight:bold;">Arus Kas Investasi Masuk</td>
						<td></td>
						<td></td>
					</tr>
					<?php
						$totalArusInvestasiMasuk = 0;
						
						$investasi = $this->db->query("SELECT 
						COALESCE(SUM(trx_jurnal_det.kredit_akhir),0) as total,
						mst_akun.nama_akun
						FROM trx_jurnal
						LEFT JOIN trx_jurnal_det ON trx_jurnal.id_jurnal = trx_jurnal_det.id_jurnal
						LEFT JOIN mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
						WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.2%'
						AND trx_jurnal.id_sumber_trans <> (SELECT id_sumber_trans FROM ref_sumber_trans WHERE ref_sumber_trans.kode = 'SA')
						AND trx_jurnal_det.id_jurnal IN (".$kasbank->first_row()->idjurnal.")
						AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')
						AND trx_jurnal_det.kredit_akhir <> 0
						GROUP BY trx_jurnal_det.id_akun");
						
						foreach($investasi->result() as $row)
						{
						
						
							$nominal = $row->total;
					?>
							<tr>
								<td style="padding-left : 15px;"><?php echo $row->nama_akun?></td>
								<td style="text-align:right;"><?php echo formatCurrency($nominal)?></td>
								<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
							</tr>
							
							
					<?php
							$totalArusInvestasiMasuk += $nominal;
						}
					?>
					<tr>
						<td style="font-weight:bold;">Total Arus Kas Investasi Masuk</td>
						<td style="text-align:right;"><?php echo formatCurrency($totalArusInvestasiMasuk)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					<tr>
						<td style="font-weight:bold;">Arus Kas Keluar</td>
						<td></td>
						<td></td>
					</tr>
					<?php
						$totalArusInvestasiKeluar = 0;
						
						$investasi = $this->db->query("SELECT 
						COALESCE(SUM(trx_jurnal_det.debet_akhir),0) as total,
						mst_akun.nama_akun
						FROM trx_jurnal
						LEFT JOIN trx_jurnal_det ON trx_jurnal.id_jurnal = trx_jurnal_det.id_jurnal
						LEFT JOIN mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
						WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.2%'
						AND trx_jurnal.id_sumber_trans <> (SELECT id_sumber_trans FROM ref_sumber_trans WHERE ref_sumber_trans.kode = 'SA')
						AND trx_jurnal_det.id_jurnal IN (".$kasbank->first_row()->idjurnal.")
						AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')
						AND trx_jurnal_det.debet_akhir <> 0
						GROUP BY trx_jurnal_det.id_akun");
						
						foreach($investasi->result() as $row)
						{
						
						
							$nominal = $row->total;
					?>
							<tr>
								<td style="padding-left : 15px;"><?php echo $row->nama_akun?></td>
								<td style="text-align:right;"><?php echo formatCurrency($nominal)?></td>
								<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
							</tr>
					
					<?php
							$totalArusInvestasiKeluar = $nominal;
						}
					?>
					<tr>
						<td style="font-weight:bold;">Total Arus Kas Investasi Keluar</td>
						<td style="text-align:right;"><?php echo formatCurrency($totalArusInvestasiKeluar)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					<?php
						$totalArusInvestasi = $totalArusInvestasiMasuk + $totalArusInvestasiKeluar;
					?>
					<tr style="font-weight:bold;">
						<td>Total Arus Kas Dari Investasi</td>
						<td style="text-align:right;"><?php echo formatCurrency($totalArusInvestasi)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					
					<!-- ARUS KAS PENDANAAN / PIUTANG -->
					<tr>
						<td style="font-weight:bold;">Arus Kas Dari Aktivitas Pendanaan</td>
						<td></td>
						<td></td>
					</tr>
					<tr style="font-weight:bold;">
						<td>Total Arus Kas Dari Aktivitas Pendanaan</td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					
					<!-- KENAIKAN DAN PENURUNAN KAS DAN SETARA KAS -->
					
					<?php
						$naikturunkassetarakas = $totalaruskas + $totalArusInvestasi + 0;
						
						$saldoawalkasdansetarakas = $this->db->query("SELECT SUM(trx_jurnal_det.debet_akhir) as total
						FROM trx_jurnal
						LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal
						left join mst_akun ON mst_akun.id_akun = trx_jurnal_det.id_akun
						WHERE trx_jurnal.id_sumber_trans = '6' AND trx_jurnal_det.id_jurnal = '1'
						AND CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '1.1.1%'
						AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')");
						
						
						$kassetarakasakhir = $naikturunkassetarakas + $saldoawalkasdansetarakas->first_row()->total;
					?>
					
					<tr style="font-weight:bold;">
						<td>Kenaikan dan Penurunan Kas dan Setaral Kas</td>
						<td style="text-align:right;"><?php echo formatCurrency($naikturunkassetarakas)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					<tr style="font-weight:bold;">
						<td>Kas dan Setara Kas, Awal</td>
						<td style="text-align:right;"><?php echo formatCurrency($saldoawalkasdansetarakas->first_row()->total)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
					<tr style="font-weight:bold;">
						<td>Kas dan Setara Kas, Akhir</td>
						<td style="text-align:right;"><?php echo formatCurrency($kassetarakasakhir)?></td>
						<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
					</tr>
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
    $html2pdf->Output('Laporan_arus_kas.pdf');
    }
    catch(HTML2PDF_exception $e) { echo $e; } 
  }
?>