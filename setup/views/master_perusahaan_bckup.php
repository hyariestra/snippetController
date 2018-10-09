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

<?php $per = $perush->row();?>

<h4>Master Perusahaan</h4>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		
		<form id="masterperusahaan">
		<div class="col-md-12">
			<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
				<div class="form-horizontal">
					<div class="col-md-6">
						<input type="hidden" id="s_idperush" name="s_idperush" value="<?php echo @$per->id_perusahaan ?>"/>
						<div class="form-group">
							<label class="col-md-3 control-label">Nama Perusahaan</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="s_nama" value="<?php echo @$per->nama_perusahaan ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Alamat Perusahaan</label>
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
							<label class="col-md-3 control-label">Nama Pemilik</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="s_pemilik" value="<?php echo @$per->nama_pemilik ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Jenis Usaha</label>
							<div class="col-md-8">
								<select class="form-control" name="s_jenis">
									<option value="-">:: Pilih Jenis Usaha ::</option>
										<?php
										foreach($jenis->result() as $jen){
											if($perush->num_rows && $per->id_jenis_usaha)
											{
												$select = ($jen->id_jenis_usaha == $per->id_jenis_usaha) ? 'selected' : '';
											}
										?>
										<option <?php echo @$select ?> value="<?php echo $jen->id_jenis_usaha?>"><?php echo $jen->nama_jenis_usaha?></option>
										<?php
										}
										?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Deskripsi</label>
							<div class="col-md-8">
								<textarea type="text" class="form-control" name="s_deskripsi" style="resize: vertical;"><?php echo @$per->deskripsi ?></textarea>
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
	});
	
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
				alert('Data Perusahaan berhasil disimpan..');
			}
			else
			{
				alert('Silahkan periksa kembali inputan Anda....');
			}
			
			$("#backDropOverlay").remove();
		});
	}
</script>
