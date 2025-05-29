<?php 
	date_default_timezone_set('Asia/Jakarta');
	session_start();
	if ( $_SESSION['akses']!= 'Admin'){// handling if dont'have session

		header('location:../../index'); 
		exit();
	} 
	$ses_name = $_SESSION['name'];
	$_SESSION['pages']="Siswa";
    
	require_once "../../include/db_config.php";
	include "control/confignusers_data.php";
	
	$kelas="";
	$jurusan="";
	$sql="";
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$kelas = $_POST["choices-kelas"]; 
		$jurusan= $_POST["choices-jurusan"]; 
		
		if($jurusan != ""){
			if($kelas!= ""){
				$sql = "SELECT * FROM data_siswa,opsi_jurusan WHERE s_jurusan='$jurusan' AND s_jurusan=j_id AND s_kelas = '$kelas' AND s_status = 'Aktif'
						ORDER BY  s_nama";
			}else{
				$sql = "SELECT * FROM data_siswa,opsi_jurusan WHERE s_jurusan='$jurusan' AND s_jurusan=j_id  AND s_status = 'Aktif'
						ORDER BY  s_nama";
			}
		}else{
			if($kelas!= ""){
				$sql = "SELECT * FROM data_siswa,opsi_jurusan WHERE s_kelas= '$kelas' AND s_jurusan=j_id AND s_status = 'Aktif'
						ORDER BY  s_nama";
			}else{
				$sql = "SELECT * FROM data_siswa,opsi_jurusan WHERE s_status = 'Aktif' AND s_jurusan=j_id ORDER BY s_nama DESC ";
			}
		}
	
	}
	else{
		$sql = "SELECT * FROM data_siswa,opsi_jurusan WHERE s_status = 'Aktif' AND s_jurusan = j_id ORDER BY s_id DESC";
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
						<h5 class="mb-0">Daftar Siswa</h5>
						<p class="text-sm mb-0">
						List seluruh siswa aktif
						</p>
					</div>
					<div class="ms-auto my-auto mt-lg-0 mt-4">
					  <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
						  <button type="button" class="btn btn-primary btn-sm" id="btn_add"  onClick="location.href='scan'">+&nbsp; Tambah Data Siswa</button>
						  <div class="btn-group ms-1" role="group">
							<button id="btnGroupDrop1" type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
							  Export
							</button>
							<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
							  <li><a class="dropdown-item" href="javascript:void(0)" id="export_pdf"   onclick="$('#table_siswa').DataTable().buttons(0,2).trigger()">PDF</a></li>
							  <li><a class="dropdown-item" href="javascript:void(0)" id="export_excel" onclick="$('#table_siswa').DataTable().buttons(0,1).trigger()">Excel</a></li>
							  <li><a class="dropdown-item" href="javascript:void(0)" id="export_csv"   onclick="$('#table_siswa').DataTable().buttons(0,0).trigger()">CSV</a></li>  
							  
							</ul>
						  </div>
						</div>
					</div>
				</div>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="d-lg-flex flex-row justify-content-end">
				  
					<div class="col-md-2 col-sm-4 mt-3 mt-sm-1 ms-4">
					   <div class="choices_inner">
						   <select class="form-control choices_input" name="choices-kelas" id="choices-kelas" placeholder="Kelas">
								<option value="" selected>Pilih Tingkatan</option>
								<?php
									$sql_tingkat = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM opsi_tk_kelas ORDER BY tk_name DESC;");
									while($data_tk = mysqli_fetch_assoc($sql_tingkat)){
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
									$sql_jurusan = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM opsi_jurusan;");
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
						   <button class="btn bg-gradient-info btn-sm mb-0" type="submit" name="button"><span class="material-icons">filter_list</span> Filter</button>
						   <a class="btn bg-gradient-default btn-sm mb-0" name="button" href="siswa"><span class="material-icons">restart_alt</span> Reset</a>
					</div>
				</div>
				</form>
			</div>
				
            <div class="card-body px-0 pb-2">
				<div class="row justify-content-center"> 
					<div class="table-responsive p-0 w-95">
					   
					   <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
					   
					   <table id="table_siswa" class="table table-hover table-fixed" style="width:100%">
					     <!--<table id="table_siswa" class="table align-items-center mb-0 display table-striped" style="width:100%">  -->
							<thead>
								<tr>
								    <th>No</th>
									<th>UID</th>
									<th>Siswa</th>
									<th class="text-center">Gender</th>
									<th class="text-center">Kelas</th>
									<th class="text-center">Umur</th>
									<th >Action</th>
								</tr>
								
							</thead>
								<tbody style="border-style:none;">
								    <?php
									    $no = 0;
										$s_member = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
										while ($d_member=mysqli_fetch_array($s_member)){
											$id64 = base64_encode($d_member['s_id']);
											$no++;
									  
									?>
									   <tr style="border-bottom: 0;">
									        <td><?= $no;?></td>
											<td><?= $d_member['s_uid'];?></td>
											<td>
												<div class="d-flex px-2 py-1"><div><img src="<?= $d_member['s_picture'];?>" class="avatar avatar-sm me-3 border-radius-lg" alt="user<?= $d_member['s_id'];?>"></div>
													<div class="d-flex flex-column justify-content-center">
														<h6 class="mb-0 text-sm"><?= $d_member['s_nama'];?></h6>
														<p class="text-xs text-secondary mb-0"><?= $d_member['s_nis'];?></p> 
													</div>	
												</div>
											</td>
											<td  class="text-center"><?= $d_member['s_kelamin'];?></td>
											<td class="text-center"><?= $d_member['s_kelas'];?> - <?= $d_member['j_short'];?> </td>
											<td  class="text-center">
											<?php
												$dateOfBirth = $d_member['s_tgl_lahir'];;
												$today = date("Y-m-d");
												$diff = date_diff(date_create($dateOfBirth), date_create($today));
												echo $diff->format('%y');
											?>
											</td>
											<td>
											<?php
											    echo "<a href='javascript:;' data-bs-toggle='modal' data-bs-target='#detailModal' data-id='". $d_member['s_id'] ."' id='getDetailUser'
													     title='Detail Record' data-toggle='tooltip'><span class='fa fa-eye'></span></a>&nbsp;&nbsp;";
												//echo "<a href='javascript:;' data-id='". $d_member['p_id'] ."' title='Update Record' data-toggle='tooltip' id='UpdateUser' ><i class='far fa-edit'></i></a>&nbsp;&nbsp;";
												echo "<a href='siswa_update?id_siswa=". $id64 ."' title='Update Record' data-toggle='tooltip'><i class='far fa-edit'></i></a>&nbsp;&nbsp;";
												echo "<a href='javascript:;' data-bs-toggle='modal' data-bs-target='#deleteModal' data-id='". $d_member['s_id'] ."' id='getDelUser'
													     title='Delete Record' data-toggle='tooltip'><span class='fa fa-trash'></span></a> &nbsp;&nbsp;&nbsp;&nbsp;";
														 
												if ($d_member['user_stat']==0){
													echo "<a href='user_create?id_siswa=". $d_member['s_id'] ."' title='Buat Akun' data-toggle='tooltip'><span class='fa fa-plus'></span></a>";
												
												}else{
													echo "<a href='user_create?id_siswa=". $d_member['s_id'] ."' title='Buat Akun' data-toggle='tooltip' disabled style='pointer-events:none; color:#cccccc'><span class='fa fa-plus' disabled></span></a>";
											
												}
													  
											?>
											</td>
									   
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
	
	<!-- Modal detail -->
	<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
		  <div class="modal-header bg-primary">
			<h5 class="modal-title font-weight-normal text-white" id="exampleModalLabel">Detail Siswa</h5>
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
  
  <!-- Github buttons -->
  <script async defer src="../../assets/js/buttons_github.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/material-dashboard-pro.min.js?v=3.0.6"></script>
  
  <script type="text/javascript">
        $(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();
						
			$(document).on('click', '#getDelUser', function(e){
			  
				 e.preventDefault();
			  
				 var uid = $(this).data('id'); // get id of clicked row
			  
				 $('#data_delete').html(''); // leave this div blank
				 $('#modalDelete').attr('href','control/siswa_del.php?id='+uid);
				 $('#modal-loader').show();      // load ajax loader on button click
				
				 $.ajax({
					  url: 'view/siswa_del.php',
					  type: 'POST',
					  data: 'id='+uid,
					  dataType: 'html'
				 })
				 .done(function(data){
					  //console.log(data); 
					  $('#data_delete').html(''); // blank before load.
					  $('#data_delete').html(data); // load here  
					  $('#modal-loader').hide(); // hide loader 					  
				 })
				 .fail(function(){
					  $('#data_delete').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
					  $('#modal-loader').hide();
				 });

			});
			
			$(document).on('click', '#getDetailUser', function(e){
			  
				 e.preventDefault();
			  
				 var uid = $(this).data('id'); // get id of clicked row
			  
				 $('#data_detail').html(''); // leave this div blank
				 $('#modal-detailloader').show();      // load ajax loader on button click
				
				 $.ajax({
					  url: 'view/siswa_detail.php',
					  type: 'POST',
					  data: 'id='+uid,
					  dataType: 'html'
				 })
				 .done(function(data){
					  //console.log(data); 
					  $('#data_detail').html(''); // blank before load.
					  $('#data_detail').html(data); // load here  
					  $('#modal-detailloader').hide(); // hide loader 					  
				 })
				 .fail(function(){
					  $('#data_detail').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
					  $('#modal-detailloader').hide();
				 });

			});
			
			//table
			//new DataTable('#table_siswa');
			//var table = $('#table_siswa').DataTable({
			//	"columnDefs": [ { "orderable": false, "targets": 5 }]
			//});
			
			
			$('#table_siswa').DataTable( {
				columnDefs: [ { 'orderable': false, 'targets': 6 }],
				buttons: [
					{
						extend: 'csvHtml5',
						title:'Daftar Siswa',
						exportOptions: {
							columns: [ 0, 1, 2, 3, 4, 5 ]
						}
					},
					{
						extend: 'excelHtml5',
						title:'Daftar Siswa',
						exportOptions: {
							columns: [ 0, 1, 2, 3, 4, 5 ]
						}
					},
					{
						extend: 'pdfHtml5',
						title:'Daftar Siswa',
						exportOptions: {
							columns: [ 0, 1, 2, 3, 4, 5 ]
						},
						customize: function (doc) {
                           doc.defaultStyle.fontSize = 11; //2, 3, 4,etc
                           doc.styles.tableHeader.fontSize = 12; //2, 3, 4, etc
						   doc.styles.title.fontSize = 16;
						   doc.styles.title.bold = true;
                           doc.content[1].table.widths = [ '5%', '20%',  '30%', '20%', '20%', '10%'];
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
			
        });
  </script>
  <script>
    if (document.getElementById('choices-kelas')) {
      var element = document.getElementById('choices-kelas');
      const example = new Choices(element, {
        searchEnabled: true
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
  <?php
  	if(isset($_GET['msg'])){
		$stat_msg=base64_decode($_GET['msg']);
		
		if($stat_msg == "300"){
		$modal_stat ="Delete Status";
		echo "<script type='text/javascript'>
			$(document).ready(function(){
				$('#Modal_notif_head').addClass('bg-primary'); 
				$('#notif_msg').text('Gagal menghapus data Siswa, silahkan ulangi proses!');
				$('#Modal_notif').modal('show');
			});
			</script>";
		}
		if($stat_msg == "301"){
		$modal_stat ="Delete Status";
		echo "<script type='text/javascript'>
			$(document).ready(function(){
			  $('#Modal_notif_head').addClass('bg-success'); 
			  $('#notif_msg').text('Data Siswa berhasil dihapus');
			  $('#Modal_notif').modal('show');
			});
			</script>";
		}
		if($stat_msg == "200"){
		$modal_stat ="Update Status";
		$info = base64_decode($_GET['info']);
		echo "<script type='text/javascript'>
			$(document).ready(function(){
			  $('#Modal_notif_head').addClass('bg-success'); 
			  $('#notif_msg').text('".$info."');
			  $('#Modal_notif').modal('show');
			});
			</script>";
		}
	}
  ?>
  <!-- Modal status delete -->
	<div class="modal fade" id="Modal_notif" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div id="Modal_notif_head" class="modal-header">
			<h5 class="modal-title font-weight-normal text-white" id="exampleModalLabel">
			<?php
			  if ($modal_stat == "Delete Status"){
				  echo "<i class='far fa-trash-alt'></i>";
				  
			  }
			  if ($modal_stat == "Update Status"){
				  echo "<i class='far fa-edit'></i>";  
			  }
			
			?>
			</i>&nbsp; <?php echo $modal_stat;?></h5>
			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
			</button>
		  </div>
		  <div class="modal-body">
			 <div class="d-flex justify-content-center">
			   <p id="notif_msg" style="font-size:20px;"></p><br>
			 </div>
		  </div>
		  <div class="modal-footer justify-content-center">
			<a role="button" class="btn bg-gradient-secondary shadow-secondary"  data-bs-dismiss="modal">OK</a>
		  </div>
		</div>
	  </div>
	</div>
</body>

</html>