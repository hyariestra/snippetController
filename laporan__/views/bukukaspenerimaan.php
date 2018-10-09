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
	<h4>Buku Kas Penerimaan</h4>
</div>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		<div class="col-md-12">
			
			<table id="datatable" class="table table-bordered table-striped">
				<thead style="background:#429489;">
					<tr>
						<th style="color:#fff;">#</th>
						<th style="color:#fff;">Tanggal</th>
						<th style="color:#fff;">No. Bukti</th>
						<th style="color:#fff;">Keterangan</th>
						<th style="color:#fff;">Debet</th>
						<th style="color:#fff;">Kredit</th>
						<th style="color:#fff;">Saldo Akhir</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$seq = 1;
						$saldoakhir = 0;
						$total = 0;
						foreach($kaspenerimaan->result() as $row)
						{
						
							$saldoakhir += $row->total;
					?>
						<tr>
							<td><?php echo $seq; ?></td>
							<td><?php echo date("d-m-Y", strtotime($row->tanggal_penjualan))?></td>
							<td><?php echo $row->no_transaksi?></td>
							<td><?php echo $row->keterangan?></td>
							<td style="text-align:right;"><?php echo number_format($row->total,2)?></td>
							<td style="text-align:right;"><?php echo number_format(0,2)?></td>
							<td style="text-align:right;"><?php echo number_format($saldoakhir,2)?></td>
						</tr>
					<?php
							$seq++;
							
							$total += $saldoakhir;
						} 
					?>
						
						<tr>
							<td colspan="6" style="font-weight:bold; text-align:center;">Total</td>
							<td style="text-align:right;font-weight:bold;"><?php echo number_format($total,2)?></td>
						</tr>
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
        <h5 style="padding: 0px 15px 0px 15px;">Tambah Bank</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formBaru" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<tr>
							<td class="form-input labelinput">Jenis Rekening</td>
							<td class="form-input">
								<div class="col-md-12">
									<select type="text" class="form-control" name="new_jenis" id="new_jenis" role="inputtext">
										<option value="">- Pilih Jenis Rekening -</option>
										<?php 
										foreach($rek->result() as $r => $ek)
										{
											echo '<option value="'.$ek->id_ref_jenis_rekening.'">'.$ek->nama_jenis_rekening.'</option>';
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Mata Uang</td>
							<td class="form-input">
								<div class="col-md-12">
									<select type="text" class="form-control" name="new_matauang" id="new_matauang" role="inputtext">
										<option value="">- Pilih Mata Uang -</option>
										<?php 
										foreach($mata->result() as $ma => $ta)
										{
											echo '<option value="'.$ta->id_mata_uang.'">'.$ta->nama_mata_uang.' ('.$ta->negara.')</option>';
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Nama Bank</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="new_namabank" id="new_namabank" role="inputtext" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">No.Rekening</td>
							<td class="form-input">
								<div class="col-md-8">
									<input type="text" class="form-control" name="new_norekening" id="new_norekening" role="inputtext" />
								</div>
							</td>
						</tr>
					</table>
					<div class="buttonfooter">
						<button class="btn btn-xs btn-success"  onclick="simpanbank()" type="button">
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
        <h5 style="padding: 0px 15px 0px 15px;">Ubah Data Item</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formUbah" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<tr>
							<td class="form-input labelinput">Jenis Rekening</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="hidden" name="edit_idbank" id="edit_idbank" />
									<select type="text" class="form-control" name="edit_jenis" id="edit_jenis" role="inputtext">
										<option value="">- Pilih Jenis Rekening -</option>
										<?php 
										foreach($rek->result() as $r => $ek)
										{
											echo '<option value="'.$ek->id_ref_jenis_rekening.'">'.$ek->nama_jenis_rekening.'</option>';
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Mata Uang</td>
							<td class="form-input">
								<div class="col-md-12">
									<select type="text" class="form-control" name="edit_matauang" id="edit_matauang" role="inputtext">
										<option value="">- Pilih Mata Uang -</option>
										<?php 
										foreach($mata->result() as $ma => $ta)
										{
											echo '<option value="'.$ta->id_mata_uang.'">'.$ta->nama_mata_uang.'</option>';
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Nama Bank</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="edit_namabank" id="edit_namabank" role="inputtext" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">No.Rekening</td>
							<td class="form-input">
								<div class="col-md-8">
									<input type="text" class="form-control" name="edit_norekening" id="edit_norekening" role="inputtext" />
								</div>
							</td>
						</tr>
					</table>
					<div class="buttonfooter">
						<button class="btn btn-xs btn-success"  onclick="simpanbankubah()" type="button">
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
			"bLengthChange" : false,
			"aaSorting": [],
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

	function simpanbank(){
  	 	var target  = "<?php echo site_url('setup/simpanbank')?>";
  	 		data 	= $('#formBaru').serialize();


  	 	$.post(target,data,function(e){
  	 		var jojon 	= $.parseJSON(e);
	  	 		jenis 	= $('#edit_jenis').find('option:selected').text();
	  	 		mata 	= $('#edit_matauang').find('option:selected').text();
  	 		//console.log(e);

  	 		if(jojon.flag)
  	 		{
  	 			$('#datatable').dataTable().fnAddData( [
                    'New',
                    jenis+'<input type="hidden" name="id_jenis_rek" id="id_jenis_rek" value="'+jojon.id_jenis_rek+'"/>',
                    mata+'<input type="hidden" name="id_mata_uang" id="id_mata_uang" value="'+jojon.id_mata_uang+'"/>',
                    jojon.nama_bank,
                    jojon.no_rekening,
                    '<input type="hidden" class="form-control" id="id_bank" name="id_bank" role="inputtext" value="'+jojon.newID+'"/><button onclick="editdata(this,'+jojon.newID+')" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button> <button onclick="deletedata(this,'+jojon.newID+')" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>' ] );
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
			var target="<?php echo site_url('setup/hapusbank')?>";
				data={id_bank:IDP};
				row     = $('#datatable').find('#id_bank[value="'+IDP+'"]').parent().parent();
			$.post(target,data,function(e){
				var jojon 	= $.parseJSON(e);

				if(jojon.flag)
				{
					$('#datatable').dataTable().fnDeleteRow(row[0], null, true);
                    alert('Data berhasil dihapus...');
				}
				else
				{
					alert('Data gagal dihapus...');
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
		var id_jenis 	= $(obj).parent().parent().find('td:eq(1) #id_jenis_rek').val();
		 	id_matauang = $(obj).parent().parent().find('td:eq(2) #id_mata_uang').val();
		 	nama 		= $(obj).parent().parent().find('td:eq(3)').text();
		 	rek 		= $(obj).parent().parent().find('td:eq(4)').text();
	    // console.log(telp1); return false;
	    
	    $('#alertMessage').remove();
	    $('#edit_idbank').val(IDP);
	    $('#edit_jenis').val(id_jenis);
	    $('#edit_matauang').val(id_matauang);
	    $('#edit_namabank').val(nama);
	    $('#edit_norekening').val(rek);

	    $("#modalubah").modal("show");

	}

	function simpanbankubah()
	{
		var target = "<?php echo site_url("setup/simpanbankubah")?>";
			data = $("#formUbah").serialize();
			// console.log(data);
			
			$.post(target, data, function(e)
			{
				var jojon 	= $.parseJSON(e);
				var idNye 	= jojon.newID;
				var row 	= $('#datatable').find('#id_bank[value="'+idNye+'"]').parent().parent();
					jenis 	= $('#edit_jenis').find('option:selected').text();
	  	 			mata 	= $('#edit_matauang').find('option:selected').text();
				// console.log(jojon); //return false;
				
				if(jojon.flag)
				{
					$('#datatable').dataTable().fnUpdate([
                        'Edit',
	                    jenis+'<input type="hidden" name="id_jenis_rek" id="id_jenis_rek" value="'+jojon.id_jenis_rek+'"/>',
	                    mata+'<input type="hidden" name="id_mata_uang" id="id_mata_uang" value="'+jojon.id_mata_uang+'"/>',
	                    jojon.nama_bank,
	                    jojon.no_rekening,
	                    '<input type="hidden" class="form-control" id="id_bank" name="id_bank" role="inputtext" value="'+jojon.newID+'"/><button onclick="editdata(this,'+jojon.newID+')" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button> <button onclick="deletedata(this,'+jojon.newID+')" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>' ], row[0] );

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
</script>