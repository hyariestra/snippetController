<?php
	//echo "<pre>";print_r($akun);"</pre>";
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

<h4>Setting Link Akun</h4>
<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
			<div class="col-md-6">
				<form id="formlinkakun" class="form-horizontal">
					
					<?php 
					$arr = 0;
					foreach($linkbank->result() as $row){ ?>
					<input type="hidden" name="id_ref_akun[]" value="<?php echo $row->id_bank ?>">
						<div class="form-group">
							<label class="control-label col-md-4"><?php echo $row->bank?></label>
							<div class="col-md-8">
								<div class="input-group">
									<span class="input-group-addon"><?php echo @$row->kode_induk.".".@$row->kode_akun ?></span>
									<input type="hidden" id="akunref_<?php echo $arr?>" value="<?php echo @$row->id_akunbank ?>" class="form-control" name="id_akun[]" />
									<input type="text" readonly class="form-control" value="<?php echo @$row->nama_akun?>" />
									
								</div>
							</div>
						</div>
					<?php 
					$arr++;
					} ?>
					
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalakun">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 10px 0px 0px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
        <h5 style="padding: 0px 15px 0px 15px;">Data Kode Akun</h5>
      </div>
      <div class="modal-body">
        <div class="row">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<input type="hidden" id="parent" />
					<table class="table table-bordered table-striped" style="background:#fff;">
						<tr>
							<th>Kode</th>
							<th>Nama Akun</th>
							<th></th>
						</tr>
						<?php
							$akun = json_decode($akun, true);
							foreach($akun as $akunrows)
							{
						?>
						<tr>
							<td>
								<?php echo $akunrows['kodeWithFormat'] ?>
								<input type="hidden" value="<?php echo $akunrows['id'] ?>" />
								<input type="hidden" value="<?php echo $akunrows['kodePlainText'] ?>" />
							</td>
							<td><?php echo $akunrows['namaWithFormat']?></td>
							<td>
								<button type="button" onclick="pickup(this)" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus-sign"></span></button>
							</td>
						</tr>
						<?php
							}
						?>
					</table>
				</div>
				
				<button type="button" onclick="simpanitem()" class="btn btn-sm btn-default" style="margin-top:10px;">
					<span class="glyphicon glyphicon-plus-sign"></span>
					Simpan Item
				</button>
				
			</div>
		</div>
      </div>
	  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
	
	
	function simpandata()
	{
	
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("akuntansi/simpandatalinkbank")?>";
			data = $("#formlinkakun").serialize();
			
		$.post(target, data, function(e){
			console.log(e);
			
			$("#backDropOverlay").remove();
		});
	}
	
	function openkodeakun(obj)
	{
		var id = $(obj).parent().find("input:first").attr("id");
		
		$("#parent").val(id);
		$("#modalakun").modal("show");
	}
	
	function pickup(obj)
	{
		var elem = $("#parent").val();
		var id = $(obj).parent().parent().find("td:eq(0)").find("input:first").val();
		var kodeakun = $(obj).parent().parent().find("td:eq(0)").find("input:last").val();
		var namaakun = $(obj).parent().parent().find("td:eq(1)").text();
		
		
		$("#"+elem).val(id);
		$("#"+elem).parent().find("span:first").text(kodeakun);
		$("#"+elem).parent().find("input:last").val(namaakun);
		
		$("#modalakun").modal("hide");
	}
</script>

