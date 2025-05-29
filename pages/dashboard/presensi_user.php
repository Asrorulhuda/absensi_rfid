<?php 
	date_default_timezone_set('Asia/Jakarta');
	session_start();
	if ( ($_SESSION['akses']!= 'Admin') && ($_SESSION['akses']!= 'User') ){header('location:../../index'); exit();}  
	$ses_name = $_SESSION['name'];
	$_SESSION['pages']="Presensi";
	$id_siswa = $_SESSION['id_siswa'];
    
	require_once "../../include/db_config.php";
	include "control/confignusers_data.php";
	
	$bulan_indo = array(
			'01' => 'JANUARI',
			'02' => 'FEBRUARI',
			'03' => 'MARET',
			'04' => 'APRIL',
			'05' => 'MEI',
			'06' => 'JUNI',
			'07' => 'JULI',
			'08' => 'AGUSTUS',
			'09' => 'SEPTEMBER',
			'10' => 'OKTOBER',
			'11' => 'NOVEMBER',
			'12' => 'DESEMBER',
	);
	
	$time= strtotime(date("Y-m-d"));
	$month_now=date("m",$time);
	$year_now=date("Y",$time);
	$bulan_now = $month_now;
	$bulan_now .="-";
	$bulan_now .=$year_now;
	
	$year="";
	$month="";
	$month_full = "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$set_bulan = $_POST['set_bulan'];
		$time= strtotime("01-". $_POST['set_bulan']);
		$month = date("m",$time);
		$month_full=date("F",$time);
		$year= date("Y",$time);
	
	}else{
		$month=date("m",$time);
		$month_full=date("F",$time);
		$year=date("Y",$time);
		$set_bulan = $month;
		$set_bulan .="-";
		$set_bulan .=$year;
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
  
   
  <link rel="stylesheet" id="pagestyle" href="../../assets/vendor/DataTables_package/jquery.dataTables.min.css"  /> 
  <link rel="stylesheet" type="text/css" href="../../assets/vendor/DataTables_package/buttons.dataTables.min.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/vendor/DataTables_package/dataTables.bootstrap5.min.css" />
  <style>
    table.dataTable.no-footer {
        border-bottom: 0 !important;
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
	    <div class="col-5">	
          <div class="card my-4 h-100" id="data_list">
		  
			<div class="card-header pb-0">
				<div class="d-lg-flex">
					
				</div>
			</div>
				
            <div class="card-body px-0 pb-2">

				<div class="row justify-content-center"> 
				   
					<div class="col-md-10 col-ms-8">
					   <?php
							
							$sql = "SELECT * FROM data_siswa, opsi_jurusan WHERE s_jurusan = j_id AND s_id='$id_siswa'";
							$s_siswa = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
							$dd_siswa = mysqli_fetch_array($s_siswa);
						  
						?>
		
					   <div class="d-flex justify-content-center animate__animated animate__fadeInDown">
						<img id="picture_modal" src="<?php echo $dd_siswa['s_picture'];?>" alt="Member Avatar" class="rounded-circle z-depth-2" width="200px" height="200px" />
					   </div>
					   <div class="d-flex justify-content-center mb-0">
						   <h5 class="mt-2 text-center"><?php echo $dd_siswa['s_nama'];?></h5>
					   </div>
					   <div class="d-flex justify-content-center mb-1">
							<p><?php echo $dd_siswa['s_uid'];?> 
							<?php 
							if ($dd_siswa['s_status'] == "Aktif"){
								echo "<span class='badge badge-success badge-sm'>".$dd_siswa['s_status']."</span>";				
							}else if ($dd_siswa['s_status'] == "Lulus"){
								echo "<span class='badge badge-danger badge-sm'>".$dd_siswa['s_status']."</span>";
							}else{
								echo "<span class='badge badge-primary badge-sm'>".$dd_siswa['s_status']."</span>";
							}
							?>
							</p> 
					   </div>
					   <div class="d-flex justify-content-center mb-0">
							<table class="table table-borderless">
							  <tbody>
								<tr>
								  <td>NIS</td>
								  <td>: <?php echo $dd_siswa['s_nis'];?></td>
								</tr>
								<tr>
								  <td>Tanggal Lahir</td>
								  <td>: <?php $tgl=date_create($dd_siswa['s_tgl_lahir']); echo date_format($tgl,"j F Y");?></td>
								</tr>
								<tr>
								  <td>Kelas</td>
								  <td>: <?php echo $dd_siswa['s_kelas'];?></td>
								</tr>
								<tr>
								  <td>Jurusan</td>
								  <td>: <?php echo $dd_siswa['j_name'];?></td>
								</tr>
								<tr>
								  <td>Phone</td>
								  <td>: <?php echo $dd_siswa['s_phone'];?></td>
								</tr>
								<tr>
								<td>Kontak Darurat</td>
								  <td>: <?php echo $dd_siswa['s_emergency'];?> (<?php echo $dd_siswa['s_emergency_user'];?>)</td>
								</tr>
							  </tbody>
							</table>
					   </div>
					</div> 
				</div>


            </div>
          </div>
        </div>
        <div class="col-7">	
          <div class="card my-4 h-100" id="data_list">
		  
			<div class="card-header pb-0">
				<div class="d-lg-flex">
					<div>
						<h5 class="mb-0">Data Presensi</h5>
						<p class="text-sm mb-0">
						Data Presensi Siswa
						</p>
					</div>
					<!--
					<div class="ms-auto my-auto mt-lg-0 mt-4">
					    <button type="button" class="btn btn-primary btn-sm" id="btn_add"  onClick="location.href='#'"><span class="material-icons">picture_as_pdf</span> Cetak PDF </button>
					</div>
					-->
				</div>
				
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="d-lg-flex flex-row justify-content-end">
				    <div class="col-md-2 col-sm-4 mt-3 mt-sm-1 ms-4">
						<div class="input-group input-group-static">
							<span class="input-group-text material-icons text-md" id="basic-addon1"> calendar_month </span>
							<input type="text" class="form-control"  name="set_bulan" id="datepicker"  aria-describedby="basic-addon2" value="<?php echo $set_bulan; ?>" >
						</div>
					</div>
					<div class="col-md-auto col-sm-auto mt-3 mt-sm-1 ms-4 justify-content-end">
					    <div class="btn-group" role="group" aria-label="Basic example">
						  <button type="submit" class="btn btn-sm bg-gradient-success" name="btn_submit" ><span class="material-icons">filter_list</span> Set Bulan</button>
						  <button type="button" class="btn btn-sm btn-primary" name="btn_reset"  onClick="location.href='presensi_user'"><span class="material-icons">restart_alt</span> Reset</button>	
						</div>
					</div>
				</div>
				</form>
			</div>
				
            <div class="card-body px-0 pb-2">
				<div class="row justify-content-center mx-2"> 
					<div class="table-responsive p-0 w-95">
					   
					   <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
					   
					   <table id="table_presensi" class="table table-hover table-fixed" style="width:100%">
					     <!--<table id="table_presensi" class="table align-items-center mb-0 display table-striped" style="width:100%">  -->
							<thead>
								<tr>
									<th>No</th>
									<th>Tanggal</th>
									<th class="text-center">Masuk</th>
									<th class="text-center">Keluar</th>
									<th class="text-center">Keterangan</th>
								</tr>
								
							</thead>
								<tbody style="border-style:none;">
								    <?php
									    $numb =0;
										$sql = "SELECT  tanggal, jam_masuk, jam_keluar, status, ket_masuk, ket_keluar, keterangan FROM data_absen, data_siswa
												WHERE data_absen.uid=data_siswa.s_uid AND data_absen.uid='".$dd_siswa['s_uid']."' AND YEAR(date(tanggal))='".$year."' AND MONTH(date(tanggal))='".$month."'";
					
										$s_member = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
										while ($d_member=mysqli_fetch_array($s_member)){
											$numb++;
									  
									?>
									   <tr style="border-bottom: 0;">
									   
									        <td><p class="text-sm mb-0 mt-2"><?= $numb;?></p></td>
											<td><p class="text-sm mb-0 mt-2"><?= $d_member['tanggal'];?></p></td>
											<td  class="text-center">
												<div class="d-flex flex-column">
													<p class="text-sm text-secondary mb-0 "><?= $d_member['jam_masuk'];?></p>
													<hr class="horizontal dark mt-1 mb-1">
													<p class="text-xs text-secondary mb-0"><?= $d_member['ket_masuk'];?></p>
												</div>
											</td>
											<td class="text-center">
												<div class="d-flex flex-column">
													<p class="text-sm text-secondary mb-0"><?= $d_member['jam_keluar'];?></p>
													<hr class="horizontal dark mt-1 mb-1">
													<p class="text-xs text-secondary mb-0"><?= $d_member['ket_keluar'];?></p>
												</div>
											</td>
											<td class="text-center"><p class="text-sm text-secondary mb-0 mt-2"><?= $d_member['keterangan'];?></p></td>
									   </tr>
									
									<?php ;} ?>
								
								</tbody>
						</table>
					   </div>
					</div>
				</div>
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
  <script src="../../assets/vendor/DataTables_package/jquery-3.5.1.js"></script>  
  <script src="../../assets/vendor/DataTables_package/jquery.dataTables.min.js"></script>
  <script src="../../assets/vendor/DataTables_package/dataTables.buttons.min.js"></script>
  <script src="../../assets/vendor/DataTables_package/jszip.min.js"></script>
  <script src="../../assets/vendor/DataTables_package/pdfmake.min.js"></script>
  <script src="../../assets/vendor/DataTables_package/vfs_fonts.js"></script>
  <script src="../../assets/vendor/DataTables_package/buttons.html5.min.js"></script>
  <script src="../../assets/vendor/DataTables_package/buttons.colVis.min.js"></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
  
  
  <!--   Core JS Files   -->
  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/choices.min.js"></script>
  
  <!-- Github buttons -->
  <script async defer src="../../assets/js/buttons_github.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/material-dashboard-pro.min.js?v=3.0.6"></script>
  
  <script type="text/javascript">
        $(document).ready(function(){
			$('#table_presensi').DataTable();
			
        });
		$("#datepicker").datepicker( {
			format: "mm-yyyy",
			startView: "months", 
			minViewMode: "months",
											
		});
		
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