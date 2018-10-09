<?php
	ob_start();
	
	$perusahaan = $this->db->query("SELECT * FROM sys_perusahaan LEFT JOIN mst_kabupaten ON sys_perusahaan.id_kabupaten = mst_kabupaten.id_kabupaten
	WHERE sys_perusahaan.id_perusahaan = '".$_SESSION['IDSekolah']."'");
	 $tglsekarang=date('Y-m-d');
	 $tglpenjualan=$result->first_row()->tanggal_penjualan;
?>
<style>
	.coll-td{
		padding:5px;
	}
	
	.ttd{
		text-align:right;
		padding-right: 10px;
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
		<h4 style="text-decoration:underline; line-height:5px;">Kuitansi Pembayaran</h4>
		<span>No Bukti : <?php echo $result->first_row()->no_transaksi?></span>
		<br>
		<span>Tanggal : <?php echo tgl_indo(@$tglpenjualan);?></span>
	</div>
	
	
	
	<div style="margin-top:30px;">
		<table border=0 style="border-collapse:collapse;">
			<tr>
				<td class="coll-td">Sudah Terima Dari</td>
				<td class="coll-td"> : </td>
				<td class="coll-td" style=" width:70%"> <?php echo $result->first_row()->nama_pelanggan?></td>
			</tr>
			<tr>
				<td class="coll-td">Uang Sejumlah</td>
				<td class="coll-td"> : </td>
				<td class="coll-td" style=" width:70%"> Rp. <?php echo number_format($result->first_row()->total)?></td>
			</tr>
			<tr>
				<td class="coll-td">Untuk Pembayaran</td>
				<td class="coll-td"> : </td>
				<td class="coll-td" style=" width:70%"> <?php echo $result->first_row()->keterangan?></td>
			</tr>
			<tr>
				<td class="coll-td">Terbilang</td>
				<td class="coll-td"> : </td>
				<td class="coll-td" style=" width:70%"> <?php echo terbilang($result->first_row()->total)?></td>
			</tr>
			
		</table>
		
		<div class="ttd">
			<span><?php echo $perusahaan->first_row()->nama_kabupaten?>, <?php echo tgl_indo($tglsekarang); ?></span>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<span style="text-decoration:underline;width:100px;"> 
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
			
			</span>
		</div>
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
		$html2pdf->Output('print_kuitansi.pdf');
	  }
	  catch(HTML2PDF_exception $e) { echo $e; } 
?>
