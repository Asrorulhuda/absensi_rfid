<?php
    $tanggal = date('d M Y');
	$day = date('D', strtotime($tanggal));
	$dayList = array(
		'Sun' => 'Minggu',
		'Mon' => 'Senin',
		'Tue' => 'Selasa',
		'Wed' => 'Rabu',
		'Thu' => 'Kamis',
		'Fri' => 'Jumat',
		'Sat' => 'Sabtu'
	);
	$today = date("d-m-Y");
		
 
    $s_member= mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM data_siswa");
	$rowcount = mysqli_num_rows($s_member);
	
	$s_absensi = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM data_absen WHERE tanggal='".$today."' GROUP BY data_absen.uid");
	$absensi = mysqli_num_rows($s_absensi);
	
	$s_invalid = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM data_invalid GROUP BY uid");
	$invalid = mysqli_num_rows($s_invalid);
	
	
	if ($rowcount > 0){
		$prosentase = ($absensi/$rowcount)* 100;	
		$prosentase = number_format($prosentase, 2);
	}else{
		$prosentase = 0;
	}
	
	
?>