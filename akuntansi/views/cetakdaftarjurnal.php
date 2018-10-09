<?php
	//echo "<pre>";print_r(json_decode($akun, true));"</pre>";
	//exit();
	ob_start();
?>
<style>
	.table tbody > tr > td.form-input{
		padding:3px 3px 3px 5px !important;
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
	
	tbody tr td{
		padding:10px;
	}
	thead tr th{
		padding:5px;
	}
	.datepicker{
		z-index:9999 !important;
	}
</style>
<page id="pagefooter">
	
		<div class="col-md-12">
			<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
				
				<table border="1" style="border-collapse:collapse;" class="table table-bordered table-striped">
					<thead >
						<tr>
							<th style="color:#fff; width:1%;background:#429489;">#</th>
							<th style="color:#fff; width:10%;background:#429489;">Tanggal</th>
							<th style="color:#fff; width:15%;background:#429489;">Nomor Bukti</th>
							<th style="color:#fff; width:15%;background:#429489;">Nomor</th>
							<th  style="color:#fff; background:#429489; width:25%;">Uraian</th>
							<th style="color:#fff;background:#429489; width:15%;">Nominal</th>
						</tr>
					</thead>
					<tbody id="datatable">
					<?php
						$seq = 1;
						$total = 0;
						foreach($ju as $row){
					?>
					<tr>
						<td><?php echo $seq?></td>
						<td><?php echo date("d-m-Y", strtotime($row['tanggal']))?></td>
						<td><?php echo $row['nobukti']?></td>
						<td><?php echo $row['nomor']?></td>
						<td><?php echo $row['uraian']?></td>
						<td style="text-align:right;"><?php echo $row['total']?></td>
					</tr>
					<?php
						$total += str_replace(",","",$row['total']);
						$seq++;
						}
					?>
					<tr>
						<td colspan="5" style="text-align:right;">Total</td>
						
						<td style="text-align:right;"><?php echo formatCurrency($total)?></td>
					</tr>
					</tbody>
				</table>
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
    // $html2pdf->setModeDebug(true);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
     ob_end_clean();
    $html2pdf->Output('rekapjurnalumum.pdf');
    }
    catch(HTML2PDF_exception $e) { echo $e; } 
?>

