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

<h4>Sinkronisasi Jurnal (Posting Jurnal)</h4>
<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
			<div class="box box-default" style="background:#429489;color:#fff;">
				<span>Sistem akan melakukan posting jurnal secara otomatis, proses sinkronisasi ini mungkin membutuhkan waktu yang lama.</span>
			</div>
			
			<div style="margin-top:20px;">
				<button type="button" onclick="simpanposting()" style="width:100%;" class="btn btn-xl btn-default">Posting Jurnal</button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	
	$(document).ready(function(){
		$("input[role='tanggal']").datepicker({
			format : "dd-mm-yy",
			autoclose : true
		});
	});
	
	
	function simpanposting()
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("akuntansi/simpanposting")?>";
			
			
		$.post(target, data, function(e){
			
			$("#main-body").html(e);
			
			$("#backDropOverlay").remove();
		});
	}
	
</script>

