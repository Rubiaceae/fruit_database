<!--#結清代收金的表單，如果有填車號，就分開結清，如果沒有填車號，就一起結清。

-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
</head>
<body >
<?php

function getlist($list){
	include("mysql_connect.inc.php");
	$sql='select * from '.$list;
	if ($result=mysqli_query($con, $sql)) {
		#echo "Get List successful!";
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	mysqli_close($con);
	return $result;
};


if(!isset($_GET['trucking_id']) && !isset($_GET['date'])) {
	echo "<h1>選擇要列印表單的日期與貨運行</h1>";
	echo '<form action="print_trucking_form.php" method="get">';
		$today = date('Y-m-d' );
		echo "日期 date";
		echo "<input type=\"date\" name=\"date\" value=\"".$today."\"></br>";
		echo "貨運行名稱<select name=\"trucking_id\">";
		$trucking_list=getlist("trucking_list");
		while($row = $trucking_list->fetch_array())
		{
			echo "\t\t<option value=\"".$row['trucking_id']."\">".$row['trucking']."</option>\n";
		}
		echo "<select>";
		echo " 車號";
		echo '<input type="text" name="carlicense" maxlength="7" size="7"></br>';
		echo '<input type="submit" value="送出表單">';
		echo '<input type="reset" value="清除表單">';
		echo '<input type ="button" onclick="javascript:location.href=\'index.html\'" value="回首頁"></input>';
	echo '</form>';

} else {

	$trucking_id=$_GET['trucking_id'];
	$date=$_GET['date'];
	$carlicense=$_GET['carlicense'];
	include("mysql_connect.inc.php");
	if(empty($carlicense)){#判斷有沒有填車牌，沒有車號就全列，照order_id排
	$sql = 'SELECT * FROM fruit_database.order_list
		left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
		left join fruit_database.shipper_list  on shipper_list.shipper_id=order_list.shipper_id 
		left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
		left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
		where order_list.trucking_id=\''.$trucking_id.'\'
		and order_list.date=\''.$date.'\'
		order by order_list.order_id
		;';
	}else{#有車號就列，照order_id排
	$sql = 'SELECT * FROM fruit_database.order_list
		left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
		left join fruit_database.shipper_list  on shipper_list.shipper_id=order_list.shipper_id 
		left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
		left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
		where order_list.trucking_id=\''.$trucking_id.'\'
		and order_list.date=\''.$date.'\'
		and order_list.carlicense=\''.$carlicense.'\'
		order by order_list.order_id
		;';
	}



	if ($result=mysqli_query($con, $sql)) {
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	#mysqli_close($con);

	$sql2="select trucking from fruit_database.trucking_list where trucking_id =".$trucking_id." limit 1;";
	
	if ($result2=mysqli_query($con, $sql2)) {
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	mysqli_close($con);	
	while($row = $result2->fetch_array()){
	$trucking=$row['trucking'];}

	$sum_trucking_money=0;




#html開始

	echo "<h1>日期:".$date." 貨運行: ".$trucking."</h1></br>\n";
	echo "<table border=\"1\">\n";
	echo "<tr><td>訂單成立時間</td><td>訂單編號</td><td>訂單日期</td><td>南部貨運商</td><td>車號</td><td>貨主</td><td>品名</td><td>數量</td><td>代收金</td><td>行口</td><td>市場</td><td>司機</td><td>趟次</td></tr>";
	while($row = $result->fetch_array())
	{
		echo "<tr> <td>" . $row['timestamp'] . "</td><td>" . $row['order_id'] . "</td><td>" . $row['date'] . "</td> <td>" . $row['trucking']."</td><td>" . $row['carlicense'] . "</td><td>".$row['shipper'] . "</td><td>".$row['product'] . "</td><td>".$row['quantity'] . "</td><td>".$row['trucking_money'] . "</td><td>".$row['consignee'] . "</td><td>".$row['station'] . "</td><td>".$row['driver'] . "</td><td>".$row['driver_trip'] . "</td></tr>\n";
	$sum_trucking_money=$sum_trucking_money+$row['trucking_money'];
	};
	echo "</table></br>\n";

	echo "<h2> 代收金總額=".$sum_trucking_money."</h2></br>";
	

}
?>

</body >
</html>
