<?php
	//echo "<pre>";print_r(json_decode($akun));"</pre>";
	
	//exit();
?>
<!DOCTYPE html>
<html>

<head>
	<title>
		Aplikasi Billing Keuangan
	</title>
	<link rel='shortcut icon' href='<?php echo base_url()?>assets/img/thumb.png' />
	<link href="<?php echo base_url()?>assets/plugins/datatables/dataTables.bootstrap.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/bootstrap.min.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/font-awesome.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/se7en-font.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/isotope.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/jquery.fancybox.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/fullcalendar.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/wizard.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/select2.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/morris.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/datatables.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/datepicker.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/timepicker.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/colorpicker.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/bootstrap-switch.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/daterange-picker.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/typeahead.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/summernote.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/pygments.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/style.css" media="all" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/color/green.css" media="all" rel="alternate stylesheet" title="green-theme" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/color/orange.css" media="all" rel="alternate stylesheet" title="orange-theme" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/color/magenta.css" media="all" rel="alternate stylesheet" title="magenta-theme" type="text/css" />
	<link href="<?php echo base_url()?>assets/stylesheets/color/gray.css" media="all" rel="alternate stylesheet" title="gray-theme" type="text/css" />
	<link href="<?php echo base_url("assets/css/jquery.steps.css")?>" media="all" rel="stylesheet" type="text/css" />
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.3.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/plugins/jQueryUI/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/raphael.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/selectivizr-min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.mousewheel.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.vmap.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.vmap.sampledata.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.vmap.world.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.bootstrap.wizard.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/fullcalendar.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/gcal.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/datatable-editable.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.easy-pie-chart.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/excanvas.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.isotope.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/isotope_extras.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/modernizr.custom.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.fancybox.pack.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/select2.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/styleswitcher.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/wysiwyg.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/summernote.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.inputmask.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.validate.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/bootstrap-fileupload.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/bootstrap-datepicker.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/bootstrap-timepicker.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/bootstrap-colorpicker.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/bootstrap-switch.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/typeahead.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/daterange-picker.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/date.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/morris.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/skycons.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/fitvids.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/jquery.sparkline.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/javascripts/respond.js" type="text/javascript"></script>
	<script src="<?php echo base_url("assets/js/jquery.steps.js")?>" type="text/javascript"></script>

	<script src="<?php echo base_url()?>assets/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
	
	<script src="<?php echo base_url()?>assets/js/sticky.js" type="text/javascript"></script>
	
	<script src="<?php echo base_url()?>assets/js/sticky.js" type="text/javascript"></script>
	
	<script src="<?php echo base_url()?>assets/plugins/auto-numeric/autoNumeric.js" type="text/javascript"></script>

	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">

	<style>
		.dropdown-submenu {
			position: relative;
		}

		.dropdown-submenu .dropdown-menu {
			top: 0;
			left: 100%;
			margin-top: -1px;
		}

	/*	yudha was here*/
		.xx{
			width: 230px;
			margin-bottom: 10px;
			margin-left: 280px;
		}
		.btn{
			margin-bottom: 10px;
		}

		/*batas suci*/

		.footer{
			bottom:0;
			left:0;
			right:0;
			position:static;
			background:#787a93;
			text-align:center;
			width:100%;
			color:#fff;
			padding:3px;
			font-size:12px;
			margin-top:45px;
		}

		.backDropOverlay {
			font-size:10px;
			position: absolute;
			top: 0px;
			height: 100px;
			z-index: 500;
			border: 1px solid black;
			background-color: black;
			width: 100%;
			height: 2000px;
			opacity: 0.6;
			text-align: center;
		}
		.backDropOverlay > div {
			margin: 20% auto;
			font-weight: bold;
			font-size: 1.6em;
			background-color: white;
			width: 145px;
			padding: 5px;
			border-radius: 3px;
			moz-border-radius: 3px;
			webkit-border-radius: 3px;
			o-border-radius: 3px;
		}
		.backDropOverlay > div > span {
			margin-left: 10px;
		}
		.backDropOverlay > div > img {
			width: 30px;
			height: 30px;
		}
		.parent{
			border:1px solid #fff; 
			border-radius:100px; 
			background:#f9f9f9; 
			margin:0px 5px 0px 0px !important;
			height:30px;
			font-size:12px;
			width:30px;
			padding:6px 0px;
			cursor:pointer;
		}

		.child{
			border:1px solid #fff; 
			background:#f9f9f9;
			font-size:12px;
			margin:0px 5px 0px 0px !important;
			padding:3px 5px;
			cursor:pointer;
		}
		
		
		
		
		.listimage{
		list-style-image: url('../assets/images/arrow.png');
		}
		
		li{
			list-style-type:none;
		}
	


	</style>
</head>
<body>
	
	
	<div class="modal-shiftfix" style="position:relative; background:#ccc;">

		<div class="navbar">
			<div class="container-fluid top-bar" style="background:#f9f9f9">
				<div class="pull-right">
					<ul class="nav navbar-nav pull-right">
						<?php
						$this->IDUser = $_SESSION['IDUser'];

						$selectQuery = $this->db->query("select nama_user as NamaUser, nama_lengkap as NamaLengkap, first_login as firstlogin from sys_user where id_user=".$this->IDUser);

						$NamaUser       = $selectQuery->first_row()->NamaUser;
						$NamaLengkap    = $selectQuery->first_row()->NamaLengkap;
						$firstlogin    = $selectQuery->first_row()->firstlogin;

						$this->IDSekolah = $_SESSION['IDSekolah'];

						$selectPerusahaan = $this->db->query("SELECT * FROM sys_perusahaan WHERE sys_perusahaan.id_perusahaan = '".$this->IDSekolah."'");

						$NamaPerusahaan = $selectPerusahaan->first_row()->nama_perusahaan;
						$AlamatPerusahaan = $selectPerusahaan->first_row()->alamat_perusahaan;


						?>
						<li class="dropdown user hidden-xs"><a style="color:#555;" data-toggle="dropdown" class="dropdown-toggle" href="#">
							<?php echo $NamaUser; ?><b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="#">
									<i class="icon-user"></i>My Account</a>
								</li>
								<li><a href="#">
									<i class="icon-gear"></i>Account Settings</a>
								</li>
								<li><a href="<?php echo site_url('user/logout'); ?>">
									<i class="icon-signout"></i>Logout</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
				<button class="navbar-toggle">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span><span class="icon-bar"></span>
				</button>
				<?php 
				$logo11=$this->db->query("select * from sys_perusahaan WHERE sys_perusahaan.id_perusahaan = '".$_SESSION['IDSekolah']."'");
				?>

				<?php if ($logo11->first_row()->gambar): ?>
					<a href="<?php echo site_url()?>main">
						<img style="height:55px;" src="<?php echo base_url("upload/".$logo11->first_row()->gambar)?>">
					</a>
				<?php endif ?>

				<?php if (!$logo11->first_row()->gambar): ?>
					<a href="<?php echo site_url()?>main">
						<img style="height:55px;" src="<?php echo base_url()?>assets/img/logo2.png">
					</a>
				<?php endif ?>


				<span style="font-size:18px; font-family:tahoma;"><?php echo $NamaPerusahaan  ?> [Aplikasi Manajemen Keuangan Berbasis Online]</span>
			
			<?php
				$namedept = $this->db->query("SELECT * FROM mst_departemen WHERE mst_departemen.id_departemen = '".$_SESSION['IDUnit']."'");
				
				$prefix = ($_SESSION['IDUnit'] == 1) ? $NamaPerusahaan : $namedept->first_row()->nama_departemen;
				
				$idunit = $this->db->query("SELECT * FROM sys_user WHERE sys_user.id_user = '".$_SESSION['IDUser']."'");
			?>

			</div>
			<div class="container-fluid main-nav clearfix">
				<div style="position:absolute;right:2%;margin-top:10px;">		  
				</div>
				
				<div class="nav-collapse" id="nav-menu">
					<?php echo treeRecursiveMenu()?>
				</div>
				
			</div>
		</div>
		<div class="container-fluid main-content">
			<div class="row">
				<div class="col-lg-12">
				<h3>Mengelola Data Informasi Bumdes</h3>
					
					<div class="thumbnail">
					<img alt="imgscreenshot" style ="height:300px; width:75%; display: block;" src="../assets/images/screenshots/informasi_bumdes.png" data-holder-rendered="true">
					<div class="caption">
					<h3> Screenshot Informasi Bumdes</h3>
					<p> Modul ini digunakan untuk persiapan data awal perusahaan, yang mana nantinya akan digunakan untuk pelaporan dll.</p>
					<ul class="listimage">
					<li>Isikan data instansi/perusahaan anda sesuai form yang di sediakan</li>
					<li>Tekan button simpan untuk menyimpan data</li>					
					</ul>
					</div>
					</img>
					</div>
					
					
				</div>
			</div>
		</div>
		
		

	</div>
	


	<script type="text/javascript">
		$(document).ready(function(){
			
			var file;
			$("#fileupload").on('change', prePareUpload);
			
			$("#modalgreeting").modal("show");

			$('.dropdown-submenu a.test').on("click", function(e){
				$(this).next('ul').toggle();
				e.stopPropagation();
				e.preventDefault();
			});
			
			$("#wizard").steps({
				headerTag: "h2",
				bodyTag: "section",
				transitionEffect: "none",
				enableFinishButton: false,
				enablePagination: false,
				enableAllSteps: false,
				titleTemplate: "#title#",
				cssClass: "tabcontrol"
			});
			

			$("a[role='linkMenu']").click(function(e){

				e.preventDefault();

				alert("oke");

				return false;
				var requestUrl = $(this).attr("href");

				$.ajax({
					async: false,
					beforeSend: function() {
						$("body").append("<div class='backDropOverlay' id='backDropOverlay'><div><img src='assets/img/loading.gif'/><span>Loading..</span></div></div>");

					},
					complete: function() {

						$("#backDropOverlay").remove();
					},
					url: requestUrl,
					success: function(data, textStatus) {

						if (textStatus == 'success')
						{
							alert("oke");
						} else {
							alert("warning");
						}
					},
					error: function(data, textStatus) {
						alert(textStatus);
					}
				});

				$(window).scrollTop(0);

				return false;
			});
			
			
		});
		

	</script>
</body>
</html>

<?php
function treeRecursiveMenu()
{

	$treemenu = '<ul id="menulevel0" class="nav" style="text-align:left;background:#429489;height:50px; padding:10px 0px 0px 10px; font-color=white !important;"><h3><font color=white>Panduan Penggunaan Software Bumdes</font></h3></ul>';

	return $treemenu;
}
?>