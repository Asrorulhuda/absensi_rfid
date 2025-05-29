<?php 
	date_default_timezone_set('Asia/Jakarta');
	session_start();
	if ( $_SESSION['akses']!= 'Admin'){// handling if dont'have session

		header('location:../../index'); 
		exit();
	} 
	$ses_name = $_SESSION['name'];
	$_SESSION['pages']="Presensi";
    
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
	$total_hari_sekolah = 0;
	$set_bulan ="";
	$where_con ="";
	$kelas="";
	$jurusan="";
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$kelas = $_POST["choices-kelas"]; 
		$jurusan= $_POST["choices-jurusan"]; 
		
		$set_bulan = $_POST['set_bulan'];
		$time= strtotime("01-". $_POST['set_bulan']);
		$month = date("m",$time);
		$month_full=date("F",$time);
		$year= date("Y",$time);
		if ($set_bulan = $bulan_now){
			$total_hari_sekolah = countWorkingDaysUntilToday($year, $month);
		}else{
			$workingDaysCount = countWorkingDaysInMonth($year, $month);
			$nationalHolidaysCount = countNationalHolidaysInMonth($year, $month);
			$total_hari_sekolah = $workingDaysCount-$nationalHolidaysCount;
		}
		
		
		if($jurusan != ""){
			if($kelas!= ""){
				$where_con= "s_jurusan='$jurusan' AND s_jurusan=j_id AND s_kelas = '$kelas' AND s_status = 'Aktif'";		
			}else{
				$where_con = "s_jurusan='$jurusan' AND s_jurusan=j_id  AND s_status = 'Aktif'";
			}
		}else{
			if($kelas!= ""){
				$where_con = "s_kelas= '$kelas' AND s_jurusan=j_id AND s_status = 'Aktif'";
			}else{
				$where_con = "s_status = 'Aktif' AND s_jurusan=j_id ";
			}
		}
	
	}else{
		$month=date("m",$time);
		$month_full=date("F",$time);
		$year=date("Y",$time);
		$set_bulan = $month;
		$set_bulan .="-";
		$set_bulan .=$year;
		$total_hari_sekolah = countWorkingDaysUntilToday($year, $month);
		$where_con = "s_status = 'Aktif' AND s_jurusan=j_id ";
	}
	
	$bulan = $month;
	$tahun = $year; 
	$tgl = 1;
    $jumtgl = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun); // dapat jumlah tanggal	
	
function fetch_data(){  
	$output = '';
    global $month, $year,$link, $where_con;	
	$bulan = $month;
	$tahun = $year; 

	$tgl = 1;
	$jumtgl = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun); // dapat jumlah tanggal

	   $sql ="SELECT siswa.s_uid, siswa.s_nama,s_kelas,j_short,
				COALESCE(GROUP_CONCAT(attendance_report.1), '-') AS '1', COALESCE(GROUP_CONCAT(attendance_report.2), '-') AS '2', COALESCE(GROUP_CONCAT(attendance_report.3), '-') AS '3', COALESCE(GROUP_CONCAT(attendance_report.4), '-') AS '4',
				COALESCE(GROUP_CONCAT(attendance_report.5), '-') AS '5', COALESCE(GROUP_CONCAT(attendance_report.6), '-') AS '6', COALESCE(GROUP_CONCAT(attendance_report.7), '-') AS '7', COALESCE(GROUP_CONCAT(attendance_report.8), '-') AS '8',
				COALESCE(GROUP_CONCAT(attendance_report.9), '-') AS '9', COALESCE(GROUP_CONCAT(attendance_report.10), '-') AS '10', COALESCE(GROUP_CONCAT(attendance_report.11), '-') AS '11', COALESCE(GROUP_CONCAT(attendance_report.12), '-') AS '12',
				COALESCE(GROUP_CONCAT(attendance_report.13), '-') AS '13', COALESCE(GROUP_CONCAT(attendance_report.14), '-') AS '14', COALESCE(GROUP_CONCAT(attendance_report.15), '-') AS '15', COALESCE(GROUP_CONCAT(attendance_report.16), '-') AS '16',
				COALESCE(GROUP_CONCAT(attendance_report.17), '-') AS '17', COALESCE(GROUP_CONCAT(attendance_report.18), '-') AS '18', COALESCE(GROUP_CONCAT(attendance_report.19), '-') AS '19', COALESCE(GROUP_CONCAT(attendance_report.20), '-') AS '20',
				COALESCE(GROUP_CONCAT(attendance_report.21), '-') AS '21', COALESCE(GROUP_CONCAT(attendance_report.22), '-') AS '22', COALESCE(GROUP_CONCAT(attendance_report.23), '-') AS '23', COALESCE(GROUP_CONCAT(attendance_report.24), '-') AS '24',
				COALESCE(GROUP_CONCAT(attendance_report.25), '-') AS '25', COALESCE(GROUP_CONCAT(attendance_report.26), '-') AS '26', COALESCE(GROUP_CONCAT(attendance_report.27), '-') AS '27', COALESCE(GROUP_CONCAT(attendance_report.28), '-') AS '28',
				COALESCE(GROUP_CONCAT(attendance_report.29), '-') AS '29', COALESCE(GROUP_CONCAT(attendance_report.30), '-') AS '30', COALESCE(GROUP_CONCAT(attendance_report.31), '-') AS '31'
				
			FROM
				data_siswa AS siswa
				LEFT JOIN v_monthly_report AS attendance_report
					ON siswa.s_uid = attendance_report.s_uid
					AND MONTH(attendance_report.tanggal) = '$bulan'
					AND YEAR(attendance_report.tanggal) = '$tahun',
				opsi_jurusan	
			WHERE ".$where_con."		
			GROUP BY
				siswa.s_uid
			ORDER BY 
			    siswa.s_nama";
						
	  
      //$sql = "SELECT * FROM data_pegawai ORDER BY id ASC";  
      $result = mysqli_query($link, $sql); 
	  $no = 1;	 
	  $strip = "-";
      while($row = mysqli_fetch_array($result)){   
		$output.='<tr style="text-align:center;">
			<td>'.$no.'</td>
			<td style="text-align:left;">'.$row["s_nama"].'</td>
			<td style="text-align:center;">'.$row["s_kelas"].'-'.$row["j_short"].'</td>
			<td>'.$row["1"].'</td>
			<td>'.$row["2"].'</td>
			<td>'.$row["3"].'</td>
			<td>'.$row["4"].'</td>
			<td>'.$row["5"].'</td>
			<td>'.$row["6"].'</td>
			<td>'.$row["7"].'</td>
			<td>'.$row["8"].'</td>
			<td>'.$row["9"].'</td>
			<td>'.$row["10"].'</td>
			<td>'.$row["11"].'</td>
			<td>'.$row["12"].'</td>
			<td>'.$row["13"].'</td>
			<td>'.$row["14"].'</td>
			<td>'.$row["15"].'</td>
			<td>'.$row["16"].'</td>
			<td>'.$row["17"].'</td>
			<td>'.$row["18"].'</td>
			<td>'.$row["19"].'</td>
			<td>'.$row["20"].'</td>
			<td>'.$row["21"].'</td>
			<td>'.$row["22"].'</td>
			<td>'.$row["23"].'</td>
			<td>'.$row["24"].'</td>
			<td>'.$row["25"].'</td>
			<td>'.$row["26"].'</td>
			<td>'.$row["27"].'</td>';	
		switch ($jumtgl) {
		case 28:
			$output .= '<td>'.$row["28"].'</td>';
			break;
		case 29:
			$output .= '<td>'.$row["28"].'</td>
					 <td>'.$row["29"].'</td>';
			break;
		case 30:
			$output .= '<td>'.$row["28"].'</td>
					 <td>'.$row["29"].'</td>
					 <td>'.$row["30"].'</td>';
			break;
		case 31:
			$output .= '<td>'.$row["28"].'</td>
					 <td>'.$row["29"].'</td>
					 <td>'.$row["30"].'</td>
					 <td>'.$row["31"].'</td>';
			break;
		default:
			//nothing
			break;
		}
		
		$searchValue = 'H';
		$count = 0;

		foreach ($row as $value) {
			if ($value == $searchValue) {
				$count++;
			}
		}
		global $total_hari_sekolah;
		$percentage = ($count/$total_hari_sekolah)*100;
		$formattedResult = number_format($percentage, 2); // Format the result with 2 decimal places
		$ket_percentage="";
		if ($percentage < 70){
			$ket_percentage="TIDAK LULUS";
		}else{
			$ket_percentage="LULUS";
		}
		$output .= '<td>'.$count.' ('.$formattedResult.'%)</td>
		            <td style="text-align:center;">'.$ket_percentage.'</td>';
		$output .='</tr>';
		$no++;
      }	  
      return $output;  
 }

function countWorkingDaysInMonth($year, $month) {
    $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $workingDayCount = 0;

    for ($day = 1; $day <= $totalDays; $day++) {
        $date = new DateTime("$year-$month-$day");
        $dayOfWeek = $date->format('N'); // 1 (Monday) through 7 (Sunday)

        if ($dayOfWeek >= 1 && $dayOfWeek <= 5) { // Monday to Friday
            $workingDayCount++;
        }
    }

    return $workingDayCount;
}


function countNationalHolidaysInMonth($year, $month) {
    $url = "https://api-harilibur.vercel.app/api?year=$year&month=$month";

    $response = file_get_contents($url);

    if ($response === false) {
        echo "Error fetching data from API.";
        return 0; // Return 0 holidays on error
    }

    $holidays = json_decode($response, true);
    $nationalHolidayCount = 0;

    foreach ($holidays as $holiday) {
        if ($holiday['is_national_holiday'] === true) {
            $holidayDate = new DateTime($holiday['holiday_date']);
            $dayOfWeek = $holidayDate->format('N'); // 1 (Monday) through 7 (Sunday)
            
            if ($dayOfWeek != 6 && $dayOfWeek != 7) { // Exclude Saturday (6) and Sunday (7)
                $nationalHolidayCount++;
            }
        }
    }

    return $nationalHolidayCount;
}

function countWorkingDaysUntilToday($year, $month){
    $currentDay = date('d');
    $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $workingDayCount = 0;

    for ($day = 1; $day <= $currentDay; $day++) {
        $date = new DateTime("$year-$month-$day");
        $dayOfWeek = $date->format('N'); // 1 (Monday) through 7 (Sunday)

        if ($dayOfWeek >= 1 && $dayOfWeek <= 5) { // Monday to Friday
            $workingDayCount++;
        }
    }

    return $workingDayCount;
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
        <div class="col-12">	
          <div class="card my-4 h-100" id="data_list">
		  
			<div class="card-header pb-0">
				<div class="d-lg-flex">
					<div>
						<h5 class="mb-0">Data Presensi</h5>
						<p class="text-sm mb-0">
						Data Presensi Bulanan
						</p>
					</div>
					<div class="ms-auto my-auto mt-lg-0 mt-2">
						<div class="alert alert-warning text-white" role="alert" style="width:300px;">
						  <h5 class="mb-0 text-white text-center"> <?php echo $bulan_indo[$month].", ".$year; ?></h5>
						</div>
					</div>
				</div>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="d-lg-flex flex-row justify-content-end">
				    <div class="col-md-2 col-sm-4 mt-3 mt-sm-1 ms-4">
						<div class="input-group input-group-static">
							<span class="input-group-text material-icons text-md" id="basic-addon1"> calendar_month </span>
							<input type="text" class="form-control"  name="set_bulan" id="datepicker"  aria-describedby="basic-addon2" value="<?php echo $set_bulan; ?>" >
						</div>
					</div>
				  
					<div class="col-md-2 col-sm-4 mt-3 mt-sm-1 ms-4">
					   <div class="choices_inner">
						   <select class="form-control choices_input" name="choices-kelas" id="choices-kelas" placeholder="Kelas">
							  <option value="" selected>Pilih Tingkatan</option>
								<?php
									//$kelas = $d_siswa['s_kelas'];
									$sql_tingkat = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM opsi_tk_kelas");
									while($data_tk = mysqli_fetch_assoc($sql_tingkat)){
										//echo '<option value="'.$data_jurusan['j_id'].'">'.$data_jurusan['j_short'].'</option>';
										if($kelas == $data_tk['tk_name']){
											  echo '<option value="'.$data_tk['tk_name'].'" selected>'.$data_tk['tk_name'].'</option>';
										  }else{
											  echo '<option value="'.$data_tk['tk_name'].'">'.$data_tk['tk_name'].'</option>';
										  }
										
									}
								?>
							</select>
						</div>
					</div>
					
					<div class="col-md-2 col-sm-4 mt-3 mt-sm-1 ms-4">
					   <div class="choices_inner">
						   <select class="form-control choices_input" name="choices-jurusan" id="choices-jurusan" placeholder="Jurusan">
						      <option value="" selected>Pilih Kelas/Jurusan</option>
							  <?php
									$sql_jurusan = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM opsi_jurusan");
									while($data_jurusan = mysqli_fetch_assoc($sql_jurusan)){
										if($jurusan == $data_jurusan['j_id']){
											echo '<option value="'.$data_jurusan['j_id'].'" selected>'.$data_jurusan['j_short'].'</option>';
										}else{
											echo '<option value="'.$data_jurusan['j_id'].'">'.$data_jurusan['j_short'].'</option>';
										}
									}
								?>
							</select>
							
							
						</div>
					</div>
					<div class="col-md-auto col-sm-auto mt-3 mt-sm-1 ms-4 justify-content-end">
					    <div class="btn-group" role="group" aria-label="Basic example">
						  <button type="submit" class="btn bg-gradient-success" name="btn_submit" ><span class="material-icons">filter_list</span> Filter</button>
						  <button type="button" class="btn btn-primary" name="btn_reset"  onClick="location.href='presensi_bulanan'"><span class="material-icons">restart_alt</span> Reset</button>
						  <button id="btnGroupDrop1" type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
							<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
							  <li><a class="dropdown-item" href="javascript:void(0)" id="export_pdf"   onclick="$('#table_rekap_bulanan').DataTable().buttons(0,2).trigger()">PDF</a></li>
							  <li><a class="dropdown-item" href="javascript:void(0)" id="export_excel" onclick="$('#table_rekap_bulanan').DataTable().buttons(0,1).trigger()">Excel</a></li>
							  <li><a class="dropdown-item" href="javascript:void(0)" id="export_csv"   onclick="$('#table_rekap_bulanan').DataTable().buttons(0,0).trigger()">CSV</a></li>  
							  
							</ul>
						</div>						   
					</div>
				</div>
				</form>
			</div>
				
            <div class="card-body px-0 pb-2">
				<div class="row justify-content-center"> 
					<div class="table-responsive p-0 w-95">
					   
					   <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
					   
					   <table id="table_rekap_bulanan" class="table table-hover display table-striped" style="width:100%">
					     <!--<table id="table_rekap_bulanan" class="table align-items-center mb-0 display table-striped" style="width:100%">  -->
							<thead>
								<tr>  
								   <th width="5%">No</th>  
								   <th width="30%">Name</th> 
								   <th width="30%">Kelas</th>
								   <?php 
									   while($tgl <= $jumtgl) {
										echo "<th>".$tgl."</th>";
										$tgl++;
									   }
								   ?>
								   <th width="30%">Kehadiran</th>
								   <th width="30%">Keterangan</th>
								</tr>  
							</thead>
							<tbody>
								<?php echo fetch_data(); ?>
							</tbody>
						</table>
					   </div>
					</div>
				</div>
            </div>
          </div>
        </div>
	  </div>
	
	<!-- Modal detail -->
	<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
		  <div class="modal-header bg-primary">
			<h5 class="modal-title font-weight-normal text-white" id="exampleModalLabel">Member Detail</h5>
			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">X
			</button>
			
		  </div>
		  <div class="modal-body">
			 <div class="spinner-border text-danger justify-content-center" role="status" id="modal-detailloader" style="display: none;">
			     <span class="visually-hidden">Loading...</span>
			 </div>
			 <div id="data_detail">
			 </div>
		  </div>
		  <div class="modal-footer">
			<!-- <a href="#" class="btn bg-gradient-info"  id="modalDetailPrint" >Print</a>&nbsp -->
			<button type="button" class="btn bg-gradient-secondary shadow-secondary" data-bs-dismiss="modal">OK</button> 
		  </div>
		</div>
	  </div>
	</div>
	
	<!-- Modal delete -->
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header bg-primary">
			<h5 class="modal-title font-weight-normal text-white" id="exampleModalLabel">Delete Data Siswa</h5>
			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">X
			</button>
			
		  </div>
		  <div class="modal-body">
			 <div class="spinner-border text-danger justify-content-center" role="status" id="modal-loader" style="display: none;">
			     <span class="visually-hidden">Loading...</span>
			 </div>
			 <div id="data_delete">
			 </div>
		  </div>
		  <div class="modal-footer">
			<a href="#" class="btn bg-gradient-danger"  id="modalDelete" >Delete</a>&nbsp
			<button type="button" class="btn bg-gradient-secondary shadow-secondary" data-bs-dismiss="modal">Cancel</button> 
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
  
  
  <!--   Core JS Files   -->
  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/choices.min.js"></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
  <!-- Github buttons -->
  <script async defer src="../../assets/js/buttons_github.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/material-dashboard-pro.min.js?v=3.0.6"></script>
  
  <script type="text/javascript">
        $(document).ready(function(){
			//var judul_bulan = <?php echo $month_full.", ".$year; ?>;
			$('[data-toggle="tooltip"]').tooltip();
			
			$("#datepicker").datepicker( {
				format: "mm-yyyy",
				startView: "months", 
				minViewMode: "months",
				
				});
				
				
		    $('#table_rekap_bulanan').DataTable( {
				"ordering": false,
				buttons: [
					{
						extend: 'csvHtml5',
						title:'Rekap Presensi Bulanan',
						exportOptions: {
							columns: ':visible'
						}
					},
					{
						extend: 'excelHtml5',
						title:'Rekap Presensi Bulanan',
						exportOptions: {
							columns: ':visible'
						}
					},
					{
						extend: 'pdfHtml5',
						title:'Rekap Presensi Bulan <?php echo $bulan_indo[$month].", ".$year; ?>',
						orientation: 'landscape',
						pageSize: 'LEGAL',
						exportOptions: {
							 columns: ':visible'
						},
						customize: function (doc) {
                           doc.defaultStyle.fontSize = 11; //2, 3, 4,etc
                           doc.styles.tableHeader.fontSize = 12; //2, 3, 4, etc
						   doc.styles.title.fontSize = 16;
						   doc.styles.title.bold = true;
                           //doc.content[1].table.widths = [ '20%',  '30%', '20%', '20%', '10%'];
						   var now = new Date();
						   var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear();
						   doc['footer']=(function(page, pages) {
								return {
									columns: [
										{
											alignment: 'left',
											text: ['Created on: ', { text: jsDate.toString() }]
										},
										{
											alignment: 'right',
											text: ['page ', { text: page.toString() },	' of ',	{ text: pages.toString() }]
										}
									],
									margin: 20
								}
							});
						   var objLayout = {};
							objLayout['hLineWidth'] = function(i) { return .5; };
							objLayout['vLineWidth'] = function(i) { return .5; };
							objLayout['hLineColor'] = function(i) { return '#aaa'; };
							objLayout['vLineColor'] = function(i) { return '#aaa'; };
							objLayout['paddingLeft'] = function(i) { return 4; };
							objLayout['paddingRight'] = function(i) { return 4; };
							doc.content[1].layout = objLayout;
                       }
					}
				]
			} );
			
			//table
			//new DataTable('#table_rekap_bulanan');
			//var table = $('#table_rekap_bulanan').DataTable({
			//	"columnDefs": [ { "orderable": false, "targets": 5 }]
			//});
			
			//var tables = $('#table_rekap_bulanan').DataTable();
			
			
        });
  </script>
  <script>
    if (document.getElementById('choices-kelas')) {
      var element = document.getElementById('choices-kelas');
      const example = new Choices(element, {
        searchEnabled: false
      });
    };
	 if (document.getElementById('choices-jurusan')) {
      var element = document.getElementById('choices-jurusan');
      const example = new Choices(element, {
        searchEnabled: true
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