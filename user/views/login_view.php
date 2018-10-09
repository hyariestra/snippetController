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
                        			<h3 style="line-height:10px !important; color:#fff; margin-bottom:5px !important;">Login Sistem Billing</h3>
                            		<p style="color:#fff;">Masukan username dan password untuk login:</p>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-lock"></i>
                        		</div>
                            </div>
                            <div class="form-bottom">
								<div class="information"></div>
			                    <form role="form" id="formlogin" action="<?php echo site_url("user/login")?>" method="post" class="login-form">
			                    	<div class="form-group">
									
										<select class="form-control" name="databases">
											<option value="-">:: Pilih Database ::</option>
										<?php
										
											$dbselect = (isset($_GET['accesskey'])) ? base64_decode($_GET['accesskey']) : "bumdes";
											
											
											setDatabase($database = "default");
											
										
											$dbShow = $this->db->query("SHOW DATABASES");
												
											
											foreach($dbShow->result_array() as $row){
											
											$isMatch = strstr($row['Database'], "syncore_genio_".$dbselect);
											
											if($isMatch)
											{
										?>
											<option value="<?php echo $row['Database']?>"><?php echo $row['Database']?></option>
										<?php
											}
										}
										?>
										</select>
									</div>
									<div class="form-group">
			                    		<label class="sr-only" for="form-username">Username</label>
			                        	<input type="text" name="username" placeholder="Username..." class="form-username form-control" id="form-username">
			                        </div>
			                        <div class="form-group">
			                        	<label class="sr-only" for="form-password">Password</label>
			                        	<input type="password" name="password" placeholder="Password..." class="form-password form-control" id="form-password">
			                        </div>
									
									<a style="color:#000; margin-bottom:10px;" class="btn btn-xl btn-default pull pull-right" href="<?php echo site_url("user/register") ?>">
										<span class="glyphicon glyphicon-user"></span>
										Daftar Akun
									</a>
			                        
									<button type="submit" class="btn">Masuk Sekarang</button>
									
									
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

    </body>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#formlogin").submit(function(e){
			
				e.preventDefault();
				
				var target = $(this).attr("action");
					data = {
						username : $("input[name='username']").val(),
						password : $("input[name='password']").val(),
						database : $("select[name='databases']").val()
					}
					
				$.post(target, data, function(e){
					
					$(".information").html(e);

					console.log(e);
				});
			});
		});
	</script>

</html>