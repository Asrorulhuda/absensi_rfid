<?php
date_default_timezone_set("Asia/Jakarta");

$db_server = 'localhost'; 
$db_name = 'u1096181_sp_siswa'; 
$db_user = 'u1096181_sps_admin'; 
$db_password = 'Rahasia@gmail.com'; 
$no_of_records_per_page = 10; 
$appname = 'Sistem Presensi Siswa'; 

$link = mysqli_connect($db_server, $db_user, $db_password, $db_name); 

$query = "SHOW VARIABLES LIKE 'character_set_database'";
if ($result = mysqli_query($link, $query)) {
    while ($row = mysqli_fetch_row($result)) {
        if (!$link->set_charset($row[1])) {
            printf("Error loading character set $row[1]: %s\n", $link->error);
            exit();
        } else {
            // printf("Current character set: %s", $link->character_set_name());
        }
    }
}


($GLOBALS["___mysqli_ston"] = mysqli_connect($db_server, $db_user, $db_password));
mysqli_select_db($GLOBALS["___mysqli_ston"], $db_name);

//pdo_con
class Database {
	private $host = "localhost";
	private $database_name = "u1096181_sp_siswa";
	private $username = "u1096181_sps_admin";
	private $password = "Rahasia@gmail.com";

	public $conn;

	public function getConnection(){
		$this->conn = null;
		try{
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
			$this->conn->exec("set names utf8");
		}catch(PDOException $exception){
			echo "Database could not be connected: " . $exception->getMessage();
		}
		return $this->conn;
	}
}  
?>


