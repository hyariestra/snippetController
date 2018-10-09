<?php
	ob_start();
	
	$perusahaan = $this->db->query("SELECT * FROM sys_perusahaan 
	WHERE sys_perusahaan.id_perusahaan = '".$_SESSION['IDSekolah']."'");

	// echo print_r($datane);
?>
<style>
	.coll-td{
		padding:5px;
		font-size:12px;
	}
	th.coll-td{
		text-align: center;
	}
	
	.ttd{
		text-align:right;
		padding-right: 50px;
		margin-top:50px;
	}
</style>

<page footer="page">
	<?php #echo print_r($bukas) ?>
	<div style="font-size:12px; float:left;position:absolute;">
		<!--<img src="<?php echo base_url("upload/".$perusahaan->first_row()->gambar)?>" style="height:50px;width:50px;" />-->
	</div>
	<div style="font-size:12px; text-align:right; margin-bottom:30px;">
		<span><?php echo $perusahaan->first_row()->nama_perusahaan?></span>
		<br>
		<span><?php echo  $perusahaan->first_row()->alamat_perusahaan?></span>
		<br>
		<span><?php echo $perusahaan->first_row()->no_telp?> </span>
	</div>
	
	<hr>
	
	<div style="font-family:tahoma;text-align:center;">
		<h4 style="text-decoration:underline; line-height:5px;">Print Buku Kas Penerimaan</h4>
	</div>
	
	<div style="margin-top:30px; position: relative; width: 755px" >
		<table border="1" width="680" style="border-collapse:collapse;max-width:100%;table-layout:fixed;">
			<tr>
				<th width="20" style="">#</th>
				<th width="105" style="">Tanggal</th>
				<th width="105" style="">No. Bukti</th>
				<th width="145" style="">Keterangan</th>
				<th width="90" style="">Debet</th>
				<th width="90" style="">Kredit</th>
				<th width="90" style="">Saldo Akhir</th>
			</tr>
			<?php
				$seq = 1;
				$saldoakhir = "";
				foreach($bukas as $row)
				{
			?>
				<tr>
					<td><?php echo $seq; ?></td>
					<td><?php echo date("d-m-Y", strtotime($row['tanggal']))?></td>
					<td><?php echo $row['nobukti']?></td>
					<td><?php echo $row['keterangan']?></td>
					<td style="text-align:right;"><?php echo $row['total']?></td>
					<td style="text-align:right;"><?php echo number_format(0,2)?></td>
					<td style="text-align:right;"><?php echo $row['salad'] ?></td>
				</tr>
			<?php
					$seq++;
					
					$saldoakhir = $row['salad'];
				} 
			?>
				
				<!-- <tr>
					<td colspan="6" style="font-weight:bold; text-align:right;">Total</td>
					<td style="text-align:right;font-weight:bold;"><?php echo $saldoakhir?></td>
				</tr> -->
		</table>
		
	</div>
	
</page>
<?php
	$content = ob_get_clean();
	  // conversion HTML => PDF
  require_once 'assets/plugins/html2pdf_v4.03/html2pdf.class.php'; // arahkan ke folder html2pdf
  try
  {
	$html2pdf = new HTML2PDF('P','A4','fr', false, 'ISO-8859-15',array(5, 5, 5, 5)); //setting ukuran kertas dan margin pada dokumen anda
	//$html2pdf->setModeDebug();
	$html2pdf->pdf->SetDisplayMode('fullpage');
	$html2pdf->setDefaultFont('Arial');
	$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	$html2pdf->Output('rekappenerimaan.pdf');
  }
  catch(HTML2PDF_exception $e) { echo $e; } 
?>
