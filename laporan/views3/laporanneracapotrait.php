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
	
	.body-laporan{
		margin-left : 10%;
		margin-right : 10%;
	}
</style>
<?php 
	//untuk sementara di bodon
	$startDate = GetTahunPeriode()."-01-01";
	$endDate = GetTahunPeriode()."-12-31";
?>

<div class="content-header">   
	<h4>Laporan Neraca</h4>
</div>
<div class="widget-container" style="padding:10px;">
	<div class="row" style="border-bottom:1px dashed #429489; padding-bottom:10px;">
		<div class="col-md-6">
			<div class="form-horizontal">
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
					<label class="col-md-3 control-label"></label>
					<div class="col-md-9">
						<button onclick="previewdata()" class="btn btn-sm btn-warning">
							<span class="glyphicon glyphicon-search"></span>
							Preview Data
						</button>
						<button type="button" onclick="printdatapotrait()" class="btn btn-sm btn-success">
							<span class="glyphicon glyphicon-print"></span>
							Print Data
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="body-content">
		
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		
		$("input[role='tanggal']").datepicker({
			"autoclose" : true,
			"format" : "dd-mm-yyyy"
		});
		
	});
	
	function previewdata()
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		var target = "<?php echo site_url("laporan/previewlaporanneracapotrait")?>";
			data = {
				periode : $("select[name='periode']").val(),
				nilai : $("select[name='nilai']").val(),
			}
			
		$.post(target, data, function(e){
		
			$("#body-content").html(e);
			
			$("#backDropOverlay").remove();
			
		});
	}
	
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

	function printdatapotrait()
	{
		previewdata();

		var periode = $("select[name='periode']").val();
			nilai = $("select[name='nilai']").val();
		var target  = "<?php echo site_url("laporan/previewlaporanneracapotrait") ?>/pdf/"+periode+"/"+nilai;
		window.open(target);
	}
	
	function printdataneraca()
	{
		var target  = "<?php echo site_url("laporan/printneraca") ?>";
		window.open(target);
	}

	function formatNumber(val) 
	{
		var a = val;
		// console.log(a);
        var b = a.replace(/[^\d]/g, "");
        	b = a.replace(/[^0-9,'.']/g,"");
        	b = b.replace(/,/ig,"");
        
        c = "";
        strLength = b.length;
        j = 0;
        for (i = strLength; i > 0; i--) {
            j = j + 1;
            if (((j % 3) == 1) && (j != 1)) {
                c = b.substr(i - 1, 1) + "," + c;
            } else {
                c = b.substr(i - 1, 1) + c;
            }
        }
        return c;
	}
</script>



