<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();
if ( $_SESSION['akses']!= 'Admin'){// handling if dont'have session

	header('location:../../index'); 
	exit();
} 
$ses_name = $_SESSION['name'];
$_SESSION['pages']="System";

require_once "../../include/db_config.php";
require_once "../../include/helpers.php";
include "control/confignusers_data.php";

// Define variables and initialize with empty values


$token ="";
$template="";
$service_status="";
$id="";

$token_err ="";
$template_err="";
$service_status_err="";
$id_err="";


// Processing form data when form is submitted
if(isset($_POST["cnfg_id"]) && !empty($_POST["cnfg_id"])){
	// Get hidden input value
	$cnfg_id = trim($_POST["cnfg_id"]);
	$cnfg_token = trim($_POST["token"]);
	$cnfg_intro = trim($_POST["template"]);
	$cnfg_status = trim($_POST["cnfg_status"]);
		

	// Prepare an update statement
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
		exit('Something weird happened');
	}

	$vars = parse_columns('wa_notification', $_POST);
	$stmt = $pdo->prepare("UPDATE wa_notification SET cnfg_token=?,cnfg_intro=?,cnfg_status=? WHERE cnfg_id=?");

	if(!$stmt->execute([ $cnfg_token,$cnfg_intro,$cnfg_status,$cnfg_id  ])) {
		echo "Something went wrong. Please try again later.";
		header("location: error.php");
	} else {
		$stmt = null;
		header("location: notification?id=$cnfg_id&stat_update=ok");
	}
} else {
	// Check existence of id parameter before processing further
	$_GET["id"] = trim($_GET["id"]);
	if(isset($_GET["id"]) && !empty($_GET["id"])){
		// Get URL parameter
		$id =  trim($_GET["id"]);

		// Prepare a select statement
		$sql = "SELECT * FROM wa_notification WHERE cnfg_id = ?";
		if($stmt = mysqli_prepare($link, $sql)){
			// Set parameters
			$param_id = $id;

			// Bind variables to the prepared statement as parameters
			if (is_int($param_id)) $__vartype = "i";
			elseif (is_string($param_id)) $__vartype = "s";
			elseif (is_numeric($param_id)) $__vartype = "d";
			else $__vartype = "b"; // blob
			mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				$result = mysqli_stmt_get_result($stmt);

				if(mysqli_num_rows($result) == 1){
					/* Fetch result row as an associative array. Since the result set
					contains only one row, we don't need to use while loop */
					$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

					// Retrieve individual field value

					$token = htmlspecialchars($row["cnfg_token"]);
					$template = htmlspecialchars($row["cnfg_intro"]);
					$service_status = htmlspecialchars($row["cnfg_status"]);
				} else{
					// URL doesn't contain valid id. Redirect to error page
					header("location: error.php");
					exit();
				}

			} else{
				echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
			}
		}

		// Close statement
		mysqli_stmt_close($stmt);

	}  else{
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
  
  <link href = "https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/css/bootstrap5-toggle.min.css" rel="stylesheet">
  <style>
  .toggle.ios,
  .toggle-on.ios,
  .toggle-off.ios {
    border-radius: 20rem;
  }
   .toggle.ios .toggle-handle {
    border-radius: 20rem;
  }
  </style>

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
      <div class="row min-vh-80 mb-3">
			<div class="col-12" id="view_data_edit">
				<div class="card mt-4 h-80">
				<div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
				  <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
					<h6 class="text-white text-capitalize ps-3">Whatsapp Integration</h6>
				  </div>
				</div>
				
				<div class="card-body">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<div class="d-flex align-items-top justify-content-left px-2">
						<div class="col-md-6">
							<div class="col-10">
								<div class="input-group input-group-static mb-2">
									<label>Service Status</label>
								</div>
								<div class="input-group input-group-static mb-4">
											<input type="checkbox" id="stat_select" class="form-control text-md text-white" data-toggle="toggle" data-style="ios" <?php if($service_status == 1){ echo "checked";} ?>>
									
								</div>
								<div class="input-group input-group-static mb-4">
									<label>API Key</label>
									<input type="text" name="token" class="form-control text-md" value="<?php echo $token; ?>" placeholder="API Key WA gateway Pasar Coding" required>
								</div>
								
							</div>						
						</div>
						<div class="col-md-6">
							<div class="col-10">
								<div class="input-group input-group-static mt-2 mb-4">
									<label>Massage Template </label>
									<textarea rows="6" name="template" class="form-control text-md" placeholder="Template Pesan yang akan digunakan"><?php echo $template ; ?></textarea>
								</div>
							</div>		
						</div>
					</div>
					<div>
					    <label><em>*WhatsApp notification integration service using WA gateway Pasar Coding</em></label>
					</div>
					<hr class="horizontal dark mt-3 mb-3">
					<div class="d-flex align-items-top justify-content-end px-2">
						<input type="hidden" name="cnfg_status" id="cnfg_status" value="<?php echo $service_status; ?>"/>
						<input type="hidden" name="cnfg_id" value="<?php echo $id; ?>"/>
						<input type="submit" class="btn bg-gradient-info shadow-secondary" value="Update Settings">
					</div>
				</form>
				</div>
				
				
			</div>
		  
		  </div>
	</div>
	
	<!-- Modal status Update Success -->
	<div class="modal fade" id="Modal_stat_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title font-weight-normal" id="exampleModalLabel">Update Status</h5>
			<button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			 <div class="d-flex justify-content-center">
			   <p style="font-size:18px;">Cunfiguration successfully updated!</p><br>
			 </div>
			 
		  </div>
		  <div class="modal-footer justify-content-center">
			<a role="button" class="btn bg-gradient-success"  href="notification?id=<?php echo $id; ?>" aria-pressed="true">OK</a>
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
  
  <!-- Github buttons -->
  <script async defer src="../../assets/js/buttons_github.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/material-dashboard-pro.min.js?v=3.0.6"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/js/bootstrap5-toggle.jquery.min.js"></script>
  
  
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script>
    $(function () {
        $('#stat_select').change(function () {
            var last_stat = $(this).prop('checked');
			if (last_stat){
				$("#cnfg_status").val(1);
			}else{
				$("#cnfg_status").val(0);
			}
			var inputValue = $("#cnfg_status").val();
			console.log("The value of the input is: " + inputValue);
        })
    })
  </script>
   <?php
  	if(isset($_GET['stat_update'])){
		$stat_del=$_GET['stat_update'];
		if($stat_del == "ok"){
		echo "<script type='text/javascript'>
			$(document).ready(function(){
			 $('#Modal_stat_update').modal('show');
			});
			</script>";
		}
	}
  ?>
</body>

</html>