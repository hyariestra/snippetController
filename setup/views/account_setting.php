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

$per = $setting->row();



?>

<h4>Setting Akun User</h4>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		
		<form id="masteruser" method='post' enctype='multipart/form-data'>
			<div id="notif" class="col-md-12"></div>
			<div class="col-md-12">

				<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
					<div class="form-horizontal">
						<div class="col-md-6">
							<input type="hidden" id="s_iduser" name="s_iduser" value="<?php echo $_SESSION['IDUser'] ?>"/>
							<div class="form-group">
								<label class="col-md-3 control-label">Nama User</label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="s_nama" value="<?php echo @$per->nama_user ?>" />
								</div>
								<label class="col-md-3 control-label">Nama Lengkap</label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="s_nama_lengkap" value="<?php echo @$per->nama_lengkap ?>" />
								</div>

								<label class="col-md-3 control-label">No Telp</label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="s_telp" value="<?php echo @$per->telp ?>" />
								</div>
								<label class="col-md-3 control-label">Email</label>
									<div class="col-md-8">
									<input type="text" class="form-control" name="s_email" value="<?php echo @$per->email ?>" />
									<button type="button" onclick="simpandata()" class="btn btn-sm btn-default">
					<span class="glyphicon glyphicon-plus-sign"></span>
					Simpan
					</button>
								</div>

								<label class="col-md-3 control-label">Ganti Password</label>
									<div class="col-md-8">
									<input type="hidden" id="pasnow" class="form-control" name="s_passwordnow" value="<?php echo @$per->password?>"/>
									<input type="text" id="pas1" class="form-control" name="s_passwordx" placeholder="Masukkan password baru" />
									<input type="text" id="pas2" class="form-control" name="s_passwordy" placeholder="Masukkan password yang sama kembali" />
					<button type="button" onclick="gantipassword()" class="btn btn-sm btn-default">
					<span class="glyphicon glyphicon-plus-sign"></span>
					Simpan
					</button>
								</div>
							</div>
						</div>
					</div>
				</div>
		
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
	
	function gantipassword()
	{

		var target = "<?php echo site_url("setup/ganti_password_user")?>";
		data   = {
			url:target,
			IDP : $("#s_iduser").val(),
			passwordx : $("input[name='s_passwordx']").val(),
			passwordy : $("input[name='s_passwordy']").val(),
			serialize : $("#masteruser").serialize()
		}
	
		$.post(target, data, function(e){
    		console.log(e);

 		   		
    		var json = $.parseJSON(e);
    		$('#notif').html(json.notif);
    	});
		
		
	}



		function simpandata()
	{

		
		var target = "<?php echo site_url("setup/update_data_user")?>";
		data   = {
			url:target,
			IDP : $("#s_iduser").val(),
			nama_user : $("input[name='s_nama']").val(),
			nama_lengkap : $("input[name='s_nama_lengkap']").val(),
			telp : $("input[name='s_telp']").val(),
			email : $("input[name='s_email']").val(),
			serialize : $("#masteruser").serialize()
		}
	
		$.post(target, data, function(e){
    		console.log(e);
		   		
    		var json = $.parseJSON(e);
    		$('#notif').html(json.notif);

    	});
		
		
	}




    	
	</script>
