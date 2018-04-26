<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<script>setTimeout(function(){window.location = document.referrer;},5000);</script><!--五秒後自動回上一頁-->
</head>



<table>
<?php 

if( $_POST['token'] == "xAD5l9weDCqKkYgZNd1ICxn4"){

	include("mysql_connect.inc.php");
  	foreach ($_POST as $key => $value) {
		if(!is_null($key) &&is_numeric($key)){
			$order_id= $order_id.'\',\''.$key;
		}	

        echo "<tr>";
        echo "<td>";
        echo $key;
        echo "</td>";
        echo "<td>";
        echo $value;
        echo "</td>";
        echo "</tr>";}

	$order_id = '\''.$order_id . '\'';
	$order_id=substr($order_id,3,strlen($order_id)-1);

	$sql = 'UPDATE fruit_database.order_list SET driver_id = \''.$_POST['driver_id'].'\' WHERE order_id in ('.$order_id.');';
	$sql2 = 'UPDATE fruit_database.order_list SET driver_trip = \''.$_POST['driver_trip'].'\' WHERE order_id in ('.$order_id.');';
	echo $order_id."</br>";
	echo $sql."</br>";
    	echo $sql2."</br>";

	if (mysqli_query($con, $sql)) {
		echo "driver_id updated successfully!</br>";
	} else {
		echo "Error updating record: " . mysqli_error($con);
	}
	if (mysqli_query($con, $sql2)) {
		echo "driver_trip updated successfully!</br>";
	} else {
		echo "Error updating record: " . mysqli_error($con);
	}
	mysqli_close($con);
}else{
	echo "ERROR! Wrong tokon!</br>";
}

?>
</table>

<input type ="button" onclick="window.location = document.referrer;" value="回到上一頁"></input>
</html>
