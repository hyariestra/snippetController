<?php
	//echo "<pre>";print_r(json_decode($akun, true));"</pre>";
	//exit();
ob_start();
?>
<style>
	

	.tabelContent  {
		font-size:12px; font-family:arial, sans-serif;
		border-collapse:collapse; border-spacing:0;
		width: 100%;
	}
	.logo {
		position: absolute;
		margin-top: 30px;
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
	h3{
		text-align: center;
	}
	h5{
		text-align: center;
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

</style>

<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<div class="row" style="border-bottom:0px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
			<div class="row" style="border-bottom:0px dashed #ccc; padding-left:10px; padding-bottom:10px; margin-bottom:10px;">
			</div>

			<?php $per = $perush->row();?>
			<div class="logo">

				<a href="<?php echo site_url()?>main">
					<img style="height:55px;" src="<?php echo base_url("./upload/".$per->gambar)?>">
				</a>
			</div>
			<h3><?php echo @$per->nama_perusahaan; ?></h3>
			<h5><?php echo @$per->alamat_perusahaan; ?></h5>
			<table class="table table-bordered table-striped tabelContent" border="1">
				<col style="width: 50%">
				<col style="width: 50%">
				<?php 
				/* <thead style="background:#429489;">
					<tr>
						<th style="color:#fff;width:15%;">Kode</th>
						<th style="color:#fff;">Nama</th>
						<th style="width:10%;color:#fff;">Aksi</th>
					</tr>
				</thead>
				*/ 
				?>
				<?php

				$kodeakun = json_decode($akun, true);

				foreach($kodeakun as $rowAkun){

					
					$checklevel = $this->db->query("SELECT mst_akun.header as header, mst_akun.level as level 
						FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '".$rowAkun['kodePlainText']."'");
					
					$kodeakun = ($rowAkun['level'] >= 3 ) ? $rowAkun['kodePlainText'] : $rowAkun['kodeWithFormat'];
					$namaakun = ($rowAkun['level'] >= 3 ) ? $rowAkun['namaPlainText'] : $rowAkun['namaWithFormat'];

					
					$background = ($rowAkun['level'] == 1 ) ? "#429489" : "";
					$color = ($rowAkun['level'] == 1 ) ? "#fff" : "#555";
					
					
					$namaakun = (@$checklevel->first_row()->level <> 3) ? "<b>".$namaakun."</b>" : $namaakun;
					
					?>
					<tr id="datatable_" style="border-bottom:1px dashed #429489; background: <?php echo $background?>; color: <?php echo $color?>">
						
						<td style="padding-right:50px; display:;" ><?php echo $rowAkun['kodePlainText']?></td>
						<td style="padding-right:50px;"><?php echo get_space($rowAkun['level']); ?><?php echo $rowAkun['namaWithFormat']?></td>
					</tr>
					<?php
				}
				?>

			</table>
		</div>
	</div>
</div>


<?php

$content = ob_get_clean();
      // conversion HTML => PDF
    require_once 'assets/plugins/html2pdf_v4.03/html2pdf.class.php'; // arahkan ke folder html2pdf
    try
    {
    $html2pdf = new HTML2PDF('L','A4','fr', false, 'ISO-8859-15',array(5, 5, 5, 5)); //setting ukuran kertas dan margin pada dokumen anda
    // $html2pdf->setModeDebug(true);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('cetaklaporankodeakun.pdf');
}
catch(HTML2PDF_exception $e) { echo $e; } 

?>