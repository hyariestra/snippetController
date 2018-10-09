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

	#salakh > td {
		background: #e4e3e3;
		text-align: right; 
		padding-right: 10px;
	}

</style>

<div class="content-header">   
	<h4>Laporan Buku Kas Harian</h4>
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
					<label class="col-md-3 control-label">Kas / Bank</label>
					<div class="col-md-8">
						<select class="form-control" name="kasbank">
							<option value="-">:: Semua Kas / Bank ::</option>
							<?php
								foreach($kasbank->result() as $row){
							?>
								<option value="<?php echo $row->id_akun?>"><?php echo $row->nama_akun?></option>
							<?php
								}
							?>	
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Jenis</label>
					<div class="col-md-8">
						<select class="form-control" name="jenis">
							<option value="-">:: Semua transaksi ::</option>
							<option value="1">Penerimaan</option>
							<option value="2">Pengeluaran</option>

						</select>
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
				<h4>Laporan Buku Kas Harian</h4>
				<h4><?php echo $perusahaan->first_row()->nama_perusahaan?></h4>
				<h5>Untuk Periode Tanggal : <span id="tanggalawal"></span> s.d <span id="tanggalakhir"></span></h5>
			</div>
			
			<div class="body-laporan">
				<div class="content-laporan">
					<table class="table table-bordered table-striped" id="lapperubdana">
						<tr>
							<th>Tanggal</th>
							<th>No.Bukti</th>
							<th>Keterangan</th>
							<th>Debet</th>
							<th>Kredit</th>
							<th>Saldo Akhir</th>
						</tr>
						<!-- JUMLAH SALDO -->
						<tr id="salakh">
							
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
		var target = "<?php echo site_url("laporan/previewbukasharian")?>";
			
			data = {
				tanggalawal : $("input[name='tanggalawal']").val(),
				tanggalakhir : $("input[name='tanggalakhir']").val(),
				kasbank : $("select[name='kasbank']").val(),
				jenis : $("select[name='jenis']").val()
			}

		// clear recent result
		$('.trmZakat').remove(); $('.klrZakat').remove();
		$('.trmInfaq').remove(); $('.klrInfaq').remove();
		$('.trmAmil').remove(); $('.klrAmil').remove();
		$('.trmNon').remove(); $('.klrNon').remove();
			
		$.post(target, data, function(e){
			var jojon = $.parseJSON(e);
			//console.log(e);
			//return false;
			var tglawal = $("input[name='tanggalawal']").val();
			var tglakhir = $("input[name='tanggalakhir']").val();
			var kasbank = $("select[name='kasbank']").val();
			var jenis = $("select[name='jenis']").val();
			
			$("span#tanggalawal").text(tglawal);
			$("span#tanggalakhir").text(tglakhir);

			// ----- fillgrid -----
			$('.datane').remove();
			var dataLength = jojon.data.length;
			
			if(dataLength > 0)
			{
				var koplong 	= ""; 
					saldoAkhir 	= jojon.SA;
					sldoakhir 	= jojon.SA.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

				koplong += "<tr class='datane'><td></td><td>SA001</td><td>Saldo Awal</td><td style='text-align:right;'></td><td style='text-align:right;'></td><td style='text-align:right;'>"+sldoakhir+"</td></tr>";
				for(var x = 0; x < dataLength; x++)
				{
					var tgl 	= jojon.data[x].tgl;
						nobuk 	= jojon.data[x].no_bukti;
						kete 	= jojon.data[x].keterangan;
						debe 	= jojon.data[x].debet;
						kred 	= jojon.data[x].kredit;
						saldoAkhir 	+= parseInt(debe) - parseInt(kred);

					debe = formatNumber(debe);
					kred = formatNumber(kred);
					sald = saldoAkhir.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
					koplong += "<tr class='datane'><td>"+tgl+"</td><td>"+nobuk+"</td><td>"+kete+"</td><td style='text-align:right;'>"+debe+"</td><td style='text-align:right;'>"+kred+"</td><td style='text-align:right;'>"+sald+"</td></tr>";
				}

				$('#lapperubdana').find('tr:last > td:eq(1)').text(sald);
				$(koplong).insertAfter('#lapperubdana > tbody > tr:eq(1)');
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

	function printdata()
	{
		var target = "<?php echo site_url("laporan/printbukasharian")?>/"+$("input[name='tanggalawal']").val()+"/"+$("input[name='tanggalakhir']").val()+"/"+$("select[name='kasbank']").val()+"/"+$("select[name='jenis']").val();

		window.open(target);
	}


	function batal()
	{
		var target = "<?php echo site_url("laporan/previewbukasharian")?>";
			
			data = {
				tanggalawal : $("input[name='tanggalawal']").val(),
				tanggalakhir : $("input[name='tanggalakhir']").val(),
				kasbank : $("select[name='kasbank']").val(),
				jenis : $("select[name='jenis']").val()
			}

		// clear recent result
		$('.trmZakat').remove(); $('.klrZakat').remove();
		$('.trmInfaq').remove(); $('.klrInfaq').remove();
		$('.trmAmil').remove(); $('.klrAmil').remove();
		$('.trmNon').remove(); $('.klrNon').remove();
			
		$.post(target, data, function(e){
			var jojon = $.parseJSON(e);
			//console.log(e);
			//return false;
			var tglawal = $("input[name='tanggalawal']").val();
			var tglakhir = $("input[name='tanggalakhir']").val();
			var kasbank = $("select[name='kasbank']").val();
			var jenis = $("select[name='jenis']").val();
			
			$("span#tanggalawal").text(tglawal);
			$("span#tanggalakhir").text(tglakhir);

			// ----- fillgrid -----
			$('.datane').remove();
			var dataLength = jojon.data.length;
			
			if(dataLength > 0)
			{
				var koplong 	= ""; 
					saldoAkhir 	= jojon.SA;
					sldoakhir 	= jojon.SA.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

				koplong += "<tr class='datane'><td></td><td>SA001</td><td>Saldo Awal</td><td style='text-align:right;'></td><td style='text-align:right;'></td><td style='text-align:right;'>"+sldoakhir+"</td></tr>";
				for(var x = 0; x < dataLength; x++)
				{
					var tgl 	= "";
						nobuk 	= "";
						kete 	= "";
						debe 	= "";
						kred 	= "";
						saldoAkhir 	="";

					debe = formatNumber(debe);
					kred = formatNumber(kred);
					sald = saldoAkhir.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
					koplong += "<tr class='datane'><td>"+tgl+"</td><td>"+nobuk+"</td><td>"+kete+"</td><td style='text-align:right;'>"+debe+"</td><td style='text-align:right;'>"+kred+"</td><td style='text-align:right;'>"+sald+"</td></tr>";
				}

				$('#lapperubdana').find('tr:last > td:eq(1)').text(sald);
				$(koplong).insertAfter('#lapperubdana > tbody > tr:eq(1)');
			}
			

		});
	}
</script>