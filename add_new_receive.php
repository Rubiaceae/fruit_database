<!--#新增行口編號、司機編號等功能所需要的接收方

-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<script>setTimeout(function(){window.location = document.referrer;},5000);</script><!--五秒後自動回上一頁-->

</head>



<table>
<?php 
#判定新增的表格是哪一種，建立$sql語法

if( $_POST['token'] == "xAD5l9weDCqKkYgZNd1ICxn4"){
	if(!empty($_POST['trucking_new'])){
		$sql = 'INSERT INTO fruit_database.trucking_list (`trucking`) values("'.$_POST['trucking_new'].'")';
	}elseif(!empty($_POST['consignee_new'])){
		$sql = 'INSERT INTO fruit_database.consignee_list (`consignee`,`station`) values("'.$_POST['consignee_new']."\",\"".$_POST['station_new'].'")';
	}elseif(!empty($_POST['driver_new'])){
		$sql = 'INSERT INTO fruit_database.driver_list (`driver`) values("'.$_POST['driver_new'].'")';
	}else{	
	echo "post error";
	}


	
	include("mysql_connect.inc.php");
	if (mysqli_query($con, $sql)) {
		echo "New member info updated successfully!</br>";
	} else {
		echo "Error updating record: " . mysqli_error($con);
	}
	mysqli_close($con);


  	foreach ($_POST as $key => $value) {
		echo "<tr>";
		echo "<td>";
		echo $key;
		echo "</td>";
		echo "<td>";
		echo $value;
		echo "</td>";
		echo "</tr>";
	}
	echo $sql;
    
}else{
	echo "ERROR! Wrong tokon!";
}


?>
</table>

<input type ="button" onclick="window.location = document.referrer;" value="回到上一頁"></input>
</html>
