<?php
	ob_start();
?>
<style>
	.coll-td{
		padding:5px;
		
	}
	.ttd{
		padding-top: 70px;
		text-align: right;
		
	}
	.head{
		text-align: right;
	}
	.logo{
		position: absolute;
	}
	hr.garis{
		color: red;
		width: 10px !important;
	}
</style>
<?php $row=$m->first_row(); ?>
<?php $val=$perush->first_row(); ?>
<?php $tgl=$row->tanggal;
 $tglsekarang=date('Y-m-d');
 ?>

<page footer="page">
<!--<div class="logo">
	<?php 
				$logo11=$this->db->query("select * from sys_perusahaan WHERE sys_perusahaan.id_perusahaan = '".$_SESSION['IDSekolah']."'");
				?>

				<?php if ($logo11->first_row()->gambar): ?>
					<a href="<?php echo site_url()?>main">
						<img style="height:55px;" src="<?php echo base_url("./upload/".$logo11->first_row()->gambar)?>">
					</a>
				<?php endif ?>

				<?php if (!$logo11->first_row()->gambar): ?>
					<a href="<?php echo site_url()?>main">
						<img style="height:55px;" src="<?php echo base_url()?>assets/img/logo2.png">
					</a>
				<?php endif ?>
</div>-->
<br>
	<div class="head" style="font-size:12px;">
		<span><?php echo @$val->nama_perusahaan; ?></span>
		<br>
		<span><?php echo @$val->alamat_perusahaan; ?></span>
		<br>
		<span>Telp : <?php echo @$val->no_telp; ?> </span>
	</div>
	<br>
	<br>
	<hr>
	<div style="font-family:tahoma;text-align:center;">
		<h4 style="text-decoration:underline; line-height:5px;">Kuitansi Pembayaran</h4>
		<span>No Bukti :<?php echo @$row->nomor_transaksi?></span>
		<br>

		<span>Tanggal : <?php echo tgl_indo(@$tgl);?></span>
	</div>
	
	<div style="margin-top:30px;">
		<table border=0 style="border-collapse:collapse;">
		<?php if (!$row->nama_pemasok): ?>
			<?php echo ""; ?>
		<?php endif ?>

		<?php if (isset($row->nama_pemasok)): ?>
			<tr>
				<td class="coll-td">Sudah Keluar Untuk</td>
				<td class="coll-td"> : </td>
				<td class="coll-td" style=" width:70%"> <?php echo @$row->nama_pemasok ?></td>
			</tr>
		<?php endif ?>

			<tr>
				<td class="coll-td">Uang Sejumlah</td>
				<td class="coll-td"> : </td>
				<td class="coll-td" style=" width:70%"> Rp. <?php echo formatCurrency($row->total);?></td>
			</tr>

			<tr>
				<td class="coll-td">Untuk Pembayaran</td>
				<td class="coll-td"> : </td>
				<td class="coll-td" style=" width:70%"> <?php echo @$row->deskripsi ?></td>
			</tr>
			<tr>
				<td class="coll-td">Terbilang</td>
				<td class="coll-td"> : </td>
				<td class="coll-td" style=" width:70%"> <?php echo terbilang($row->total);?></td>
			</tr>
		</table>
	</div>
	<div class="ttd">	
		<?php echo @$val->nama_kabupaten; ?>, <?php echo tgl_indo($tglsekarang); ?>
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
