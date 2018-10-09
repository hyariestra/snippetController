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
	<form id="f_search" class="form-horizontal" style="border-bottom:1px dashed #999; padding-bottom:10px; margin-bottom:10px;">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-3 control-label">Tanggal Awal</label>
					<div class="col-md-8">
						<div class="input-group">
							<input type="text" value="<?php echo date("d-m-Y") ?>" class="form-control" name="tanggalawal" role="tanggal">
							<span class="input-group-addon glyphicon glyphicon-search"></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Tanggal Akhir</label>
					<div class="col-md-8">
						<div class="input-group">
							<input type="text" value="<?php echo date("d-m-Y") ?>" class="form-control" name="tanggalakhir" role="tanggal">
							<span class="input-group-addon glyphicon glyphicon-search"></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-8">
						<button type="button" onclick="caridata()" class="btn btn-sm btn-default">
							<span class="glyphicon glyphicon-search"></span>
							Cari Data
						</button>
						<button type="button" onclick="printdata()" class="btn btn-sm btn-warning">
							<span class="glyphicon glyphicon-search"></span>
							Print Data
						</button>
						<button type="button" onclick="batal()" class="btn btn-sm btn-danger">
							<span class="glyphicon glyphicon-remove"></span>
							Batal
						</button>
					</div>
				</div>
			</div>
		</div>
	</form>
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
				<tbody id="inibodi">
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
						
						<!-- <tr>
							<td colspan="6" style="font-weight:bold; text-align:right;">Total</td>
							<td style="text-align:right;font-weight:bold;"><?php echo number_format($total,2)?></td>
						</tr> -->
				</tbody>
			</table>
			<!-- <ul style="font-size:12px;">
				<b>NOTE :</b> 
				<li><b>"checked"</b> untuk <b>mensetting tahun ajaran yang aktif.</b></li>
			</ul> -->
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#datatablexx").dataTable({
			"bLengthChange" : false,
			"aaSorting": [],
		});
		
		
		$("input[role='tanggal']").datepicker({
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

	function caridata()
	{
		var target = "<?php echo site_url("laporan/caribukaspenerimaan")?>";
			data = $('#f_search').serialize();
			// console.log(data);

		$('#inibodi').html("");
		$.post(target, data, function(e){
			var jojon 	= $.parseJSON(e);
				konten 	= "";
				salad 	= 0;
			// console.log(jojon);


			for(var x=0; x < jojon.bukas.length; x++)
			{
				var nom = x + 1;
				konten += "<tr><td>"+nom+"</td><td>"+jojon.bukas[x].tanggal+"</td><td>"+jojon.bukas[x].nobukti+"</td><td>"+jojon.bukas[x].keterangan+"</td><td style='text-align:right;'>"+jojon.bukas[x].total+"</td><td style='text-align:right;'>0.00</td><td style='text-align:right;'>"+jojon.bukas[x].salad+"</td></tr>";
				salad = jojon.bukas[x].salad;
			}

			// konten += "<tr><td colspan='6' style='font-weight:bold; text-align:right;'>Total</td><td style='text-align:right;font-weight:bold;'>"+salad+"</td></tr>";
			$('#inibodi').html(konten);

		});
	}

	function printdata()
	{
		caridata();
		var target = "<?php echo site_url("laporan/printbukaspenerimaan")?>/"+$("input[name='tanggalawal']").val()+"/"+$("input[name='tanggalakhir']").val();

		window.open(target);
	}
</script>