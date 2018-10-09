<?php
	ob_start();

	// echo print_r($datane);
?>
<style>
	.table tbody > tr > td.form-input{
		padding:3px 3px 3px 5px !important;
	}
	table tr th{
		text-align:center;
	}
	
	table tr td, table tr th{
		border: 1px solid #000;
		padding:5px;
		border-collapse:collapse;
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
				<h4>Laporan Perubahan Aset Kelolaan</h4>
				<h4><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
				<h5>Per 31 Desember <?php echo GetTahunPeriode();?></h5>
			</div>
			
			<div class="body-laporan">
					<table style="border-collapse:collapse; width:100%;" width="100" class="table table-bordered table-striped">
						<tr>
							<th>Keterangan</th>
							<th style="width:100">Saldo Awal <br> (Rp.)</th>
							<th style="width:100">Penambahan <br> (Rp.)</th>
							<th style="width:100">Pengurangan <br> (Rp.)</th>
							<th style="width:100">Penyisihan <br> (Rp.)</th>
							<th style="width:100">Akumulasi <br> Penyusutan <br> (Rp.)</th>
							<th style="width:100">Saldo Akhir <br> (Rp.)</th>
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
							AND trx_jurnal.id_sumber_trans <> 6");
							
							$pengurangan = $this->db->query("SELECT 
							COALESCE(SUM(trx_jurnal_det.kredit_akhir), 0) AS total 
							FROM trx_jurnal 
							LEFT JOIN trx_jurnal_det ON trx_jurnal_det.id_jurnal = trx_jurnal.id_jurnal
							WHERE trx_jurnal_det.id_akun = '".$row['idakun']."'
							AND trx_jurnal.id_sumber_trans <> 6");
							
							$total = (@$saldoawal->first_row()->nominal + @$penambahan->first_row()->total) - @$pengurangan->first_row()->total;
						
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
							<td style="text-align:right;"><?php echo formatCurrency(0)?></td>
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
	</div>
</div>

</page>


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
