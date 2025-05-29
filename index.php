<?php
//header('Location: apps'); /* Redirect browser */

/* Make sure that code below does not get executed when we redirect. */
//exit;
session_start();
require_once "include/db_config.php";

$sql = "SELECT * FROM system_config WHERE id =1";

$system_conf = mysqli_query($GLOBALS["___mysqli_ston"],$sql);
$row = mysqli_fetch_array($system_conf);
	$nama_perusahaan = $row["company"];
	$title_bar = $row["title_bar"];
	$icon_bar = $row["icon_bar"];
	$icon_dashboard = $row["icon_dashboard"];
	$sign_in_bg = $row["sign_in_bg"];



if(isset($_POST["username"]) && !empty($_POST["username"])){
	$username = $_POST["username"];
	$password = md5($_POST["password"]);
	
				  
	$cekdata = "SELECT * FROM users WHERE username ='$username' AND password ='$password' LIMIT 1 ";
	$data_users = mysqli_query($GLOBALS["___mysqli_ston"], $cekdata) or die(mysqli_error($GLOBALS["___mysqli_ston"]));			  
	if(mysqli_num_rows($data_users)>0){ 
	    $listdata_users = mysqli_fetch_array($data_users);
		$_SESSION['name'] = $listdata_users["name"];
		$_SESSION['id'] = $listdata_users["id"];
		$_SESSION['akses'] = $listdata_users["level_akses"];
		$_SESSION['id_siswa'] = $listdata_users["id_siswa"];
		header('Location: pages/dashboard/');
		//if ($_SESSION['akses'] == 'Admin'){ header('Location: pages/dashboard/');}
		//if ($_SESSION['akses'] == 'User'){ //header('Location: pages/user/');}
    }else{
	    //echo '<script language="javascript" type="text/javascript"> alert("Error login. Invalid username or password");</script>';
		//exit();
        header('location:index?msg='.base64_encode('nok'));		
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
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/system_data/favicon.ico">
  
  <title><?php echo $title_bar; ?> </title>
  <!--     Fonts and icons     -->
  <link href="assets/css/Roboto.css" rel="stylesheet" type="text/css" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="assets/js/kit.fontawesome.com_42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="assets/css/Material_icon.css" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.0.4" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-200">
  
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('<?php echo $sign_in_bg; ?>');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom animate__animated animate__pulse">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 animate__animated animate__fadeInDown">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h4>
                  <div class="row mt-3">
				    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0"><?php echo $title_bar; ?></h4>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form role="form" class="text-start" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required >
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <div class="form-check form-switch d-flex align-items-center mb-3">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe">
                    <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                  </div>
                  <div class="text-center">
					<input type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2" value="Sign in"> 
									
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	
	<!-- Modal -->
	<div class="modal fade" id="fail_signin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title font-weight-normal" id="exampleModalLabel">Sign-in Failed!</h5>
			<button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="d-flex justify-content-center text-center">
			   <p style="font-size:20px;">The username or password you entered does not match. Please try again!</p><br>
			 </div>
		  </div>
		  <div class="modal-footer justify-content-center">
			<button type="button" class="btn bg-gradient-primary" data-bs-dismiss="modal">OK</button>
		  </div>
		</div>
	  </div>
	</div>
	
	
  </main>


  <script src="assets/js/jquery-3.6.3.min.js"></script>
  <!--   Core JS Files  
  <script src="assets/js/plugins/jquery.min.js"></script>  -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script src="assets/js/buttons_github.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/material-dashboard.min.js?v=3.0.4"></script>
  <?php
    if(isset($_GET['msg'])){
		$msg = base64_decode($_GET['msg']);
		if($msg == "nok"){
		echo "<script type='text/javascript'>
			$(document).ready(function(){
			 $('#fail_signin').modal('show');
			});
			</script>";
		}
	}
  
  ?>
  
</body>

</html>