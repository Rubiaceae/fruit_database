<!--#新增行口運金的接收方

-->
<?php
#session_start();
#if(!isset($_SESSION['name']) || empty($_SESSION['name'])){
#	echo "尚未登入!";
#	echo "<script>setTimeout(function(){location.href='index.html';},1000);</script><!--五秒後自動回上一頁-->";
#	exit;
#}
?>


<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<script>setTimeout(function(){window.location = document.referrer;},2000);</script><!--2秒後自動回上一頁-->
</head>



<table>
<?php 
#判定新增的表格是哪一種，建立$sql語法

if( $_POST['token'] == "xAD5l9weDCqKkYgZNd1ICxn4"){
	include("mysql_connect.inc.php");
	
  	foreach ($_POST['order'] as $key => $value) {
		$sql = 'INSERT INTO fruit_database.order_list (`date`,`trucking_id`,`carlicense`,`shipper`,`product`,`quantity`,`trucking_money`,`consignee_id`,`note`) VALUES('.$value.')';
		echo $sql."</br>";
		if (mysqli_query($con, $sql)) {
			echo "訂單上傳成功!</br>";
		} else {
			echo "Error updating record: " . mysqli_error($con);
		}
	}	        
	echo "<tr>";
        echo "<td>";
        echo $key;
        echo "</td>";
        echo "<td>";
        echo $value;
        echo "</td>";
        echo "</tr>";





	mysqli_close($con);
    
}else{
echo "ERROR! Wrong tokon!";
}


?>
</table>

<input type ="button" onclick="window.location = document.referrer;" value="回到上一頁"></input>
</html>
