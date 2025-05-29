<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


date_default_timezone_set('Asia/Jakarta');
include_once '../../include/db_config.php';
include_once '../class/absensi.php';

$cnfg_status = 0;

$template_message ="";
$cnfg_token ="";
$tipe_presensi="";
$status_presensi="";
$ket_tambahan="";

$sql_cek_wa_service = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM wa_notification WHERE cnfg_id=1;");
while($data_wa_service = mysqli_fetch_assoc($sql_cek_wa_service)){
	$cnfg_status = $data_wa_service['cnfg_status'];
	$template_message = $data_wa_service['cnfg_intro'];
	$cnfg_token = $data_wa_service['cnfg_token'];
}

$database = new Database();
$db = $database->getConnection();

$item = new Absensi($db);
$item->uid = isset($_GET['uid']) ? $_GET['uid'] : die('wrong structure!');
	
if($item->createData()){
	// create array
	$data_arr = array(
		"waktu" => $item->waktu,
		"nama" => $item->nama,
		"uid" => $item->uid,
		"status" =>  $item->status,
		"keterangan" =>  $item->ket_absen
	);
	http_response_code(200);
	echo json_encode($data_arr);
	
	if ($cnfg_status == 1){
		
		// Retrieve the posted data
		$waktu = $item->waktu;
		$nama = $item->nama;
		$status = $item->status;
		$keterangan = $item->ket_absen;
		$ket_masuk = $item->ket_masuk; //
		$ket_keluar = $item->ket_keluar; //
		$kontak_wali = $item->wali_kontak;
		$nama_wali = $item->wali_nama;
		$jam_masuk = $item->jm_masuk;
		$jam_pulang = $item->jm_pulang;

		if ($status == "IN" ){
			$tipe_presensi = "ABSENSI MASUK";
			if ($ket_masuk != ""){
				$status_presensi = $ket_masuk;
				$ket_tambahan = "Untuk diketahui bahwa jadwal Absensi Masuk adalah pukul *".$jam_masuk."*";
			}else{
				$status_presensi = "Tepat Waktu";
			}
			
		}
		if ($status == "OUT" ){
			$tipe_presensi = "ABSENSI PULANG";
			if ($ket_keluar != ""){
				$status_presensi = $ket_keluar;
				$ket_tambahan = "Untuk diketahui bahwa jadwal Absensi Pulang adalah pukul *".$jam_pulang."*";
			}else{
				$status_presensi = "Tepat Waktu";
			}
		}
		if ($status == "IN" || $status == "OUT" ){
			// Replace placeholders with actual values
			//Yth. Wali murid {nama_siswa} Menginformasikan bahwa {nama_siswa} baru saja melaksanakan {tipe_presensi}, tepatnya pukul {waktu} dengan status presensi adalah {status_presensi}
			//Yth. Wali murid {nama_siswa} Menginformasikan bahwa {nama_siswa} baru saja melaksanakan *{tipe_presensi}*, tepatnya pukul *{waktu}* dengan status presensi adalah *{status_presensi}*. {ket_tambahan}
			$wa_message = str_replace(['{nama_siswa}', '{tipe_presensi}', '{waktu}', '{status_presensi}', '{ket_tambahan}'], [$nama, $tipe_presensi, $waktu, $status_presensi, $ket_tambahan], $template_message);


			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://whatsapp.pasarcoding.com/send-message',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS => array(
			    'api_key' =>  $cnfg_token,
				'sender' =>  '6289516426939',
				'number' =>  $kontak_wali,
				'message' =>  $wa_message,
			)
			));

			$response = curl_exec($curl);

			curl_close($curl);
			//echo $response;
		}
		
	}
	
	
} else{
	http_response_code(404);
	echo json_encode("Failed!");
}
?>