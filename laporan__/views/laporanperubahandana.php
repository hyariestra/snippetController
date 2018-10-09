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
	
	.content-laporan{
		border : 1px solid #ccc;
		padding : 10px;
	}
	
	.body-laporan{
		margin-left : 10%;
		margin-right : 10%;
	}
</style>

<div class="content-header">   
	<h4>Laporan Perubahan Dana</h4>
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
			<div style="text-align:center;">
				<h4>Laporan Perubahan Dana</h4>
				<h4><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
				<h5>Untuk Periode Tanggal : <span id="tanggalawal"></span> s.d <span id="tanggalawal"></span></h5>
			</div>
			
			<div class="body-laporan">
				<div class="content-laporan">
					<table class="table table-bordered table-striped">
						<tr>
							<th width="80%">Keterangan</th>
							<th>Total (Rp.)</th>
						</tr>
						<!-- Dana Zakat -->
						<tr>
							<td>
								<span style="font-size:14px;font-weight:bold;">DANA ZAKAT</span>
							</td>
							<td></td>
						</tr>
						<tr id="penerimanZakat">
							<td>
								<span style="font-weight:bold;">Penerimaan</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="penyaluranZakat">
							<td>
								<span style="font-weight:bold;">Penyaluran</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<!-- INFAQ SODAQOH -->
						<tr>
							<td>
								<span style="font-size:14px;font-weight:bold;">DANA INFAQ / SEDEKAH</span>
							</td>
							<td></td>
						</tr>
						<tr id="penerimanInfaq">
							<td>
								<span style="font-weight:bold;">Penerimaan</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="penyaluranInfaq">
							<td>
								<span style="font-weight:bold;">Penyaluran</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<!-- DANA AMIL -->
						<tr>
							<td>
								<span style="font-size:14px;font-weight:bold;">DANA AMIL</span>
							</td>
							<td></td>
						</tr>
						<tr id="penerimanAmil">
							<td>
								<span style="font-weight:bold;">Penerimaan</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="penyaluranAmil">
							<td>
								<span style="font-weight:bold;">Penggunaan</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<!-- DANA NONHALAL -->
						<tr>
							<td>
								<span style="font-size:14px;font-weight:bold;">DANA NON HALAL</span>
							</td>
							<td></td>
						</tr>
						<tr id="penerimanNonHalal">
							<td>
								<span style="font-weight:bold;">Penerimaan</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="penyaluranNonHalal">
							<td>
								<span style="font-weight:bold;">Penggunaan</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<!-- JUMLAH SALDO -->
						<tr>
							<td>
								<span style="font-weight:bold;">Jumlah Saldo dana zakat, dana infaq/sedekah, dana amil dan dana nonhalal</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		
		$("input[role='tanggal']").datepicker({
			"autoclose" : true,
			"format" : "dd-mm-yyyy"
		});
		
	});
	
	function caridata()
	{
		var target = "<?php echo site_url("laporan/previewperubahandana")?>";
			
			data = {
				tanggalawal : $("input[name='tanggalawal']").val(),
				tanggalakhir : $("input[name='tanggalakhir']").val()
			}
			
		$.post(target, data, function(e){
			console.log(e);
			
			var tglawal = $("input[name='tanggalawal']").val();
			var tglakhir = $("input[name='tanggalakhir']").val();
			
			$("span#tanggalawal").text(tglawal);
			$("span#tanggalakhir").text(tglakhir);
		});
	}
</script>