<?php

	$namaInstansi = $this->db->query("SELECT * FROM sys_perusahaan WHERE sys_perusahaan.id_perusahaan = '".$_SESSION['IDSekolah']."'")->first_row()->nama_perusahaan;

    $data = $this->db->query("select * from ref_narasi_calk");

    $pointA1 = '';
    $pointA2 = '';
    $pointBA = '';
    $pointBB = '';
    $pointBC = '';
    $pointBD = '';
    $pointBE = '';
    $pointBF = '';
    $pointBG = '';
    $pointD  = '';

    if ($data->num_rows() > 0 )
    {
      $pointA1 = $data->first_row()->point_a1;
      $pointA2 = $data->first_row()->point_a2;
      $pointBA = $data->first_row()->point_ba;
      $pointBB = $data->first_row()->point_bb;
      $pointBC = $data->first_row()->point_bc;
      $pointBD = $data->first_row()->point_bd;
      $pointBE = $data->first_row()->point_be;
      $pointBF = $data->first_row()->point_bf;
      $pointBG = $data->first_row()->point_bg;
      $pointD  = $data->first_row()->point_d;
    } 

?>
<section class="content-header">   
  <h4>Setting Narasi CALK</h4>     
</section>

<div class="widget-container" style="padding:10px;">
	
	<div class="row">
	
		<div class="col-md-12">
		<div class="notification"></div>
			<form action="laporan/UpdateCALKPointA" id="formPointA">
			  <div class="row">
				  <div class="col-md-12">
					<div class="">
					  <div class="box-header">
						<h4><span class="label label-success">A. UMUM</span></h4>
					  </div><!-- /.box-header -->
					   <div class="box-header">
						<h4><span class="label label-success">1. Pendirian Perusahaan dan Informasi Lainnya</span></h4>
					  </div><!-- /.box-header -->
					  <div class="box-body pad">
						  <textarea name="pointA1" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Letakkan kalimat narasi disini" class="textarea"><?php echo $pointA1 ?></textarea>
					  </div>  
					  <div class="box-header">
						<h4><span class="label label-success">2. Susunan Kepengurusan <?php echo $namaInstansi?> pada tahun <?php echo GetTahunPeriode()?> sbb:</span></h4>
					  </div><!-- /.box-header -->
					  <div class="box-body pad">
						  <textarea name="pointA2" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Letakkan kalimat narasi disini" class="textarea"><?php echo $pointA2 ?></textarea>
					  </div>
					</div><!-- /.box -->
				  </div><!-- /.col-->
			  </div> 
			</form>

			<div class="row">
				<div class="col-md-12">
				  <div class="box-header">
					<button class="btn btn-sm btn-success pull-right" onclick="simpanPointA()"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Simpan Point A</button>
				  </div>
				</div>
			</div>    
		</div>
	</div>
	
	<div class="row">
	
		<div class="col-md-12">
		<div class="notification"></div>
			<form action="laporan/UpdateCALKPointB" id="formPointB">
				<div class="row">
					<div class="col-md-12">
					  <div class="">
						<div class="box-header">
						  <h4><span class="label label-success">B. IKHTISAR KEBIJAKAN AKUNTANSI</span></h4>
						</div><!-- /.box-header -->
						 <div class="box-header">
						  <h4><span class="label label-success">a. Dasar Penyajian Laporan Keuangan</span></h4>
						</div><!-- /.box-header -->
						<div class="box-body pad">
							<textarea name="pointBA" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Letakkan kalimat narasi disini" class="textarea"><?php echo $pointBA; ?></textarea>
						</div>
						<div class="box-header">
						  <h4><span class="label label-success">b. Setara Kas</span></h4>
						</div><!-- /.box-header -->
						<div class="box-body pad">
							<textarea name="pointBB" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Letakkan kalimat narasi disini" class="textarea"><?php echo $pointBB; ?></textarea>
						</div>
						<div class="box-header">
						  <h4><span class="label label-success">c. Piutang Usaha</span></h4>
						</div><!-- /.box-header -->
						<div class="box-body pad">
							<textarea name="pointBC" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Letakkan kalimat narasi disini" class="textarea"><?php echo $pointBC; ?></textarea>
						</div>
						<div class="box-header">
						  <h4><span class="label label-success">d. Pihak-Pihak Yang Mempunyai Hubungan Istimewa</span></h4>
						</div><!-- /.box-header -->
						<div class="box-body pad">
							<textarea name="pointBD" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Letakkan kalimat narasi disini" class="textarea"><?php echo $pointBD; ?></textarea>
						</div>
						<div class="box-header">
						  <h4><span class="label label-success">e. Aset Tetap</span></h4>
						</div><!-- /.box-header -->
						<div class="box-body pad">
							<textarea name="pointBE" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Letakkan kalimat narasi disini" class="textarea"><?php echo $pointBE; ?></textarea>
						</div>
						<div class="box-header">
						  <h4><span class="label label-success">f. Pengakuan Pendapatan dan Beban</span></h4>
						</div><!-- /.box-header -->
						<div class="box-body pad">
							<textarea name="pointBF" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Letakkan kalimat narasi disini" class="textarea"><?php echo $pointBF; ?></textarea>
						</div>
						<div class="box-header">
						  <h4><span class="label label-success">g. Pajak Penghasilan</span></h4>
						</div><!-- /.box-header -->
						<div class="box-body pad">
							<textarea name="pointBG" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Letakkan kalimat narasi disini" class="textarea"><?php echo $pointBG; ?></textarea>
						</div>

					  </div><!-- /.box -->
					</div><!-- /.col-->
			  </div>
			</form>

			<div class="row">
				<div class="col-md-12">
				  <div class="box-header">
					<button class="btn btn-sm btn-success pull-right" onclick="simpanPointB()"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Simpan Point B</button>
				  </div>
				</div>
			</div>   
		</div>
	</div>
	
	<div class="row">
		
		<div class="col-md-12">
		<div class="notification"></div>
			<form action="laporan/UpdateCALKPointD" id="formPointD">
				<div class="row">
					<div class="col-md-12">
					  <div class="">
						 <div class="box-header">
						  <h4><span class="label label-success">D. PENJELASAN ATAS INFORMASI-INFORMASI NON KEUANGAN</span></h4>
						</div><!-- /.box-header -->
						<div class="box-body pad">
							<textarea name="pointD" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Letakkan kalimat narasi disini" class="textarea"><?php echo $pointD; ?></textarea>
						</div>
					  </div><!-- /.box -->
					</div><!-- /.col-->
				</div>

			</form>

			<div class="row">
				<div class="col-md-12">
				  <div class="box-header">
					<button class="btn btn-sm btn-success pull-right" onclick="simpanPointD()"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Simpan Point D</button>
				  </div>
				</div>
			</div> 
		</div>
	</div>
</div>

<script src="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<link href="<?php echo base_url()?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css" media="all" rel="stylesheet" type="text/css" />
<script>
     
    $('document').ready(function()
    {  
      $(".textarea").wysihtml5();
    });
	
	function sendRequestForm(requestUrl, requestData, objReference) {
		$.ajax({
			async: false,
			beforeSend: function() {
				$("body").append("<div class='backDropOverlay' id='backDropOverlay'><div><img src='assets/images/loading.gif'/><span>Processing..</span></div></div>");
			},
			complete: function() {
				$("#backDropOverlay").remove();
			},
			url: requestUrl,
			data: requestData,
			type: 'POST',
			success: function(data, textStatus) {
			
			
				$('body').find('#alertMessage').remove();
				$('body').find('.backDropOverlay').remove();
				(textStatus == 'success') ? $('.' + objReference).before(data): $('.' + objReference).html("<div class='alert alert-warning'  id='alertMessage'>Proses penerimaan data dari server tidak berhasil [" + textStatus + "] </div>");
			},
			error: function(data, textStatus) {
				$('body').find('#alertMessage').remove();
				$('body').find('.backDropOverlay').remove();
				$('.' + objReference).before("<div class='alert alert-warning' id='alertMessage'>Proses pengiriman data ke server tidak berhasil : [" + textStatus + "] </div>");
			}
		});

		$(window).scrollTop(0);
	}

    function simpanPointA()
    {
      $('#alertMessage').remove();
      sendRequestForm($('#formPointA').attr('action'), $('#formPointA').serialize(), 'notification:first');
    }

    function simpanPointB()
    { 
       $('#alertMessage').remove();
       sendRequestForm($('#formPointB').attr('action'), $('#formPointB').serialize(), 'notification:first');
    }

    function simpanPointD()
    {
      $('#alertMessage').remove();
      sendRequestForm($('#formPointD').attr('action'), $('#formPointD').serialize(), 'notification:first');
    }
</script>


















