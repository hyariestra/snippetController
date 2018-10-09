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
		
		<div class="form-horizontal">
			<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Tanggal</label>
						<div class="col-md-8">
							<div class="input-group">
								<input type="text" name="tanggal" value="<?php echo date("d-m-Y")?>" role="tanggal" readonly class="form-control" />
								<span class="input-group-addon glyphicon glyphicon-calendar"></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nomor</label>
						<div class="col-md-8">
							<input type="text" name="nomor"  class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nomor Bukti</label>
						<div class="col-md-8">
							<input type="text" name="nomorbukti"  class="form-control" />
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Uraian</label>
						<div class="col-md-8">
							<textarea name="uraian" class="form-control" rows="6"></textarea>
							<span class="badge" style="margin-top:5px; color:#f9f9f9;"><b>Note:</b> Perhatikan tanda (*) adalah harus diisi</span>
						</div>
					</div>
				</div>
			
				
				<div style="margin-bottom:10px;">
					<input type="hidden" id="nominaldebet" />
					<input type="hidden" id="nominalkredit" />
					<font style="font-family:tahoma; font-size:17px; border-bottom:1px dotted #000;">Debet : Rp. <span id="debet">0,-</span></font> 
					<font style="font-family:tahoma; font-size:17px; border-bottom:1px dotted #000;">Kredit : Rp. <span id="kredit">0,-</span></font>
				</div>
				
				<button type="button" onclick="ambiltemplate()" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-folder-open"></span> Ambil Template</button>
				<button type="button" onclick="tambahtemplate()" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-floppy-disk"></span> Simpan Template</button>
				
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
						<tbody id="trxJU"></tbody>
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
					<form id="s_form">
						<div class="row" style="margin:0px 0px 10px 0px;">
							<div class="col-md-6 pull pull-right ">
								<div class="input-group">
									<input type="text" placeholder="pencarian data . . . ." role='inputtext' class="form-control" name="s_akun" />
									<span onclick="carikodeakun()" role="glyphicongroup" class='input-group-addon glyphicon glyphicon-search'></span>
								</div>
							</div>
						</div>
					</form>
					<table class="table table-bordered table-striped" style="background:#fff;">
						<thead>
							<tr>
								<th>Kode</th>
								<th>Nama Akun</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tableakun">
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
						</tbody>
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

<div class="modal fade" id="modalsimpanjurnal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 10px 0px 0px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
        <h5 style="padding: 0px 15px 0px 15px;">Simpan Template</h5>
      </div>
      <div class="modal-body">
        <div class="row">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<div style="background:#fff;padding:10px;">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-md-3">Nama Template :</label>
								<div class="input-group col-md-9">
									<input class="form-control" type="text" name="namatemplate">
									<span class="input-group-addon glyphicon glyphicon-floppy-disk"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<button type="button" onclick="simpantemplate()" class="btn btn-sm btn-default" style="margin-top:10px;">
					<span class="glyphicon glyphicon-plus-sign"></span>
					Simpan Template
				</button>
				
			</div>
		</div>
      </div>
	  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="ambiltemplatejurnal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 10px 0px 0px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
        <h5 style="padding: 0px 15px 0px 15px;">Ambil Template</h5>
      </div>
      <div class="modal-body">
        <div class="row">
	        <div class="col-md-12">
				<div style="background:#429489;min-height:0px;" class="widget-container padded">
					<div style="background:#fff;padding:10px;">
						<div class="form-horizontal">
							<table class="table table-bordered table-striped">
								<thead>
									<tr style="background:#429489; color:#fff;">
										<th>#</th>
										<th>Nama Template</th>
										<th style="width:15%;">Aksi</th>
									</tr>
								</thead>
								<tbody id="tabletmp"></tbody>
							</table>
							
							<span style="font-size:12px;"><b>Note : </b> Silahkan klik tombol <button type="button" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></button> untuk memlih data.</span>
						</div>
					</div>
				</div>
				
				
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
		
		$("#s_form").submit(function(e){
			e.preventDefault();
			var target = "<?php echo site_url("akuntansi/carikodeakun")?>";
			data = {
				akun : $("input[name='s_akun']").val()
			}
			
			$.post(target, data, function(e){
				
				var json = $.parseJSON(e);
				
				fillgridakun(json);
				
			});
		});
	});
	
	function fillgridakun(json)
	{
		var table = document.getElementById("tableakun");
			table.innerHTML = "";
			
		for(i = 0; i < json.length; i++)
		{
			var row = table.insertRow();
			
			var KodeAkun = json[i].kodeWithFormat;
			var NamaAkun = json[i].namaWithFormat;
			var btnAksi = "";
			
			var ColKode = row.insertCell(0);
			var ColAkun = row.insertCell(1);
			var ColAksi = row.insertCell(2);
			
			if(json[i].kodePlainText.substring(0,5) == "1.1.1")
			{
				if(json[i].level > 4)
				{
					btnAksi = '<button type="button" onclick="pickup(this)" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus-sign"></span></button>';
				}
			}
			else
			{
				if(json[i].level > 3)
				{
					btnAksi = '<button type="button" onclick="pickup(this)" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus-sign"></span></button>';
				}
			}
			ColKode.innerHTML = KodeAkun+'<input type="hidden" value="'+json[i].id+'" /><input type="hidden" value="'+json[i].kodePlainText+'" />';
			ColAkun.innerHTML = NamaAkun;
			ColAksi.innerHTML = btnAksi;
			
			
		}
	}
	
	function ambiltemplate()
	{
		var target = "<?php echo site_url("akuntansi/gettemplate")?>";
		
		$.post(target, "", function(e){
			
			var json = $.parseJSON(e);
			
			filldatatemplate(json);
			
		})
		.done(function(){
			
			$("#ambiltemplatejurnal").modal("show");
			
		});
	}
	
	function filldatatemplate(json)
	{
		var table = document.getElementById("tabletmp");
			table.innerHTML = "";
			
		for(i = 0; i < json.data.length; i++)
		{
			var row = table.insertRow();
			
			var	IDJurnalTmp = json.data[i].idJurnalTmp;
				NamaTmp = json.data[i].namatemplate;
				Aksi = json.data[i].aksi;
				Seq = eval(i) + eval(1);
				
			var ColSeq = row.insertCell(0);
				ColNama = row.insertCell(1);
				ColAksi = row.insertCell(2);
				
			ColSeq.innerHTML = Seq;
			ColNama.innerHTML = NamaTmp;
			ColAksi.innerHTML = Aksi;
		}
		
	}



  	 function deleteTmp(obj,IDP)
  	 {
  	 	var isdelete = confirm("Apakah anda yakin akan menghapus data ini?");

  	 	if(isdelete)
  	 	{
  	 		var target="<?php echo site_url('akuntansi/deleteTemplate')?>";
  	 		data={id_pendaftar:IDP};
  	 		row = $(obj).parent().parent().remove();
  	 		$.post(target,data,function(e){
  	 			var jojon 	= $.parseJSON(e);

  	 			if(jojon.flag)
  	 			{
  	 				
  	 				alert('Data template berhasil dihapus...');
  	 			}
  	 			else
  	 			{
  	 				alert('Data template gagal dihapus...');
  	 			}

  	 			$('html, body').css('overflow-y','auto');
  	 		});
  	 	}
  	 }
	
	
	function tambahtemplate()
	{
		$("#modalsimpanjurnal").modal("show");
	}
	
	function simpantemplate()
	{
		var datatable = $("tbody#trxJU").find("tr");
			template = $("input[name='namatemplate']").val();
			
			$("tbody#trxJU").find("tr").find("td").find("input").css({"background" : "#fff"});
			$("tbody#trxJU").find("tr").find("td").find("input").css({"color" : "#000"});
			
			flag = false;
			
		
		$.each(datatable, function(i, v){
			
			var data = $(this).find("td:eq(0)").find("input:eq(1)").val();
			
			if((data == undefined || data == ""))
			{
				$(this).find("td").find("input").css({"background" : "#d9534f", "color" : "#fff"});
				
				flag = true;
				
			}
				
		});
		
		if(!flag && datatable.length > 0)
		{
			$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
			var target = "<?php echo site_url("akuntansi/simpantemplate")?>";
				data = {
					namatemplate : $("input[name='namatemplate']").val(),
					serialize : $("#inputdata").serialize()
				}
				
			$.post(target, data, function(e){
				
				
				console.log(e);
			})
			.done(function(){
				$("#modalsimpanjurnal").modal("hide");
				$("#backDropOverlay").remove();
			});
		
		}
		else
		{
			alert("Silahkan periksa kembali inputan anda.");
		}
	}
	
	function getTmp(id)
	{
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("akuntansi/putTemplate")?>";
		
			data = {
				idx : id
			}
			
		$.post(target, data, function(e){
			
			var json = $.parseJSON(e);
			
			InsertRowTmp(json);
			
		})
		.done(function(){
			
			$("#ambiltemplatejurnal").modal("hide");
			
			$("#backDropOverlay").remove();
			
		});
	}
	
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
	
	function InsertRowTmp(data)
	{
		var table = document.getElementById("trxJU");
			table.innerHTML = "";
			
		for(i = 0; i < data.json.length; i++)
		{
			var row = table.insertRow();
			
			var IDx = data.json[i].idJurnalTemp;
			var IDAkun = data.json[i].IDAkun;
			var KodeAkun = data.json[i].KodeAkun;
			var NamaAkun = data.json[i].NamaAkun;
			var Debet = data.json[i].Debet;
			var Kredit = data.json[i].Kredit;
			var Memo = data.json[i].Memo;
			
			var ColKodeAkun = row.insertCell(0);
			var ColNamaAkun = row.insertCell(1);
			var ColDebet = row.insertCell(2);
			var ColKredit = row.insertCell(3);
			var ColMemo = row.insertCell(4);
			var ColAksi = row.insertCell(5);
		
			ColKodeAkun.innerHTML = "<div class='input-group'><input readonly value='"+KodeAkun+"' type='text' class='form-control' role='inputtext' /><input type='hidden' value='"+IDAkun+"' name='idakun[]' /><span onclick='openkodeakun(this)' role='glyphicongroup' class='input-group-addon glyphicon glyphicon-search'></span></div>";
			ColNamaAkun.innerHTML = "<input readonly type='text' value='"+NamaAkun+"' class='form-control' role='inputtext' />";
			ColDebet.innerHTML = "<div class='input-group'><span role='glyphicongroup' class='input-group-addon'>Rp. </span><input onkeyup='formatNumber(this)' onblur='sumdebet(this); lock(this, \"debet\");' style='text-align:right;' type='text' class='form-control' value='"+formatCurrency(Debet)+"' role='inputtext' name='debet[]' /></div>";
			ColKredit.innerHTML = "<div class='input-group'><span role='glyphicongroup' class='input-group-addon'>Rp. </span><input onkeyup='formatNumber(this)' onblur='sumkredit(this); lock(this, \"kredit\");' style='text-align:right;' type='text' class='form-control' value='"+formatCurrency(Kredit)+"' role='inputtext' name='kredit[]' /></div>";
			ColMemo.innerHTML = "<input type='text' class='form-control' role='inputtext' value='"+Memo+"' name='memo[]' />";
			ColAksi.innerHTML = "<button onclick='removed(this)' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-remove'></span></button>";
	
		}
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
			var json = $.parseJSON(e);
			fillgridakun(json);
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
		var debet = $("input#nominaldebet").val().replace(/,/ig, "");
		var kredit = $("input#nominalkredit").val().replace(/,/ig, "");
		
		if(debet != kredit)
		{
			alert("Data tidak balance. silahkan cek data anda kembali.");
			return false;
		}	
		
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("akuntansi/simpandatajurnal")?>";
		
			data = {
				tanggal : $("input[name='tanggal']").val(),
				nomor	: $("input[name='nomor']").val(),
				nobukti	: $("input[name='nomorbukti']").val(),
				uraian 	: $("textarea[name='uraian']").val(),
				serialize : $("form#inputdata").serialize()
			}
			
		$.post(target, data, function(e){
			console.log(e);
			
			var html = "<?php echo site_url("akuntansi/jurnalumum")?>";
			
			$("#main-body").load(html);
			
			$("#backDropOverlay").remove();
		});
	}
	
	function removed(obj)
	{
		$(obj).parent().parent().remove();
	}
	
	function formatCurrency(nominal)
	{
		var nilai = nominal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
		
		return nilai;
	}
</script>

