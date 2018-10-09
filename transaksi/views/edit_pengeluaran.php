<?php
						
	$tanggal = date("Y-m-d", strtotime($pengeluaran->first_row()->tanggal));
	
	$tanggal = explode("-", $tanggal);
	$hari = $tanggal[0];
	$bulan = $tanggal[1];
	$tahun = $tanggal[2];
	
	$notransaksi = $pengeluaran->first_row()->nomor_transaksi;
	$notrans = explode("/", $notransaksi);
	
	$units = $notrans[0];
	$banks = $notrans[1];
	$bulan = $notrans[2];
	$tahun = $notrans[3];
	$nomorurut = $notrans[4];
	
?>

<style>
	.table tbody > tr > td.form-input{
		padding:3px 3px 3px 5px !important;
	}
	
	input[role='inputtext']{
		padding:5px !important;
		height:30px;
	}
	
	span[role='glyphicongroup']{
		padding:0px 12px !important;
		height:25px;
	}
	.kana {
		text-align: right;
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
	.dudu{
		text-align: right;
		
	}
	#duit{
		font-size: 30px;
		text-align: right;
	}
	.hiddenrow
	{
		visibility: hidden;
	}
</style>
<?php 
$trx 	= $pengeluaran->row();
$det 	= $pengeluaran->result();
?>

<h4>Edit Pengeluaran</h4>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		
		<div class="col-md-12">
			<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
				<div class="dudu">
					<div class="btn">
						<button onclick="daftar()" class="btn btn-sm btn-success">
							<span class="glyphicon glyphicon-list"></span>
							Daftar BKK 
						</button>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label">Metode Bayar</label>
							<div class="col-md-8">
								<select onchange="pilihkasbank(this)" class="form-control" name="id_metodebayar">
									<option value="-">:: Pilih Metode Bayar ::</option>
									<?php
									foreach($metodebayar->result() as $row){
										?>
										<option value="<?php echo $row->id_metode_bayar?>"><?php echo $row->nama_metode_bayar?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div id="kasbankx">
							<div class="form-group" id="changebank" >
								<label class="col-md-3 control-label">Bank</label>
								<div class="col-md-8">
									<select class="form-control" name="id_bank">
										<option value="-">:: Pilih Bank ::</option>
										<?php
										foreach($bank->result() as $row){
											?>
											<option value="<?php echo $row->id_bank?>"><?php echo $row->nama_bank?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">No. Transaksi</label>
							<div class="col-md-8">
								<input type="hidden" class="form-control" value="<?php echo $pengeluaran->first_row()->nama_departemen?>" name="namaunit"/>
								<input type="hidden" class="form-control" value="<?php echo $pengeluaran->first_row()->nama_bank?>" name="namabank"/>
								<input type="hidden" class="form-control" value="<?php echo $bulan?>" name="bulan"/>
								<input type="hidden" class="form-control" value="<?php echo $tahun?>" name="tahun"/>
								<div class="input-group">
									<span class="input-group-addon" id="namaunit_text"><?php echo $units?></span>
									<span class="input-group-addon" id="namabank_text"><?php echo $banks?></span>
									<span class="input-group-addon" id="bulan_text"><?php echo $bulan?></span>
									<span class="input-group-addon" id="tahun_text"><?php echo $tahun?></span>
									<input type="text" class="form-control" value="<?php echo $nomorurut?>" name="no_transaksi"/>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label"></label>
							<div class="col-md-8">
								<span>
									<b>NB :</b> Silahkan Klik tombol <button type="button" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus-sign"></span></button> pada pojok kanan bawah 
									Untuk Menambahkan item list lebih dari satu.
								</span>
							</div>
						</div>
						
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label">Tanggal Transaksi</label>
							<div class="col-md-8">
								<div class="input-group">
									<input type="text" value="<?php echo date("d-m-Y", strtotime($trx->tanggal))?>" readonly role="tanggal" class="form-control" name="tgl_transaksi"/>
									<span class="input-group-addon glyphicon glyphicon-calendar"></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo $setting->first_row()->label_pemasok?></label>
							<div class="col-md-8">
								<div class="input-group">
									<input type="hidden" name="idpembelian" value="<?php echo $trx->id_pembelian ?>">
									<input type="text" id="nama_pemasok" value="<?php echo $trx->nama_pemasok ?>" readonly class="form-control"/>
									<input type="hidden" readonly class="form-control" id="id_pemasok" name="id_pemasok" value="<?php echo $trx->id_pemasok ?>" />
									<span onclick="openpemasok()" class="input-group-addon glyphicon glyphicon-search"></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Keterangan</label>
							<div class="col-md-8">
								<textarea class="form-control" name="keterangan" rows="6"><?php echo $trx->deskripsi?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<h4>Detail Transaksi</h4>
			<div class="row">
				<div class="col-md-12">
					<p id="duit">Rp.0</p>
					<form id="formitems">
						<table class="table table-bordered">
							<tr style="background:#429489;">
								<th style="color:#fff;" width="1%">No</th>
								<th style="color:#fff;" width="5%"><input type="checkbox" style="display:block;"></th>
								<th style="color:#fff;">Nama Item</th>
								<th style="color:#fff; width:10%;">Jumlah Item</th>
								<th style="color:#fff;">Harga Item</th>
								<th style="color:#fff;">Potongan</th>
								<th style="color:#fff;">Memo</th>
								<th style="color:#fff;">Subtotal</th>
								<th style="color:#fff;background:#fff; width:5%;"><button type="button" onclick="tambahrow()" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus-sign"></span></button></th>
							</tr>
							<tbody id="dataitem">
								<?php 
								$seq = 1;
								foreach ($det as $d => $et) 
									{ ?>
								<tr>
									<td><?php echo $seq?></td>
									<td><input type="checkbox" style="display:block;"></td>
									<td>
										<div class="input-group">
											<input type="text" readonly role="inputtext" class="form-control" value="<?php echo $et->nama_item ?>" />
											<input readonly type="hidden" role="inputtext" class="form-control" name="item[]" value="<?php echo $et->id_item ?>" />
											<span onclick="openitem(this)" class="input-group-addon glyphicon glyphicon-search"></span>
										</div>
									</td>
									<td><input type="number" value="1" onblur="akumulasi(this)" role="inputtext" class="form-control" name="jmlitem[]" value="<?php echo $et->jumlah ?>" /></td>
									<td>
											<div class="input-group">
												<span class="input-group-addon">Rp. </span>
												<input type="text" onblur="akumulasi2(this)" onkeyup="formatNumber(this)" style="text-align:right;" role="inputtext" class="form-control" value="<?php echo number_format($et->harga)?>" name="harga[]" />

											</div>
										</td>
									<td>
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input type="text" style="text-align:right;" value="<?php echo number_format($et->potongan) ?>" onkeyup="formatNumber(this)" role="inputtext" class="form-control" name="potongan[]" />
										</div>
									</td>
									<td><input type="text" role="inputtext" class="form-control" name="memo[]" value="<?php echo $et->memo ?>" /></td>
									<?php 
									$rebus 	= ($et->jumlah * $et->harga) - $et->potongan;
									?>
									<td>
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input type="text" style="text-align:right;" value="<?php echo number_format($rebus) ?>"  readonly="readonly" onkeyup="formatNumber(this)" role="inputtext" class="form-control" name="subtotal[]" />
										</div>
									</td>
									<td>
										<button type="button" onclick="removed(this)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
										
									</td>
								</tr>

								<?php
								$seq++;
							}
							?>

						</tbody>
					</table>
				</form>
				<div class="kana">
					<button type="button" onclick="simpandata()" class="btn btn-sm btn-default">
						<span class="glyphicon glyphicon-save"></span> Simpan Data
					</button>
					<button type="button" onclick="cetaksimpan()" class="btn btn-sm btn-default">
						<span class="glyphicon glyphicon-save"></span> Cetak & Simpan Data
					</button>
				</div>
				<div class="hiddenrow">
					<div class="col-md-6">
						<div class="row">
							<div class="form-horizontal">
								<div class="form-group">
									<label class="control-label col-md-7">Total Bayar : </label>
									<div class="col-md-5">
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input style="text-align:right" type="text" id="subtotal" readonly class="form-control" class="inputtext"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-7">Dibayar : </label>
									<div class="col-md-5">
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input type="text" style="text-align:right" onblur="getsisa(this)" onkeyup="formatNumber(this)" id="bayar"  class="form-control" class="inputtext"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-7">Sisa Bayar : </label>
									<div class="col-md-5">
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input type="text" style="text-align:right" id="sisabayar"  readonly class="form-control" class="inputtext"/>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="modalPemasok">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 10px 0px 0px !important;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
				<h5 style="padding: 0px 15px 0px 15px;">Data <?php echo $setting->first_row()->label_pemasok?></h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div style="background:#429489;min-height:0px;" class="widget-container padded">
							<div class="xx">
								<form class="up" role="search" action="" method="post">
									<div class="input-group">
										<input type="text" id="s_cari" class="form-control" name="kata_kunci" placeholder="cari pemasok...">
										<span class="input-group-btn">
											<button onclick="caridata()" type="button" value="cari" class="btn btn-primary">Cari</button>
										</span>
									</div>
								</form>
							</div>
							<table class="table table-bordered table-striped" style="background:#fff;">
								<tr>
									<th>#</th>
									<th>Nama <?php echo $setting->first_row()->label_pemasok?></th>
									<th>Alamat</th>
									<th>Aksi</th>
								</tr>
								<tbody id="listpemasok">

								</tbody>
							</table>
						</div>
						
						<button type="button" onclick="tambahpemasok()" class="btn btn-sm btn-default" style="margin-top:10px;">
							<span class="glyphicon glyphicon-plus-sign"></span>
							tambah <?php echo $setting->first_row()->label_pemasok?>
						</button>
						
					</div>
				</div>
			</div>
			<div class="modal-footer customefooter" style="text-align:left !important;">
				<ul>
					Note :
					<li><?php echo $setting->first_row()->label_pemasok?> dapat dipilih lebih dari satu, kemudian klik tombol sebelah kanan.</li>
					<li>Tombol "tambah <?php echo $setting->first_row()->label_pemasok?>" untuk input data <?php echo $setting->first_row()->label_pemasok?> bila tidak ada pada list.</li>
				</ul>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalTambahPemasok">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 10px 0px 0px !important;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
				<h5 style="padding: 0px 15px 0px 15px;">Tambah Data <?php echo $setting->first_row()->label_pemasok?></h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div style="background:#429489;min-height:0px;" class="widget-container padded">
							<form id="formtambahpemasok">
								<table class="table table-bordered table-striped" style="background:#fff;">
									<tr>
										<td>Nama <?php echo $setting->first_row()->label_pemasok?></td>
										<td><input type="text" class="form-control" name="nama" role="inputtext" /></td>
									</tr>
										<tr>
										<td>Unit</td>
										<td class="form-input">
											<select class="form-control" name="new_unit">
												<option>:: Pilih Unit ::</option>
												<?php
												if($_SESSION['IDUnit'] == 1)
												{
													foreach($unit->result() as $row)
													{
														?>
														<option value="<?php echo $row->id_departemen?>"><?php echo $row->nama_departemen?></option>
														<?php
													}
												}
												else
												{
													foreach($unit->result() as $row)
													{
														if($_SESSION['IDUnit'] == $row->id_departemen)
														{
															?>
															<option <?php if($_SESSION['IDUnit'] == $row->id_departemen){ echo "selected='selected'";} ?> value="<?php echo $row->id_departemen?>"><?php echo $row->nama_departemen?></option>
															<?php
														}
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Alamat <?php echo $setting->first_row()->label_pemasok?></td>
										<td>
											<textarea class="form-control" name="alamat" rows="5"></textarea>
										</td>
									</tr>
								
									<tr>
										<td>Telp 1</td>
										<td><input type="text" class="form-control" name="telp1" role="inputtext" /></td>
									</tr>
									<tr>
										<td>Telp 2</td>
										<td><input type="text" class="form-control" name="telp2" role="inputtext" /></td>
									</tr>
									<tr>
										<td>Email</td>
										<td><input type="text" class="form-control" name="email" role="inputtext" /></td>
									</tr>
								</table>
							</form>
						</div>

						<button id="btnsimpan" type="button" onclick="simpanpemasok()" class="btn btn-sm btn-default" style="margin-top:10px;">
							<span class="glyphicon glyphicon-plus-sign"></span>
							simpan <?php echo $setting->first_row()->label_pemasok?>
						</button>

						<button id="btnloading" type="button" class="btn btn-sm btn-default" style="margin-top:10px;display:none;">
							<span class="glyphicon glyphicon-plus-sign"></span>
							Prosessing . . . .
						</button>
					</div>
				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modalItem">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 10px 0px 0px !important;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
				<h5 style="padding: 0px 15px 0px 15px;">Data Item List</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div style="background:#429489;min-height:0px;" class="widget-container padded">

							<input type="hidden" id="parent" />
							<form id="s_form">
								<div class="form-group">
									<div class="input-group">
										<input class="form-control" name="s_box" placeholder="Cari Data Item ... (Tekan Enter)" />
										<span onclick="searchitem()" class="input-group-addon glyphicon glyphicon-search"></span>
									</div>
								</div>
							</form>
							<table class="table table-bordered table-striped" style="background:#fff;">
								<tr>
									<th>Nama Item</th>
									<th>Harga</th>
									<th>Unit</th>
									<th>Kategori Item</th>
									<th>Aksi</th>
								</tr>
								<tbody id="listitem">

								</tbody>
							</table>
						</div>
						<!--
						<button type="button" onclick="tambahitem()" class="btn btn-sm btn-default" style="margin-top:10px;">
							<span class="glyphicon glyphicon-plus-sign"></span>
							tambah item
						</button>
						-->
					</div>
				</div>
			</div>
			<div class="modal-footer customefooter" style="text-align:left !important;">
				<ul>
					Note :
					<li>Item dapat dipilih lebih dari satu, kemudian klik tombol sebelah kanan.</li>
					<li>Tombol "tambah item" untuk input data pemasok bila tidak ada pada list.</li>
				</ul>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modalTambahItem">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 10px 0px 0px !important;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
				<h5 style="padding: 0px 15px 0px 15px;">Tambah Data</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div style="background:#429489;min-height:0px;" class="widget-container padded">
							<form id="formtambahitem">
								<table class="table table-bordered table-striped" style="background:#fff;">
									<tr>
										<td>Nama Item</td>
										<td><input type="text" class="form-control" name="namaitem" role="inputtext" /></td>
									</tr>
									<tr>
										<td>Tipe Item</td>
										<td>
											<select class="form-control" name="idtipeitem">
												<option value="-">:: Pilih Tipe Item ::</option>
												<?php
												$item = $this->db->query("SELECT * FROM ref_tipe_item");

												foreach($item->result() as $row)
												{
													?>
													<option value="<?php echo $row->id_ref_tipe_item?>"><?php echo $row->tipe_item?></option>
													<?php
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Kategori Item</td>
										<td>
											<select class="form-control" name="idkategori">
												<option value="-">:: Pilih Kategori Item ::</option>
												<?php
												$kategori = $this->db->query("SELECT * FROM mst_kategori_item");

												foreach($kategori->result() as $row)
												{
													?>
													<option value="<?php echo $row->id_kategori_item?>"><?php echo $row->nama_kategori?></option>
													<?php
												}
												?>
											</select>
										</td>
									</tr>

									<tr>
										<td>Satuan</td>
										<td><input type="text" class="form-control" name="satuan" role="inputtext" /></td>
									</tr>
									<tr>
										<td>Harga Beli</td>
										<td>
											<div class="input-group">
												<span class="input-group-addon">Rp. </span>
												<input style="text-align:right;" type="text" onkeyup="formatNumber(this)" class="form-control" name="hargabeli" role="inputtext" />
											</div>
										</td>
									</tr>
									<tr>
										<td>Harga Jual</td>
										<td>
											<div class="input-group">
												<span class="input-group-addon">Rp. </span>
												<input style="text-align:right;" type="text" onkeyup="formatNumber(this)" class="form-control" name="hargajual" role="inputtext" />
											</div>
										</td>
									</tr>
								</table>
							</form>
						</div>

						<button type="button" onclick="simpanitem()" class="btn btn-sm btn-default" style="margin-top:10px;">
							<span class="glyphicon glyphicon-plus-sign"></span>
							Simpan Item
						</button>

					</div>
				</div>
			</div>
			<div class="modal-footer customefooter" style="text-align:left !important;">
				<ul>
					Note :
					<li>Item dapat dipilih lebih dari satu, kemudian klik tombol sebelah kanan.</li>
					<li>Tombol "tambah item" untuk input data pemasok bila tidak ada pada list.</li>
				</ul>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
	
	$(document).ready(function(){
		var metod = "<?php echo $trx->id_metode_bayar ?>";
		bank  = "<?php echo $trx->id_bank ?>";

		$("input[role='tanggal']").datepicker({
			format : "dd-mm-yyyy",
			autoclose : true
		})
		.on('changeDate', function(e){
			
			var kasbank = $("#idbank option:selected").val();
			var tanggal = $(e.currentTarget).val().split('-');
				hari = tanggal[0];
				bulan = tanggal[1];
				tahun = tanggal[2];
				idpenjualan = $("#id_penjualan").val();
			
			convertbulantoromawi(bulan);
			//getautonumberchange(idpenjualan, kasbank, bulan, tahun);
			
		});

		$("select[name='id_metodebayar']").val(metod);
		$("select[name='id_bank']").val(bank);

		
		subutotal();

		$("#s_form").submit(function(e){
			e.preventDefault();
			var target = "<?php echo site_url("transaksi/cariitempengeluaran")?>";
				data = {
					item : $("input[name='s_box']").val()
				}
				
			$.post(target, data, function(e){
				var json = $.parseJSON(e);
				fillgriditem(json)
			});
		});
		
	});
	
	function convertbulantoromawi(bulan)
	{
		var target = "<?php echo site_url("transaksi/convertbulan")?>";
			data = {
				bulan : bulan
			}
			
		$.post(target, data, function(e){
			
			$("span#bulan_text").text(e);
			$("input[name='bulan']").val(e);
		});
	}

	function searchitem()
	{
		var target = "<?php echo site_url("transaksi/cariitempengeluaran")?>";
			data = {
				item : $("input[name='s_box']").val()
			}
			
		$.post(target, data, function(e){
			var json = $.parseJSON(e);
			fillgriditem(json)
		});
	}
	
	function tambahrow()
	{
		var table = document.getElementById("dataitem");
		
		var row = table.insertRow();
		
		var Seq = "-";
		
		var Checkedbox = "<input type='checkbox' style='display:block;' />";
		
		var Item = "<div class='input-group'>";
		Item += "<input readonly class='form-control' role='inputtext' />";
		Item += "<input readonly type='hidden' class='form-control' role='inputtext' name='item[]' />";
		Item += "<span onclick='openitem(this)' class='input-group-addon glyphicon glyphicon-search'></span>";
		Item += "</div>";

		var JumlahItem = "<input type='number' value='1' onblur='akumulasi(this)' class='form-control' role='inputtext' name='jmlitem[]' />";
		
		var Harga = "<div class='input-group'>";
		Harga += "<span class='input-group-addon'>Rp. </span>";
		Harga += "<input style='text-align:right;' class='form-control' role='inputtext' name='harga[]' />";
		Harga += "</div>";

		var Potongan = "<div class='input-group'>";
		Potongan += "<span class='input-group-addon'>Rp. </span>";
		Potongan += "<input style='text-align:right;' class='form-control' onblur='akumulasi2(this)' role='inputtext' value='0,00' onkeyup='formatNumber(this)' name='potongan[]' />";
		Potongan += "</div>";

		var Memo = "<input type='text'  class='form-control' role='inputtext' name='memo[]' />";

		var Total = "<div class='input-group'>";
		Total += "<span class='input-group-addon'>Rp. </span>";
		Total += "<input style='text-align:right;' readonly class='form-control' role='inputtext' value='0,00' onkeyup='formatNumber(this)' name='potongan[]' />";
		Total += "</div>";

		var ColSeq = row.insertCell(0);
		var ColCheckbox = row.insertCell(1);
		var ColItem = row.insertCell(2);
		var ColJumlahItem = row.insertCell(3);
		var ColHarga = row.insertCell(4);
		var ColPotongan = row.insertCell(5);
		var ColMemo = row.insertCell(6);
		var ColTotal = row.insertCell(7);
		var ColAksi = row.insertCell(8);

		ColSeq.innerHTML = Seq;
		ColCheckbox.innerHTML = Checkedbox;
		ColItem.innerHTML = Item;
		ColJumlahItem.innerHTML = JumlahItem;
		ColHarga.innerHTML = Harga;
		ColPotongan.innerHTML = Potongan;
		ColMemo.innerHTML = Memo;
		ColTotal.innerHTML = Total;
		ColAksi.innerHTML = "<button type='button' onclick='removed(this)' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span></button>";
		
	}
	
	function removed(obj)
	{
		$(obj).parent().parent().remove();
	}
	
	function openpemasok()
	{
		var target = "<?php echo site_url("transaksi/getpemasok")?>";

		$.post(target, data, function(e){

			var json = $.parseJSON(e);
			
			fillgridpemasok(json);
			$("#modalPemasok").modal("show");
		});
	}
	
	function fillgridpemasok(json)
	{
		console.log(json);
		//return false;
		
		var table = document.getElementById("listpemasok");
		table.innerHTML = "";
		
		for(i = 0; i < json.pemasok.length; i++)
		{
			var row = table.insertRow();
			
			var seq 		 = eval(i) + eval(1);
			var idpemasok  = json.pemasok[i].idpemasok;
			var alamat 		 = json.pemasok[i].alamatpemasok;
			var namapelangan = json.pemasok[i].namapemasok;
			
			var ColSeq		 		= row.insertCell(0);
			var ColNamaPemasok		= row.insertCell(1);
			var ColAlamat	 		= row.insertCell(2);
			var ColAksi		 		= row.insertCell(3);
			
			ColSeq.innerHTML 			= seq+"<input type='hidden' value='"+idpemasok+"' />";
			ColNamaPemasok.innerHTML  = namapelangan;
			ColAlamat.innerHTML 		= alamat;
			ColAksi.innerHTML 		 	= "<button type='button' onclick='pilihpemasok(this)' class='btn btn-xs btn-default'><span class='glyphicon glyphicon-plus-sign'></span></button>";
		}
	}
	
	function tambahpemasok()
	{
		$("#modalPemasok").modal("hide");
		$("#modalTambahPemasok").modal("show");
	}
	
	function simpanpemasok()
	{
		$("#btnsimpan").hide();
		$("#btnloading").show();
		
		var target = "<?php echo site_url("transaksi/simpandatapemasok")?>";
		data = $("#formtambahpemasok").serialize();

		$.post(target, data, function(e){
			
			$("#modalTambahPemasok").modal("hide");
		});
	}
	
	function pilihpemasok(obj)
	{
		var idpemasok = $(obj).parent().parent().find("td").eq(0).find("input:first").val();
		namapemasok = $(obj).parent().parent().find("td").eq(1).text();

		$("#nama_pemasok").val(namapemasok);
		$("#id_pemasok").val(idpemasok);
		
		$("#modalPemasok").modal("hide");
	}
	
	function getnumbertransaksi()
	{
		var target = "<?php echo site_url("transaksi/getnumbertransaksipengeluaran")?>";

		$.post(target,"", function(e){
			
			$("input[name='no_transaksi']").val(e);
		});
	}
	
	function changemetode(obj)
	{
		var nilai = $(obj).val();
		
		var display = (nilai == 1) ? "none" : "";
		
		if(nilai == 1)
		{
			$("select[name='id_bank']").val(1);
		}
		
		$("#changebank").css({"display" : display});
	}
	
	function openitem(obj)
	{
		var target = "<?php echo site_url("transaksi/getitemlistbarang")?>";
		index = $(obj).parent().parent().parent().index();


		
		$.post(target,"",function(e){

			var json = $.parseJSON(e);
			
			fillgriditem(json);
			
			$("#parent").val(index);
			
			$("#modalItem").modal("show");

		});
	}
	
	function fillgriditem(json)
	{
		var table = document.getElementById("listitem");

		table.innerHTML = "";

		if(json.flag)
		{
			for(i = 0; i < json.item.length; i++)
			{
				var row = table.insertRow();
				
				var IDItem 		= json.item[i].iditem;
				var NamaItem 	= json.item[i].namaitem;
				var HargaItem 	= json.item[i].hargaitem;
				var Satuan 		= json.item[i].satuan;
				var Kategori 		= json.item[i].kategori;
				
				var ColNamaItem  = row.insertCell(0);
				var ColHargaItem = row.insertCell(1);
				var ColSatuan	 = row.insertCell(2);
				var ColKategori	 = row.insertCell(3);
				var ColAksi 	 = row.insertCell(4);
				
				ColHargaItem.style.textAlign = "right";

				ColNamaItem.innerHTML = NamaItem+"<input type='hidden' value='"+IDItem+"' />";
				ColHargaItem.innerHTML = HargaItem;
				ColSatuan.innerHTML = Satuan;
				ColKategori.innerHTML = Kategori;
				ColAksi.innerHTML = "<button onclick='pickitem(this)' type='button' class='btn btn-xs btn-default'><span class='glyphicon glyphicon-plus-sign'></span></button>";
			}	
		}
	}
	
	function pickitem(obj)
	{
		var index = $("#parent").val();
		
		var	IDItem = $(obj).parent().parent().find("td").eq(0).find("input:first").val();
		var	NamaItem = $(obj).parent().parent().find("td").eq(0).text();
		var	Harga = $(obj).parent().parent().find("td").eq(1).text();

		var table = $("tbody#dataitem").find("tr").eq(index);
		
		$(table).find("td").eq(2).find("input:eq(0)").val(NamaItem);
		$(table).find("td").eq(2).find("input:eq(1)").val(IDItem);
		
		$(table).find("td").eq(4).find("input:first").val(Harga);
		
		$("#modalItem").modal("hide");
	}
	
	function tambahitem()
	{
		$("#modalItem").modal("hide");
		$("#modalTambahItem").modal("show");
	}
	
	function simpanitem()
	{
		var target = "<?php echo site_url("transaksi/simpanitem")?>";
		
		data = $("#formtambahitem").serialize();

		$.post(target, data, function(e){
			console.log(e);
			
			$("#modalTambahItem").modal("hide");
		});
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

    function simpandata()
    {
    	var metodebayar = $("select[name='id_metodebayar']").val();
    	var bank = $("select[name='id_bank']").val();
    	var ket = $("textarea[name='keterangan']").val();
    	var table = $("tbody#dataitem").find("tr");

    	var flag = false;

    	if(metodebayar == "-" || bank == "-" || ket == "")
    	{

    		flag = true;
    	}

    	$.each(table, function(i, v){

    		var iditem = $(this).find("td:eq(2)").find("input:last").val();
    		var nominal = $(this).find("td:eq(4)").find("input:first").val();

    		if(iditem == "" || nominal == "0" || nominal == "")
    		{
    			flag = true;
    		}

    	});

    	if(flag)
    	{
    		alert("Silahkan cek kembali inputan anda.");
    		return false;
    	}

    	$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");

    	var target = "<?php echo site_url("transaksi/simpandatapengeluaranubah")?>"
    	data = {
    		id_pembelian : $("input[name='idpembelian']").val(),
    		idpemasok : $("#id_pemasok").val(),
			namaunit : $("input[name='namaunit']").val(),
    		namabank : $("input[name='namabank']").val(),
    		bulan : $("input[name='bulan']").val(),
    		tahun : $("input[name='tahun']").val(),
    		notransaksi : $("input[name='no_transaksi']").val(),
    		tgltransaksi : $("input[name='tgl_transaksi']").val(),
    		idmetodebayar : $("select[name='id_metodebayar']").val(),
    		idbank : $("select[name='id_bank']").val(),
    		keterangan : $("textarea[name='keterangan']").val(),
    		serialize : $("#formitems").serialize()
    	}

    	$.post(target, data, function(e){
    		console.log(e);

    		var html = "<?php echo site_url("transaksi/pengeluaran")?>";

    		$("#main-body").load(html);
    		$("#backDropOverlay").remove();

    	});
    }


    function akumulasi(obj)
    {
    	var jumlahitem  = $(obj).parent().parent().find("td").eq(3).find("input:first").val();
    	var harga		= $(obj).parent().parent().find("td").eq(4).find("input:first").val();
    	var potongan	= $(obj).parent().parent().find("td").eq(5).find("input:first").val();


    	harga = harga.replace(/,/ig, "");
    	potongan = potongan.replace(/,/ig, "");

    	var total = (jumlahitem * harga) - potongan;

    	var total = total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

    	$(obj).parent().parent().find("td").eq(7).find("input:first").val(total);

    	subutotal();
    }

    function akumulasi2(obj)
    {
    	var jumlahitem  = $(obj).parent().parent().parent().find("td").eq(3).find("input:first").val();
    	var harga		= $(obj).parent().parent().parent().find("td").eq(4).find("input:first").val();
    	var potongan	= $(obj).parent().parent().parent().find("td").eq(5).find("input:first").val();

    	harga = harga.replace(/,/ig, "");
    	potongan = potongan.replace(/,/ig, "");

    	var total = (jumlahitem * harga) - potongan;

    	var total = total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    	console.log(total);

    	$(obj).parent().parent().parent().find("td").eq(7).find("input:first").val(total);

    	subutotal();

    }

    function subutotal()
    {
    	var table = $("tbody#dataitem").find("tr");
    	subtotal = 0;

    	$.each(table, function(i, v){

    		var total = $(v).find("td:eq(7)").find("input").val().replace(/,/ig, "");

    		subtotal +=+ total;
			//console.log($(v).find("td:eq(6)").find("input").val());
		});

    	//console.log(subtotal);
    	var subtotals = subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    	$("#subtotal").val(subtotals);

    	document.getElementById("duit").innerHTML = 'RP'+' '+subtotals;
    }

    function getsisa(obj)
    {
    	var subtotal = $("#subtotal").val().replace(/,/ig, "");
    	var bayar = $(obj).val().replace(/,/ig, "");
    	var sisa = bayar - subtotal;

    	var sisa = sisa.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    	$("#sisabayar").val(sisa);
    }

    function cetaksimpan()
    {
    	var target = "<?php echo site_url("transaksi/simpancetakpengeluaranubah")?>";

    	$.ajax({

    		url : target,
    		dataType : "json",
    		method : "POST",
    		async: false,
    		data : {
    			id_pembelian : $("input[name='idpembelian']").val(),
    			idpemasok : $("#id_pemasok").val(),
				namaunit : $("input[name='namaunit']").val(),
				namabank : $("input[name='namabank']").val(),
				bulan : $("input[name='bulan']").val(),
				tahun : $("input[name='tahun']").val(),
    			notransaksi : $("input[name='no_transaksi']").val(),
    			tgltransaksi : $("input[name='tgl_transaksi']").val(),
    			idmetodebayar : $("select[name='id_metodebayar']").val(),
    			idbank : $("select[name='id_bank']").val(),
    			keterangan : $("textarea[name='keterangan']").val(),
    			serialize : $("#formitems").serialize()
    		},
    		success(result,status,xhr){
    			console.log(result);

    			window.open(result.url);
    		},
    		error(xhr,status,error)
    		{
    			console.log(xhr.responseText);
    		}
    	});
    }

    function pilihkasbank(obj){
    	var nilai = $(obj).val();
    	var target ="<?php echo site_url('transaksi/pilihkasbank')?>";
    	var data = {
    		id : nilai
    	}

    	$.post(target, data, function(e){
    		console.log(e);
    		$('#kasbankx').html(e);
    	});
    }
	
	function getkasbank() 
	{
    	var x = $("#idbank option:selected").text();
    	var no =  $("input[name='namabank']").val(x);
		
		var tanggal = $("input[name='tgl_transaksi']").val();
		var tanggal = tanggal.split('-');
			hari = tanggal[0];
			bulan = tanggal[1];
			tahun = tanggal[2];
			idpenjualan = $("#id_penjualan").val();
				
		var kasbank = $("#idbank option:selected").val();
		
		
		var text = $("span#namabank_text").text(x);
		
		//getautonumberchange(idpenjualan, kasbank, bulan, tahun);
    	

	}


</script>
