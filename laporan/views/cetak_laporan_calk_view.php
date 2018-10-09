

<style type="text/css">
  .table tbody > tr > td.form-input{
    padding:3px 3px 3px 5px !important;
  }
  
  ul li{
    list-style-type: none;
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
	<h4>Laporan CALK</h4>
</div>
<div class="widget-container" style="padding:10px;">
	<div class="row" style="border-bottom:1px dashed #429489; padding-bottom:10px;">
		<div class="col-md-6">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-md-3 control-label">Periode</label>
					<div class="col-md-9">
						<select onchange="getperiode(this)" name="periode" class="form-control">
							<option value="Bulan">Bulan</option>
							<option value="Triwulan">Triwulan</option>
							<option value="Semester">Semester</option>
							<option value="Tahun">Tahun</option>
						</select>
					</div>
				</div>
				<div class="form-group">
          <label class="col-md-3 control-label" id="labelchange">Bulan</label>
          <div class="col-md-9" id="selectchange">
            <select name="nilai" class="form-control">
            <?php for($i = 1; $i <=12; $i++){ ?>
              <option value="<?php echo $i?>"><?php echo convertBulan($i) ?></option>
            <?php } ?>
            </select>
          </div>
        </div>
				<div class="form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-9">
						<button type="button" onclick="previewcalk()" class="btn btn-sm btn-warning">
							<span class="glyphicon glyphicon-search"></span>
							Print Preview
						</button>
						<button type="button" onclick="printdatacalk()" class="btn btn-sm btn-success">
							<span class="glyphicon glyphicon-print"></span>
							Print Data
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
  <div id="body-content"></div>

</div>
<?php
/*
  $content = ob_get_clean();
  // conversion HTML => PDF
  require_once 'assets/plugins/html2pdf_v4.03/html2pdf.class.php'; // arahkan ke folder html2pdf
  try
  {
    $html2pdf = new HTML2PDF('P','Legal','en', false, 'ISO-8859-15',array(10, 10, 10, 5)); //setting ukuran kertas dan margin pada dokumen anda
    //$html2pdf->setModeDebug();
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Laporan_CALK.pdf');
  }
  catch(HTML2PDF_exception $e) { echo $e; } 
 */
?>

<script>

  $(document).ready(function(){
    
    $("input[role='tanggal']").datepicker({
      "autoclose" : true,
      "format" : "dd-mm-yyyy"
    });
    
  });

  function previewcalk()
  {
    $("body").append("<div style='position:fixed !important;' class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Loading..</span></div></div>");
    var target = "<?php echo site_url("laporan/previewlaporancalk")?>";
      data = {
        periode : $("select[name='periode']").val(),
        nilai : $("select[name='nilai']").val(),
      }
    
    $("#body-content").html('');

    $.post(target, data, function(e){
      $("#body-content").html(e);
      $("#backDropOverlay").remove();
    });
  }

  function printdatacalk()
  {
    previewcalk();
    var peri = $("select[name='periode']").val();
        nil = $("select[name='nilai']").val();
    var target = "<?php echo site_url("laporan/previewlaporancalk")?>/pdf/"+peri+"/"+nil;
    window.open(target);
  }
  
  function getperiode(obj)
  {
    var val   = $(obj).val();
      target  = "<?php echo site_url("laporan/getperiode")?>";
      data = {
        periode : $("select[name='periode']").val()
      }

    $.post(target, data, function(e){
      var json = $.parseJSON(e);
      console.log(json);

      $("#selectchange").html(json.select);
      $("#labelchange").html(json.label);
    });
      
  }
</script>