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
	<h4>Item</h4>
</div>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		<div class="col-md-12">
			<button onclick="tambahdata()" class="btn btn-sm btn-default">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Tambah
			</button>
			<table id="datatable" class="table table-bordered table-striped">
				<thead style="background:#429489;">
					<tr>
						<th style="color:#fff;">#</th>
						<th style="color:#fff;">Tipe Item</th>
						<th style="color:#fff;">Kategori Item</th>
						<th style="color:#fff;">Nama Item</th>
						<th style="color:#fff;">Satuan</th>
						<th style="color:#fff;">Harga Beli</th>
						<th style="color:#fff;">Harga Jual</th>
						<th width="5%" style="color:#fff;">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($kat->result() as $row)
						{
							$item 	= $this->db->query("SELECT item.*, tipe.tipe_item, kate.nama_kategori 
								FROM mst_item item
								LEFT JOIN ref_tipe_item tipe ON tipe.id_ref_tipe_item = item.id_ref_tipe_item
								LEFT JOIN mst_kategori_item kate ON kate.id_kategori_item = item.id_kategori_item
								WHERE item.id_kategori_item = ".$row->id_kategori_item." ");
					?>
						<tr>
							<td style="background: #429489; color: white;" colspan="8" id="kategori-<?php echo $row->id_kategori_item;?>">
								<?php echo $row->nama_kategori;?>
							</td>
							<td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td>
							<td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td>
							<td style="display: none;"></td>
						</tr>
						<?php 
							$seq = 1;
							foreach($item->result() as $i => $tem)
							{ ?>
							<tr>
								<td><?php echo $seq; ?></td>
								<td><?php echo $tem->tipe_item?><input type="hidden" name="id_ref_tipe_item" id="id_ref_tipe_item" value="<?php echo $tem->id_ref_tipe_item ?>"/></td>
								<td><?php echo $tem->nama_kategori?><input type="hidden" name="id_kategori_item" id="id_kategori_item" value="<?php echo $tem->id_kategori_item ?>"/></td>
								<td><?php echo $tem->nama_item?></td>
								<td><?php echo $tem->satuan?></td>
								<td style="text-align: right;"><?php echo number_format($tem->harga_beli)?></td>
								<td style="text-align: right;"><?php echo number_format($tem->harga_jual)?></td>
								<td>
									<input type="hidden" class="form-control" id="id_item" name="id_item" role="inputtext" value="<?php echo $tem->id_item; ?>"/>
									<input type="hidden" class="form-control" id="id_akun" name="id_akun" role="inputtext" value="<?php echo $tem->id_akunitem; ?>"/>
									<button onclick="editdata(this,<?php echo $tem->id_item ?>)" class="btn btn-xs btn-warning">
										<span class="glyphicon glyphicon-pencil"></span>
									</button>
									<button onclick="deletedata(this,<?php echo $tem->id_item ?>)" class="btn btn-xs btn-danger">
										<span class="glyphicon glyphicon-trash"></span>
									</button>
								</td>
							</tr>
						<?php
								$seq++;
							}	
						} ?>
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
        <h5 style="padding: 0px 15px 0px 15px;">Tambah Item</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formBaru" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<input type="hidden" name="new_idakun" />
						<tr>
							<td class="form-input labelinput">Tipe Item</td>
							<td class="form-input">
								<div class="col-md-12">
									<select onchange="getakun(this,'new_')" type="text" class="form-control" name="new_tipe" id="new_tipe" role="inputtext">
										<option value="">- Pilih Tipe Item -</option>
										<?php 
										foreach($tipe->result() as $ti => $pe)
										{
											echo '<option value="'.$pe->id_ref_tipe_item.'">'.$pe->tipe_item.'</option>';
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Kategori Item</td>
							<td class="form-input">
								<div class="col-md-12">
									<select type="text" class="form-control" name="new_kategori" id="new_kategori" role="inputtext">
										<option value="">- Pilih Kategori Item -</option>
										<?php 
										foreach($kat->result() as $k => $at)
										{
											echo '<option value="'.$at->id_kategori_item.'">'.$at->nama_kategori.'</option>';
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Nama Item</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="new_nama" id="new_nama" role="inputtext" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Satuan</td>
							<td class="form-input">
								<div class="col-md-8">
									<input type="text" class="form-control" name="new_satuan" id="new_satuan" role="inputtext" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Harga Beli</td>
							<td class="form-input">
								<div class="col-md-8">
									<div class="input-group">
										<span class="input-group-addon">Rp. </span>
										<input type="text" style="text-align:right;" class="form-control" onkeyup="formatNumber(this)" name="new_hargabeli" id="new_hargabeli" role="inputtext" />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Harga Jual</td>
							<td class="form-input">
								<div class="col-md-8">
									<div class="input-group">
										<span class="input-group-addon">Rp. </span>
										<input type="text" style="text-align:right;" class="form-control" onkeyup="formatNumber(this)" name="new_hargajual" id="new_hargajual" role="inputtext" />
									</div>
								</div>
							</td>
						</tr>
					</table>
					<div class="buttonfooter">
						<button class="btn btn-xs btn-success"  onclick="simpanitem()" type="button">
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
	 
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Ubah -->
<div class="modal fade" id="modalubah">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 10px 0px 0px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 style="padding: 0px 15px 0px 15px;">Ubah Data Item</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formUbah" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<input type="hidden" name="edit_idakun" />
						<tr>
							<td class="form-input labelinput">Tipe Item</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="hidden" name="edit_iditem" id="edit_iditem" value="" />
									<select type="text" onchange="getakun(this,'edit_')" class="form-control" name="edit_tipe" id="edit_tipe" role="inputtext">
										<option value="">- Pilih Tipe Item -</option>
										<?php 
										foreach($tipe->result() as $ti => $pe)
										{
											echo '<option value="'.$pe->id_ref_tipe_item.'">'.$pe->tipe_item.'</option>';
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Kategori Item</td>
							<td class="form-input">
								<div class="col-md-12">
									<select type="text" class="form-control" name="edit_kategori" id="edit_kategori" role="inputtext">
										<option value="">- Pilih Kategori Item -</option>
										<?php 
										foreach($kat->result() as $k => $at)
										{
											echo '<option value="'.$at->id_kategori_item.'">'.$at->nama_kategori.'</option>';
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Nama Item</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="edit_nama" id="edit_nama" role="inputtext" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Satuan</td>
							<td class="form-input">
								<div class="col-md-8">
									<input type="text" class="form-control" name="edit_satuan" id="edit_satuan" role="inputtext" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Harga Beli</td>
							<td class="form-input">
								<div class="col-md-8">
									<div class="input-group">
										<span class="input-group-addon">Rp. </span>
										<input type="text" style="text-align:right;" class="form-control" onkeyup="formatNumber(this)" name="edit_hargabeli" id="edit_hargabeli" role="inputtext" />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Harga Jual</td>
							<td class="form-input">
								<div class="col-md-8">
									<div class="input-group">
										<span class="input-group-addon">Rp. </span>
										<input type="text" style="text-align:right;" class="form-control" onkeyup="formatNumber(this)" name="edit_hargajual" id="edit_hargajual" role="inputtext" />
									</div>
								</div>
							</td>
						</tr>
					</table>
					<div class="buttonfooter">
						<button class="btn btn-xs btn-success"  onclick="simpanitemubah()" type="button">
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
	  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
	$(document).ready(function(){
		
		
		
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

	function simpanitem(){
  	 	var target  = "<?php echo site_url('setup/simpanitem')?>";
  	 		data 	= $('#formBaru').serialize();
  	 	//console.log(data);
  	 	if($('#new_kategori').val() == '')
  	 	{
  	 		alert('Kategori Item wajib diisi...');
  	 		return false;
  	 	}

  	 	$.post(target,data,function(e){
  	 		var jojon 	= $.parseJSON(e);
  	 		//console.log(e);

  	 		if(jojon.flag)
  	 		{
  	 			$('#datatable').dataTable().fnAddData( [
                    'New',
                    jojon.tipe_item+'<input type="hidden" name="id_ref_tipe_item" id="id_ref_tipe_item" value="'+jojon.id_ref_tipe_item+'"/>',
                    jojon.kategori+'<input type="hidden" name="id_kategori_item" id="id_kategori_item" value="'+jojon.id_kategori_item+'"/>',
                    jojon.nama_item,
                    jojon.satuan,
                    jojon.harga_beli,
                    jojon.harga_jual,
                    '<input type="hidden" class="form-control" id="id_item" name="id_item" role="inputtext" value="'+jojon.newID+'"/><input type="hidden" class="form-control" id="id_akun" name="id_akun" role="inputtext" value="'+jojon.idakun+'"/><button onclick="editdata(this,'+jojon.newID+')" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button> <button onclick="deletedata(this,'+jojon.newID+')" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>' ] );
  	 			alert('Data item berhasil disimpan...');
  	 		}
  	 		else
  	 		{
  	 			alert('Data gagal disimpan. Periksa kembali input Anda...');
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
			var target="<?php echo site_url('setup/hapusitem')?>";
				data={id_item:IDP};
				row     = $('#datatable').find('#id_item[value="'+IDP+'"]').parent().parent();
			$.post(target,data,function(e){
				var jojon 	= $.parseJSON(e);

				if(jojon.flag)
				{
					$('#datatable').dataTable().fnDeleteRow(row[0], null, true);
                    alert('Data item berhasil dihapus...');
				}
				else
				{
					alert('Data item gagal dihapus...');
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
		
		var id_tipe 	= $(obj).parent().parent().find('td:eq(1) #id_ref_tipe_item').val();
		 	id_kategori = $(obj).parent().parent().find('td:eq(2) #id_kategori_item').val();
		 	nama 		= $(obj).parent().parent().find('td:eq(3)').text();
		 	satuan 		= $(obj).parent().parent().find('td:eq(4)').text();
		 	harga_beli 	= $(obj).parent().parent().find('td:eq(5)').text();
		 	harga_jual 	= $(obj).parent().parent().find('td:eq(6)').text();
			idakun		= $(obj).parent().parent().find('td:eq(7)').find("input:last").val();
	    // console.log(telp1); return false;
		
		//console.log(idakun);
	    
	    $('#alertMessage').remove();
	    $('#edit_iditem').val(IDP);
	    $('#edit_tipe').val(id_tipe);
	    $('#edit_kategori').val(id_kategori);
	    $('#edit_nama').val(nama);
	    $('#edit_satuan').val(satuan);
	    $('#edit_hargabeli').val(harga_beli);
	    $('#edit_hargajual').val(harga_jual);
	    $('input[name="edit_idakun"]').val(idakun);

	    $("#modalubah").modal("show");

	}

	function simpanitemubah()
	{
		var target = "<?php echo site_url("setup/simpanitemubah")?>";
			data = $("#formUbah").serialize();
			// console.log(data);

			if($('#edit_kategori').val() == '')
	  	 	{
	  	 		alert('Kategori item wajib diisi...');
	  	 		return false;
	  	 	}
			
			$.post(target, data, function(e)
			{
				var jojon 	= $.parseJSON(e);
				var idNye 	= jojon.newID;
				var row 	= $('#datatable').find('#id_item[value="'+idNye+'"]').parent().parent();
				// console.log(jojon); //return false;
				
				if(jojon.flag)
				{
					$('#datatable').dataTable().fnUpdate([
                        'Edit',
	                    jojon.tipe_item+'<input type="hidden" name="id_ref_tipe_item" id="id_ref_tipe_item" value="'+jojon.id_ref_tipe_item+'"/>',
	                    jojon.kategori+'<input type="hidden" name="id_kategori_item" id="id_kategori_item" value="'+jojon.id_kategori_item+'"/>',
	                    jojon.nama_item,
	                    jojon.satuan,
	                    jojon.harga_beli,
	                    jojon.harga_jual,
	                    '<input type="hidden" class="form-control" id="id_item" name="id_item" role="inputtext" value="'+jojon.newID+'"/><button onclick="editdata(this,'+jojon.newID+')" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button> <button onclick="deletedata(this,'+jojon.newID+')" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>' ], row[0] );

					alert("Data berhasil diupdate...");
				}
				else
				{
					alert("Data gagal diupdate. Silahkan periksa kembali...");
				}
				loadGridData();
	   		});
	   	
	   	$("#modalubah").modal("hide");	

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
	
	function getakun(obj, prefix)
	{
		var target = "<?php echo site_url("setup/getlinkkodeakun")?>";
			data = {
				idtipe : $(obj).val()
			}
			
		$.post(target, data, function(e){
			$("input[name='"+prefix+"idakun']").val(e);
		});
	}
</script>