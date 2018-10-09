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

<h4>Setting Kategori Item</h4>

<?php  //print_r($_SESSION['IDUnit']);?>
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
					
					
					if((substr($rowAkun['kodePlainText'],0,1) == 4 || substr($rowAkun['kodePlainText'],0,1) == 5) && ($rowAkun['level'] >= 2 && $rowAkun['level'] <= 3 )){

						$checklevel = $this->db->query("SELECT mst_akun.header as header, mst_akun.level as level  FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) = '".$rowAkun['kodePlainText']."'");

						
						$kodeakun = ($rowAkun['level'] >= 3 ) ? $rowAkun['kodePlainText'] : $rowAkun['kodeWithFormat'];
						$namaakun = ($rowAkun['level'] >= 3 ) ? $rowAkun['namaPlainText'] : $rowAkun['namaWithFormat'];

						$background = ($rowAkun['level'] == 2 ) ? "#429489" : "";
						$color = ($rowAkun['level'] == 2 ) ? "#fff" : "#555";


						$namaakun = (@$checklevel->first_row()->level == 3) ? "<b>".$namaakun."</b>" : $namaakun;
						
						if($rowAkun['IDUnit'] == 1)
						{
							$displayUnit = "";
						}
						elseif($rowAkun['IDUnit'] != 1)
						{
							if($rowAkun['IDUnit'] != $_SESSION['IDUnit'])
							{
								$displayUnit = "";
							}
						}
						
						?>
						<tr id="datatable_" style="display:<?php echo $displayUnit?>;border-bottom:1px dashed #429489; background: <?php echo $background?>; color: <?php echo $color?>">

							<td style="padding-right:50px; display:none;" ><?php echo $rowAkun['kodePlainText']?></td>
							<td style="padding-right:50px;"><?php echo get_space($rowAkun['level']); ?><?php echo $namaakun?></td>
							<td style="width: 10%">
								<?php 
								if((@$checklevel->first_row()->level == 3))
								{
									?>
									<button buttonType="actionItems" buttonType="actionItems" class= "btn btn-xs btn-success" onclick="TambahAkunBaru(this, '<?php echo $rowAkun['id'] ?>')" ><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></button>
									<?php
								}
								elseif((@$checklevel->first_row()->level > 3))
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
						$where  = ($_SESSION['IDUnit'] != 1) ? ' and mst_kategori_item.id_unit= "'.$_SESSION['IDUnit'].'"'  : ''; 
						$kategori = $this->db->query("SELECT 
							mst_kategori_item.id_akun as idakun, 
							mst_departemen.nama_departemen as namadept,
							mst_kategori_item.nama_kategori as nama_akun, mst_akun.level as level, CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) as kodeakun
							FROM mst_kategori_item 
							left join mst_akun ON mst_kategori_item.id_akun = mst_akun.id_akun
							LEFT JOIN mst_departemen ON mst_departemen.id_departemen = mst_kategori_item.id_unit
							WHERE mst_kategori_item.id_akun IN (select id_akun from mst_akun WHERE mst_akun.id_induk = '".$rowAkun['id']."') ".$where);
						foreach ($kategori->result_array() as $value) {
							
							?>
							<tr id="datatable_" style="border-bottom:1px dashed #429489;">

								<td style="padding-right:50px; display:none;" >
								<?php echo $value['kodeakun']?>
								</td>
								<td style="padding-right:50px;"><?php echo get_space($value['level']); ?><?php echo $value['nama_akun'] ?></td>
								<td style="width: 10%">
									<!--<button buttonType="actionItems" buttonType="actionItems" class= "btn btn-xs btn-success" onclick="TambahAkunBaru(this, '<?php echo $value['idakun'] ?>')" ><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></button>-->
									<button buttonType="actionItems" class= "btn btn-xs btn-warning" onclick="UbahAkunBaru(this, '<?php echo $value['idakun'] ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></button>
									<button buttonType="actionItems" class= "btn btn-xs btn-danger" onclick="HapusAkun(this, '<?php echo $value['idakun'] ?>');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></button>
								</td>
							</tr>
							<?php
						}
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
				<h5 style="padding: 0px 15px 0px 15px;">Tambah Kategori</h5>
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
										<td class="form-input labelinput">* Nama Kategori</td>
										<td class="form-input">
											<div class="col-md-12">
												<input type="text" class="form-control" name="new_namajudul" id="new_namajudul" role="inputtext" />
												<p id="message"></p>
											</div>
										</td>
									</tr>
									<?php
										if($_SESSION['IDUnit'] != 1)
										{
									?>
										<tr style="display:none;">
											<td><input type="hidden" class="form-control" value="<?php echo $_SESSION['IDUnit']?>" name="new_departemen" id="new_departemen" /></td>
										</tr>
									<?php
										}
										else
										{
									?>
											<tr>
												<td class="form-input labelinput">* Departemen</td>
												<td class="form-input">
													<div class="col-md-12">
														<select class="form-control" name="new_departemen" id="new_departemen">
															<option value="-">:: Pilih Departemen ::</option>
															<?php
															foreach ($dep->result() as $d => $ep) {
																echo "<option value='".$ep->id_departemen."'>".$ep->nama_departemen."</option>";
															}
															?>
														</select>

														<p id="messageDep"></p>
													</div>
												</td>
											</tr>
									<?php
										}
									?>
									<tr>
										<td class="form-input labelinput">* Kelompok Arus Kas</td>
										<td class="form-input">
											<div class="col-md-12">
												<select class="form-control" name="new_aruskas" id="new_aruskas">
													<?php
													foreach ($aruskas->result() as $ak) {
														echo "<option value='".$ak->id_aruskas_kel."'>".$ak->nama."</option>";
													}
													?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td class="form-input labelinput">* Group</td>
										<td class="form-input">
											<div class="col-md-12">
												<select class="form-control" name="new_group" id="new_group">
													<?php
													foreach ($grouplabel->result() as $gl) {
														echo "<option value='".$gl->id_group_label."'>".$gl->nama_group_label."</option>";
													}
													?>
												</select>
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
										<td class="form-input labelinput">* Departemen</td>
										<td class="form-input">
											<div class="col-md-12">
												<select class="form-control" name="edit_departemen" id="edit_departemen">
													<option value="-">:: Pilih Departemen ::</option>
													<?php
													foreach ($dep->result() as $d => $ep) {
														echo "<option value='".$ep->id_departemen."'>".$ep->nama_departemen."</option>";
													}
													?>
												</select>

												<p id="messageDep_"></p>
											</div>
										</td>
									</tr>
									<tr>
										<td class="form-input labelinput">* Kelompok Arus Kas</td>
										<td class="form-input">
											<div class="col-md-12">
												<select class="form-control" name="edit_aruskas" id="edit_aruskas">
													<?php
													foreach ($aruskas->result() as $ak) {
														echo "<option value='".$ak->id_aruskas_kel."'>".$ak->nama."</option>";
													}
													?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td class="form-input labelinput">* Group</td>
										<td class="form-input">
											<div class="col-md-12">
												<select class="form-control" name="edit_group" id="edit_group">
													<?php
													foreach ($grouplabel->result() as $gl) {
														echo "<option value='".$gl->id_group_label."'>".$gl->nama_group_label."</option>";
													}
													?>
												</select>
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
		var loadhtml = "<?php echo site_url("akuntansi/settingkategoriakun")?>";
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

		var param	= "kategori";
		target  = "<?php echo site_url('akuntansi/simpanjudul')?>/"+param;
		data 	= $('#formBaru').serialize();

		if($('#new_namajudul').val() == '')
		{
			$('#message').html("wajib isi");
			return false;
		}
		else if($('#new_departemen').val() == '-')
		{
			$('#messageDep').html("wajib isi");
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


		var target 	= "<?php echo site_url("akuntansi/getdataeditakun")?>";
		data 	= { idakun : IDP };

		$.post(target, data, function(e)
		{
			var jojon = $.parseJSON(e);
			console.log(jojon);

			$('#alertMessage').remove();
			$('#edit_idjudul').val(IDP);	   
			$('#edit_namajudul').val(jojon.nama_akun);
			$('#edit_departemen').val(jojon.id_unit);

			$("#modalubah").modal("show");	
		})

	}

	

	function simpanjudulubah()
	{  
		var param = "kategori";
		target = "<?php echo site_url("akuntansi/simpanjudulubah")?>/"+param;
		data = $("#formUbah").serialize();
			// console.log(data);

			if($('#edit_namajudul').val() == '')
			{
				$('#message_').html("wajib isi");
				return false;
			}
			else if($('#edit_departemen').val() == '-')
			{
				$('#messageDep_').html("wajib isi");
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
			var param = "kategori";
			target = "<?php echo site_url('akuntansi/hapusjudul')?>/"+param;
			data =
			{
				id_judul : IDP
			};
			
			$.post(target,data,function(e){
				var jojon 	= $.parseJSON(e);

				if(jojon.flag)
				{

					alert('Data berhasil dihapus...');
				}
				else
				{
					alert('Kategori ini masih memiliki item');
				}

				$('html, body').css('overflow-y','auto');
				loadGridData();
			});
		}		
	}

</script>

