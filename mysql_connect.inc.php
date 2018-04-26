<?php 
	$con=mysqli_connect("127.0.0.1","root","password","fruit_database") or die("Error " . mysqli_error($con)); 
	// 檢查連線態狀
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	// 設定MySQL為utf8編碼
	mysqli_query($con,"SET NAMES 'utf8'");

?>



