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
						<tr id="totalpenerimanZakat">
							<td style="text-align: right;">
								<span style="font-weight:bold;">Total</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="penyaluranZakat">
							<td>
								<span style="font-weight:bold;">Penyaluran</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="totalpenyaluranZakat">
							<td style="text-align: right;">
								<span style="font-weight:bold;">Total</span>
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
						<tr id="totalpenerimanInfaq">
							<td style="text-align: right;">
								<span style="font-weight:bold;">Total</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="penyaluranInfaq">
							<td>
								<span style="font-weight:bold;">Penyaluran</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="totalpenyaluranInfaq">
							<td style="text-align: right;">
								<span style="font-weight:bold;">Total</span>
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
						<tr id="totalpenerimanAmil">
							<td style="text-align: right;">
								<span style="font-weight:bold;">Total</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="penyaluranAmil">
							<td>
								<span style="font-weight:bold;">Penggunaan</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="totalpenyaluranAmil">
							<td style="text-align: right;">
								<span style="font-weight:bold;">Total</span>
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
						<tr id="totalpenerimanNonHalal">
							<td style="text-align: right;">
								<span style="font-weight:bold;">Total</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="penyaluranNonHalal">
							<td>
								<span style="font-weight:bold;">Penggunaan</span>
							</td>
							<td style="text-align:right"></td>
						</tr>
						<tr id="totalpenyaluranNonHalal">
							<td style="text-align: right;">
								<span style="font-weight:bold;">Total</span>
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

		// clear recent result
		$('.trmZakat').remove(); $('.klrZakat').remove();
		$('.trmInfaq').remove(); $('.klrInfaq').remove();
		$('.trmAmil').remove(); $('.klrAmil').remove();
		$('.trmNon').remove(); $('.klrNon').remove();
		$('#totalpenerimanZakat').find('td:eq(1)').text(0);
		$('#totalpenyaluranZakat').find('td:eq(1)').text(0);
		$('#totalpenerimanInfaq').find('td:eq(1)').text(0);
		$('#totalpenyaluranInfaq').find('td:eq(1)').text(0);
		$('#totalpenerimanAmil').find('td:eq(1)').text(0);
		$('#totalpenyaluranAmil').find('td:eq(1)').text(0);
		$('#totalpenerimanNonHalal').find('td:eq(1)').text(0);
		$('#totalpenyaluranNonHalal').find('td:eq(1)').text(0);
			
		$.post(target, data, function(e){
			var jojon = $.parseJSON(e);
			console.log(jojon);
			
			var tglawal = $("input[name='tanggalawal']").val();
			var tglakhir = $("input[name='tanggalakhir']").val();
			
			$("span#tanggalawal").text(tglawal);
			$("span#tanggalakhir").text(tglakhir);

			// ----- fillgrid -----
			// zakat
			if(jojon.zakat.terima.length > 0)
			{
				var terong = '';
					total = jojon.zakat.totTerima;
					// console.log(total);
				for(var x=0; x < jojon.zakat.terima.length; x++)
				{
					terong += '<tr id="" class="trmZakat"><td style="padding-left:25px;"><span style="">'+jojon.zakat.terima[x].nama_item+'</span></td><td style="text-align:right">'+formatNumber(jojon.zakat.terima[x].total)+'</td></tr>';
				}

				$(terong).insertAfter('#penerimanZakat');
				$('#totalpenerimanZakat').find('td:eq(1)').text(total);
			}

			if(jojon.zakat.keluar.length > 0)
			{
				var terong = '';
					total = jojon.zakat.totKeluar;
				for(var x=0; x < jojon.zakat.keluar.length; x++)
				{
					var subtot = jojon.zakat.keluar[x].total.replace('.0000', '');
					terong += '<tr id="" class="klrZakat"><td style="padding-left:25px;"><span style="">'+jojon.zakat.keluar[x].nama_item+'</span></td><td style="text-align:right">'+formatNumber(subtot)+'</td></tr>';
				}

				$(terong).insertAfter('#penyaluranZakat');
				$('#totalpenyaluranZakat').find('td:eq(1)').text(total);
			}

			// infaq
			if(jojon.infaq.terima.length > 0)
			{
				var terong = '';
					total = jojon.infaq.totTerima;
				for(var x=0; x < jojon.infaq.terima.length; x++)
				{
					terong += '<tr id="" class="trmInfaq"><td style="padding-left:25px;"><span style="">'+jojon.infaq.terima[x].nama_item+'</span></td><td style="text-align:right">'+formatNumber(jojon.infaq.terima[x].total)+'</td></tr>';
				}

				$(terong).insertAfter('#penerimanInfaq');
				$('#totalpenerimanInfaq').find('td:eq(1)').text(total);
			}

			if(jojon.infaq.keluar.length > 0)
			{
				var terong = '';
					total = jojon.infaq.totKeluar;
				for(var x=0; x < jojon.infaq.keluar.length; x++)
				{
					var subtot = jojon.infaq.keluar[x].total.replace('.0000', '');
					terong += '<tr id="" class="klrInfaq"><td style="padding-left:25px;"><span style="">'+jojon.infaq.keluar[x].nama_item+'</span></td><td style="text-align:right">'+formatNumber(subtot)+'</td></tr>';
				}

				$(terong).insertAfter('#penyaluranInfaq');
				$('#totalpenyaluranInfaq').find('td:eq(1)').text(total);
			}

			// amil
			if(jojon.amil.terima.length > 0)
			{
				var terong = '';
					total = jojon.amil.totTerima;
				for(var x=0; x < jojon.amil.terima.length; x++)
				{
					terong += '<tr id="" class="trmAmil"><td style="padding-left:25px;"><span style="">'+jojon.amil.terima[x].nama_item+'</span></td><td style="text-align:right">'+formatNumber(jojon.amil.terima[x].total)+'</td></tr>';
				}

				$(terong).insertAfter('#penerimanAmil');
				$('#totalpenerimanAmil').find('td:eq(1)').text(total);
			}

			if(jojon.amil.keluar.length > 0)
			{
				var terong = '';
					total = jojon.amil.totKeluar;
				for(var x=0; x < jojon.amil.keluar.length; x++)
				{
					var subtot = jojon.amil.keluar[x].total.replace('.0000', '');
					terong += '<tr id="" class="klrAmil"><td style="padding-left:25px;"><span style="">'+jojon.amil.keluar[x].nama_item+'</span></td><td style="text-align:right">'+formatNumber(subtot)+'</td></tr>';
				}

				$(terong).insertAfter('#penyaluranAmil');
				$('#totalpenyaluranAmil').find('td:eq(1)').text(total);
			}

			// non-halal
			if(jojon.non.terima.length > 0)
			{
				var terong = '';
					total = jojon.non.totTerima;
				for(var x=0; x < jojon.non.terima.length; x++)
				{
					terong += '<tr id="" class="trmNon"><td style="padding-left:25px;"><span style="">'+jojon.non.terima[x].nama_item+'</span></td><td style="text-align:right">'+formatNumber(jojon.non.terima[x].total)+'</td></tr>';
				}

				$(terong).insertAfter('#penerimanNonHalal');
				$('#totalpenerimanNonHalal').find('td:eq(1)').text(total);
			}

			if(jojon.non.keluar.length > 0)
			{
				var terong = '';
					total = jojon.non.totKeluar;
				for(var x=0; x < jojon.non.keluar.length; x++)
				{
					var subtot = jojon.non.keluar[x].total.replace('.0000', '');
					terong += '<tr id="" class="klrNon"><td style="padding-left:25px;"><span style="">'+jojon.non.keluar[x].nama_item+'</span></td><td style="text-align:right">'+formatNumber(subtot)+'</td></tr>';
				}

				$(terong).insertAfter('#penyaluranNonHalal');
				$('#totalpenyaluranNonHalal').find('td:eq(1)').text(total);
			}
		});
	}

	function formatNumber(val) 
	{
		var a = val;
		// console.log(a);
        var b = a.replace(/[^\d]/g, "");
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
        return c;
	}
</script>