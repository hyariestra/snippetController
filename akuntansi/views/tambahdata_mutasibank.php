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

<h4>Tambah Mutasi Bank</h4>
<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		
		<div class="form-horizontal">
					<button onclick="daftar()" class="btn btn-sm btn-success">
						<span class="glyphicon glyphicon-list"></span>
						Daftar Mutasi Bank
					</button>
	
			<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Tanggal</label>
						<div class="col-md-9">
							<div class="input-group">
								<input type="text" name="tanggal" value="<?php echo date("d-m-Y")?>" role="tanggal" readonly class="form-control" />
								<span class="input-group-addon glyphicon glyphicon-calendar"></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nomor</label>
						<div class="col-md-9">
							<input type="text" name="nomor" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nomor Bukti</label>
						<div class="col-md-9">
							<input type="text" name="nomorbukti" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Dari Kas / Bank</label>
						<div class="col-md-9">
							<div class="input-group">
								<input type="text" readonly class="form-control" name="dari" />
								<input type="hidden" style="text-align:right;" readonly class="form-control" name="idakun_dari" />
								<span onclick="openkodeakun(this)"  class="input-group-addon glyphicon glyphicon-search"></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Ke Kas / Bank</label>
						<div class="col-md-9">
							<div class="input-group">
								<input type="text" readonly class="form-control" name="ke" />
								<input type="hidden" style="text-align:right;" readonly class="form-control" name="idakun_ke" />
								<span onclick="openkodeakun(this)"  class="input-group-addon glyphicon glyphicon-search"></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nominal</label>
						<div class="col-md-9">
							<div class="input-group">
								<span class="input-group-addon">Rp.</span>
								<input type="text" style="text-align:right;" class="form-control" onkeyup="formatNumber(this)" name="nominal" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Uraian</label>
						<div class="col-md-9">
							<textarea rows="6" type="text" name="uraian" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group pull pull-right">
						<button onclick="simpandata()" class="btn btn-sm btn-default">
							<span class="glyphicon glyphicon-save"></span>
							Simpan Data
						</button>
					</div>
				</div>
				
			
				
				
				
			</div>
		</div>
		
	</div>
</div>


<div class="modal fade" id="modalakun">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 10px 0px 0px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
        <h5 style="padding: 0px 15px 0px 15px;">Data Kode Akun</h5>
      </div>
      <div class="modal-body">
        <div class="row">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<input id="parent" type="hidden" />
					
					<div class="row" style="margin:0px 0px 10px 0px;">
						<div class="col-md-6 pull pull-right ">
							<div class="input-group">
								<input type="text" placeholder="pencarian data . . . ." role='inputtext' class="form-control" name="s_akun" />
								<span onclick="carikodeakun()" role="glyphicongroup" class='input-group-addon glyphicon glyphicon-search'></span>
							</div>
						</div>
					</div>
					
					<table class="table table-bordered table-striped" style="background:#fff;">
						<tr>
							<th>Kode</th>
							<th>Nama Akun</th>
							<th></th>
						</tr>
						<?php
						$akun = json_decode($akun, true);
						foreach($akun as $akunrows)
						{
							if(substr($akunrows['kodePlainText'],0,5) == "1.1.1")
							{
							
							
							$namaakun = ($akunrows['level'] > 4) ? $akunrows['namaPlainText'] : "<b>".$akunrows['namaPlainText']."</b>";
						
						?>
							<tr>
								<td>
									<?php echo $akunrows['kodeWithFormat']?>
									<input type="hidden" value="<?php echo $akunrows['id'] ?>" />
									<input type="hidden" value="<?php echo $akunrows['kodePlainText'] ?>" />
								</td>
								<td><?php echo $namaakun?></td>
								<td>
								<?php if($akunrows['level'] > 4){?>
									<button type="button" onclick="pickup(this)" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus-sign"></span></button>
								<?php } ?>
								</td>
							</tr>
						<?php
							}
						}
						?>
					</table>
				</div>
				
				<button type="button" onclick="simpanitem()" class="btn btn-sm btn-default" style="margin-top:10px;">
					<span class="glyphicon glyphicon-plus-sign"></span>
					Simpan Item
				</button>
				
			</div>
		</div>
      </div>
	  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">
	
	$(document).ready(function()
	{
		$("input[role='tanggal']").datepicker({
			format : 'dd-mm-yyyy',
			autoclose : true
		});
		
		newNomor();
		newNomorBukti();
	});
	
	function tambahdata()
	{
	
		
		
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("akuntansi/tambahdata_jurnalumum")?>";
			
			
		$.post(target, data, function(e){
			
			$("#main-body").html(e);
			
			$("#backDropOverlay").remove();
		});
	}
	
	function tambahrow()
	{
		var table = document.getElementById("trxJU");
		
			row = table.insertRow();
			
		var ColKodeAkun = row.insertCell(0);
		var ColNamaAkun = row.insertCell(1);
		var ColDebet = row.insertCell(2);
		var ColKredit = row.insertCell(3);
		var ColMemo = row.insertCell(4);
		var ColAksi = row.insertCell(5);
		
		
		
		ColKodeAkun.innerHTML = "<div class='input-group'><input readonly type='text' class='form-control' role='inputtext' /><input type='hidden' name='idakun[]' /><span onclick='openkodeakun(this)' role='glyphicongroup' class='input-group-addon glyphicon glyphicon-search'></span></div>";
		ColNamaAkun.innerHTML = "<input readonly type='text' class='form-control' role='inputtext' />";
		ColDebet.innerHTML = "<div class='input-group'><span role='glyphicongroup' class='input-group-addon'>Rp. </span><input onkeyup='formatNumber(this)' onblur='sumdebet(this); lock(this, \"debet\");' style='text-align:right;' type='text' class='form-control' role='inputtext' name='debet[]' /></div>";
		ColKredit.innerHTML = "<div class='input-group'><span role='glyphicongroup' class='input-group-addon'>Rp. </span><input onkeyup='formatNumber(this)' onblur='sumkredit(this); lock(this, \"kredit\");' style='text-align:right;' type='text' class='form-control' role='inputtext' name='kredit[]' /></div>";
		ColMemo.innerHTML = "<input type='text' class='form-control' role='inputtext' name='memo[]' />";
		ColAksi.innerHTML = "<button onclick='removed(this)' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-remove'></span></button>";
	}
	
	function lock(obj, param)
	{
		var colDebet = $(obj).parent().parent().parent().find("td:eq(2)").find("input:first");
		var colKredit = $(obj).parent().parent().parent().find("td:eq(3)").find("input:first");
		
		(param == "debet") ? colKredit.attr("readonly", true) : colDebet.attr("readonly", true);
		(param == "debet") ? colKredit.removeAttr("onblur") : colDebet.removeAttr("onblur");
		(param == "debet") ? colKredit.val("0") : colDebet.val("0");
		
		
	}
	
	function openkodeakun(obj)
	{
		var index = $(obj).parent().parent().parent().index();
		
		console.log(index);
		$("#parent").val(index);
		$("#modalakun").modal("show");
		
	}
	
	function carikodeakun()
	{
		var target = "<?php echo site_url("akuntansi/carikodeakun")?>";
			data = {
				akun : $("input[name='s_akun']").val()
			}
			
		$.post(target, data, function(e){
			console.log(e);
		});
	}
	
	function pickup(obj)
	{
		var idakun = $(obj).parent().parent().find("td:eq(0)").find("input:first").val();
		var kodeakun = $(obj).parent().parent().find("td:eq(0)").find("input:last").val();
		var namaakun = $(obj).parent().parent().find("td:eq(1)").text();
		var index = $("#parent").val();
		
		$(".form-horizontal").find(".form-group").eq(index).find("input:first").val(namaakun);
		$(".form-horizontal").find(".form-group").eq(index).find("input:last").val(idakun);
		
		
		
		$("#modalakun").modal("hide");
	}
	
	function formatNumber(objSource) 
	{
		a = $(objSource).val();
        //b = a.replace(/[^\d]/g, "");
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
        $(objSource).val(c);
	}
	
	function sumdebet(obj)
	{
		var sumdebet = 0;
		var debet = $(obj).parent().parent().parent().parent().find("tr");
		
		$.each(debet, function(i, v){
			
			var nominal = $(v).find("td:eq(2)").find("input:first").val().replace(/,/ig,"");
			
				sumdebet +=+ nominal;
		});
		
		var total = sumdebet.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
		$("span#debet").text(total);
		$("input#nominaldebet").val(total);
	}
	
	function sumkredit(obj)
	{
		var sumkredit = 0;
		var kredit = $(obj).parent().parent().parent().parent().find("tr");
		
		$.each(kredit, function(i, v){
			
			var nominal = $(v).find("td:eq(3)").find("input:first").val().replace(/,/ig,"");
			
				sumkredit +=+ nominal;
		});
		
		var total = sumkredit.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
		$("span#kredit").text(total);
		$("input#nominalkredit").val(total);
	}
	
	function newNomor()
	{
		var target = "<?php echo site_url("akuntansi/getNumberMutasiBank")?>";
			
		$.post(target, "", function(e){
		
			$("input[name='nomor']").val(e);
			
		});
	}
	
	function newNomorBukti()
	{
		var target = "<?php echo site_url("akuntansi/generateNumberBuktiMutasiBank")?>";
			
		$.post(target, "", function(e){
		
			$("input[name='nomorbukti']").val(e);
			
		});
	}
	
	function simpandata()
	{
		
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("akuntansi/simpandataMutasiBank")?>";
		
			data = {
				tanggal : $("input[name='tanggal']").val(),
				nomor	: $("input[name='nomor']").val(),
				nobukti	: $("input[name='nomorbukti']").val(),
				idakun_dari	: $("input[name='idakun_dari']").val(),
				idakun_ke	: $("input[name='idakun_ke']").val(),
				nominal	: $("input[name='nominal']").val(),				
				uraian 	: $("textarea[name='uraian']").val(),
				
			}
			
		$.post(target, data, function(e){
			console.log(e);
			
			var html = "<?php echo site_url("akuntansi/mutasibank")?>";
			
			$("#main-body").load(html);
			
			$("#backDropOverlay").remove();
		});
	}
	
	function removed(obj)
	{
		$(obj).parent().parent().remove();
	}

	 function daftar()
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("akuntansi/mutasibank")?>";
		
		$.post(target, "", function(e){
			$("#main-body").html(e);
			
			$("#backDropOverlay").remove();
		});
	}


</script>

