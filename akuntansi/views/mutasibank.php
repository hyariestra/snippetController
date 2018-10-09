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
	background:#787a93;
	color:#fff;
	font-size:12px;
}

.datepicker{
	z-index:9999 !important;
}
</style>

<h4>Mutasi Bank</h4>
<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
			<div class="row">

				<div class="btn">
					<a href="javascript:toggleDiv('myContent');" class="btn btn-sm btn-success">
						<span class="glyphicon glyphicon-list"></span>&nbsp;Daftar Mutasi Bank
					</a>
				</div>
				<div id="myContent">
					<form id="caridatamutasi">
						<div class="form-horizontal">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3">Tanggal Awal</label>
									<div class="col-md-7">
										<div class="input-group">
											<input type="text" readonly role="tanggal" class="form-control" name="s_tanggalawal">
											<span class="input-group-addon glyphicon glyphicon-calendar"></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Nomor Bukti</label>
									<div class="col-md-7">
										<input type="text" class="form-control" name="s_nomorbukti">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-4">Tanggal Akhir</label>
									<div class="col-md-7">
										<div class="input-group">
											<input type="text" readonly role="tanggal" class="form-control" name="s_tanggalakhir">
											<span class="input-group-addon glyphicon glyphicon-calendar"></span>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"></label>
									<div class="col-md-7">
										<button type="button" onclick="caridatamutasi()" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span> Cari Data</button>
										<button type="button" onclick="printdata()" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-print"></span> Cetak Data</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>

			</div>
			<button onclick="tambahdata()" class="btn btn-sm btn-default" style="margin-bottom:10px;">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Tambah
			</button>
			<button onclick="deleteall()" class="btn btn-sm btn-default" style="margin-bottom:10px;">
				<span class="glyphicon glyphicon-trash"></span>
				Delete
			</button>

			<table class="table table-bordered table-striped">
				<thead style="background:#429489;">
					<tr>
						<th style="color:#fff; width:1%;">#</th>
						<th style="color:#fff; width: 1%;"><input id="checkall" type="checkbox" style="display:block !important;" /></th>
						<th style="color:#fff; width:10%;">Tanggal</th>
						<th style="color:#fff; width:15%;">Nomor Bukti</th>
						<th style="color:#fff; width:15%;">Nomor</th>
						<th style="color:#fff; ">Uraian</th>
						<th style="color:#fff; width:15%;">Nominal</th>
						<th style="color:#fff; width:15%;">Aksi</th>
						
					</tr>
				</thead>
				<tbody id="itemdata">
					<?php
					$seq = 1;
					foreach($mutasi->result() as $row){

						$nominal = $this->db->query("SELECT SUM(trx_judet.debet) as total  FROM trx_judet 
							WHERE trx_judet.id_ju = '".$row->id_ju."'");
							?>
							<tr>
								<td><input type="text" value="<?php echo $row->id_ju?>"><?php echo $seq?></td>
								<td><input type="checkbox" class="chkbox" role="checkdata" value="<?php echo $row->id_ju?>" style="display:block !important;" /></td>
								<td><?php echo date("d-m-Y", strtotime($row->tanggal))?></td>
								<td><?php echo $row->no_bukti?></td>
								<td><?php echo $row->nomor?></td>
								<td><?php echo $row->uraian?></td>
								<td><?php echo number_format($nominal->first_row()->total)?></td>
								<td>
									<button type="button" onclick="editdata(this,'<?php echo $row->id_ju?>')" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button>
									<button type="button" onclick="deletedata(this,'<?php echo $row->id_ju?>')" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
								</td>
							</tr>
							<?php
							$seq++;
						}
						?>
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

	function deletedata(obj, idju)
	{

		var conf = confirm("apakah anda yakin akan menghapus data ini ?");

		if(conf)
		{
			var target = "<?php echo site_url("akuntansi/deletejurnalumum")?>";
			data = {
				idju : idju
			}

			$.post(target, data, function(e){

				$(obj).parent().parent().remove();
			});
		}
	}

	function deleteall()
	{

		console.log(ids);
		
		var conf = confirm("apakah anda yakin akan menghapus data ini ?");
		if (conf)
		{

			var ids = [];
			$(".chkbox").each(function () {
				if ($(this).is(":checked")) {
					ids.push($(this).val());
					
				}
			});

			if(ids.length>0)
			{
				$.ajax({
					type: 'POST',
					url: "<?php echo site_url("akuntansi/deletemutasiall")?>",
					data: {
						idpenjualan: ids
					},
					success: function (data) {
						console.log(data);
						var ids = [];
						$(".chkbox").each(function () {
							if ($(this).is(":checked")) {
								$(this).parent().parent().remove();
							}
						});
					}		
				});
			}
			else
			{

				alert("silahkan pilih data.");
			}


		}
		else
		{
			alert("Hapus dibatalkan.");
		}

	}

	function tambahdata()
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");

		var target = "<?php echo site_url("akuntansi/tambahdata_mutasibank")?>";


		$.post(target, data, function(e){

			$("#main-body").html(e);

			$("#backDropOverlay").remove();
		});
	}

	function editdata(obj, idju)
	{


		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");

		var target = "<?php echo site_url("akuntansi/editdata_mutasibank")?>";
		data = {
			IDx : idju
		}

		$.post(target, data, function(e){
			console.log(e);
			$("#main-body").html(e);
			$("#backDropOverlay").remove();
		});
	}
	function toggleDiv(divId) {
		$("#"+divId).toggle();
	}

	function caridatamutasi()
	{
		var target 	= "<?php echo site_url("transaksi/caridatamutasi") ?>";
		data 	= $('#caridatamutasi').serialize();

		$.post(target, data, function(e)
		{

			var jojon 	= $.parseJSON(e);
				/*console.log(jojon);
				return false;
				*/
				if(jojon.flag)
				{
					inggridpangalila(jojon.data);
				}
				else
				{
					alert('Tidak ada data yang cocok dengan pencarian Anda...');
					$('tbody').html('<tr><td colspan="8" style="text-align:center;"><i>- Tidak ada data yang sesuai dengan pencarian Anda -</i></td></tr>');
				}

			});
	}

	function inggridpangalila(data)
	{
		// console.log(data);
		var table 	= document.getElementById('itemdata');
		table.innerHTML 	= '';


		for(var x=0; x < data.length; x++)
		{
			var row = table.insertRow();
			// console.log(data[x]['id_penjualan']);
			var seq 	= eval(x) + eval(1);
			idbiaya 	= data[x].id;
			tglpenjualan = data[x].tanggal;
			nm 			= data[x].nm;
			nmtok 		= data[x].nmtok;
			uraian 		= data[x].uraian;
			total 		= data[x].total;

			var ColSeq 		= row.insertCell(0);
			var ColCekbok 	= row.insertCell(1);
			var ColTanggal 	= row.insertCell(2);
			var ColNoBuk 	= row.insertCell(3);
			var ColNomor 	= row.insertCell(4);
			var ColUraian 	= row.insertCell(5);
			var ColTotal 	= row.insertCell(6);
			var ColAksi 	= row.insertCell(7);

			ColSeq.innerHTML 		= '<input type="hidden" value="'+idbiaya+'">'+seq;
			ColCekbok.innerHTML 	= '<input type="checkbox" class="chkbox" role="checkdata" value="'+idbiaya+'" style="display:block !important;" />';
			ColTanggal.innerHTML	= tglpenjualan;
			ColNoBuk.innerHTML 		= nm;
			ColNomor.innerHTML 		= nmtok;
			ColUraian.innerHTML 		= uraian;
			ColTotal.innerHTML 		= total;
			ColAksi.innerHTML 		= '<button onclick="editdata(this)" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button> <button onclick="deletedatapenjualan(this)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>';

		}
	}


	function printdata()
	{
		caridatamutasi();
		var	s_tanggalawal = $("input[name='s_tanggalawal']").val();
		var	s_tanggalakhir = $("input[name='s_tanggalakhir']").val();
		var	s_nomorbukti = $("input[name='s_nomorbukti']").val();
		var target = "<?php echo site_url("transaksi/printdatamutasi")?>?tanggalawal="+s_tanggalawal+"&&tanggalakhir="+s_tanggalakhir+"&&nmbukti="+s_nomorbukti;;

		window.open(target, "_blank");

	}


	</script>

