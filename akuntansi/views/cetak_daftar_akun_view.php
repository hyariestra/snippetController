<?php
	//echo "<pre>";print_r(json_decode($akun, true));"</pre>";
	//exit();
	$tipe = $this->uri->segment(3);
	
	if($tipe == "pdf")
	{
		ob_start();
	}
	elseif($tipe == "excel")
	{	
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment;filename=\"filename.xls\"");
	}
?>

<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<div class="row" style="border-bottom:0px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
			
			<table id="example" style="width:100%;border-collapse:collapse;" border="1" cellpadding="5">
				
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
					<tr style=" background: <?php echo $background?>; color: <?php echo $color?>">
						
						<td style="padding-right:50px;" ><?php echo $rowAkun['kodePlainText']?></td>
						<td style="padding-right:50px;width:70%;"><?php echo get_space($rowAkun['level']); ?><?php echo $rowAkun['namaWithFormat']?></td>
					</tr>
					<?php
				}
				?>

			</table>
		</div>
	</div>
</div>

<?php
	
	if($tipe == "pdf")
	{
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
		$html2pdf->Output('rekappenerimaan.pdf');
		}
		catch(HTML2PDF_exception $e) { echo $e; } 
	}
?>

