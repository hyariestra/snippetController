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
	<h4>Kategori ZIS</h4>
</div>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		<div class="col-md-12">
			<button onclick="tambahdata()" class="btn btn-sm btn-default">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Tambah
			</button>
			<!-- <button onclick="deleteall()" class="btn btn-sm btn-default" style="background:#787a93;">
				<span class="glyphicon glyphicon-trash"></span>
				Delete
			</button> -->
			
			<table id="datatable" class="table table-bordered table-striped">
				<thead style="background:#429489;">
					<tr>
						<th width="3%" style="color:#fff;">#</th>
						<!-- <th style="color:#fff;"><input id="checkall" type="checkbox" style="display:block !important;" /></th> -->
						<th width="20%" style="color:#fff;">Nama Kategori</th>
						<th style="color:#fff;">Deskripsi</th>
						<th width="5%" style="color:#fff;">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $seq = 1;
						foreach($kat->result() as $row){ ?>
						<tr>
							<td><?php echo $seq; ?></td>
							<!-- <td><input <?php #if($row->issetting){ echo "checked='checked'";} ?> type="checkbox" onclick="updatetahunajaran(this)" role="checkdata" style="display:block !important;" />
								<input type="hidden" class="form-control" id="id_tahun_ajaran" name="id_tahun_ajaran" role="inputtext" value="<?php #echo $row->id_tahun_ajaran; ?>" /> 
							</td>-->
							<td><?php echo $row->nama_kategori?></td>
							<td><?php echo $row->deskripsi?></td>
							<td>
								<input type="hidden" class="form-control" id="id_kategori" name="id_kategori" role="inputtext" value="<?php echo $row->id_kategori_item; ?>" />
								<button onclick="editdata(this,<?php echo $row->id_kategori_item ?>)" class="btn btn-xs btn-warning">
									<span class="glyphicon glyphicon-pencil"></span>
								</button>
								<button onclick="deletedata(this,<?php echo $row->id_kategori_item ?>)" class="btn btn-xs btn-danger">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
							</td>
						</tr>
					<?php $seq++;} ?>
				</tbody>
			</table>
			<!-- <ul style="font-size:12px;">
				<b>NOTE :</b> 
				<li><b>"checked"</b> untuk <b>mensetting tahun ajaran yang aktif.</b></li>
			</ul> -->
		</div>
	</div>
</div>

<!-- Tambah Data baru -->
<div class="modal fade" id="modalForm">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 10px 0px 0px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
        <h5 style="padding: 0px 15px 0px 15px;">Tambah Kategori Item</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formBaru" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<tr>
							<td class="form-input labelinput">Nama Kategori</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="new_nama" id="new_nama" role="inputtext" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Deskripsi</td>
							<td class="form-input">
								<div class="col-md-12">
									<textarea style="resize: vertical;" type="text" class="form-control" name="new_deskripsi" id="new_deskripsi" role="inputtext"></textarea>
								</div>
							</td>
						</tr>
					</table>
					<div class="buttonfooter">
						<button class="btn btn-xs btn-success"  onclick="simpankategori()" type="button">
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
        <h5 style="padding: 0px 15px 0px 15px;">Ubah Data Pemasok</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formUbah" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<tr>
							<td class="form-input labelinput">Nama Kategori</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="hidden" class="form-control" name="edit_id_kategori" id="edit_id_kategori" role="inputtext" />
									<input type="text" class="form-control" name="edit_nama" id="edit_nama" role="inputtext" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Deskripsi</td>
							<td class="form-input">
								<div class="col-md-12">
									<textarea style="resize: vertical;" type="text" class="form-control" name="edit_deskripsi" id="edit_deskripsi" role="inputtext"></textarea>
								</div>
							</td>
						</tr>		
					</table>
					<div class="buttonfooter">
						<button class="btn btn-xs btn-success"  onclick="simpankategoriubah()" type="button">
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
    	var loadhtml = "<?php echo site_url("setup/mastertahunajaran")?>";
    	$("#main-body").load(loadhtml);  
  	}
		
	function tambahdata()
	{
		$("#formBaru")[0].reset();
		$("#modalForm").modal("show");
	}

	function simpankategori(){
  	 	var target  = "<?php echo site_url('setup/simpankategori')?>";
  	 		data 	= $('#formBaru').serialize();
  	 	//console.log(data);
  	 	$.post(target,data,function(e){
  	 		var jojon 	= $.parseJSON(e);
  	 		//console.log(e);

  	 		if(jojon.flag)
  	 		{
  	 			$('#datatable').dataTable().fnAddData( [
                    'New',
                    jojon.nama_kategori,
                    jojon.deskripsi,
                    '<input type="hidden" class="form-control" id="id_kategori" name="id_kategori" role="inputtext" value="'+jojon.newID+'" /><button onclick="editdata(this,'+jojon.newID+')" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button> <button onclick="deletedata(this,'+jojon.newID+')" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>' ] );
  	 			alert('Data berhasil disimpan...');
  	 		}
  	 		else
  	 		{
  	 			alert('Data gagal disimpan. Periksa kembali input mata uang & negara...');
  	 		}

  			$('html, body').css('overflow-y','auto');
  			// loadGridData();
	   	});
	   	$("#modalForm").modal("hide");	
	}
	
	function deletedata(obj, IDP)
	{
		var isdelete = confirm("Apakah anda yakin akan menghapus data ini ?");
		
		if(isdelete)
		{
			//$(obj).parent().parent().remove();
			var target="<?php echo site_url('setup/hapuskategori')?>";
				data={id_kategori:IDP};
				row     = $('#datatable').find('#id_kategori[value="'+IDP+'"]').parent().parent();
			$.post(target,data,function(e){
				var jojon 	= $.parseJSON(e);

				if(jojon.flag)
				{
					$('#datatable').dataTable().fnDeleteRow(row[0], null, true);
                    alert('Data pemasok berhasil dihapus...');
				}
				else
				{
					alert('Data pemasok gagal dihapus...');
				}

	  			$('html, body').css('overflow-y','auto');
	  			// loadGridData();
	   		});
		}		
	}
	
	function deleteall()
	{
		var isdelete = confirm("Apakah anda yakin akan menghapus semua data ?");
		var ch		 = $("#datatable").find("tbody input[type='checkbox'][role='checkdata']");
		var sel 	 = false;

		
		if(isdelete)
		{
			ch.each(function(){
			
				$this = $(this);
				if($this.is(":checked")){
					sel = true; //set to true if there is/are selected row
					$this.parent().parent().remove(); //remove row when animation is finished
				}
				
			});
			
			if(!sel) alert('No data selected');
		}
		
		return false;
	}
	
	function batal()
	{
		$("#modalForm").modal("hide");
	}

	function batal_ubah()
	{
		$("#modalubah").modal("hide");
	}

	function editdata(obj,IDP)
	{
		$('#formUbah')[0].reset();
		var nama  	 = $(obj).parent().parent().find('td:eq(1)').text();
		 	deskripsi= $(obj).parent().parent().find('td:eq(2)').text();
	    // console.log(telp1); return false;
	    
	    $('#alertMessage').remove();
	    $('#edit_id_kategori').val(IDP);
	    $('#edit_nama').val(nama);
	    $('#edit_deskripsi').val(deskripsi);
	  
	    $("#modalubah").modal("show");

	}

	function simpankategoriubah()
	{
		var target = "<?php echo site_url("setup/simpankategoriubah")?>";
			data = $("#formUbah").serialize();
			// console.log(data);
			
			$.post(target, data, function(e)
			{
				var jojon 	= $.parseJSON(e);
				var idNye 	= jojon.newID;
				var row 	= $('#datatable').find('#id_kategori[value="'+idNye+'"]').parent().parent();

				// console.log(row); //return false;

				if(jojon.flag)
				{
					$('#datatable').dataTable().fnUpdate( [
                        'Edit',
	                    jojon.nama_kategori,
	                    jojon.deskripsi,
	                    '<input type="hidden" class="form-control" id="id_kategori" name="id_kategori" role="inputtext" value="'+jojon.newID+'" /><button onclick="editdata(this,'+jojon.newID+')" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button> <button onclick="deletedata(this,'+jojon.newID+')" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>' ], row[0] );
					
					alert("Data berhasil diupdate...");
				}
				else
				{
					alert("Data gagal diupdate. Silahkan periksa kembali input mata uang & negara...");
				}
				loadGridData();
	   		});
	   	
	   	$("#modalubah").modal("hide");	

	}
</script>