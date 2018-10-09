<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Aplikasi Billing Keuangan</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/form-elements.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/style2.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

    </head>

    <body>

        <!-- Top content -->
        <div class="top-content">
        	
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1><strong>Kuitansi</strong> Software Keuangan</h1>
                            <div class="description">
                            	<p style="line-height:20px;">
	                            	Mempermudahkan anda dalam pencatatan keuangan usaha anda. 
	                            	Dari pencatatan uang masuk, uang keluar hingga pelaporan keuangan.
                            	</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                        	<div class="form-top" style="padding-bottom:0px !important;">
                        		<div class="form-top-left">
                        			<h3 style="line-height:10px !important; color:#fff; margin-bottom:5px !important;">Daftar akun</h3>
									<p style="color:#fff;">Lengkapi data anda untuk dapat login :</p>
								</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-user"></i>
                        		</div>
                            </div>
                            <div class="form-bottom">
								<div class="information"></div>
			                    <form role="form" id="formregister" action="<?php echo site_url("user/simpanregister")?>" method="post" class="login-form">
			                    	<div class="form-group">
									
										<select class="form-control" onchange="changeprov(this)" name="provinsi">
											<option value="">:: Pilih Provinsi ::</option>
											<?php 
												foreach($provinsi->result() as $prov)
												{
											?>
												<option value="<?php echo $prov->id_propinsi."#".$prov->kode_propinsi ?>"><?php echo $prov->nama_propinsi?></option>
											<?php
												}
											?>
										</select>
									</div>
									<div class="form-group">
			                    		<select class="form-control" onchange="changekab(this)" name="kabupaten" id="kabupaten">
											<option value="">:: Pilih Kabupaten ::</option>
										</select>
			                        </div>
			                        <div class="form-group">
										<input type="hidden" name="kodeprovinsi" value="" />
										<input type="hidden" name="kodekabupaten" value="" />
										<div class="input-group">
											<span class="input-group-addon" id="kodeprovinsi" ></span>
											<span class="input-group-addon" id="kodekabupaten" ></span>
											<input readonly style="background:;text-align:left" class="form-control input-group-addon" id="noreg" name="noreg" value='0' />
										</div>
									</div>
									<div class="form-group">
			                        	<input type="text" name="namalembaga" placeholder="Nama Lembaga" class="form-control" />
			                        </div>
									<div class="form-group">
			                        	<input type="text" name="email" placeholder="Email" class="form-control" />
			                        </div>
									<div class="form-group">
			                        	<input type="text" name="telp" placeholder="No. Telp" class="form-control" />
			                        </div>
									<div class="form-group">
			                        	<input type="text" name="username" placeholder="Username" class="form-control" />
			                        </div>
									<div class="form-group">
			                        	<input type="text" name="namalengkap" placeholder="Nama Lengkap" class="form-control" />
			                        </div>
									<div class="form-group">
			                        	<input type="password" name="password" placeholder="Password" class="form-control" />
			                        </div>
			                        <div class="form-group">
			                        	<textarea name="alamat" placeholder="Alamat ..." class="form-control"></textarea>
			                        </div>
									<button type="submit" class="btn">Simpan Akun</button>
			                    </form>
		                    </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>


        <!-- Javascript -->
        <script src="<?php echo base_url()?>assets/js/jQuery-2.1.3.min.js"></script>
        <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url()?>assets/js/jquery.backstretch.min.js"></script>
        <script src="<?php echo base_url()?>assets/js/scripts.js"></script>
        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->
		
		<!-- Tambah Data baru -->
		<div class="modal fade" id="modalNotif">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row">
							<p>Selamat, akun anda telah berhasil dibuat</p>
							<span>Akun anda telah terdaftar. Silahkan tunggu konfirmasi pengaktifan akun dari admin. Terima kasih telah mendaftarkan diri anda.</span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" onclick="finish()" class="btn btn-xs">Selesai</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

    </body>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#formregister").submit(function(e){
			
				e.preventDefault();
				
				var target = $(this).attr("action");
					data = $(this).serialize();
					
				$.post(target, data, function(e){
					$("#modalNotif").modal("show");
				});
			});
		});
		
		function finish()
		{
			var target = "<?php echo site_url("")?>";
			window.open(target);
		}
		
		function changeprov(obj)
		{
			$("select#kabupaten").html("");
			
			var target = "<?php echo site_url("user/getkabupaten")?>";
				prov = $(obj).val();
				prov = prov.split("#");
				id_prov = prov[0]; 
				kode_prov = prov[1]; 
				
				data = {
					idprov : id_prov,
					kodeprov : kode_prov
				}
				
			$.post(target, data, function(e){
				
				var json = $.parseJSON(e);
				$("select#kabupaten").append(json.html);
				
			});
		}
		
		function changekab(obj)
		{
			var prefixprov = $("select[name='provinsi']").val();
			var prefixkab = $("select[name='kabupaten']").val();
			
			var	prov = prefixprov.split("#");
			var	kab = prefixkab.split("#");
				
			var	kodeprov = prov[0];
			var	kodekab = kab[0];
			
			$("input[name='kodeprovinsi']").val(kodeprov);
			$("input[name='kodekabupaten']").val(kodekab);
			
			$("span#kodeprovinsi").text(kodeprov);
			$("span#kodekabupaten").text(kodekab);
			
			var target = "<?php echo site_url("user/getnoreg")?>";
				data = {
					idprov : kodeprov,
					idkab : kodekab,
				}
			
			$.post(target, data, function(e){
				
				$("input[name='noreg']").val(e);
			});
		}
	</script>

</html>