<?php
	ob_start();
	
	$perusahaan = $this->db->query("SELECT * FROM sys_perusahaan 
	WHERE sys_perusahaan.id_perusahaan = '".$_SESSION['IDSekolah']."'");
?>
<style>
	.coll-td{
		padding:5px;
		font-size:12px;
	}
	
	.ttd{
		text-align:right;
		padding-right: 50px;
		margin-top:50px;
	}
</style>
<page footer="page">
	<!--<div style="font-size:12px; float:left;position:absolute;">
		<img src="<?php echo base_url("upload/".$perusahaan->first_row()->gambar)?>" style="height:50px;width:50px;" />
		
		
	</div>-->
	<div style="font-size:12px; text-align:right; margin-bottom:30px;">
		<span><?php echo $perusahaan->first_row()->nama_perusahaan?></span>
		<br>
		<span><?php echo  $perusahaan->first_row()->alamat_perusahaan?></span>
		<br>
		<span><?php echo $perusahaan->first_row()->no_telp?> </span>
	</div>
	
	<hr>
	
	<div style="font-family:tahoma;text-align:center;">
		<h4 style="text-decoration:underline; line-height:5px;">Print Rekap BKK</h4>
	</div>
	
	<div style="margin-top:30px;">
		<table border="1" width="680" style="border-collapse:collapse;max-width:100%;table-layout:fixed;">
			<tr>
				<th class="coll-td"> No </th>
				<th class="coll-td" style="width:20%;"> Tanggal </th>
				<th class="coll-td" style="width:15%;"> No. Transaksi </th>
				<th class="coll-td"> Metode Bayar </th>
				<th class="coll-td"> Kas / Bank </th>
				<th class="coll-td" style="width:25%;"> Keterangan </th>
				<th class="coll-td" style="width:15%;"> Nominal </th>
			</tr>
			<?php 
			$seq = 1;
			$total = 0;
			foreach($data as $rows){ 
				$total += $rows['total'];
			?>
			<tr>
				<td class="coll-td"><?php echo $seq?></td>
				<td class="coll-td"> <?php echo $rows['tanggal']?> </td>
				<td class="coll-td"> <?php echo $rows['notrans']?> </td>
				<td class="coll-td"> <?php echo $rows['metodebayar']?> </td>
				<td class="coll-td"> <?php echo $rows['bank']?> </td>
				<td class="coll-td"> <?php echo $rows['keterangan']?> </td>
				<td class="coll-td" style="text-align:right;"> <?php echo number_format($rows['total'])?> </td>
			</tr>
			<?php 
				$seq++;
			} ?>
			<tr>
				<td class="coll-td" colspan="6" style="text-align: right;">Total Pendapatan</td>
				<td class="coll-td" style="text-align:right;"> <?php echo number_format($total)?> </td>
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
