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
				<form class="form-horizontal">
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
								<input type="text"  role="tanggal" class="form-control" name="s_nomorbukti">
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
							<label class="control-label col-md-4">Nomor</label>
							<div class="col-md-7">
								<input type="text" role="tanggal" class="form-control" name="s_nomor">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"></label>
							<div class="col-md-7">
								<button class="btn btn-sm btn-default">
									<span class="glyphicon glyphicon-search"></span>
									Cari Data
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			
			<button onclick="tambahdata()" class="btn btn-sm btn-default" style="margin-bottom:10px;">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Tambah Data
			</button>
			
			<table class="table table-bordered table-striped">
				<thead style="background:#429489;">
					<tr>
						<th style="color:#fff; width:1%;">#</th>
						<th style="color:#fff; width:10%;">Tanggal</th>
						<th style="color:#fff; width:15%;">Nomor Bukti</th>
						<th style="color:#fff; width:15%;">Nomor</th>
						<th style="color:#fff; ">Uraian</th>
						<th style="color:#fff; width:15%;">Nominal</th>
						<th style="width:5%;"></th>
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
					<td><?php echo $seq?></td>
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
			format : "dd-mm-yy",
			autoclose : true
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
</script>

