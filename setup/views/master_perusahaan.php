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

<?php

$per = $perush->row();



?>

<h4>Informasi <?php echo $setting->first_row()->label_lembaga?></h4>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		
		<form id="masterperusahaan" method='post' enctype='multipart/form-data'>
			<div class="col-md-12">
				<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
					<div class="form-horizontal">
						<div class="col-md-6">
							<input type="hidden" id="s_idperush" name="s_idperush" value="<?php echo @$per->id_perusahaan ?>"/>
							<div class="form-group">
								<label class="col-md-3 control-label">Nama <?php echo $setting->first_row()->label_lembaga?></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_nama" value="<?php echo @$per->nama_perusahaan ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Provinsi</label>
								<div class="col-md-8">
									<select class="form-control" onchange="getkabupaten(this)" name="id_prov">
										<?php
										foreach($prov->result() as $row){
											?>
											<option <?php if($row->id_propinsi == $perush->first_row()->id_propinsi){ echo "selected='selected'";} ?> value="<?php echo $row->id_propinsi."#".$row->kode_propinsi?>"><?php echo $row->nama_propinsi?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Kabupaten</label>
								<div class="col-md-8">
									<select class="form-control" name="id_kab" id="kabupaten">
										<?php 
										if(!$perush->first_row()->id_kabupaten)
										{ 
											?>
											<option value="">:: Pilih Kabupaten ::</option>
											<?php 
										}
										else
										{ 
											$kabu = $this->db->query("SELECT * FROM mst_kabupaten 
												LEFT JOIN mst_propinsi ON mst_propinsi.kode_propinsi = mst_kabupaten.kode_propinsi
												WHERE mst_propinsi.id_propinsi = '".$perush->first_row()->id_propinsi."'");
											
											foreach($kabu->result() as $kb)
											{
												?>
												<option <?php if($kb->id_kabupaten == $perush->first_row()->id_kabupaten){ echo "selected='selected'";} ?> value="<?php echo $kb->id_kabupaten?>"><?php echo  $kb->nama_kabupaten?></option>
												<?php		
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Alamat <?php echo $setting->first_row()->label_lembaga?></label>
								<div class="col-md-8">
									<textarea type="text" class="form-control" name="s_alamat" style="resize: vertical;"><?php echo @$per->alamat_perusahaan ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">No.Telp</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_telp" value="<?php echo @$per->no_telp ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Nama Pengurus</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="s_pemilik" value="<?php echo @$per->nama_pemilik ?>" />
								</div>
							</div>
							<div class="form-group" style="display: none;">
								<label class="col-md-3 control-label">Jenis Usaha</label>
								<div class="col-md-8">
									<select class="form-control" name="s_jenis">
										<option value="-">:: Pilih Jenis Usaha ::</option>
										<?php
										foreach($jenis->result() as $jen){
											?>
											<option <?php if(@$per->id_jenis_usaha == $jen->id_jenis_usaha){echo "selected='selected'";} ?> value="<?php echo $jen->id_jenis_usaha?>"><?php echo $jen->nama_jenis_usaha?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group" style="display: none;">
								<label class="col-md-3 control-label">Deskripsi</label>
								<div class="col-md-8">
									<textarea type="text" class="form-control" name="s_deskripsi" style="resize: vertical;"><?php echo @$per->deskripsi ?></textarea>
								</div>
							</div>
							<div class="form-group">

								<label class="col-md-3 control-label">Logo</label>
								<div class="col-md-8">
									<input id="fileupload"  type="file" class="form-control"/>
									<div class="hidden" class="" id="listfile">

									</div>
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
		
		var target = "<?php echo site_url("setup/simpanperusahaan")?>";
		data   = $('#masterperusahaan').serialize();
		// console.log(data); return false;
		
		$.post(target, data, function(e){
			// $("#main-body").html(e);
			var jojon 	= $.parseJSON(e);
			if(jojon.flag)
			{
				$('#s_idperush').val(jojon.theID);
				var test = "<?php echo site_url("upload")?>/"+jojon.gambar;
				$(".container-fluid > a > img").removeAttr('src');
				$(".container-fluid > a > img").attr('src', test);
				alert('Data berhasil disimpan..');
			}
			else
			{
				alert('Silahkan periksa kembali inputan Anda....');
			}
			
			$("#backDropOverlay").remove();
		});
	}
</script>
