<?php
	//echo "<pre>";print_r(json_decode($akun, true));"</pre>";
	//exit();
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
		background:#429489;
		color:#fff;
		font-size:12px;
	}
	
	.datepicker{
		z-index:9999 !important;
	}
</style>

<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<div class="row" style="border-bottom:0px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
			<div class="row" style="border-bottom:0px dashed #ccc; padding-left:10px; padding-bottom:10px; margin-bottom:10px;">
			</div>
		
			<table id="example" width="100%" cellpadding="5">

				<?php /* <thead style="background:#429489;">
					<tr>
						<th style="color:#fff;width:15%;">Kode</th>
						<th style="color:#fff;">Nama</th>
						<th style="width:10%;color:#fff;">Aksi</th>
					</tr>
				</thead>
				*/ ?>
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
						<td style="width: 10%">
							
						</td>
					</tr>
					<?php
				}
				?>

			</table>
		</div>
	</div>
</div>


