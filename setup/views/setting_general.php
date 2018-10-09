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


<h4>Setting General</h4>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		
		<form id="masterperusahaan">
			<div class="col-md-12">
				<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
					<div class="form-horizontal">
						<div class="col-md-6">
							<input type="hidden" id="s_idsetting" name="s_idsetting" value="<?php echo @$general->first_row()->id_setup_general?>"/>
							<div class="form-group">
								<label class="col-md-3 control-label">Label Lembaga</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_lembaga" value="<?php echo @$general->first_row()->label_lembaga?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Label Aplikasi</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_aplikasi" value="<?php echo @$general->first_row()->label_aplikasi?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Label Pelanggan</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_pelanggan" value="<?php echo @$general->first_row()->label_pelanggan?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Label Pemasok</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_pemasok" value="<?php echo @$general->first_row()->label_pemasok?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Label Header</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_header" value="<?php echo @$general->first_row()->label_header?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Label Laporan</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_laporan" value="<?php echo @$general->first_row()->label_laporan?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Label Tambahan</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_tambahan" value="<?php echo @$general->first_row()->label_tambahan?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Label Perubahan Ekuitas</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_lpe" value="<?php echo @$general->first_row()->label_perubahanaset?>" />
								</div>
							</div>
						</div>
					</div>
				</div>

				<button type="button" onclick="simpandata()" class="btn btn-sm btn-default">
					<span class="glyphicon glyphicon-plus-sign"></span>
					Simpan
				</button>
			</div>
		</form>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		// eaa kopongg;
		var file;
		$("#fileupload").on('change', prePareUpload);
	});
	
	function getkabupaten(obj)
	{
		var provinsi = $(obj).val();
			provinsi = provinsi.split("#");
		
		var idprov = provinsi[0];
		var kodeprov = provinsi[1];
		
		var target = "<?php echo site_url("setup/getkabupaten")?>";
			data = {
				idprov : idprov,
				kodeprov : kodeprov
			}
			
		$.post(target, data, function(e){
			
			$("select#kabupaten").html("");
			
		})
		.done(function(e){
			var json = $.parseJSON(e);
			
			$("select#kabupaten").append(json.html);
		});
	}

	function prePareUpload(event)
	{
		
		file = event.target.files;
		//loadHtml = "<div class='bg bg-success bg-xs' style='padding:5px 10px; border-radius:3px; color:#555; margin-top:3px;'><input type='hidden' name='file[]' value='"+file[0].name+"' /><span class='glyphicon glyphicon-file' aria-hidden='true'></span> "+file[0].name+" <span onclick='removes(this)' style='cursor:pointer;' class='glyphicon glyphicon-remove pull-right' aria-hidden='true'></span></div>";
		

		
		saveUpload(event);
		
		//console.log(file);
	}
	function saveUpload(event)
	{
		event.stopPropagation();
		event.preventDefault();
		
		
		//$("#btnSimpan").attr("disabled", "disabled");
		//$("#btnSimpan").html("Loading . . .");
		
		var data = new FormData();

		

		$.each(file, function(key, val){
			data.append(key, val);
			//console.log(val.name);
		});
		
		//console.log(data);
		//return false;
		//console.log(data);
		//return false;
		
		$.ajax({
			url : '<?php echo site_url("setup/uploadFileMulti")?>',
			type : 'POST',
			data : data,
			cache : false,
			processData : false,
			contentType : false,
			success: function(res, textStatus, jqXHR)
			{
				console.log(res);
				
				if(res)
				{
					
					$("#listfile").html("<input type='text' id='fileuploadname' name='gambar' value='"+file[0].name+"'/>");
				}
				else
				{
					
				}
			},
			error: function(jqXHR, textStatus, errorMessage)
			{
				console.log('ERRORS: ' + textStatus);
			}
			
		});
		
	}
	
	function simpandata()
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("setup/simpansetupgeneral")?>";
		data   = $('#masterperusahaan').serialize();
		// console.log(data); return false;
		
		$.post(target, data, function(e){
			// $("#main-body").html(e);
			var jojon 	= $.parseJSON(e);
			if(jojon.flag)
			{
				$('#s_idsetting').val(jojon.theID);
				
				alert('Data Bumdes berhasil disimpan..');
			}
			else
			{
				alert('Silahkan periksa kembali inputan Anda....');
			}
			
			$("#backDropOverlay").remove();
		});
	}
</script>
