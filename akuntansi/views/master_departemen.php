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
		background:#429489;
		color:#fff;
		font-size:12px;
	}
	
	.datepicker{
		z-index:9999 !important;
	}
</style>

<h4>Setting Departemen</h4>


<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<div class="row" style="border-bottom:0px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
			<table id="example" width="100%" cellpadding="5">
				<tr id="datatable_" style="border-bottom:1px dashed #429489; background: #429489; color: #fff">
				<td style="padding-right:50px;"><b>Departemen</b></td>
				<td style="width: 10%"></td>
			</tr>
			<tr id="datatable_" style="border-bottom:1px dashed #429489; background: ; color: #555">
				<td style="padding-right:50px;">&nbsp;&nbsp;&nbsp;<b>Departemen</b></td>
				<td style="width: 10%">
					<button buttontype="actionItems" class="btn btn-xs btn-success" onclick="TambahAkunBaru()">
						<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
					</button>
				</td>
			</tr>
				<?php foreach($dep->result() as $depart){ ?>
					<tr id="datatable_" style="border-bottom:1px dashed #429489;">
						<td style="padding-right:50px;"><?php echo get_space(2)."(".$depart->kode_departemen.") ".$depart->nama_departemen ?></td>
						<td style="width: 10%">
							<button buttonType="actionItems" buttonType="actionItems" class= "btn btn-xs btn-warning" onclick="UbahDepartemen(this, '<?php echo $depart->id_departemen ?>')" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></button>
							<button buttonType="actionItems" buttonType="actionItems" class= "btn btn-xs btn-danger" onclick="HapusDepartemen(this, '<?php echo $depart->id_departemen ?>')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></button>
						</td>
					</tr>
				<?php
				}
				?>
			
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
        <h5 style="padding: 0px 15px 0px 15px;">Tambah Departemen</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formBaru" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<tr>
							<td class="form-input labelinput">* Kode Departemen</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="new_kode" id="new_kode" role="inputtext" />
									
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">* Nama Departemen</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="new_nama" id="new_nama" role="inputtext" />
									
								</div>
							</td>
						</tr>
					</table>
					<div class="buttonfooter">
						<button class="btn btn-xs btn-success"  onclick="simpandepartemen()" type="button">
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
        <h5 style="padding: 0px 15px 0px 15px;">Edit Departemen</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formUbah" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<input type="hidden" name="edit_iddepartemen" id="edit_iddepartemen" />
						<tr>
							<td class="form-input labelinput">* Kode Departemen</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="edit_kode" id="edit_kode" role="inputtext" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">* Nama Departemen</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="edit_nama" id="edit_nama" role="inputtext" />		
								</div>
							</td>
						</tr>
					</table>
					<div class="buttonfooter">
						<button class="btn btn-xs btn-success"  onclick="simpandepartemenubah()" type="button">
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
$(document).ready(function() 
{



});

function loadGridData(){  
    	var loadhtml = "<?php echo site_url("akuntansi/settingdepartemen")?>";
    	$("#main-body").load(loadhtml);  
  	}

function batal()
	{
		$("#modalForm").modal("hide");
		$('#message').hide();
	}

function TambahAkunBaru()
	{
		$("#formBaru")[0].reset();
		$("#modalForm").modal("show");
	}


function simpandepartemen(){
		var param 	= "bank";
			target  = "<?php echo site_url('akuntansi/simpantambahdepartemen')?>";
  	 		data 	= $('#formBaru').serialize();

  	 		if($('#new_nama').val() == '')
	  	 	{
	  	 		$('#message').html("wajib isi");
	  	 		return false;
	  	 	}

$.post(target,data,function(e){
  	 		var jojon 	= $.parseJSON(e);
  	 		//console.log(e);

  	 		if(jojon.flag)
  	 		{
  	 			
  	 			alert('Data Departemen berhasil disimpan...');
  	 		}
  	 		else
  	 		{
  	 			alert('Data gagal disimpan. Periksa kembali input Anda...');
  	 		}

  			$('html, body').css('overflow-y','auto');
  			 loadGridData();
	   	});
	   	$("#modalForm").modal("hide");	
	}	

	function batal_ubah()
	{
		$("#modalubah").modal("hide");
		$('#message_').hide();
	}

	

	function UbahDepartemen(obj,IDP)
	{
		$('#formUbah')[0].reset();
			
		var nama 		= $(obj).parent().parent().find('td:eq(1)').text().trim();
		var target		= "<?php echo site_url("akuntansi/getdatadepartemen")?>";
			data		= {
				idx : IDP
			}
	    
	    $('#alertMessage').remove();

	    $.post(target, data, function(e){

			var json = $.parseJSON(e);
	    	// console.log(json); return false;
			
			$('#edit_iddepartemen').val(IDP);	   
			$('#edit_kode').val(json.dep.kode_departemen);
			$('#edit_nama').val(json.dep.nama_departemen);

			$("#modalubah").modal("show");
		});
	}

	function simpandepartemenubah()
	{  
		var target = "<?php echo site_url("akuntansi/simpandepartemenubah")?>/";
			data = $("#formUbah").serialize();
			// console.log(data);

			if($('#edit_nama').val() == '')
	  	 	{
	  	 		$('#message_').html("wajib isi");
	  	 		return false;
	  	 	}
			
			$.post(target,data,function(e){
	  	 		var jojon 	= $.parseJSON(e);
	  	 		//console.log(e);

	  	 		if(jojon.flag)
	  	 		{
	  	 			alert('Data item berhasil diupdate...');
	  	 		}
	  	 		else
	  	 		{
	  	 			alert('Data gagal diupdate. Periksa kembali input Anda...');
	  	 		}
				loadGridData();
	   		});
	   	
	   	$("#modalubah").modal("hide");	

	    $("#modalubah").remove("hide");	
	}

	function HapusAkun(obj, IDP)
	{
		var isdelete = confirm("Apakah anda yakin akan menghapus data ini ?");
		
		if(isdelete)
		{
			//$(obj).parent().parent().remove();
			var param = "bank";
				target="<?php echo site_url('akuntansi/hapusjudul')?>/"+param;
				data={id_judul:IDP};
			
			$.post(target,data,function(e){
				var jojon 	= $.parseJSON(e);

				if(jojon.flag)
				{
				
                    alert('Data berhasil dihapus...');
				}
				else
				{
					alert('Data gagal dihapus...');
				}

	  			$('html, body').css('overflow-y','auto');
	  			
	   		});
		}		
	}
	
	function HapusDepartemen(obj, idx)
	{
	
		var conf = confirm("Apakah anda yakin akan menghapus data ini ?");
		
		if(conf)
		{
		
			
			var target = "<?php echo  site_url("akuntansi/hapusDepartement")?>";
				data = {
					idx : idx
				}
				
			$.post(target, data, function(e){
				
				loadGridData();
				
			})
			.done(function(e){
				alert("Data berhasil dihapus.");
			});
		
		}
	}

</script>

