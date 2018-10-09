<?php
	//echo "<pre>";print_r(json_decode($akun, true));"</pre>";
	//exit();
?>
<style>
	.table tbody > tr > td.form-input{
		padding:3px 3px 3px 5px !important;
	}
	
	.table tbody>tr>td{
		padding : 5px !important;
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

<h4>Setting Kode Akun</h4>
<div class="widget-container" style="padding:10px;">
	<div class="col-md-12">
		<div class="row" style="border-bottom:1px dashed #ccc; padding-bottom:10px; margin-bottom:10px;">
			<div style="padding:10px 0px;" class="pull pull-right">
				<button onclick="simpandata()" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-save"></span> Simpan Data</button>
			</div>
			<div class="pull pull-left">
				<span class="badge" style="background:#777;">
					<b>Total Aset :</b> Rp. <span id="nominalaset"></span>
					=
					<b>Total Kewajiban :</b> Rp. <span id="nominalkewajiban">0,00.-</span>
					<b>Total Ekuitas :</b> Rp. <span id="nominalekuitas">0,00.-</span>
					
				</span>
				
				<br>
				<span class="badge" style="background:#777;">
					<b>Total Saldo Awal :</b> Rp. <span id="nominalSaldoawal">0,00.-</span>
				</span>
			</div>
			<form id="formsaldoawal">
				<table class="table" id="tableakun">
					
					
					<?php
						
						$kodeakun = json_decode($akun, true);
						
						foreach($kodeakun as $rowAkun)
						{
						
							//echo "<pre>";print_r(substr($rowAkun['kodePlainText'],0,1));"</pre>";
							
							
							if(substr($rowAkun['kodePlainText'],0,1) != 4 && substr($rowAkun['kodePlainText'],0,1) != 5)
							{
							
							$saldoawal = $this->db->query("SELECT * FROM mst_saldo_awal WHERE mst_saldo_awal.id_akun = '".$rowAkun['id']."'");
							
							//echo "<pre>";print_r(substr($rowAkun['kodePlainText'],0,1));"</pre>";
							$saldoawal = (isset($saldoawal->first_row()->nominal)) ? $saldoawal->first_row()->nominal : 0;
						
							$background = ($rowAkun['level'] == 1) ? "#429489" : "";
							$color = ($rowAkun['level'] == 1) ? "#fff" : "#555";
							
							$kodeakun = ($rowAkun['level'] >= 3 ) ? $rowAkun['kodePlainText'] : $rowAkun['kodeWithFormat'];
							$namaakun = ($rowAkun['level'] >= 3 ) ? $rowAkun['namaPlainText'] : $rowAkun['namaWithFormat'];
							$space = ($rowAkun['level'] >= 3 ) ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : "";
							
							$checklevel = $this->db->query("SELECT MAX(mst_akun.level) as lev FROM mst_akun WHERE CONCAT(mst_akun.kode_induk,'.',mst_akun.kode_akun) LIKE '".$rowAkun['kodePlainText']."%'");
					
							$level = ($checklevel->first_row()->lev > 0) ? $checklevel->first_row()->lev  : "";
							
							$namaakun = ($rowAkun['level'] < $level ) ? "<b>".$namaakun."</b>" : $namaakun;
							
							$refAkunlink = $this->db->query("SELECT * FROM ref_akunlink WHERE ref_akunlink.id_akunlink IN (2,3)");
							
							foreach($refAkunlink->result() as $checkAkun)
							{
								$data['idakun'][] = $checkAkun->id_akun;
							}
						
							$displaRow = (in_array($rowAkun['id'], $data['idakun'])) ? "display:none;" : "";
							
							$saldoawal = (in_array($rowAkun['id'], $data['idakun'])) ? 0 : $saldoawal;
						?>
								<tr style="background: <?php echo $background; ?>; color:<?php echo $color;?>; <?php echo $displaRow;?>">
									
									<td>
									<input type="hidden" name="id_akun[]" value="<?php echo $rowAkun['id']?>" />
									<input type="hidden" value="<?php echo substr($rowAkun['kodePlainText'],0,1)?>" />
									<?php echo $space.$namaakun?></td>
									<td style="width:10%;width:20%;">
									<?php
										$display = ($rowAkun['level'] == $level ) ? "" : "display:none";
									?>
										<div style="<?php echo $display ?>"  class="input-group">
											<span style="padding: 0px 10px !important;" class="input-group-addon">Rp. </span>
											<input style="text-align:right;" onblur="hitungsaldoawal(this)" value="<?php echo $saldoawal ?>"  type="text" role="numeric" class="form-control" name="saldoawal[]" />
										</div>
									<?php
										
									}
									?>
									</td>
								</tr>
					<?php
							
						}
					?>
					
				
				</table>
				<input type="hidden" name="totalasset" value="<?php echo @$aset->first_row()->total ?>" />
				<input type="hidden" name="totalkewajiban" value="<?php echo @$kewajiban->first_row()->total ?>" />
				<input type="hidden" name="totalekuitas" value="<?php echo @$ekuitass->first_row()->total ?>" />
				
				<input type="hidden" name="totalsaldoawal"  />
			</form>
			<div class="pull pull-right">
				<button type="button" onclick="simpandata()" class="btn btn-sm btn-default">
					<span class="glyphicon glyphicon-save"></span>
					Simpan data
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		getdatasaldo();
		
		initAutoNumeric()
	});
	
	function initAutoNumeric()
    {

       

        $('[role="numeric"]').autoNumeric("init",{
            aSep: ',',
            aDec: '.', 
            nBracket: '(,)',
            mDec: 2
        });
    }
	
	function formatNumber(objSource) 
	{
		a = $(objSource).val();
        //b = a.replace(/[^\d]/g, "");
        b = a.replace(/[^0-9,'.']-/g,"");
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
	
	function getdatasaldo()
	{
		var totalaset = (isNaN(eval($("input[name='totalasset']").val()))) ? 0 : eval($("input[name='totalasset']").val());
		var totalkewajiban = (isNaN(eval($("input[name='totalkewajiban']").val()))) ? 0 : eval($("input[name='totalkewajiban']").val());
		var totalekuitas = (isNaN(eval($("input[name='totalekuitas']").val()))) ? 0 : eval($("input[name='totalekuitas']").val());
		
		
		$("span#nominalaset").text(totalaset.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
		$("span#nominalkewajiban").text(totalkewajiban.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
		$("span#nominalekuitas").text(totalekuitas.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
		
		var totalSA = totalaset - (totalkewajiban + totalekuitas);
		
		console.log(totalSA);
		
		$("span#nominalSaldoawal").text(totalSA.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
		
		$("input[name='totalsaldoawal']").val(totalSA);
	}
	
	
	function hitungsaldoawal(obj)
	{
	
		var total = 0;
		var kode = $(obj).parent().parent().parent().find("td:eq(0)").find("input:eq(1)").val();
		
		var loop = $("table#tableakun").find("tr");
		
		$.each(loop, function(i, v){
			var col = $(v).find("td:eq(0)").find("input:eq(1)").val();
			var nominal = $(v).find("td:eq(1)").find("input:eq(0)").val();
			
			if(col == kode)
			{
				if(nominal.substr(0,1) == "(")
				{
					nominal = nominal.replace("(","");
					nominal = nominal.replace(")","");
					nominal = nominal.replace(/,/ig, "");
					nominal = nominal*-1;
					
				}
				else
				{
				
					nominal = nominal.replace(/,/ig, "");
				}
				
				
			
				total +=+ nominal;
				
			}
		});
		
		if(kode == "1")
		{
			var textinput = "totalasset";
		}
		else if(kode == "2")
		{
			var textinput = "totalkewajiban";
		}
		else if(kode == "3")
		{
			var textinput = "totalekuitas";
		}
		
		
		console.log(textinput);
		
		$("input[name='"+textinput+"']").val(total);
		
		var totalaset = (isNaN(eval($("input[name='totalasset']").val()))) ? 0 : eval($("input[name='totalasset']").val());
		var totalkewajiban = (isNaN(eval($("input[name='totalkewajiban']").val()))) ? 0 : eval($("input[name='totalkewajiban']").val());
		var totalekuitas = (isNaN(eval($("input[name='totalekuitas']").val()))) ? 0 : eval($("input[name='totalekuitas']").val());
		
		
		var totalSA = totalaset - (totalkewajiban + totalekuitas);
		
		
		
		$("span#nominalaset").text(totalaset.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
		$("span#nominalkewajiban").text(totalkewajiban.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
		$("span#nominalekuitas").text(totalekuitas.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
		$("span#nominalSaldoawal").text(totalSA.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
		
		$("input[name='totalsaldoawal']").val(totalSA);
		
		console.log(totalekuitas);
	}
	
	function simpandata()
	{
		var a = isNaN(eval($("input[name='totalasset']").val()));
		var b = isNaN(eval($("input[name='totalkewajiban']").val()));
		var c = isNaN(eval($("input[name='totalekuitas']").val()));
		var sa = isNaN(eval($("input[name='totalsaldoawal']").val()));
		
		
		
	
		$("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
		
		var target = "<?php echo site_url("akuntansi/simpansaldoawal")?>";
			data = $("#formsaldoawal").serialize();
			
		$.post(target, data, function(e){
			console.log(e);
			
			
			
			
			$("#backDropOverlay").remove();
		});
	}
</script>

