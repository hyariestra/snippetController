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
	
	span.totalnominal{
		background:#ccc;
		padding: 10px;
		font-size: 17px;
		font-family: tahoma;
	}
</style>

<div class="content-header">   
	<h4>Laporan Penjualan Per/ Kategori</h4>
</div>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		<div class="col-md-12">
			<div class="form-horizontal">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-2 control-label">Kategori Item</label>
						<div class="col-md-8">
							<select id="idkategori" class="form-control">
								<option value="-">:: Pilih Semua Kategori::</option>
								<?php
									foreach($kategori->result() as $row){
								?>
								<option value="<?php echo $row->id_kategori_item?>"><?php echo $row->nama_kategori?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"></label>
						<div class="col-md-4">
							<button onclick="caridata()" class="btn btn-sm btn-default">
								<span class="glyphicon glyphicon-search"></span>
								Cari Data
							</button>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="pull pull-right">
						<span  class="totalnominal">Total Pengeluaran : Rp. 0.00,-</span>
					</div>
				</div>
			</div>
				
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
				<tbody id="listdata">
					<?php
						$seq = 1;
						$saldoakhir = 0;
						$total = 0;
						foreach($penjualan->result() as $row)
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
						
						<tr>
							<td colspan="6" style="font-weight:bold; text-align:center;">Total</td>
							<td style="text-align:right;font-weight:bold;"><?php echo number_format($total,2)?></td>
						</tr>
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
	function caridata()
	{
		$("body").append("<div class='backDropOverlay' id='backDropOverlay'><div><img src='assets/img/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("laporan/caridata_perkategori")?>";
			data = {
				idx : $("select#idkategori").val()
			}
			
		$.post(target, data, function(e){
			
			var json = $.parseJSON(e);
			fillgridrow(json);
			$("#backDropOverlay").remove();
		});
	}
	
	function fillgridrow(json)
	{
		var table = document.getElementById("listdata");
			table.innerHTML = "";
			
			
		var saldoakhir = 0;
		for(i = 0; i < json.data.length; i++)
		{
			var row = table.insertRow();
			
			var Seq = eval(0) + eval(1);
			var Tanggal = json.data.tanggal;
			var NoBukti = json.data.nobukti;
			var Keterangan = json.data.keterangan;
			var Debet = json.data.debet;
			var Kredit = 0;
			
			saldoakhir += Debet;
			
			
			var ColSeq = row.insertCell(0);
			var ColTanggal = row.insertCell(1);
			var ColNoBukti = row.insertCell(2);
			var ColKeterangan = row.insertCell(3);
			var ColDebet = row.insertCell(4);
			var ColKredit = row.insertCell(5);
			var ColSaldoAkhir = row.insertCell(6);
			
			ColSeq.innerHTML = Seq;
			ColTanggal.innerHTML = Tanggal;
			ColNoBukti.innerHTML = NoBukti;
			ColKeterangan.innerHTML = Keterangan;
			ColDebet.innerHTML = Debet;
			ColKredit.innerHTML = Kredit;
			ColSaldoAkhir.innerHTML = saldoakhir;
		}
	}
</script>