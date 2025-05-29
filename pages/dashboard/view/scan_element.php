<?php 
date_default_timezone_set('Asia/Jakarta');

require_once "../../../include/db_config.php";

$sql = "SELECT * FROM tmp_datacard ORDER BY id DESC LIMIT 1";
if($stmt = mysqli_prepare($link, $sql)){
// Attempt to execute the prepared statement
	if(mysqli_stmt_execute($stmt)){
		$result = mysqli_stmt_get_result($stmt);

		if(mysqli_num_rows($result) > 0){
			/* Fetch result row as an associative array. Since the result set
			contains only one row, we don't need to use while loop */
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

			$id = $row["id"];
			$uid = $row["uid"];
			$jam = $row["jam"];
			$status = $row["card_status"];
			$card_status = "";
			if ($status == "INVALID"){ $card_status = "Tidak Terdaftar"; ?>
				
				<div class="multisteps-form__content" style="height:500px;">
					<div class="col-md-10 col-ms-8">
						<div class="row mt-5" >
							<div class="col-12 col-sm-6 mt-3">
								<div class="row mt-3 justify-content-center" style="height: 306px;">	
									<div class="d-flex justify-content-center align-items-center">
									   <img src="../../assets/img/invalid.png" width="60%" />
									</div>
								</div>
								
							</div>
							<div class="col-12 col-sm-6 mt-3 mt-sm-0">
								<div class="row mt-5">	
									<div class="input-group input-group-static">
									  <label>UID</label>
									  <input type="text" class="form-control" value="<?= $uid;?>" readonly>
									</div>
								</div>
								<div class="row mt-4">
									<div class="input-group input-group-static">
									  <label>Waktu</label>
									  <input type="text" class="form-control" value="<?= $jam;?>" readonly>
									</div>
								</div>
								<div class="row mt-4">	
									<div class="input-group input-group-static">
									  <label>Status Kartu</label>
									  <input type="text" class="form-control" value="<?= $card_status;?>" readonly>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-end mt-3 mb-5">
							<a class="btn bg-gradient-success ms-2 mb-0" style="text-color:white" type="button" href="siswa_registration?uid=<?= $uid;?>">Daftarkan Kartu</a>
						</div>
					</div>
				</div>
			<?php
			}else{
				//3. Wrong Reader IN 
				//4. Wrong Reader OUT
				//5. Still Active in another room
				//6. Reader Not registered/invalid reader
				$logo_display = 0;
				if ($status=="IN"){$card_status = "Tap IN Detected "; $logo_display = 0;}
				if ($status=="OUT"){$card_status = "Tap OUT Detected";$logo_display = 1;}
				if ($status=="LOCKED"){$card_status = "Tidak dapat melakukan presensi";$logo_display = 2;}
				if ($status=="IN2"){$card_status = "Sudah melakukan presensi";$logo_display = 0;}
				
				$sql = "SELECT * FROM data_siswa WHERE s_uid='".$uid."'";
				if($stmt = mysqli_prepare($link, $sql)){
				// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($stmt)){
						$result = mysqli_stmt_get_result($stmt);

						if(mysqli_num_rows($result) > 0){
							$baris = mysqli_fetch_array($result, MYSQLI_ASSOC);
							$avatar = $baris["s_picture"];
							$nama = $baris["s_nama"];
							
						}
						?>
						<div class="multisteps-form__content" style="height:500px;">
						<div class="col-md-10 col-ms-8">
							<div class="row mt-3" >
								<div class="col-12 col-sm-6 mt-3">
									<div class="row mt-3 justify-content-center" style="height: 306px;">	
										<div class="d-flex justify-content-center align-items-center">
										   <img src="<?= $avatar;?>" width="80%" class="rounded mx-auto d-block" />
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-6 mt-3 mt-sm-0">
									<div class="row mt-6">	
										<div class="input-group input-group-static">
										  <label>Time</label>
										  <input type="text" class="form-control" value="<?= $jam;?>" readonly>
										</div>
									</div>
									<div class="row mt-4">	
										<div class="input-group input-group-static">
										  <label>UID</label>
										  <input type="text" class="form-control" value="<?= $uid;?>" readonly>
										</div>
									</div>
									<div class="row mt-4">	
										<div class="input-group input-group-static">
										  <label>Nama Siswa</label>
										  <input type="text" class="form-control" value="<?= $nama;?>" readonly>
										</div>
									</div>
									<div class="row mt-4">	
										<div class="input-group input-group-static">
										  <label>Status</label>
										  <input type="text" class="form-control" aria-describedby="basic-addon2" value="<?= $card_status; ?>" readonly>
										  <?php 
											if($logo_display == 2){ ?>
											   <span class="input-group-text badge badge-danger badge-sm" id="basic-addon2" style="padding-left:10px; padding-right:10px;"><i class="material-icons opacity-10">phonelink_erase</i></span>
										  <?php 	
											}if($logo_display == 1){ ?>
												<span class="input-group-text badge badge-primary badge-sm" id="basic-addon2" style="padding-left:10px; padding-right:10px;"><i class="material-icons opacity-10">logout</i></span>
										   <?php 	
											}if($logo_display == 0){ ?>
												<span class="input-group-text badge badge-success badge-sm" id="basic-addon2" style="padding-left:10px; padding-right:10px;"><i class="material-icons opacity-10">login</i></span>
										   <?php 	
											}
											 ?>
											
										  
										</div>
									</div>
								
								</div>
							</div>
						</div>
						</div>
						
										 
						<?php
						
						
					}
				}
			}
			
		} else{
			?>	
			<div class="row mb-5" style="height:500px;">	
				<div class="col-12">
					<div class="row mt-3 justify-content-center">	
						<div class="d-flex justify-content-center align-items-center">
							<img src="../../assets/img/scan2.gif" class="img-fluid" style="width: 70%"> <br> 
						</div>
					</div>
					
				</div>
				<div class="col-12">
					<div class="d-flex justify-content-center align-items-center">
						<h5>Tap Your Card!</h5>
					</div>
				</div>
			</div>	
			<?php
		}

	} else{
		echo "Oops! Something went wrong. Please try again later.";
	}
}

$sql = "DELETE FROM tmp_datacard ";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_execute($stmt);
