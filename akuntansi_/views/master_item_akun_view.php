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

<h4>Setting Item</h4>


<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<div class="row" style="border-bottom:0px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
			<table id="example" width="100%" cellpadding="5">
				<?php /* <thead style="background:#429489;">
					<tr>
						<th style="color:#fff;width:15%;">Kode</th>
						<th style="color:#fff;">Nama</th>
						<th style="width:10%;color:#fff;">Aksi</th>
					</tr>
				</thead>
				*/ ?>
				<?php
					
				$kodeakun = json_decode($akun, true);
					
				foreach($kodeakun as $rowAkun){
				
					if((substr($rowAkun['kodePlainText'],0,1) == 4 || substr($rowAkun['kodePlainText'],0,1) == 5) && ($rowAkun['level'] >= 3 && $rowAkun['level'] <= 5 )){
					
					$checklevel = $this->db->query("SELECT mst_akun.header as header, mst_akun.level as level  FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '".$rowAkun['kodePlainText']."'");
					
					
					
					$kodeakun = ($rowAkun['level'] >= 3 ) ? $rowAkun['kodePlainText'] : $rowAkun['kodeWithFormat'];
					$namaakun = ($rowAkun['level'] >= 3 ) ? $rowAkun['namaPlainText'] : $rowAkun['namaWithFormat'];
					
					$background = ($rowAkun['level'] == 3 ) ? "#429489" : "";
					$color = ($rowAkun['level'] == 3 ) ? "#fff" : "#555";
					
					
					$namaakun = (@$checklevel->first_row()->level <= 4) ? "<b>".$namaakun."</b>" : $namaakun;
					
					?>
					<tr id="datatable_" style="border-bottom:1px dashed #429489; background: <?php echo $background?>; color: <?php echo $color?>">
						
						<td style="padding-right:50px; display:none;" ><?php echo $rowAkun['kodePlainText']?></td>
						<td style="padding-right:50px;"><?php echo get_space($rowAkun['level']); ?><?php echo $namaakun?></td>
						<td style="width: 10%">
							<?php 
							if((@$checklevel->first_row()->level == 4))
							{
							?>
								<button buttonType="actionItems" buttonType="actionItems" class= "btn btn-xs btn-success" onclick="TambahAkunBaru(this, '<?php echo $rowAkun['id'] ?>')" ><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></button>
							<?php
							}
							elseif((@$checklevel->first_row()->level > 4))
							{
							?>
								<button buttonType="actionItems" class= "btn btn-xs btn-warning" onclick="UbahAkunBaru(this, '<?php echo $rowAkun['id'] ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></button>
								<button buttonType="actionItems" buttonType="actionItems" class= "btn btn-xs btn-danger" onclick="HapusAkun(this, '<?php echo $rowAkun['id'] ?>')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></button>
								
							<?php
							}
							?>
						</td>
					</tr>
					<?php
					}
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
        <h5 style="padding: 0px 15px 0px 15px;">Tambah Akun</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formBaru" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<input type="hidden" name="simpanId" id="simpanId">
						<input type="hidden" name="simpanIdakun" id="simpanIdakun">
						<tr>
							<td class="form-input labelinput">* Nama Akun</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="new_namajudul" id="new_namajudul" role="inputtext" />
									
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Satuan</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="new_satuan" id="new_satuan" role="inputtext" />
									
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Harga Jual</td>
							<td class="form-input">
								<div class="col-md-12">
									<div class="input-group">
										<span class="input-group-addon">Rp.</span>
										<input type="text" style="text-align:right;" onkeyup="formatNumber(this)" class="form-control" name="new_jual" id="new_jual" role="inputtext" />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Harga Beli</td>
							<td class="form-input">
								<div class="col-md-12">
									<div class="input-group">
										<span class="input-group-addon">Rp.</span>
										<input type="text" style="text-align:right;" onkeyup="formatNumber(this)" class="form-control" name="new_beli" id="new_beli" role="inputtext" />
									</div>
								</div>
							</td>
						</tr>
						
					</table>
					<div class="buttonfooter">
						<button class="btn btn-xs btn-success"  onclick="simpanjudul(simpanId.value)" type="button">
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
        <h5 style="padding: 0px 15px 0px 15px;">Kode Akun</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formUbah" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<input type="hidden" name="edit_idjudul" id="edit_idjudul" />
						<tr>
							<td class="form-input labelinput">Nama Akun</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="edit_namajudul" id="edit_namajudul" role="inputtext" />
									<p id="message_"></p>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Satuan</td>
							<td class="form-input">
								<div class="col-md-12">
									<input type="text" class="form-control" name="edit_satuan" id="edit_satuan" role="inputtext" />
									
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Harga Jual</td>
							<td class="form-input">
								<div class="col-md-12">
									<div class="input-group">
										<span class="input-group-addon">Rp.</span>
										<input type="text" style="text-align:right;" onkeyup="formatNumber(this)" class="form-control" name="edit_jual" id="edit_jual" role="inputtext" />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td class="form-input labelinput">Harga Beli</td>
							<td class="form-input">
								<div class="col-md-12">
									<div class="input-group">
										<span class="input-group-addon">Rp.</span>
										<input type="text" style="text-align:right;" onkeyup="formatNumber(this)" class="form-control" name="edit_beli" id="edit_beli" role="inputtext" />
									</div>
								</div>
							</td>
						</tr>
						
					</table>
					<div class="buttonfooter">
						<button class="btn btn-xs btn-success"  onclick="simpanjudulubah()" type="button">
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
    	var loadhtml = "<?php echo site_url("akuntansi/settingitemakun")?>";
    	$("#main-body").load(loadhtml);  
  	}

function batal()
	{
		$("#modalForm").modal("hide");
		$('#message').hide();
	}

function TambahAkunBaru(ref, IDP)
	{
		

		$("#formBaru")[0].reset();

		$("#modalForm").modal("show"); 

		$("#simpanId").val($(ref).parent().parent().index());

		$("#simpanIdakun").val(IDP);

	}


function simpanjudul(url){

  	 	var param	= "item";
			target  = "<?php echo site_url('akuntansi/simpanjudul')?>/"+param;
  	 		data 	= $('#formBaru').serialize();

  	 		if($('#new_namajudul').val() == '')
	  	 	{
	  	 		$('#message').html("wajib isi");
	  	 		return false;
	  	 	}

		$.post(target,data,function(e){
  	 		var jojon 	= $.parseJSON(e);
  	 		//console.log(e);

  	 		var url_ = parseInt(url) + 1;

  	 		if(jojon.flag)
  	 		{
  	 			var table = document.getElementById('example');
  	 			var row = table.insertRow(url_);

  	 			var colKode = row.insertCell(0);
  	 			colKode.innerHTML = jojon.nama_akun;


							
					
  	 			alert('Data item berhasil disimpan...');
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

	

	function UbahAkunBaru(obj,IDP)
	{
		$('#formUbah')[0].reset();

			
		var target	= "<?php echo site_url("akuntansi/getedititemakun")?>";
			data = {
				idx : IDP
			}
	    
	    // console.log(telp1); return false;
	    
	    $('#alertMessage').remove();

	    $.post(target, data, function(e){
			
			
			var json = $.parseJSON(e);
			console.log(json);
			$('#edit_idjudul').val(IDP);	   
			$('#edit_namajudul').val(json.namaitem);
			$('#edit_satuan').val(json.satuan);
			$('#edit_jual').val(json.hargajual);
			$('#edit_beli').val(json.hargabeli);
			

			$("#modalubah").modal("show");
		
		});



	}

	

	function simpanjudulubah()
	{  
		var param = "akun";
			target = "<?php echo site_url("akuntansi/simpanjudulubah")?>/"+param;
			data = $("#formUbah").serialize();
			// console.log(data);

			if($('#edit_namajudul').val() == '')
	  	 	{
	  	 		$('#message_').html("wajib isi");
	  	 		return false;
	  	 	}
			
			$.post(target,data,function(e){
  	 		var jojon 	= $.parseJSON(e);
  	 		//console.log(e);

  	 		if(jojon.flag)
  	 		{
  	 			
  	 			alert('Data item berhasil disimpan...');





  	 		}
  	 		else
  	 		{
  	 			alert('Data gagal disimpan. Periksa kembali input Anda...');
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
			var param = "akun";
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
	  			loadGridData();
	   		});
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

