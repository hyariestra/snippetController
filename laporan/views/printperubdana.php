<?php
	//echo "<pre>";print_r($zakat);"</pre>";
	//exit();
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
		<h4 style="text-decoration:underline; line-height:5px; margin: 5px">Laporan Perubahan Dana</h4>
		<h4 style="margin: 5px"><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
		<h5 style="margin: 5px"><?php echo $tglHeader; ?></h5>
	</div>
	
	<div style="margin-top:30px; position: relative; width: 755px" >
		<table border="1" style="border-collapse:collapse;">
			<?php $total = 0;?>
			<tr>
				<th class="coll-td" style="width: 85%; vertical-align: middle;"> Keterangan </th>
				<th class="coll-td" style="width: 15%; vertical-align: middle;"> Total (Rp.) </th>
			</tr>
			<!-- ZAKAT -->
			<tr>
				<td style="font-weight: bold; padding-left: 5px;">DANA ZAKAT</td> <td></td>
			</tr>
			<tr>
				<td style="font-weight: bold;padding-left: 10px;">Penerimaan</td> <td></td>
			</tr>
			<?php 
			foreach($zakat['terima'] as $rows){ 
				?>
			<tr>
				<td class="coll-td" style="padding-left: 25px"> <?php echo $rows['nama_item']?> </td>
				<td class="coll-td" style="text-align: right;"> <?php echo $rows['total']?> </td>
			</tr> 
			<?php 
			} ?>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold;">Total</td>
				<td class="coll-td" style="text-align:right; font-weight: bold;"> <?php echo $zakat['totTerima']?> </td>
			</tr>
			<tr>
				<td style="font-weight: bold;padding-left: 10px;">Penyaluran</td> <td></td>
			</tr>
			<?php 
			foreach($zakat['keluar'] as $rows){ 
				?>
			<tr>
				<td class="coll-td" style="padding-left: 25px"> <?php echo $rows['nama_item']?> </td>
				<td class="coll-td" style="text-align: right;"> <?php echo $rows['total']?> </td>
			</tr> 
			<?php 
			} ?>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold;">Total</td>
				<td class="coll-td" style="text-align:right; font-weight: bold;"><?php echo $zakat['totKeluar']?></td>
			</tr>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold; background: #e4e3e3;">Total Dana Zakat</td>
				<td class="coll-td" style="text-align:right; font-weight: bold; background: #e4e3e3;"><?php echo $zakat['totZakat'] ?></td>
			</tr>
			<!-- INFAQ -->
			<tr>
				<td style="font-weight: bold; padding-left: 5px;">DANA INFAQ</td> <td></td>
			</tr>
			<tr>
				<td style="font-weight: bold;padding-left: 10px;">Penerimaan</td> <td></td>
			</tr>
			<?php 
			foreach($infaq['terima'] as $rows){ 
				?>
			<tr>
				<td class="coll-td" style="padding-left: 25px"> <?php echo $rows['nama_item']?> </td>
				<td class="coll-td" style="text-align: right;"> <?php echo $rows['total'] ?> </td>
			</tr> 
			<?php 
			} ?>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold;">Total</td>
				<td class="coll-td" style="text-align:right; font-weight: bold;"> <?php echo $infaq['totTerima'] ?> </td>
			</tr>
			<tr>
				<td style="font-weight: bold;padding-left: 10px;">Penyaluran</td> <td></td>
			</tr>
			<?php 
			foreach($infaq['keluar'] as $rows){ 
				?>
			<tr>
				<td class="coll-td" style="padding-left: 25px"> <?php echo $rows['nama_item']?> </td>
				<td class="coll-td" style="text-align: right;"> <?php echo $rows['total'] ?> </td>
			</tr> 
			<?php 
			} ?>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold;">Total</td>
				<td class="coll-td" style="text-align:right; font-weight: bold;"> <?php echo $infaq['totKeluar']?> </td>
			</tr>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold; background: #e4e3e3;">Total Dana Infaq</td>
				<td class="coll-td" style="text-align:right; font-weight: bold; background: #e4e3e3;"> <?php echo $infaq['totInfaq'] ?> </td>
			</tr>
			<!-- AMIL -->
			<tr>
				<td style="font-weight: bold; padding-left: 5px;">DANA AMIL</td> <td></td>
			</tr>
			<tr>
				<td style="font-weight: bold;padding-left: 10px;">Penerimaan</td> <td></td>
			</tr>
			<?php 
			foreach($amil['terima'] as $rows){ 
				?>
			<tr>
				<td class="coll-td" style="padding-left: 25px"> <?php echo $rows['nama_item']?> </td>
				<td class="coll-td" style="text-align: right;"> <?php echo $rows['total'] ?> </td>
			</tr> 
			<?php 
			} ?>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold;">Total</td>
				<td class="coll-td" style="text-align:right; font-weight: bold;"> <?php echo $amil['totTerima']?> </td>
			</tr>
			<tr>
				<td style="font-weight: bold;padding-left: 10px;">Penyaluran</td> <td></td>
			</tr>
			<?php 
			foreach($amil['keluar'] as $rows){ 
				?>
			<tr>
				<td class="coll-td" style="padding-left: 25px"> <?php echo $rows['nama_item']?> </td>
				<td class="coll-td" style="text-align: right;"> <?php echo $rows['total'] ?> </td>
			</tr> 
			<?php 
			} ?>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold;">Total</td>
				<td class="coll-td" style="text-align:right; font-weight: bold;"> <?php echo $amil['totKeluar']?> </td>
			</tr>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold; background: #e4e3e3;">Total Dana Amil</td>
				<td class="coll-td" style="text-align:right; font-weight: bold; background: #e4e3e3;"> <?php echo $amil['totAmil']?> </td>
			</tr>
			<!-- NON HALAL -->
			<tr>
				<td style="font-weight: bold; padding-left: 5px;">DANA INFAQ</td> <td></td>
			</tr>
			<tr>
				<td style="font-weight: bold;padding-left: 10px;">Penerimaan</td> <td></td>
			</tr>
			<?php 
			foreach($non['terima'] as $rows){ 
				?>
			<tr>
				<td class="coll-td" style="padding-left: 25px"> <?php echo $rows['nama_item']?> </td>
				<td class="coll-td" style="text-align: right;"> <?php echo $rows['total'] ?> </td>
			</tr> 
			<?php 
			} ?>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold;">Total</td>
				<td class="coll-td" style="text-align:right; font-weight: bold;"> <?php echo $non['totTerima'] ?> </td>
			</tr>
			<tr>
				<td style="font-weight: bold;padding-left: 10px;">Penyaluran</td> <td></td>
			</tr>
			<?php 
			foreach($non['keluar'] as $rows){ 
				?>
			<tr>
				<td class="coll-td" style="padding-left: 25px"> <?php echo $rows['nama_item']?> </td>
				<td class="coll-td" style="text-align: right;"> <?php echo $rows['total'] ?> </td>
			</tr> 
			<?php 
			} ?>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold;">Total</td>
				<td class="coll-td" style="text-align:right; font-weight: bold;"> <?php echo $non['totKeluar']?> </td>
			</tr>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold; background: #e4e3e3;">Total Dana Non-Halal</td>
				<td class="coll-td" style="text-align:right; font-weight: bold; background: #e4e3e3;"> <?php echo $non['totNon']?></td>
			</tr>
			<tr>
				<td class="coll-td" style="text-align:right; font-weight: bold;">
					Jumlah Saldo dana zakat, dana infaq/sedekah, dana amil dan dana non-halal
				</td>
				<td class="coll-td" style="text-align:right; font-weight: bold;"><?php echo $totalAll?></td>
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
		$html2pdf->Output('laporanperubahandana.pdf');
	  }
	  catch(HTML2PDF_exception $e) { echo $e; } 
?>