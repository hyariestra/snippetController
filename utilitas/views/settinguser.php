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
	<h4>Setting User</h4>
</div>
<div class="widget-container" style="padding:10px;">
	<div class="row">
		<div class="col-md-6">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-md-2 control-label">Group User</label>
					<div class="col-md-8">
						<select onchange="changegroup(this)" name="idgroup" class="form-control">
							<option value="-">:: Pilih Group User ::</option>
							<?php
								foreach($groupuser->result() as $row)
								{
							?>
								<option value="<?php echo $row->id_group?>"><?php echo $row->nama_group?></option>
							<?php
								}
							?>
						</select>
					</div>
				</div>
			</div>
			
			<div style="border:1px solid #ccc; background:#f5f5f5; min-height:500px; padding:10px;">
				<h4>List User</h4>
				<ul id="list-user"></ul>
			</div>
			
			
		</div>
		
		<div class="col-md-6">
			<table class="table table-bordered table-striped">
				<tr>
					<th style="width:1%;"><input style="display:inline;" type="checkbox" id="checkall"></th>
					<th style="width:15%;">Kode Modul</th>
					<th>Nama Modul</th>
				</tr>
				<tbody id="listmodul">
				<?php
					 echo displaymodul();
				?>
				</tbody>
			</table>
			<button onclick="simpandata()" class="btn btn-sm btn-default" style="margin-top:10px;">
				<span class="glyphicon glyphicon-save"></span>
				Simpan Data
			</button>
			
		</div>
	</div>
</div>

<?php
	function displaymodul()
	{
		$CI =& get_instance();
		$table ="";
		$menu = $CI->db->query("SELECT (SELECT CAST(REPLACE(kode_modul,'.','') AS UNSIGNED) ) AS kodeUrut, 
		sys_modul.id_modul, 
		sys_modul.nama_modul,
		sys_modul.induk_menu
		FROM sys_modul WHERE sys_modul.header = 1 AND sys_modul.aktif = 1
		ORDER BY kodeUrut");
		
		
		foreach($menu->result() as $row)
		{
			$table .= "	<tr style='font-weight:bold;'>
							<td><input type='checkbox' style='display:inline;' value='".$row->id_modul."' name='id_modul[]'></td>
							<td>".$row->kodeUrut."</td>
							<td>".$row->nama_modul."</td>
						</tr>";
			
			$childmenu = $CI->db->query("SELECT (SELECT CAST(REPLACE(kode_modul,'.','') AS UNSIGNED) ) AS kodeUrut, 
			sys_modul.kode_modul,
			sys_modul.id_modul, 
			sys_modul.nama_modul 
			FROM sys_modul WHERE sys_modul.header <> 1 AND sys_modul.aktif = 1
			AND induk_menu = '".$row->id_modul."'
			ORDER BY kodeUrut");
			
			foreach($childmenu->result() as $child)
			{
				
				$table .= "	<tr>
								<td><input type='checkbox' style='display:inline;' value='".$child->id_modul."' name='id_modul[]'></td>
								<td style='padding-left:20px;'>".$child->kode_modul."</td>
								<td style='padding-left:20px;'>".$child->nama_modul."</td>
							</tr>";
			}
		}
		
		echo $table;
		
	}
?>

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
	
	function changegroup(obj)
	{
		$("body").append("<div class='backDropOverlay' id='backDropOverlay'><div><img src='assets/img/loading.gif'/><span>Loading..</span></div></div>");
		
		var idgroup = $(obj).val();
		var target = "<?php echo site_url("utilitas/getusergroup")?>";
			data = {
				idgroup : idgroup
			}
		
		$.post(target, data, function(e){
			
			$("ul#list-user").html("");
			
			$("input[name='id_modul[]']").prop("checked", false);
			
			var json = $.parseJSON(e);
			
			if(json.flag_user)
			{
				var app = "";
				$.each(json.user, function(i, v){
					app += "<li><span class='glyphicon glyphicon-user'></span> "+v.namauser+"</li>";
				});
				
				$("ul#list-user").append(app);
				
				
				var modul = $("#listmodul").find("tr");
				
				var modules = []
				$.each(modul, function(i, v){
					var idmodul = $(this).find("td").eq(0).find("input[name='id_modul[]']").val();
					
					modules.push(parseInt(idmodul));
					
				});
				
				
				if(json.flag)
				{
					for(ii = 0; ii < json.modul.length; ii++)
					{
						
						if($.inArray(json.modul[ii].idmodul, modules))
						{
							$("input[name='id_modul[]'][value='"+json.modul[ii].idmodul+"']").prop("checked",true);
							console.log(json.modul[ii].idmodul+ " ====== TRUE" );
						}
						else
						{
							
							console.log(json.modul[ii].idmodul+ " ====== FALSE" );
						}
						
					}
				}
			}
			//console.log(modules);
			$("#backDropOverlay").remove();
		});
		
	}
	
	function simpandata()
	{
		$("body").append("<div class='backDropOverlay' id='backDropOverlay'><div><img src='assets/img/loading.gif'/><span>Loading..</span></div></div>");
		
		var ch		 = $("tbody#listmodul").find("input[type='checkbox']");
		var sel 	 = false;
		var modul	 = [];
		
		ch.each(function(){
			
			$this = $(this);
			if($this.is(":checked")){
				sel = true; //set to true if there is/are selected row
				//$this.parent().parent().remove(); 
				//remove row when animation is finished
				moduls = $this.val();
				
				modul.push(moduls);
			}
			
		});
		
		var target = "<?php echo site_url("utilitas/simpandatauser")?>";
			data = {
				idgroup : $("select[name='idgroup']").val(),
				module : modul
			}
			
		$.post(target, data, function(e){
			console.log(e);
			
			$("#backDropOverlay").remove();
		});
	}

</script>