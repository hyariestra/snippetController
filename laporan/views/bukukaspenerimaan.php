<style>
	.table tbody > tr > td.form-input{
		padding:3px 3px 3px 5px !important;
	}
	
	ul li{
		list-style-type: none;
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
	
	.content-laporan{
		border : 1px solid #ccc;
		padding : 10px;
	}
	
	/*.body-laporan{
		margin-left : 10%;
		margin-right : 10%;
	}*/
</style>

<?php 
	//untuk sementara di bodon
	$startDate = GetTahunPeriode()."-01-01";
	$endDate = GetTahunPeriode()."-12-31";
?>

<div class="content-header">   
	<h4>Buku Kas Penerimaan</h4>
</div>
<div class="widget-container" style="padding:10px;">
	<form id="f_search" class="form-horizontal" style="border-bottom:1px dashed #999; padding-bottom:10px; margin-bottom:10px;">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-3 control-label">Periode</label>
					<div class="col-md-9">
						<select onchange="getperiode(this)" name="periode" class="form-control">
							<option value="Bulan">Bulan</option>
							<option value="Triwulan">Triwulan</option>
							<option value="Semester">Semester</option>
							<option value="Tahun">Tahun</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label" id="labelchange">Bulan</label>
					<div class="col-md-9" id="selectchange">
						<select name="nilai" class="form-control">
						<?php for($i = 1; $i <=12; $i++){ ?>
							<option value="<?php echo $i?>"><?php echo convertBulan($i) ?></option>
						<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Kas / Bank</label>
					<div class="col-md-9">
						<select class="form-control" name="kasbank">
							<option value="-">:: Semua Kas / Bank ::</option>
							<?php
								foreach($kasbank->result() as $row){
							?>
								<option value="<?php echo $row->id_akunbank?>"><?php echo $row->nama_bank?></option>
							<?php
								}
							?>	
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-8">
						<button type="button" onclick="caridata()" class="btn btn-sm btn-default">
							<span class="glyphicon glyphicon-search"></span>
							Cari Data
						</button>
						<button type="button" onclick="printdata()" class="btn btn-sm btn-warning">
							<span class="glyphicon glyphicon-search"></span>
							Print Data
						</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div id="body-content">
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#datatablexx").dataTable({
			"bLengthChange" : false,
			"aaSorting": [],
		});
		
		
		$("input[role='tanggal']").datepicker({
			"autoclose" : true,
			"format" : "dd-mm-yyyy"
		});
		
		$("#checkall").click(function(){
			var ischeck = $(this).is(":checked");
			
			if(ischeck)
			{
				$("input").prop("checked", true);
			}
			else
			{
				$("input").prop("checked", false);
			}
		});
		
		
	});

	function getperiode(obj)
	{
		var val 	= $(obj).val();
			target 	= "<?php echo site_url("laporan/getperiode")?>";
			data = {
				periode : $("select[name='periode']").val()
			}

		$.post(target, data, function(e){
			
			var json = $.parseJSON(e);
			
			$("#selectchange").html(json.select);
			$("#labelchange").html(json.label);
		});
			
	}

	function caridata()
	{

		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		var target = "<?php echo site_url("laporan/caribukaspenerimaan")?>";
			data = {
				periode : $("select[name='periode']").val(),
				nilai : $("select[name='nilai']").val(),
				kasbank : $("select[name='kasbank']").val(),
			}
			
		$.post(target, data, function(e){
			// console.log(e);
			$("#body-content").html(e);
			
			$("#backDropOverlay").remove();
			
		});
	}

	function printdata()
	{
		caridata();

		var periode = $("select[name='periode']").val();
			nilai 	= $("select[name='nilai']").val();
			kasbank	= $("select[name='kasbank']").val();
			
			target = "<?php echo site_url("laporan/caribukaspenerimaan")?>/pdf/"+periode+"/"+nilai+"/"+kasbank;

		window.open(target);
	}
</script>