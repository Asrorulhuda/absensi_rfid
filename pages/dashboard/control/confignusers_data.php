<?php 
	$ses_id = $_SESSION['id'];
	
	$sql = "SELECT * FROM users WHERE id ='$ses_id'";

	$user_conf = mysqli_query($GLOBALS["___mysqli_ston"],$sql);
	if(mysqli_num_rows($user_conf)>0){ 
		$user_data = mysqli_fetch_array($user_conf);
			$user_id = $user_data["id"];
			$user_name = $user_data["name"];
			$user_email = $user_data["email"];
			$user_username = $user_data["username"];
			$user_password = $user_data["password"];
			$user_pic = $user_data["picture"];
	}
	
	
	$sql = "SELECT * FROM system_config WHERE id =1";

	$system_conf = mysqli_query($GLOBALS["___mysqli_ston"],$sql);
	$row = mysqli_fetch_array($system_conf);
	
	$nama_perusahaan = $row["company"];
	$title_bar = $row["title_bar"];
	$icon_bar = $row["icon_bar"];
	$icon_dashboard = $row["icon_dashboard"];
	$company = $row["company"];
	$footer = $row["footer"];
	
	
	
?>