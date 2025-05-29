<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();
if ( $_SESSION['akses']!= 'Admin'){// handling if dont'have session

	header('location:../../index'); 
	exit();
} 
$ses_name = $_SESSION['name'];
$_SESSION['pages']="Guru";

require_once "../../include/db_config.php";
require_once "../../include/helpers.php";
include "control/confignusers_data.php";

// Define variables and initialize with empty values
$g_id = "";
$g_nip = "";
$g_nama = "";
$g_tgl_lahir = "";
$g_kelamin = "";
$g_jabatan = "";
$g_mail = "";
$g_contact = "";
$g_kompetensi= "";
$g_picture = "";
$g_tgs_tambahan = "";
$g_alamat = "";


$g_nip_err = "";
$g_nama_err = "";
$g_tgl_lahir_err = "";
$g_kelamin_err = "";
$g_jabatan_err = "";
$g_mail_err = "";
$g_contact_err = "";
$g_kompetensi_err= "";
$g_picture_err = "";
$g_tgs_tambahan_err = "";
$g_alamat_err = "";



// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$g_id = trim($_POST["g_id"]);
	$g_nip = trim($_POST["g_nip"]);
	$g_nama = trim($_POST["g_nama"]);
	$g_tgl_lahir = trim($_POST["g_tgl_lahir"]);
	$g_kelamin = trim($_POST["g_kelamin"]);
	$g_jabatan = trim($_POST["g_jabatan"]);
	$g_mail = trim($_POST["g_mail"]);
	$g_contact = trim($_POST["g_contact"]);
	$g_kompetensi= trim($_POST["g_kompetensi"]);
	$g_picture = trim($_POST["g_picture"]);
	$g_tgs_tambahan = trim($_POST["g_tgs_tambahan"]);
	$g_alamat = trim($_POST["g_alamat"]);
	

	$dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
	$options = [
	  PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
	  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
	  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
	];
	try {
	  $pdo = new PDO($dsn, $db_user, $db_password, $options);
	} catch (Exception $e) {
	  error_log($e->getMessage());
	  exit('Something weird happened'); //something a user can understand
	}

	$vars = parse_columns('data_guru', $_POST);
    $stmt = $pdo->prepare("UPDATE data_guru SET g_nip=?, g_nama=?, g_tgl_lahir=?, g_kelamin=?, g_jabatan=?, g_mail=?, g_contact=?, g_kompetensi=?, g_picture=?, g_tgs_tambahan=?, g_alamat=? WHERE g_id=?");



	if($stmt->execute([ $g_nip,$g_nama,$g_tgl_lahir,$g_kelamin,$g_jabatan,$g_mail,$g_contact,$g_kompetensi,$g_picture,$g_tgs_tambahan,$g_alamat,$g_id ])) {
			$stmt = null;			
			header("location: guru");
		} else{
			echo "Something went wrong. Please try again later.";
		}

}else {
    $_GET["id_guru"] = trim($_GET["id_guru"]);
    if(isset($_GET["id_guru"]) && !empty($_GET["id_guru"])){
        $id_guru =  base64_decode($_GET["id_guru"]);
		$sql = "SELECT * FROM data_guru WHERE g_id='$id_guru'";
		$g_list = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$g_data = mysqli_fetch_array($g_list);
	}else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}	
	
?>

<!--
=========================================================
* Material Dashboard 2 - v3.0.4
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../<?php echo $icon_bar; ?>">
  <title><?php echo $title_bar; ?></title>
  
  <!--     Fonts and icons     -->
  <link href="../../assets/css/Roboto.css" rel="stylesheet" type="text/css" />
  <!-- Nucleo Icons -->
  <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="../../assets/js/kit.fontawesome.com_42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="../../assets/css/Material_icon.css" rel="stylesheet">
  
  <link href="../../assets/css/animate.min.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../../assets/css/material-dashboard-pro.min.css?v=3.0.6" rel="stylesheet" />
  <link id="pagestyle" href="../../assets/vendor/DataTables_package/jquery.dataTables.min.css" rel="stylesheet" /> 
  
  <link href="../../assets/vendor/dropzone.css" rel="stylesheet" />
  <link href="../../assets/vendor/cropper.css" rel="stylesheet"/>
  

  <!--
   <link id="pagestyle" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet" /> 
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.bootstrap5.min.css" />
  -->
</head>

<body class="g-sidenav-show  bg-gray-200">
  <!-- Sidebar -->
  <?php include 'view/part_sidenav.php';?>
  <!-- End of Sidebar -->
  
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
	 <?php include 'view/part_topnav.php';?>
	<!-- End Navbar -->
    <div class="container-fluid py-4">
		<div class="row mb-3">
			<div class="col-12">
			  <div class="card my-4 h-200">
				<div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
				  <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
					<h6 class="text-white text-capitalize ps-3">Tambah Data Guru</h6>
				  </div>
				</div>
				
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="card-body px-0 pb-2 mx-4">
					<div class="row mb-2 mt-2">
						<h5 class="font-weight-bolder mb-0">Data Guru</h5>
						<p class="mb-0 text-sm">Detail Informasi Guru</p>
					</div>
					<div class="row mb-2 mt-2">
						<div class="col-12 col-sm-4">
							<div class="row mt-5 justify-content-center" style="height: 306px;">	
								<form method="post">
									<div class="d-flex justify-content-center align-items-center">
									  <div class="image_area" id="avatar_area" style="width:auto;">
											<label for="upload_image" class="text-center">
												<img src="<?= $g_data['g_picture'];?>" id="uploaded_image" class="rounded-circle z-depth-2" width="60%" height="60%" />
												<div class="overlay">
													<div class="text">*Click gambar untuk mengganti photo</div>
												</div>
												<input type="file" name="image" class="image" id="upload_image" style="display:none" />
											</label>
										
									  </div>
									</div>
								</form>
								
							</div>
							<div class="row mt-2">	
							    <div class="input-group input-group-static mb-4">
								  <label>NIP/NUPTK</label>
								  <input class="form-control" type="text" name="g_nip" id="g_nip" value="<?= $g_data['g_nip'];?>"  placeholder="Isi NIP/NUPTK guru terlebih dahulu!"  required>
								</div>
							</div>
						</div>
						<div class="col-12 col-sm-4 mt-3 mt-sm-0">
							<div class="row">
								<div class="input-group input-group-static">
									   <label>Nama</label>
									   <input type="text" class="form-control" name="g_nama" value="<?= $g_data['g_nama'];?>"  placeholder="Nama Guru"  required>
								</div>
							</div>
							
							<div class="row mt-4">	
								<div class="col-8 col-sm-7">
									<div class="input-group input-group-static">
									   <label>Tanggal Lahir</label>
									   <input type="date" class="form-control" name="g_tgl_lahir" value="<?= $g_data['g_tgl_lahir'];?>" required>
									</div>
								</div>
								<div class="col-4 col-sm-5">
									<div class="input-group input-group-static">
										 <label for="exampleFormControlSelect1" class="ms-0">Jenis Kelamin</label>
										 <select class="form-control"  name="g_kelamin" id="choices-gender" >
										    <option value="" >Jenis Kelamin</option>
											<option value="Laki-laki" <?php if($g_data['g_kelamin']=="Laki-laki") echo 'selected="selected"'; ?> >Laki-laki</option>
											<option value="Perempuan" <?php if($g_data['g_kelamin']=="Perempuan") echo 'selected="selected"'; ?> >Perempuan</option>
										 </select>
									</div>
								</div>
							</div>
							<div class="row mt-4">
								<div class="input-group input-group-static">
									   <label>Jabatan</label>
									   <input type="text" class="form-control" name="g_jabatan" placeholder="Jabatan Guru" value="<?= $g_data['g_jabatan'];?>"  required>
								</div>
							</div>
							<div class="row mt-4">
								<div class="input-group input-group-static">
									   <label>Kompetansi</label>
									   <input type="text" class="form-control" name="g_kompetensi" placeholder="Kompetansi / Mapel yang diampu" value="<?= $g_data['g_kompetensi'];?>"  required>
								</div>
							</div>
							<div class="row mt-4">
								<div class="input-group input-group-static">
									   <label>Tugas Tambahan</label>
									   <input type="text" class="form-control" name="g_tgs_tambahan" placeholder="Tugas tambahan yang diberikan" value="<?= $g_data['g_tgs_tambahan'];?>"  required>
								</div>
							</div>
							
						</div>
						<div class="col-12 col-sm-4 mt-3 mt-sm-0">
							<div class="row">
								<div class="input-group input-group-static">
									   <label>Kontak/No HP</label>
									   <input type="text" class="form-control" name="g_contact" placeholder="Kontak atau nomor telephone" value="<?= $g_data['g_contact'];?>"  required>
								</div>
							</div>
							
							<div class="row mt-4">
								<div class="input-group input-group-static">
									   <label>Email</label>
									   <input type="text" class="form-control" name="g_mail" placeholder="Alamat surel/ email" value="<?= $g_data['g_mail'];?>"  required>
								</div>
							</div>
							<div class="row mt-4 mb-2">
								<div class="input-group input-group-static">
									   <label>Alamat</label>
									   <textarea class="form-control" name="g_alamat" rows="5"><?= $g_data['g_alamat'];?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="row mb-2 mt-2 mb-4">
						<!-- hidden val -->
						<input type="hidden" name="g_picture" id="picture" value="<?= $g_data['g_picture'];?>">
						<input type="hidden" name="g_id" id="g_id" value="<?= $g_data['g_id']; ?>">
						<hr>
						<div class="button-row d-flex mt-2">
							<input type="submit" class="btn bg-gradient-primary ms-auto mb-0 js-btn-next" style="text-color:white" value="Update Data">
							<a class="btn bg-gradient-info ms-2 mb-0" style="text-color:white" type="button" href="guru">Cancel</a>
						</div>
					
					</div>
				</div>
				
				</form>
			  </div>
			</div>
		</div>
	
	
	</div>
	
	<!-- crop Modal-->
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content" >
				<div class="modal-header">
					<p class="modal-title" id="exampleModalLabel" style="color:#728394;">Crop Picture Image</p>
					<button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
						<div class="img-container" >
							<div class="row" style="max-height:800px">
									<img src="" id="sample_image" style="max-width: auto; max-height: 800px;"/>
								
							</div>
						</div>
				</div>
				<div class="modal-footer justify-content-center">
					<button type="button" id="crop" class="btn btn-primary btn-md">Crop</button>
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancel</button> 
				</div>
			</div>
		</div>
	 </div>	

	
	<!-- footer -->
	 <?php include 'view/part_footer.php';?>
	<!-- footer -->
	</div>
  </main>
  <?php include 'view/part_theme_config.php';?>
 
  
  <!--   Jquery JS Files   -->
  <script src="../../assets/js/jquery-3.5.1.js"></script>
  
  <!--   Core JS Files   -->
  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/datatables.js"></script>
  <script src="../../assets/js/plugins/choices.min.js"></script>
  
  <!-- <script src="../../assets/js/plugins/multistep-form.js"></script> -->
  
  <!-- Github buttons -->
  <script async defer src="../../assets/js/buttons_github.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/material-dashboard-pro.min.js?v=3.0.6"></script>
  <script src="../../assets/vendor/dropzone.js"></script>
  <script src="../../assets/vendor/cropper.js"></script>
  
  <script>
  $(document).ready(function(){

		var $modal = $('#modal');

		var image = document.getElementById('sample_image');
		var cropper;
		
		$('#upload_image').change(function(event){
			//$('#modal-wishes').modal('hide');
			var files = event.target.files;

			var done = function(url){
				image.src = url;
				$modal.modal('show');
			};

			if(files && files.length > 0)
			{
				reader = new FileReader();
				reader.onload = function(event)
				{
					done(reader.result);
				};
				reader.readAsDataURL(files[0]);
			}
			
		});
		
		$modal.on('shown.bs.modal', function() {
			cropper = new Cropper(image, {
				aspectRatio: 1,
				viewMode: 3,
				preview:'.preview'
			});
		}).on('hidden.bs.modal', function(){
			cropper.destroy();
			cropper = null;
		});
		
		$('#crop').click(function(){
			canvas = cropper.getCroppedCanvas({
				width:360,
				height:360
			});

			canvas.toBlob(function(blob){
				url = URL.createObjectURL(blob);
				var reader = new FileReader();
				reader.readAsDataURL(blob);
				reader.onloadend = function(){
					var base64data = reader.result;
					var nip_guru = $('#g_nip').val();
					$.ajax({
						url:'control/guru_pict_update.php',
						method:'POST',
						data:{image:base64data, nip_guru: nip_guru},
						success:function(data)
						{
							$modal.modal('hide');
							$('#uploaded_image').attr('src', data);
							$('#picture').val(data);
						}
					});
				};
			});
			
			
		});			
	});
  </script>
  <script>
	if (document.getElementById('choices-gender')) {
      var element = document.getElementById('choices-gender');
      const example = new Choices(element, {
        searchEnabled: false
      });
    };
  </script>
    <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

</body>

</html>