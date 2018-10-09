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
	<!-- <div style="font-size:12px; float:left;position:absolute;">
		<img src="<?php echo base_url("upload/".$perusahaan->first_row()->gambar)?>" style="height:50px;width:50px;" />
	</div> -->
	<div style="font-size:12px; text-align:right; margin-bottom:30px;">
		<span><?php echo $perusahaan->first_row()->nama_perusahaan?></span>
		<br>
		<span><?php echo  $perusahaan->first_row()->alamat_perusahaan?></span>
		<br>
		<span><?php echo $perusahaan->first_row()->no_telp?> </span>
	</div>
	
	<hr>
	
	<div style="font-family:tahoma;text-align:center;">
		<h4 style="text-decoration:underline; line-height:5px;">Buku Kas Harian</h4>
	</div>
	
	<div style="margin-top:30px; position: relative; width: 640px" >
		<table border="1" style="border-collapse:collapse; width:auto;">
			<tr>
				<th class="coll-td" style="width: 10%"> Tanggal </th>
				<th class="coll-td" style="width: 20%"> No.Bukti </th>
				<th class="coll-td" style="width: 25%"> Keterangan </th>
				<th class="coll-td" style="width: 15%"> Debet </th>
				<th class="coll-td" style="width: 15%"> Kredit </th>
				<th class="coll-td" style="width: 15%"> Saldo AKhir </th>
			</tr>
			<?php 
			$saldoakhir = 0;
			if ($flag) {
				# code...
			
			foreach($datane as $rows){ 
				$saldoakhir +=+ $rows['debet'] - $rows['kredit'];
				?>

			<tr>
				<td class="coll-td"> <?php echo $rows['tgl']?> </td>
				<td class="coll-td"> <?php echo $rows['no_bukti']?> </td>
				<td class="coll-td"> <?php echo $rows['keterangan']?> </td>
				<td class="coll-td" style="text-align:right;"> <?php echo number_format($rows['debet'])?> </td>
				<td class="coll-td" style="text-align:right;"> <?php echo number_format($rows['kredit'])?> </td>
				<td class="coll-td" style="text-align:right;"> <?php echo number_format($saldoakhir)?> </td>
			</tr> 
			<?php 
			} } ?>

			<tr>
				<td class="coll-td" style="text-align: right; font-weight: bold;" colspan="5">Saldo Akhir</td>
				<td class="coll-td" style="text-align:right;"> <?php echo number_format($saldoakhir)?> </td>
			</tr>
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
