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
		background:#787a93;
		color:#fff;
		font-size:12px;
	}
	
	.datepicker{
		z-index:9999 !important;
	}
</style>

<h4>Setting Kode Akun</h4>
<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
			<table class="table table-bordered table-striped">
				<thead style="background:#429489;">
					<tr>
						<th style="color:#fff;width:15%;">Kode</th>
						<th style="color:#fff;">Nama</th>
						<th style="color:#fff;width:15%;">Tipe</th>
						<th style="color:#fff;width:10%;">Saldo Normal</th>
						<th style="width:10%;color:#fff;">Aksi</th>
					</tr>
				</thead>
				
				<?php
					
					$kodeakun = json_decode($akun, true);
					
					foreach($kodeakun as $rowAkun){
					
				?>
				<tr>
					<td><?php echo $rowAkun['kodeWithFormat']?></td>
					<td><?php echo $rowAkun['namaWithFormat']?></td>
					<td><?php echo $rowAkun['namaAkunTipe']?></td>
					<td><?php echo $rowAkun['saldoNormal']?></td>
					<td><?php echo $rowAkun['action']?></td>
				</tr>
				<?php
					}
				?>
			
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() 
{
    

   
});
</script>

