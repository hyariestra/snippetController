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
	<h4>Data Group User</h4>
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
			
			<table id="datatable" class="table table-bordered table-striped">
				<thead style="background:#787a93;">
					<tr>
						<th style="color:#fff;">#</th>
						<th style="color:#fff;"><input id="checkall" type="checkbox" style="display:block !important;" /></th>
						<th style="color:#fff;">Group</th>
						<th style="color:#fff;">Deskripsi</th>
						<th style="width:5%;color:#fff;">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $seq = 1;
					foreach($group->result() as $row){ ?>
					<tr>
						<td><?php echo $seq; ?>
							<input type="hidden" value="<?php echo $row->id_group?>">
						</td>
						<td><input type="checkbox" value="<?php echo $row->id_group?>" class="chkbox" role="checkdata" style="display:block !important;" /></td>
						<td><?php echo $row->nama_group?></td>
						<td><?php echo $row->deskripsi?></td>
						<td>
							<button onclick="editdata(this)" class="btn btn-xs btn-warning">
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
							<button onclick="deletedata(<?php echo $row->id_group ?>)" class="btn btn-xs btn-danger">
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
				<h5 style="padding: 0px 15px 0px 15px;">Tambah Group User</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<form id="formBaru" class="form-horizontal">
						<div class="col-md-12">
							<div style="background:#787a93;min-height:0px;" class="widget-container padded">
								<table class="table table-bordered tsble-striped" style="background:#fff;">
									<tr>
										<td class="form-input labelinput">Nama Group</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="group" name="group" role="inputtext" />
											</div>
										</td>
									</tr>

									<tr>
										<td class="form-input labelinput">Deskripsi</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="deskripsi" name="deskripsi" role="inputtext" />
											</div>
										</td>
									</tr>
								</table>
								<div class="buttonfooter">
									<button class="btn btn-xs btn-success"  onclick="simpangroup()" type="button">
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
										<td class="form-input labelinput">Nama Group</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="group_ubah" name="group_ubah" role="inputtext" />
												<input type="hidden" class="form-control" id="idgroup" name="idgroup" role="inputtext" />
											</div>
										</td>
									</tr>

									<tr>
										<td class="form-input labelinput">Deskripsi</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" id="deskripsi_ubah" name="deskripsi_ubah" role="inputtext" />
											</div>
										</td>
									</tr>
								</table>
								<div class="buttonfooter">
									<button class="btn btn-xs btn-success"  onclick="updategroup()" type="button">
										<span class="glyphicon glyphicon-save"></span> Update
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
	var loadhtml = "<?php echo site_url("utilitas/groupuser")?>";
	$("#main-body").load(loadhtml);  
}

function tambahdata()
{
	$("#modalForm").modal("show");
}

function simpangroup(){
		//alert('ok');
		var target="<?php echo site_url('utilitas/simpangroup')?>";
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
			var target="<?php echo site_url('utilitas/hapusgroupuser')?>";
			data={
				idgroup:obj
			}
			$.post(target,data,function(e){
	  	 		//console.log(e);
	  	 		$('html, body').css('overflow-y','auto');
	  	 		alert('Data berhasil dihapus');
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
					url: "<?php echo site_url("utilitas/deletegroupall")?>",
					data: {
						idg : ids
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
		var idgroup = $(obj).parent().parent().find("td").eq(0).find("input").eq(0).val();
		var group = $(obj).parent().parent().find("td").eq(2).text();
		var deskripsi = $(obj).parent().parent().find("td").eq(3).text();
		
		$("#idgroup").val(idgroup);
		$("#group_ubah").val(group);
		$("#deskripsi_ubah").val(deskripsi);
		
		
		$("#modalubah").modal("show");

	}

	function updategroup()
	{
		var target = "<?php echo site_url("utilitas/updategroup")?>";
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

    </script>