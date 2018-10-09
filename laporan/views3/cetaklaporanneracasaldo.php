<?php
	$kodeakun = json_decode($akun, true);

	$tes = SanitizeParanoid($this->uri->segment(3));
	if($tes == "pdf")
	{
		ob_start();
		$periode = SanitizeParanoid($this->uri->segment(4));
		$nilai = SanitizeParanoid($this->uri->segment(5));
	}
	
	//echo "<pre>";print_r($kodeakun);"</pre>";
	//exit();
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
	<?php }else{ ?>
		.table tbody > tr > td.form-input{
			padding:3px 3px 3px 5px !important;
		}
		
		ul li{
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
		
		.body-laporan{
			margin-left : 5%;
			margin-right : 5%;
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
			<h4>Laporan Neraca Saldo</h4>
			<h4><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
			<span>Per 31 Desember <?php echo GetTahunPeriode();?></span>
		</div>
		
		<div class="body-laporan">
			<div class="content-laporan">
				<table class="table table-bordered table-striped tabelContent" border="1">
					<col width="15%">
					<col width="20%">
					<col style="">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					
					<tr>
						<th rowspan="2">Kode Akun</th>
						<th rowspan="2">Nama Akun</th>
						<th rowspan="2" style="text-align:center">Saldo Awal</th>
						<th colspan="2" style="text-align:center">Mutasi</th>
						<th colspan="2" style="text-align:center">Saldo Akhir</th>
						
					</tr>
					<tr>
						<th>D</th>
						<th>K</th>
						<th>D</th>
						<th>K</th>
					</tr>
					<?php
						$kodeakun = json_decode($akun, true);
						
						$totalDebet = 0;
						$totalKredit = 0;
						
						$totalSA = 0;
						
						$totalMutasiDebet = 0;
						$totalMutasiKredit = 0;
						
						foreach($kodeakun as $row)
						{
							
							if($row['Header'] <> 1 && (substr($row['kodePlainText'],0,1) <> 4 && substr($row['kodePlainText'],0,1) <> 5))
							{
								$qryNS = $this->db->query("SELECT 
								SUM(trx_jurnal_det.debet_akhir) as debet,
								SUM(trx_jurnal_det.kredit_akhir) as kredit
								FROM trx_jurnal 
								LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
								WHERE trx_jurnal_det.id_akun = '".$row['id']."'
								AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')
								AND trx_jurnal.id_sumber_trans != (SELECT id_sumber_trans FROM ref_sumber_trans WHERE ref_sumber_trans.kode = 'SA')
								GROUP BY trx_jurnal_det.id_akun");
								
								$qrySA = $this->db->query("SELECT * FROM mst_saldo_awal 
								WHERE mst_saldo_awal.id_akun = '".$row['id']."'");
								
								$saldoawal = (isset($qrySA->first_row()->nominal)) ? $qrySA->first_row()->nominal : 0;
								
								$debet = (isset($qryNS->first_row()->debet)) ? $qryNS->first_row()->debet : 0;
								$kredit = (isset($qryNS->first_row()->kredit)) ? $qryNS->first_row()->kredit : 0;
								
								
								
								$saldonormal = $this->db->query("SELECT 
								mst_akun.saldo_normal 
								FROM mst_akun 
								WHERE mst_akun.id_akun = '".$row['id']."'");
								
								$totalSaldoAkhir = ($saldonormal->first_row()->saldo_normal == "D") ? ($saldoawal + $debet) - $kredit : ($saldoawal + $kredit) - $debet ;
								
								$mutasiDebet = ($saldonormal->first_row()->saldo_normal == "D") ? $totalSaldoAkhir : 0;
								$mutasiKredit = ($saldonormal->first_row()->saldo_normal == "K") ? $totalSaldoAkhir : 0;
							
							
					?>
								<tr>
									<td><?php echo $row['kodePlainText']?></td>
									<td><?php echo $row['namaPlainText']?></td>
									<td style="text-align:right"><?php echo formatCurrency($saldoawal);?></td>
									<td style="text-align:right"><?php echo formatCurrency($debet);?></td>
									<td style="text-align:right"><?php echo formatCurrency($kredit);?></td>
									<td style="text-align:right"><?php echo formatCurrency($mutasiDebet);?></td>
									<td style="text-align:right"><?php echo formatCurrency($mutasiKredit);?></td>
								</tr>
					<?php
								$totalMutasiDebet += $mutasiDebet;
								$totalMutasiKredit += $mutasiKredit;
								
								$totalDebet += $debet;
								$totalKredit += $kredit;
								
								$totalSA += $saldoawal;
								
							}
						}
						
						
						//GET KATEGORI DAN ITEM// 
						
						$qyrItem = $this->db->query("SELECT 
						mst_item.id_akunitem as idakun,
						mst_akun.header as header,
						mst_item.nama_item as namaakun,
						mst_item.id_item as iditem,
						mst_akun.saldo_normal as saldonormal,
						CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun
						FROM mst_item
						LEFT JOIN mst_akun ON mst_akun.id_akun = mst_item.id_akunitem
						ORDER BY CAST(REPLACE(CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun),'.','') AS UNSIGNED) ASC");
						
						$mutasiDebetItem = 0;
						$mutasiKreditItem = 0;
						
						$totalDebetItem = 0;
						$totalKreditItem = 0;
						
						$totalMutasiKreditItem = 0;
						$totalMutasiDebetItem = 0;
						
						foreach($qyrItem->result_array() as $rowItem)
						{
						
							if(substr($rowItem['kodeakun'],0,1) == 4)
							{
						
								$nominal = $this->db->query("SELECT 
								SUM(COALESCE((trx_penjualan_det.harga * trx_penjualan_det.jumlah_item),0) - COALESCE(trx_penjualan_det.potongan,0)) as total 
								FROM trx_penjualan
								LEFT JOIN trx_penjualan_det ON trx_penjualan_det.id_penjualan = trx_penjualan.id_penjualan
								WHERE trx_penjualan_det.id_item = '".$rowItem['iditem']."'
								and (trx_penjualan.tanggal_penjualan  >= '".$startDate."' and trx_penjualan.tanggal_penjualan  <= '".$endDate."')");
							
								$nominalJu = $this->db->query("SELECT COALESCE(SUM(trx_jurnal_det.debet_akhir) - SUM(trx_jurnal_det.kredit_akhir), 0 ) as total FROM trx_jurnal
								LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
								WHERE trx_jurnal_det.id_akun = '".$rowItem['idakun']."'
								AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')");
							}
							elseif(substr($rowItem['kodeakun'],0,1) == 5)
							{
								$nominal = $this->db->query("SELECT 
								SUM(COALESCE((trx_pembelian_persediaan_det.harga * trx_pembelian_persediaan_det.jumlah),0) - COALESCE(trx_pembelian_persediaan_det.potongan,0)) as total 
								FROM trx_pembelian_persediaan
								LEFT JOIN trx_pembelian_persediaan_det ON trx_pembelian_persediaan_det.id_pembelian = trx_pembelian_persediaan.id_pembelian
								WHERE trx_pembelian_persediaan_det.id_item = '".$rowItem['iditem']."'
								and (trx_pembelian_persediaan.tanggal  >= '".$startDate."' and trx_pembelian_persediaan.tanggal  <= '".$endDate."') ");
							
								$nominalJu = $this->db->query("SELECT COALESCE(SUM(trx_jurnal_det.debet_akhir) - SUM(trx_jurnal_det.kredit_akhir), 0 ) as total FROM trx_jurnal
								LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
								WHERE trx_jurnal_det.id_akun = '".$rowItem['idakun']."'
								AND (trx_jurnal.tgl_jurnal >= '".$startDate."' AND trx_jurnal.tgl_jurnal <= '".$endDate."')");
							}
							
							$saldoawalItem = 0;
							
							//$nominal = @$nominal->first_row()->total + @$nominalJu->first_row()->total;
							
							$debetItem = ($rowItem['saldonormal'] == "D") ? @$nominal->first_row()->total + @$nominalJu->first_row()->total : 0;
							$kreditItem = ($rowItem['saldonormal'] == "K") ? @$nominal->first_row()->total + (@$nominalJu->first_row()->total *-1)  : 0;
							
							$mutasiDebetItem = ($rowItem['saldonormal'] == "D") ? ($saldoawalItem + $debetItem) - $kreditItem : 0;
							$mutasiKreditItem = ($rowItem['saldonormal'] == "K") ? ($saldoawalItem + $kreditItem) - $debetItem : 0;
					?>
							<tr>
								<td><?php echo $rowItem['kodeakun']?></td>
								<td><?php echo $rowItem['namaakun']?></td>
								<td style="text-align:right"><?php echo formatCurrency($saldoawalItem);?></td>
								<td style="text-align:right"><?php echo formatCurrency($debetItem); ?></td>
								<td style="text-align:right"><?php echo formatCurrency($kreditItem);?></td>
								<td style="text-align:right"><?php echo formatCurrency($mutasiDebetItem);?></td>
								<td style="text-align:right"><?php echo formatCurrency($mutasiKreditItem);?></td>
							</tr>
					<?php
					
						$totalMutasiDebetItem += $mutasiDebetItem;
						$totalMutasiKreditItem += $mutasiKreditItem;
						
						$totalDebetItem += $debetItem;
						$totalKreditItem += $kreditItem;
					
						}
						
						$totalDebet = $totalDebet + $totalDebetItem;
						$totalKredit = $totalKredit + $totalKreditItem;
						
						$totalMutasiDebet = $totalMutasiDebet + $totalMutasiDebetItem;
						$totalMutasiKredit = $totalMutasiKredit + $totalMutasiKreditItem;
						
						
						
					?>
					
					<tr>
							<td colspan="2">Total</td>
							<td style="text-align:right"><?php echo formatCurrency($totalSA);?></td>
							<td style="text-align:right"><?php echo formatCurrency($totalKredit);?></td>
							<td style="text-align:right"><?php echo formatCurrency($totalDebet);?></td>
							<td style="text-align:right"><?php echo formatCurrency($totalMutasiDebet);?></td>
							<td style="text-align:right"><?php echo formatCurrency($totalMutasiKredit);?></td>
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
    $html2pdf->Output('Laporan_neraca_saldo.pdf');
    }
    catch(HTML2PDF_exception $e) { echo $e; } 
  }
?>