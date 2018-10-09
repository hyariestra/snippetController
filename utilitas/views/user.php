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

<div class="content-header">   
	<h4>Data User</h4>
</div>
<div class="row">
	<!-- <?php 
	print_r($_SESSION['IDUnit']);

	?> -->
</div>	
<div class="widget-container" style="padding:10px;">
	<div class="row">
		<div class="col-md-12">
			<button onclick="tambahdata()" class="btn btn-sm btn-default" style="background:#787a93;">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Tambah
			</button>
			<button onclick="deleteall()" class="btn btn-sm btn-default" style="background:#787a93;">
				<span class="glyphicon glyphicon-trash"></span>
				Delete
			</button>
			<button onclick="printdata()" class="btn btn-sm btn-default" style="background:#787a93;">
				<span class="glyphicon glyphicon-export"></span>
				Export Data
			</button>
			<table id="datatable" class="table table-bordered table-striped">
				<thead style="background:#787a93;">
					<tr>
						<th style="color:#fff;">#</th>
						<th style="color:#fff;"><input id="checkall" type="checkbox" style="display:block !important;" /></th>
						<th style="color:#fff;">Nama user</th>
						<th style="color:#fff;">Nama Lengkap</th>
						<th style="color:#fff;">Group</th>
						<th style="color:#fff;">Email</th>
						<th style="color:#fff;">Status</th>
						<th style="color:#fff;">Password</th>
						<th style="width:5%;color:#fff;">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $seq = 1;
					foreach($user->result() as $row){ ?>
					<tr>
						<td><?php echo $seq; ?>
							<input type="hidden" value="<?php echo $row->id_user?>">
							<input type="hidden" value="<?php echo $row->id_group?>">
							<input type="hidden" value="<?php echo $row->isaktif?>">
						</td>
						<td><input type="checkbox" value="<?php echo $row->id_user?>" class="chkbox"role="checkdata" style="display:block !important;" /></td>
						<td><?php echo $row->nama_user?></td>
						<td><?php echo $row->nama_lengkap?></td>
						<td><?php echo $row->nama_group?></td>
						<td><?php echo $row->email?></td>
						<td><?php echo $status = ($row->isaktif) ? "Aktif" : "Tidak Aktif"; ?></td>
						<td style="text-align:right;"><?php echo $row->password?></td>
						<td>
							<button onclick="editdata(this)" class="btn btn-xs btn-warning">
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
							<button onclick="deletedata(<?php echo $row->id_user ?>)" class="btn btn-xs btn-danger">
								<span class="glyphicon glyphicon-trash"></span>
							</button>
						</td>
					</tr>
					<?php $seq++;} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Tambah Data baru -->
<div class="modal fade" id="modalForm">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 10px 0px 0px !important;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
				<h5 style="padding: 0px 15px 0px 15px;">Tambah User</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="formBaru" class="form-horizontal">
						<div class="col-md-12">
							<div style="background:#787a93;min-height:0px;" class="widget-container padded">
								<table class="table table-bordered tsble-striped" style="background:#fff;">
									<tr>
										<td class="form-input labelinput">Nama User</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="namauser" name="namauser" role="inputtext" />
											</div>
										</td>
									</tr>

									<tr>
										<td class="form-input labelinput">Nama Lengkap</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="namalengkap" name="namalengkap" role="inputtext" />
											</div>
										</td>
									</tr>

									<tr>
										<td class="form-input labelinput">Group</td>
										<td class="form-input">
											<div class="col-md-12">
												<select class="form-control" name="group">
													<option value="-">:: Pilih Group ::</option>
													<?php
													foreach($group->result() as $row){
														?>
														<option value="<?php echo $row->id_group?>"><?php echo $row->nama_group?></option>
														<?php
													}
													?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td class="form-input labelinput">Email</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="email" name="email" role="inputtext" />
											</div>
										</td>
									</tr>
									<tr>
										<td class="form-input labelinput">Password</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="password" class="form-control" id="password" name="password" role="inputtext" />
											</div>
										</td>
									</tr>
									<tr>
										<td class="form-input labelinput">Unit</td>
										<td class="form-input">
											<div class="col-md-12">
												<select class="form-control" name="unit">
													<option value="-">:: Pilih Unit ::</option>
													<?php
													foreach($depar->result() as $row){
														?>
														<option value="<?php echo $row->id_departemen?>"><?php echo $row->nama_departemen?></option>
														<?php
													}
													?>
												</select>
											</div>
										</td>
									</tr>
								</table>
								<div class="buttonfooter">
									<button class="btn btn-xs btn-success"  onclick="simpanuser()" type="button">
										<span class="glyphicon glyphicon-save"></span> Simpan
									</button>
									<button onclick="batal()" type="button" class="btn btn-xs btn-danger">
										<span class="glyphicon glyphicon-remove"></span> Batal
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer customefooter" style="">
				tanda (*) harus untuk diisi.
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Ubah -->
<div class="modal fade" id="modalubah">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 10px 0px 0px !important;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
				<h5 style="padding: 0px 15px 0px 15px;">Ubah Data User</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="formUbah" class="form-horizontal">
						<div class="col-md-12">
							<div style="background:#787a93;min-height:0px;" class="widget-container padded">
								<table class="table table-bordered tsble-striped" style="background:#fff;">

									<tr>
										<td class="form-input labelinput">Nama User</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="namauser_ubah" name="namauser" role="inputtext" />
												<input type="hidden" class="form-control" id="iduser_ubah" name="iduser" role="inputtext" />
											</div>
										</td>
									</tr>
									<tr>
										<td class="form-input labelinput">Nama Lengkap</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="namalengkap_ubah" name="namalengkap" role="inputtext" />
											</div>
										</td>
									</tr>

									<tr>
										<td class="form-input labelinput">Group</td>
										<td class="form-input">
											<div class="col-md-12">
												<select class="form-control" name="group_ubah">
													<option value="-">:: Pilih Group ::</option>
													<?php
													foreach($group->result() as $row){
														?>
														<option value="<?php echo $row->id_group?>"><?php echo $row->nama_group?></option>
														<?php
													}
													?>
												</select>
											</div>
										</td>
									</tr>

									<tr>
										<td class="form-input labelinput">Email</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="email_ubah" name="email" role="inputtext" />
											</div>
										</td>
									</tr>
									<tr>
										<td class="form-input labelinput">Password</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="password_ubah" name="password" role="inputtext" />
											</div>
										</td>
									</tr>
									<tr>
										<td class="form-input labelinput">Aktif</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="checkbox" class="form-control" style="display:inline;width:14px;" id="isaktif_ubah" name="isaktif" />
											</div>
										</td>
									</tr>
								</table>
								<div class="buttonfooter">
									<button class="btn btn-xs btn-success"  onclick="simpanubahuser()" type="button">
										<span class="glyphicon glyphicon-save"></span> Simpan
									</button>
									<button onclick="batal_ubah()" type="button" class="btn btn-xs btn-danger">
										<span class="glyphicon glyphicon-remove"></span> Batal
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer customefooter" style="">
				tanda (*) harus untuk diisi.
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){
	$("#datatable").dataTable({
		"bLengthChange" : true
	});


	$(".datepicker").datepicker({
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

function loadGridData(){  
	var loadhtml = "<?php echo site_url("utilitas/user")?>";
	$("#main-body").load(loadhtml);  
}

function tambahdata()
{
	$("#modalForm").modal("show");
}

function simpanuser(){
		//alert('ok');
		var target="<?php echo site_url('utilitas/simpanuser')?>";
		data = $('#formBaru').serialize();


		if (data != "") {

			$.post(target,data,function(e){
				console.log(e);
				
				$('html, body').css('overflow-y','auto');
				loadGridData();
			});
			$("#modalForm").modal("hide");	
		} else {
			alert("Data belum lengkap .. ");
		}
	}
	
	function deletedata(obj)
	{

		var isdelete = confirm("Apakah anda yakin akan menghapus data ini ?");
		
		if(isdelete)
		{
			//$(obj).parent().parent().remove();
			var target="<?php echo site_url('utilitas/hapususer')?>";
			data={
				iduser:obj
			}
			$.post(target,data,function(e){
	  	 		//console.log(e);
	  	 		$('html, body').css('overflow-y','auto');
	  	 		alert('User berhasil dihapus');
	  	 		loadGridData();
	  	 	});
		}		
	}
	
	function deleteall()
	{

		console.log(ids);
		
		var conf = confirm("apakah anda yakin akan menghapus data ini ?");
		if (conf)
		{

			var ids = [];
			$(".chkbox").each(function () {
				if ($(this).is(":checked")) {
					ids.push($(this).val());
					console.log(ids);
				}
			});

			if(ids.length>0)
			{
				$.ajax({
					type: 'POST',
					url: "<?php echo site_url("utilitas/deleteuserall")?>",
					data: {
						idu : ids
					},
					success: function (e) {
						console.log(e);
						var ids = [];
						$(".chkbox").each(function () {
							if ($(this).is(":checked")) {
								$(this).parent().parent().remove();
							}
						});
					}		
				});
				alert("Data berhasil dihapus");
			}
			else
			{

				alert("silahkan pilih data.");
			}

		}
		else
		{
			alert("Hapus dibatalkan.");
		}

	}

	
	function batal()
	{
		$("#modalForm").modal("hide");
	}

	function batal_ubah()
	{
		$("#modalubah").modal("hide");
	}

	function editdata(obj)
	{
		var iduser = $(obj).parent().parent().find("td").eq(0).find("input").eq(0).val();
		var idgroup = $(obj).parent().parent().find("td").eq(0).find("input").eq(1).val();
		var namauser = $(obj).parent().parent().find("td").eq(2).text();
		var namalengkap = $(obj).parent().parent().find("td").eq(3).text();
		var email = $(obj).parent().parent().find("td").eq(5).text();
		var status = $(obj).parent().parent().find("td").eq(0).find("input").eq(2).val();
		
		
		$("#namauser_ubah").val(namauser);
		$("#namalengkap_ubah").val(namalengkap);
		$("#email_ubah").val(email);
		$("select[name='group_ubah']").val(idgroup);
		
		if(status == 1)
		{
			$("input[type='checkbox'][id='isaktif_ubah']").prop("checked", true);
		}
		else
		{
			$("input[type='checkbox'][id='isaktif_ubah']").prop("checked", false);
		}
		
		$("#iduser_ubah").val(iduser);
		
		$("#modalubah").modal("show");

	}

	function simpanubahuser()
	{
		var target = "<?php echo site_url("utilitas/simpanubahuser")?>";
		data = $("#formUbah").serialize();

		if (data != "") {	
			$.post(target, data, function(e)
			{
				console.log(e);
				alert("Data berhasil diupdate...");
				loadGridData();
			});

			$("#modalubah").modal("hide");	
		} else {
			alert("Data belum lengkap..");
		}

	}
	
	function formatNumber(objSource) 
	{
		a = $(objSource).val();
        //b = a.replace(/[^\d]/g, "");
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
        $(objSource).val(c);
    }


    	function printdata()
	{
		var target  = "<?php echo site_url("utilitas/printdatauser") ?>/pdf/";
		window.open(target);
	}


    </script>