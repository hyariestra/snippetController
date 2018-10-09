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

<h4>Transaksi Pengeluaaran Kredit</h4>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		
		<div class="col-md-12">
			<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
				<div class="btn">
					<a href="javascript:toggleDiv('myContent');" class="btn btn-sm btn-success">
						<span class="glyphicon glyphicon-list"></span>&nbsp;Daftar BKK Pengeluaran Kredit
					</a>
				</div>
				<div id="myContent">
					<form id="caridatapengeluaran">
						<div class="form-horizontal">
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
									<label class="col-md-3 control-label">Nama Pemasok</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="s_namapemasok"/>
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
									<label class="col-md-3 control-label"></label>
									<div class="col-md-8">
										<button onclick="caridatapengeluaran()" type="button" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span> Cari Data</button>
										<button onclick="cetakdatapengeluaran()" type="button" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-print"></span> Cetak Data</button>
									</div>
								</div>
							</div>
						</div>
					</form>
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
						<th style="color:#fff;">Pemasok</th>
						<th style="color:#fff;">No. Transaksi</th>
						<th style="color:#fff;">Keterangan</th>
						<th style="color:#fff;">Nominal</th>
						<th style="width:8%;color:#fff;">Aksi</th>
					</tr>
				</thead>
				<tbody id="tabpengeluaran">
					<?php 
					$seq = 1;
					foreach($pengeluaran->result() as $row){ ?>
					<tr>
						<td><input type="hidden" value="<?php echo $row->id_pembelian?>"><?php echo $seq?></td>
						<td><input type="checkbox" class="chkbox" role="checkdata" value="<?php echo $row->id_pembelian?>" style="display:block !important;" /></td>
						<td><?php echo date("d-m-Y", strtotime($row->tanggal))?></td>
						<td><?php echo $row->nama_pemasok ?></td>
						<td><?php echo $row->nomor_transaksi ?></td>
						<td><?php echo $row->ket?></td>
						<td style="text-align:right;"><?php echo number_format($row->total)?></td>
						<td>
							<button onclick="editdata(this)" class="btn btn-xs btn-warning">
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
							<button onclick="deletedata(this)" class="btn btn-xs btn-danger">
								<span class="glyphicon glyphicon-trash"></span>
							</button>
							<button onclick="printdatakuitansi('<?php echo $row->id_pembelian?>')" class="btn btn-xs btn-primary">
								<span class="glyphicon glyphicon-print"></span>
							</button>
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
	
	function tambahdata()
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("transaksi/tambahpengeluarankredit")?>";
		
		$.post(target, "", function(e){
			$("#main-body").html(e);
			
			$("#backDropOverlay").remove();
		});
	}
	
	function editdata(obj)
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("transaksi/getdataeditpengeluarankredit")?>";
		data = {
			idpengeluaran : $(obj).parent().parent().find("td").eq(0).find("input:first").val()
		}

		$.post(target, data, function(e){
			
			$("#main-body").html(e);
			
			$("#backDropOverlay").remove();
			
		});
	}

	function deletedata(obj)
	{
		var IDx 	= $(obj).parent().parent().find('td:eq(0) input').val();
		var target = "<?php echo site_url("transaksi/deletepengeluarankredit")?>";
		data = {
			idpengeluaran : IDx
		}
		// console.log(IDx);
		var isdelete = confirm("Apakah anda yakin akan menghapus data ini ?");
		
		if(isdelete)
		{
			$.post(target, data, function(e){
				var jojon 	= $.parseJSON(e);

				if(jojon.flag)
				{
					$(obj).parent().parent().remove();
					alert("Data pengeluaran telah dihapus...");
				}
				else
				{
					alert("Data pengeluaran gagal...");
				}
			})
		}

	}

	function deleteall()
	{
		var ids = [];
		var na = $("input[name='aa']").val();
		$(".chkbox").each(function () {
			if ($(this).is(":checked")) {
				ids.push($(this).val());
				$(this).parent().parent().remove();
			}
		});
		console.log(ids);
		if (ids.length) {
			$.ajax({
				type: 'POST',
				url: "<?php echo site_url("transaksi/deletepengeluaranall?na='+na'")?>",
				data: {
					idpenjualan: ids
				},
				success: function (data) {
					console.log(data);
				}
			});
		} else {
			alert("Please select items.");
		}
	}

	function caridatapengeluaran()
	{
		var target 	= "<?php echo site_url("transaksi/caridatapengeluaran") ?>";
		data 	= $('#caridatapengeluaran').serialize();

		$.post(target, data, function(e)
		{
			var jojon 	= $.parseJSON(e);
			
			if(jojon.flag)
			{
				inggridpangalilapembelian(jojon.pel);
			}
			else
			{
				alert('Tidak ada data yang cocok dengan pencarian Anda...');
				$('tbody').html('<tr><td colspan="9" style="text-align:center;"><i>- Tidak ada data yang sesuai dengan pencarian Anda -</i></td></tr>');
			}

		});
	}

	function inggridpangalilapembelian(data)
	{
		var table 	= document.getElementById('tabpengeluaran');
		table.innerHTML 	= '';


		for(var x=0; x < data.length; x++)
		{
			var row = table.insertRow();
			// console.log(data[x]['id_penjualan']);
			var seq 	= eval(x) + eval(1);
			idpembelian 	= data[x].id_pembelian;
			tanggal 	= data[x].tanggal;
			pemasok 	= data[x].nama_pemasok;
			notrans 	= data[x].nomor_transaksi;
			metode 		= data[x].nama_metode_bayar;
			bank 		= data[x].nama_bank;
			ket 		= data[x].ket;
			nom 		= data[x].total;

			var ColSeq 		= row.insertCell(0);
			var ColCekbok 	= row.insertCell(1);
			var ColTanggal 	= row.insertCell(2);
			var ColNama 	= row.insertCell(3);
			var ColNotrans 	= row.insertCell(4);
			var ColMetode 	= row.insertCell(5);
			var ColBank 	= row.insertCell(6);
			var ColKet 		= row.insertCell(7);
			var ColNom 		= row.insertCell(8);
			var ColButt 	= row.insertCell(9);

			ColSeq.innerHTML 		= '<input type="hidden" value="'+idpembelian+'">'+seq;
			ColCekbok.innerHTML 	= '<input type="checkbox" class="chkbox" role="checkdata" value="'+idpembelian+'" style="display:block !important;" />';
			ColTanggal.innerHTML	= tanggal;
			ColNama.innerHTML 		= pemasok;
			ColNotrans.innerHTML 	= notrans;
			ColMetode.innerHTML 	= metode;
			ColBank.innerHTML 		= bank;
			ColKet.innerHTML 		= ket;
			ColNom.innerHTML 		= nom;
			ColButt.innerHTML 		= '<button onclick="editdata(this)" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button> <button onclick="deletedata(this)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>';

		}
	}

	function cetakdatapengeluaran()	
	{
		caridatapengeluaran();

		var	s_tanggalawal = $("input[name='s_tanggalawal']").val();
		var	s_tanggalakhir = $("input[name='s_tanggalakhir']").val();
		var	s_namapelanggan = $("input[name='s_namapemasok']").val();
		var	s_notrans = $("input[name='s_notrans']").val();
		var	s_metodebayar = $("select[name='s_metodebayar']").val();
		var	s_bank = $("select[name='s_bank']").val();
		var target = "<?php echo site_url("transaksi/printrekappembelian")?>?tanggalawal="+s_tanggalawal+"&&tanggalakhir="+s_tanggalakhir+"&&pemasok="+s_namapelanggan+"&&notrans="+s_notrans+"&&metodebayar="+s_metodebayar+"&&bank="+s_bank;

		window.open(target, "_blank");
	}
	
	function toggleDiv(divId) {
		$("#"+divId).toggle();
	}

</script>
