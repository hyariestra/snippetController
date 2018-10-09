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

<h4>Jurnal Umum</h4>
<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<input type="hidden" name="idju" value="<?php echo $ju->first_row()->id_ju?>" readonly class="form-control" />
		<div class="form-horizontal">
			<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Tanggal</label>
						<div class="col-md-8">
							<div class="input-group">
								<input type="text" name="tanggal" value="<?php echo date("d-m-Y", strtotime($ju->first_row()->tanggal))?>" role="tanggal" readonly class="form-control" />
								<span class="input-group-addon glyphicon glyphicon-calendar"></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nomor</label>
						<div class="col-md-8">
							<input type="text" name="nomor" value="<?php echo $ju->first_row()->nomor?>" readonly class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nomor Bukti</label>
						<div class="col-md-8">
							<input type="text" name="nomorbukti" value="<?php echo $ju->first_row()->no_bukti?>" readonly class="form-control" />
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Uraian</label>
						<div class="col-md-8">
							<textarea name="uraian" class="form-control" rows="8"><?php echo $ju->first_row()->uraians?></textarea>
							<span class="badge" style="margin-top:5px; color:#f9f9f9;"><b>Note:</b> Perhatikan tanda (*) adalah harus diisi</span>
						</div>
					</div>
				</div>
			
				<!--
				<div style="margin-bottom:10px;">
					<font style="font-family:tahoma; font-size:17px; border-bottom:1px dotted #000;">Debet : Rp. <span id="debet">0,-</span></font> 
					<font style="font-family:tahoma; font-size:17px; border-bottom:1px dotted #000;">Kredit : Rp. <span id="kredit">0,-</span></font>
				</div>
				-->
				
				<form id="inputdata" >
					<table class="table table-bordered table-striped">
						<thead style="background:#429489;">
							<tr>
								<th style="color:#fff; width:15%;">Kode Akun</th>
								<th style="color:#fff;">Nama Akun</th>
								<th style="color:#fff;width:15%;">Debet</th>
								<th style="color:#fff;width:15%;">Kredit</th>
								<th style="color:#fff;">Memo</th>
								<th style="background:#fff;width:1%;">
									<button type="button" onclick="tambahrow()" class="btn btn-xs btn-default">
										<span class="glyphicon glyphicon-plus-sign"></span>
									</button>
								</th>
							</tr>
						</thead>
						<tbody id="trxJU">
						<?php
						foreach($ju->result() as $row){
						?>
							<tr>
								<td>
									<div class='input-group'>
										<input value="<?php echo $row->kodeakun?>" readonly type='text' class='form-control' role='inputtext' />
										<input type='hidden' value="<?php echo $row->idakun?>" name='idakun[]' /><span onclick='openkodeakun(this)' role='glyphicongroup' class='input-group-addon glyphicon glyphicon-search'></span>
									</div>
								</td>
								<td><input readonly type='text' class='form-control' value="<?php echo $row->namaakun?>" role='inputtext' /></td>
								<td><div class='input-group'><span role="glyphicongroup" class='input-group-addon'>Rp. </span><input name="debet[]" onkeyup="formatNumber(this)" type="text" style="text-align:right;" class="form-control" role="inputtext" value="<?php echo number_format($row->debet)?>"></div></td>
								<td><div class='input-group'><span role="glyphicongroup" class='input-group-addon'>Rp. </span><input name="kredit[]" onkeyup="formatNumber(this)" type="text" style="text-align:right;" class="form-control" role="inputtext" value="<?php echo number_format($row->kredit)?>"></div></td>
								<td><input type="text" class="form-control" role="inputtext" name="memo[]" value="<?php echo $row->memo?>"></td>
								<td style="background:#fff;width:1%;">
									<button type="button" onclick="removed(this)" class="btn btn-xs btn-danger">
										<span class="glyphicon glyphicon-remove"></span>
									</button>
								</td>
							</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</form>
				<div class="pull pull-right">
					<button onclick="simpandata()" class="btn btn-sm btn-default">
						<span class="glyphicon glyphicon-save"></span>
						Simpan Data
					</button>
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
					<input type="hidden" id="parent" />
					
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
						?>
						<tr>
							<td>
								<?php echo $akunrows['kodeWithFormat']?>
								<input type="hidden" value="<?php echo $akunrows['id'] ?>" />
								<input type="hidden" value="<?php echo $akunrows['kodePlainText'] ?>" />
							</td>
							<td><?php echo $akunrows['namaWithFormat']?></td>
							<td>
								<?php
								if(substr($akunrows['kodePlainText'],0,5) == "1.1.1")
								{
									if($akunrows['level'] > 4)
									{
								?>
										<button type="button" onclick="pickup(this)" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus-sign"></span></button>
								<?php
									}
								}
								else
								{
									if($akunrows['level'] > 3)
									{
									?>
										<button type="button" onclick="pickup(this)" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus-sign"></span></button>
									<?php
									}
								}
								?>
							</td>
						</tr>
						<?php
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
		ColDebet.innerHTML = "<div class='input-group'><span role='glyphicongroup' class='input-group-addon'>Rp. </span><input onkeyup='formatNumber(this)' onblur='sumdebet(this)' style='text-align:right;' type='text' class='form-control' role='inputtext' name='debet[]' /></div>";
		ColKredit.innerHTML = "<div class='input-group'><span role='glyphicongroup' class='input-group-addon'>Rp. </span><input onkeyup='formatNumber(this)' onblur='sumkredit(this)' style='text-align:right;' type='text' class='form-control' role='inputtext' name='kredit[]' /></div>";
		ColMemo.innerHTML = "<input type='text' class='form-control' role='inputtext' name='memo[]' />";
		ColAksi.innerHTML = "<button onclick='removed(this)' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-remove'></span></button>";
	}
	
	function openkodeakun(obj)
	{
		var index = $(obj).parent().parent().parent().index();
		
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
		
		$("tbody#trxJU").find("tr").eq(index).find("td:eq(0)").find("input:eq(0)").val(kodeakun);
		$("tbody#trxJU").find("tr").eq(index).find("td:eq(0)").find("input:eq(1)").val(idakun);
		$("tbody#trxJU").find("tr").eq(index).find("td:eq(1)").find("input:eq(0)").val(namaakun);
		
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
	}
	
	function newNomor()
	{
		var target = "<?php echo site_url("akuntansi/generateNumber")?>";
			
		$.post(target, "", function(e){
		
			$("input[name='nomor']").val(e);
			
		});
	}
	
	function newNomorBukti()
	{
		var target = "<?php echo site_url("akuntansi/generateNumberBukti")?>";
			
		$.post(target, "", function(e){
		
			$("input[name='nomorbukti']").val(e);
			
		});
	}
	
	function simpandata()
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("akuntansi/updatedatajurnal")?>";
		
			data = {
				IDx		: $("input[name='idju']").val(),
				tanggal : $("input[name='tanggal']").val(),
				nomor	: $("input[name='nomor']").val(),
				nobukti	: $("input[name='nomorbukti']").val(),
				kontak	: $("select[name='kontak']").val(),
				uraian 	: $("textarea[name='uraian']").val(),
				serialize : $("form#inputdata").serialize()
			}
			
		$.post(target, data, function(e){
		
			
			var html = "<?php echo site_url("akuntansi/jurnalumum")?>";
			
			$("#main-body").load(html);
			
			$("#backDropOverlay").remove();
		});
	}
	
	function removed(obj)
	{
		$(obj).parent().parent().remove();
	}
</script>

