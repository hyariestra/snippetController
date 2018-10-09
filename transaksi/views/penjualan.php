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

<h4>Transaksi Penerimaan</h4>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		
		<div class="col-md-12">

			<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
				<div class="btn">
					<a href="javascript:toggleDiv('myContent');" class="btn btn-sm btn-success">
						<span class="glyphicon glyphicon-list"></span>&nbsp;Pencarian BKM
					</a>
				</div>

				<div id="myContent" class="form-horizontal">
					<div class="col-md-6">
						
						<div class="form-group">
							<label class="col-md-3 control-label">Tanggal Awal</label>
							<div class="col-md-8">
								<div class="input-group">
									<input type="text" role="tanggal" readonly class="form-control" name="s_tanggalawal"/>
									<span class="input-group-addon glyphicon glyphicon-calendar"></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Nama Pelanggan</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="s_namapelanggan"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Metode Bayar</label>
							<div class="col-md-8">
								<select class="form-control" name="s_metodebayar">
									<option value="-">:: Semua Metode Bayar ::</option>
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
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label">Tanggal Akhir</label>
							<div class="col-md-8">
								<div class="input-group">
									<input type="text"  role="tanggal" readonly class="form-control" name="s_tanggalakhir"/>
									<span class="input-group-addon glyphicon glyphicon-calendar"></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">No. Transaksi</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="s_notrans"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Bank</label>
							<div class="col-md-8">
								<select class="form-control" name="s_bank">
									<option value="-">:: Semua Bank ::</option>
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
						<div class="form-group">
							<label class="col-md-3 control-label"></label>
							<div class="col-md-8">
								<button type="button" onclick="caridata()" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span> Cari Data</button>
								<button type="button" onclick="printdata()" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-print"></span> Cetak Data</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<button onclick="tambahdata()" class="btn btn-sm btn-default">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Tambah
			</button>
			<button onclick="deleteall()" class="btn btn-sm btn-default">
				<span class="glyphicon glyphicon-trash"></span>
				Delete
			</button>
			
			<table id="datatable" class="table table-bordered table-striped" style="margin-top:10px;">
				<thead style="background:#429489;">
					<tr>
						<th style="color:#fff;">#</th>
						<th style="color:#fff;"><input id="checkall" type="checkbox" style="display:block !important;" /></th>
						<th style="color:#fff;">Tanggal</th>
						<th style="color:#fff;">Pelanggan</th>
						<th style="color:#fff;">No. Transaksi</th>
						<th style="color:#fff;">Metode Bayar</th>
						<th style="color:#fff;">Kas / Bank</th>
						<th style="color:#fff;">Keterangan</th>
						<th style="color:#fff;">Nominal</th>
						<th style="width:8%;color:#fff;">Aksi</th>
					</tr>
				</thead>
				<tbody id="tabpenjualan">
					<?php 
					$seq = 1;
					foreach($penjualan->result() as $row){ ?>
					<tr>
						<td><input type="hidden" value="<?php echo $row->id_penjualan?>"><?php echo $seq?></td>
						<td><input type="checkbox" class="chkbox" role="checkdata" value="<?php echo $row->id_penjualan?>" style="display:block !important;" /></td>
						<td><?php echo date("d-m-Y", strtotime($row->tanggal_penjualan))?></td>
						<td><?php echo $row->nama_pelanggan?></td>
						<td><?php echo $row->no_transaksi?></td>
						<td><?php echo $row->nama_metode_bayar?></td>
						<td><?php echo $row->nama_bank?></td>
						<td><?php echo $row->keterangan?></td>
						<td style="text-align:right;"><?php echo number_format($row->total)?></td>
						<td>
							<button onclick="editdata(this)" class="btn btn-xs btn-warning">
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
							<button onclick="deletedata(this)" class="btn btn-xs btn-danger">
								<span class="glyphicon glyphicon-trash"></span>
							</button>
							<a href="transaksi/printkuitansi/<?php echo $row->id_penjualan ?>"  target="_blank" style='margin-left:3px;' class='btn btn-xs btn-primary' ><span class='glyphicon glyphicon-print' aria-hidden='true'></a>
						</td>
					</tr>
					<?php 
					$seq++;
				}?>
			</tbody>
		</table>
	</div>
</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$("input[role='tanggal']").datepicker({
			format : "dd-mm-yyyy",
			autoclose : true
		});
		$('#myContent').hide();
		
		$('#checkall').click(function(){
			if(this.checked)
				$(".chkbox").prop("checked", true);
			else
				$(".chkbox").prop("checked", false);
		});
	});
	
	function printdata()
	{
		var target = "<?php echo site_url("transaksi/printrekappenjualan")?>";

		var	s_tanggalawal = $("input[name='s_tanggalawal']").val();
		var	s_tanggalakhir = $("input[name='s_tanggalakhir']").val();
		var	s_namapelanggan = $("input[name='s_namapelanggan']").val();
		var	s_notrans = $("input[name='s_notrans']").val();
		var	s_metodebayar = $("select[name='s_metodebayar']").val();
		var	s_bank = $("select[name='s_bank']").val();

		window.open(target+"?tanggalawal="+s_tanggalawal+"&tanggalakhir="+s_tanggalakhir+"&pelanggan="+s_namapelanggan+"&notrans="+s_notrans+"&metodebayar="+s_metodebayar+"&bank="+s_bank, "_blank")
	}
	
	
	function tambahdata()
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("transaksi/tambahpenjualan")?>";
		
		$.post(target, "", function(e){
			$("#main-body").html(e);
			
			$("#backDropOverlay").remove();
		});
	}
	
	function editdata(obj)
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("transaksi/editdatabkm")?>";
		data = {
			idpenjualan : $(obj).parent().parent().find("td").eq(0).find("input:first").val()
		}

		$.post(target, data, function(e){
			
			$("#main-body").html(e);
			
			$("#backDropOverlay").remove();
			
		});
	}

	function deletedata(obj)
	{
		var IDx 	= $(obj).parent().parent().find('td:eq(0) input').val();
		var target = "<?php echo site_url("transaksi/deletedatapenjualan")?>";
		data = {
			idpenjualan : IDx
		}
		// console.log(IDx);
		//alert(IDx)
		var isdelete = confirm("Apakah anda yakin akan menghapus data penjualan ini ?");
		
		if(isdelete)
		{
			$.post(target, data, function(e){

				$(obj).parent().parent().remove();
				
			})
		}

	}

	function deleteall()
	{
		var ids = [];
		$(".chkbox").each(function () {
			if ($(this).is(":checked")) {
				ids.push($(this).val());
				//$(this).parent().parent().remove();
			}
		});
		console.log(ids);
		if (ids.length) {
			var conf = confirm("Apakah anda yakin akan menghapus data ini ?");
			
			if(conf)
			{
				$.ajax({
					type: 'POST',
					url: "<?php echo site_url("transaksi/deleteall")?>",
					data: {
						idpenjualan: ids
					},
					success: function (data) {
						console.log(data);
						$(".chkbox").each(function () {
							if ($(this).is(":checked")) {
								//ids.push($(this).val());
								$(this).parent().parent().remove();
							}
						});
					}
				});
			}
		} else {
			alert("Please select items.");
		}
	}

	function deleteall__(obj) {
		var IDx 	= $(obj).parent().parent().find('td:eq(0) input').val();
		var target = "<?php echo site_url("transaksi/deleteall")?>";
		data = {
			idpenjualan : IDx
		}
		/*console.log(IDx);
		alert(IDx)*/
		var isdelete = confirm("Apakah anda yakin akan menghapus data penjualan ini ?");
		
		if(isdelete)
		{
			$.post(target, data, function(e){

				$(obj).parent().parent().remove();
				
			})
		}
	}

	
	function caridata()
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("transaksi/caridatapenjualan")?>";
		data = {
			s_tanggalawal : $("input[name='s_tanggalawal']").val(),
			s_tanggalakhir : $("input[name='s_tanggalakhir']").val(),
			s_namapelanggan : $("input[name='s_namapelanggan']").val(),
			s_metodebayar : $("select[name='s_metodebayar']").val(),
			s_notrans : $("input[name='s_notrans']").val(),
			s_bank : $("select[name='s_bank']").val()
		};

		$.post(target, data, function(e){
			console.log(e);
			var jojon 	= $.parseJSON(e);
			
			if(jojon.flag)
			{
				inggridpangalila(jojon.data);
			}
			else
			{
				alert('Tidak ada data yang cocok dengan pencarian Anda...');
				$('tbody').html('<tr><td colspan="8" style="text-align:center;"><i>- Tidak ada data yang sesuai dengan pencarian Anda -</i></td></tr>');
			}
			
			$("#backDropOverlay").remove();
		});
	}


	
	function inggridpangalila(data)
	{
		// console.log(data);
		var table 	= document.getElementById('tabpenjualan');
		table.innerHTML 	= '';


		for(var x=0; x < data.length; x++)
		{
			var row = table.insertRow();
			// console.log(data[x]['id_penjualan']);
			var seq 			= eval(x) + eval(1);
			idpenjualan 	= data[x].id_penjualan;
			namapelanggan 	= data[x].nama_pelanggan;
			tglpenjualan 	= data[x].tanggal_penjualan;
			notrans 		= data[x].no_transaksi;
			metode 			= data[x].nama_metode_bayar;
			bank 			= data[x].nama_bank;
			ket 			= data[x].keterangan;
			nom 			= data[x].total;

			var ColSeq 		= row.insertCell(0);
			var ColCekbok 	= row.insertCell(1);
			var ColTanggal 	= row.insertCell(2);
			var ColPelanggan= row.insertCell(3);
			var ColNotrans 	= row.insertCell(4);
			var ColMetode 	= row.insertCell(5);
			var ColBank 	= row.insertCell(6);
			var ColKet 		= row.insertCell(7);
			var ColNom 		= row.insertCell(8);
			var ColButt 	= row.insertCell(9);

			ColSeq.innerHTML 		= '<input type="hidden" value="'+idpenjualan+'">'+seq;
			ColCekbok.innerHTML 	= '<input type="checkbox" class="chkbox" role="checkdata" value="'+idpenjualan+'" style="display:block !important;" />';
			ColTanggal.innerHTML	= tglpenjualan;
			ColPelanggan.innerHTML	= namapelanggan;
			ColNotrans.innerHTML 	= notrans;
			ColMetode.innerHTML 	= metode;
			ColBank.innerHTML 		= bank;
			ColKet.innerHTML 		= ket;
			ColNom.innerHTML 		= nom;
			ColButt.innerHTML 		= '<button onclick="editdata(this)" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button> <button onclick="deletedata(this)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button><a href="transaksi/printkuitansi/'+idpenjualan+'"target="_blank" style="margin-left:3px;" class="btn btn-xs btn-primary" ><span class="glyphicon glyphicon-print" aria-hidden="true"></a>';

		}


	}

	function toggleDiv(divId) {
		$("#"+divId).toggle();
	}
</script>
