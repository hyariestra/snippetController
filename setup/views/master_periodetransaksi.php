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

<?php 
	$per 	= $period->row();
	$sasi 	= array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
	$sasiText = (@$per->bulan) ? @$per->bulan.' - '.$sasi[@$per->bulan-1] : '';
?>
<div class="content-header">   
	<h4>Periode Transaksi</h4>
</div>
<div class="widget-container" style="padding:10px;">
	<div class="col-md-6">
		<div class="row">
			<form id="formjenispembayaran" class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-md-3">Bulan : </label>
					<div class="col-md-8">
						<div class="input-group"  onclick="openmodalsasi()" style="cursor: pointer;">
							<input type="hidden" class="form-control" id="idperiode" value="<?php echo @$per->id_periode?>" name="idperiode">
							<input type="hidden" class="form-control" id="bulan" value="<?php echo @$per->bulan?>" name="bulan">
							<input style="cursor: pointer;" id="sasiText" type="text" readonly="readonly" class="form-control" value="<?php echo $sasiText ?>">
							<span class="input-group-addon glyphicon glyphicon-search"></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">Tahun : </label>
					<div class="col-md-8">
						<div class="input-group"  onclick="openmodaltaun()" style="cursor: pointer;">
							<input style="cursor: pointer;" type="text" readonly="readonly" class="form-control" name="tahun" id="tahun" value="<?php echo @$per->tahun?>">
							<span class="input-group-addon glyphicon glyphicon-search"></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"></label>
					<div class="col-md-8">
						<button type="button" onclick="simpandata()" class="btn btn-sm btn-default">
							<span class="glyphicon glyphicon-save"></span>
							Simpan Setting
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Tambah Data Sasi -->
<div class="modal fade" id="modalSasi">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 10px 0px 0px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 style="padding: 0px 15px 0px 15px;">Bulan</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formBaru" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<tr>
							<th>No</th>
							<th>Bulan</th>
							<th>Aksi</th>
						</tr>
						<?php 
						$seq = 1;
						foreach($sasi as $row) { ?>
						<tr>
							<td class="form-input labelinput">
								<?php echo $seq?>
							</td>
							<td class="form-input">
								<span><?php echo $seq.' - '.$row?></span>
							</td>
							<td class="form-input">
								<button type="button" onclick="pilihBulan(this,<?php echo $seq?>)" class="btn btn-xs btn-default">
									<span class="glyphicon glyphicon-pencil"></span>
								</button>
							</td>
						</tr>
						<?php 
						$seq++;
						} ?>
					</table>
					
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
</div><!-- /.modalSasi ENDS -->

<!-- Tambah Data Sasi -->
<div class="modal fade" id="modalTaun">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 10px 0px 0px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 style="padding: 0px 15px 0px 15px;">Tahun</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form id="formBaru" class="form-horizontal">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<table class="table table-bordered tsble-striped" style="background:#fff;">
						<tr>
							<th>No</th>
							<th>Tahun</th>
							<th>Aksi</th>
						</tr>
						<?php 
						$theTahun 	= date("Y");
						for ($i=0; $i < 11 ; $i++) { 
							$year 	= $theTahun + $i;
						?>
						<tr>
							<td class="form-input labelinput">
								<?php echo $i+1?>
							</td>
							<td class="form-input">
								<span><?php echo $year?></span>
							</td>
							<td class="form-input">
								<button type="button" onclick="pilihTaun(<?php echo $year?>)" class="btn btn-xs btn-default">
									<span class="glyphicon glyphicon-pencil"></span>
								</button>
							</td>
						</tr>
						<?php
						} ?>
					</table>
					
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
</div><!-- /.modalTaun ENDS -->

<script type="text/javascript">
	$(document).ready(function(){
		$("#datatable").dataTable({
			"bLengthChange" : true
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
	
	function openmodalsasi()
	{
		$("#modalSasi").modal("show");
	}
	function pilihBulan(obj,bulan)
	{
		var sasiText = $(obj).parent().parent().find("td").eq(1).find("span").text();
		console.log(sasiText);
		
		$("#sasiText").val(sasiText);
		$("#bulan").val(bulan);
		
		$("#modalSasi").modal("hide");
		
	}

	function openmodaltaun()
	{
		$("#modalTaun").modal("show");
	}
	function pilihTaun(taun)
	{		
		$("#tahun").val(taun);
				
		$("#modalTaun").modal("hide");	
	}
	
	function simpandata()
	{
		$("body").append("<div class='backDropOverlay' id='backDropOverlay'><div><img src='assets/img/loading.gif'/><span>Loading..</span></div></div>");
		var target = "<?php echo site_url("setup/simpanperiodetransaksi")?>";
			data = $("#formjenispembayaran").serialize();
			
		$.post(target, data, function(e){
			var jojon 	= $.parseJSON(e);
			// console.log(e);
			if(jojon.flag)
			{
				$('#idperiode').val(jojon.theID);
				alert('Periode transaksi berhasil disimpan...');	
			}
			else
			{
				alert('Periksa kembali input bulan dan tahun...');
			}

			$("#backDropOverlay").remove();
		});
			
	}
	
</script>