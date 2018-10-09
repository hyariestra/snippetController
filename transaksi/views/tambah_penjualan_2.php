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

<h4>Tambah Penerimaan</h4>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		
		<div class="col-md-12">
			<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
				<div class="form-horizontal">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label">Pelanggan</label>
							<div class="col-md-8">
								<div class="input-group">
									<input type="text" id="nama_pelanggan"  readonly class="form-control"/>
									<input type="hidden" readonly class="form-control" id="id_pelanggan" name="id_pelanggan"/>
									<span onclick="openpelanggan()" class="input-group-addon glyphicon glyphicon-search"></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">No. Transaksi</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="no_transaksi"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Tanggal Transaksi</label>
							<div class="col-md-8">
								<div class="input-group">
									<input type="text" value="<?php echo date("d-m-Y")?>" readonly role="tanggal" class="form-control" name="tgl_transaksi"/>
									<span class="input-group-addon glyphicon glyphicon-calendar"></span>
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
							<div class="form-group" id="changebank" style="">
								<label class="col-md-3 control-label">Bank</label>
								<div class="col-md-8">
									<select class="form-control" id="idbank" name="id_bank">
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
							<label class="col-md-3 control-label">Keterangan</label>
							<div class="col-md-8">
								<textarea class="form-control" name="keterangan" rows="6"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<h4>Detail Transaksi</h4>
			<div class="row">
				<div class="col-md-12">
					<form id="formitems">
						<table class="table table-bordered">
							<tr style="background:#429489;">
								<th style="color:#fff;" width="1%">No</th>
								<th style="color:#fff;" width="5%"><input type="checkbox" style="display:block;"></th>
								<th style="color:#fff;">Nama Item</th>
								<th style="color:#fff; width:10%;">Jumlah Item</th>
								<th style="color:#fff;">Nominal</th>
								<th style="color:#fff;">Potongan</th>
								<th style="color:#fff;">Memo</th>
								<th style="color:#fff;">Total</th>
								<th style="color:#fff;background:#fff; width:5%;"><button type="button" onclick="tambahrow()" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus-sign"></span></button></th>
							</tr>
							<tbody id="dataitem">
								<tr>
									<td>1</td>
									<td><input type="checkbox" style="display:block;"></td>
									<td>
										<div class="input-group">
											<input type="text" readonly role="inputtext" class="form-control" />
											<input readonly type="hidden" role="inputtext" class="form-control" name="item[]" />
											<span onclick="openitem(this)" class="input-group-addon glyphicon glyphicon-search"></span>
										</div>
									</td>
									<td><input type="number" onblur="akumulasi(this)" role="inputtext" value="1" class="form-control" name="jmlitem[]" /></td>
									<td>
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input type="text" style="text-align:right;"  onblur="akumulasi2(this)" onkeyup="formatNumber(this)" role="inputtext" class="form-control" name="harga[]" />
										</div>
									</td>
									<td>
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input type="text" style="text-align:right;" readonly onblur="akumulasi2(this)" value="0,00" onkeyup="formatNumber(this)" role="inputtext" class="form-control" name="potongan[]" />
										</div>
									</td>
									<td>
										<input type="text" role="inputtext" class="form-control" name="memo[]" />
									</td>
									<td>
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input type="text" style="text-align:right;" readonly  value="0,00" role="inputtext" class="form-control" />
										</div>
									</td>
									<td>
										<button type="button" onclick="removed(this)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
										
									</td>
								</tr>
							</tbody>
						</table>
					</form>
					
					<div class="col-md-6">
						<div class="row">
							<button type="button" onclick="simpandata()" class="btn btn-sm btn-default">
								<span class="glyphicon glyphicon-save"></span> Simpan Data
							</button>
							
							<button type="button" onclick="cetaksimpan()" class="btn btn-sm btn-default">
								<span class="glyphicon glyphicon-save"></span> Cetak & Simpan Data
							</button>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="row">
							<div class="form-horizontal">
								<div class="form-group">
									<label class="control-label col-md-6">Total Bayar : </label>
									<div class="col-md-6">
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input type="text" style="text-align:right;" id="subtotal" readonly class="form-control" class="inputtext"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-6">Dibayar : </label>
									<div class="col-md-6">
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input type="text" style="text-align:right;" onblur="getsisa(this)" onkeyup="formatNumber(this)" id="dibayar" class="form-control" class="inputtext"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-6">Kembalian : </label>
									<div class="col-md-6">
										<div class="input-group">
											<span class="input-group-addon">Rp. </span>
											<input type="text" style="text-align:right;" id="sisabayar" readonly class="form-control" class="inputtext"/>
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

<div class="modal fade" id="modalPelanggan">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 10px 0px 0px !important;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
<h5 style="padding: 0px 15px 0px 15px;">Data Pelanggan</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">

						<div style="background:#429489;min-height:0px;" class="widget-container padded">
							<div class="xx">
								<form class="up" role="search" action="" method="post">
									<div class="input-group">
										<input type="text" id="s_cari" class="form-control" name="kata_kunci" placeholder="cari pelanggan...">
										<span class="input-group-btn">
											<button onclick="caridata()" type="button" value="cari" class="btn btn-primary">Cari</button>
										</span>
									</div>
								</form>
							</div>
							<table class="table table-bordered table-striped" style="background:#fff;">
								<thead>
									<tr>
										<th>#</th>
										<th>Nama Pelanggan</th>
										<th>Alamat</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody id="listpelanggan">

								</tbody>
							</table>
						</div>

						<button type="button" onclick="tambahpelanggan()" class="btn btn-sm btn-default" style="margin-top:10px;">
							<span class="glyphicon glyphicon-plus-sign"></span>
							tambah muzakki
						</button>
					</div>
				</div>
			</div>
			<div class="modal-footer customefooter" style="text-align:left !important;">
				<ul>
					Note :
					<li>Pelanggan dapat dipilih lebih dari satu, kemudian klik tombol sebelah kanan.</li>
					<li>Tombol "tambah pelanggan" untuk input data pelanggan bila tidak ada pada list.</li>
				</ul>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalTambahPelanggan">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="padding:10px 10px 0px 0px !important;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
				<h5 style="padding: 0px 15px 0px 15px;">Tambah Data Pelanggan</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div style="background:#429489;min-height:0px;" class="widget-container padded">
							<form id="formtambahpelanggan">
								<table class="table table-bordered table-striped" style="background:#fff;">
									<tr>
										<td>Nama Pelanggan</td>
										<td><input type="text" class="form-control" name="nama" role="inputtext" /></td>
									</tr>
									<tr>
										<td>Alamat Pelanggan</td>
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

						<button id="btnsimpan" type="button" onclick="simpanpelanggan()" class="btn btn-sm btn-default" style="margin-top:10px;">
							<span class="glyphicon glyphicon-plus-sign"></span>
							simpan pelanggan
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

						<button type="button" onclick="tambahitem()" class="btn btn-sm btn-default" style="margin-top:10px;">
							<span class="glyphicon glyphicon-plus-sign"></span>
							tambah item
						</button>
					</div>
				</div>
			</div>
			<div class="modal-footer customefooter" style="text-align:left !important;">
				<ul>
					Note :
					<li>Item dapat dipilih lebih dari satu, kemudian klik tombol sebelah kanan.</li>
					<li>Tombol "tambah item" untuk input data pelanggan bila tidak ada pada list.</li>
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
									<?php
									$idakun = $this->db->query("SELECT mst_akun.id_akun 
										FROM mst_akun WHERE mst_akun.kode_akun = '4' 
										AND mst_akun.level = '1' 
										AND mst_akun.header = '1'");
										?>
										<input type="hidden" name="idakun" value="<?php echo $idakun->first_row()->id_akun?>" />
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
						<li>Tombol "tambah item" untuk input data pelanggan bila tidak ada pada list.</li>
					</ul>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script type="text/javascript">

		$(document).ready(function(){

			$("input[role='tanggal']").datepicker({
				format : "dd-mm-yyyy",
				autoclose : true
			});

			$('#datatable').DataTable();
			getnumbertransaksipenjualan();

		});

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
			
			var JumlahItem = "<input type='number' onblur='akumulasi(this)' class='form-control' value='1' role='inputtext' name='jmlitem[]' />";

			var Harga = "<div class='input-group'>";
			Harga += "<span class='input-group-addon'>Rp. </span>";
			Harga += "<input style='text-align:right;' onblur='akumulasi2(this)' class='form-control' onkeyup='formatNumber(this)' role='inputtext' name='harga[]' />";
			Harga += "</div>";
			
			var Potongan = "<div class='input-group'>";
			Potongan += "<span class='input-group-addon'>Rp. </span>";
			Potongan += "<input style='text-align:right;' readonly class='form-control' onblur='akumulasi2(this)' role='inputtext' value='0,00' onkeyup='formatNumber(this)' name='potongan[]' />";
			Potongan += "</div>";
			
			var Memo = "<input class='form-control' role='inputtext'  name='memo[]' />";
			
			
			var Total = "<div class='input-group'>";
			Total += "<span class='input-group-addon'>Rp. </span>";
			Total += "<input style='text-align:right;' class='form-control' readonly role='inputtext' value='0,00' name='total[]' />";
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

			subutotal();
		}

		function openpelanggan()
		{
			var target = "<?php echo site_url("transaksi/getpelanggan")?>";
			
			$.post(target, data, function(e){

				var json = $.parseJSON(e);

				fillgridpelanggan(json);
				$("#modalPelanggan").modal("show");
			});
		}

		function fillgridpelanggan(json)
		{
			console.log(json);
			//return false;

			var table = document.getElementById("listpelanggan");
			table.innerHTML = "";

			for(i = 0; i < json.pelanggan.length; i++)
			{
				var row = table.insertRow();

				var seq 		 = eval(i) + eval(1);
				var idpelanggan  = json.pelanggan[i].idpelanggan;
				var alamat 		 = json.pelanggan[i].alamatpelanggan;
				var namapelangan = json.pelanggan[i].namapelanggan;

				var ColSeq		 		= row.insertCell(0);
				var ColNamaPelanggan	= row.insertCell(1);
				var ColAlamat	 		= row.insertCell(2);
				var ColAksi		 		= row.insertCell(3);

				ColSeq.innerHTML 			= seq+"<input type='hidden' value='"+idpelanggan+"' />";
				ColNamaPelanggan.innerHTML  = namapelangan;
				ColAlamat.innerHTML 		= alamat;
				ColAksi.innerHTML 		 	= "<button type='button' onclick='pilihpelanggan(this)' class='btn btn-xs btn-default'><span class='glyphicon glyphicon-plus-sign'></span></button>";
			}
		}

		function tambahpelanggan()
		{
			$("#modalPelanggan").modal("hide");
			$("#modalTambahPelanggan").modal("show");
		}

		function simpanpelanggan()
		{
			$("#btnsimpan").hide();
			$("#btnloading").show();

			var target = "<?php echo site_url("transaksi/simpandatapelanggan")?>";
			data = $("#formtambahpelanggan").serialize();

			$.post(target, data, function(e){

				$("#modalTambahPelanggan").modal("hide");
			});
		}

		function pilihpelanggan(obj)
		{
			var idpelanggan = $(obj).parent().parent().find("td").eq(0).find("input:first").val();
			namapelanggan = $(obj).parent().parent().find("td").eq(1).text();

			$("#nama_pelanggan").val(namapelanggan);
			$("#id_pelanggan").val(idpelanggan);

			$("#modalPelanggan").modal("hide");
		}

		function getnumbertransaksipenjualan()
		{
			var target = "<?php echo site_url("transaksi/getnumbertransaksi")?>";

			$.post(target,"", function(e){
				console.log(e);
				$("input[name='no_transaksi']").val(e);
			});
		}



		function openitem(obj)
		{
			var target = "<?php echo site_url("transaksi/getitemlist")?>";
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
					var Unit 		= json.item[i].unit;
					var Kategori 		= json.item[i].kategori;

					var ColNamaItem  = row.insertCell(0);
					var ColHargaItem = row.insertCell(1);
					var ColUnit	 = row.insertCell(2);
					var ColKategori	 = row.insertCell(3);
					var ColAksi 	 = row.insertCell(4);

					ColHargaItem.style.textAlign = "right";
					ColKategori.style.background = "#429489";
					ColKategori.style.color = "#fff";

					ColNamaItem.innerHTML = NamaItem+"<input type='hidden' value='"+IDItem+"' />";
					ColHargaItem.innerHTML = HargaItem;
					ColUnit.innerHTML = Unit;
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

    	var target = "<?php echo site_url("transaksi/simpandatabkm")?>"
    	data = {
    		idpelanggan : $("#id_pelanggan").val(),
    		notransaksi : $("input[name='no_transaksi']").val(),
    		tgltransaksi : $("input[name='tgl_transaksi']").val(),
    		idmetodebayar : $("select[name='id_metodebayar']").val(),
    		idbank : $("select[name='id_bank']").val(),
    		keterangan : $("textarea[name='keterangan']").val(),
    		serialize : $("#formitems").serialize()
    	}

    	$.post(target, data, function(e){
    		console.log(e);

    		var html = "<?php echo site_url("transaksi/penjualan")?>";

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


    	var subtotals = subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    	$("#subtotal").val(subtotals);
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
    	var metodebayar = $("select[name='id_metodebayar']").val();
    	var bank = $("select[name='idmetodebayar']").val();
    	var ket = $("textarea[name='keterangan']").val();

    	if(metodebayar == "-" || bank == "-" || ket == "")
    	{
    		alert("Silahakan cek kembali inputan anda.");
    		return false;
    	}

    	var target = "<?php echo site_url("transaksi/cetaksimpanpemasukan")?>";

    	$.ajax({

    		url : target,
    		dataType : "json",
    		method : "POST",
    		async: false,
    		data : {
    			idpelanggan : $("#id_pelanggan").val(),
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

    			var loadContent = "<?php echo site_url("transaksi/penjualan")?>";
    			$("#main-body").load(loadContent);
    		},
    		error(xhr,status,error)
    		{
    			console.log(xhr.responseText);
    		}
    	});
    }

    function changemetode(obj)
    {
    	var nilai = $(obj).val();

    	var kodeakun = (nilai == 1) ? "1.1.1.01" : "1.1.1.02";

    	var target = "<?php echo site_url("transaksi/getComboKasBank")?>";
    	data = {
    		kodeakun : kodeakun
    	}

    	$.post(target, data, function(e){
    		console.log(e);

    		$("select#idbank").html(e);
    	});

    }
    function caridata() {
    	var nilai = $('#s_cari').val();


    	var target = "<?php echo site_url("transaksi/cari_mustahiq")?>";
    	data = {
    		mustahiq : nilai
    	}

    	$.post(target, data, function(e){
    		console.log(e);
    		var json=$.parseJSON(e);
    		fillgridpelanggan(json);
    		$("select#idbank").html(e);

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
</script>
