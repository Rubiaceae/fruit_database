<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
</head>
<body >
<?php
if(!isset($_GET['driver_id']) && !isset($_GET['date'])) {
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


	echo "<h1>選擇要列印表單的日期與司機</h1>";
	echo '<form action="print_driver_table.php" method="get">';
		$today = date('Y-m-d' );
		echo "日期 date";
		echo "<input type=\"date\" name=\"date\" value=\"".$today."\"></br>";
		echo "司機名稱<select name=\"driver_id\">";
		$driver_list=getlist("driver_list");
		while($row = $driver_list->fetch_array())
		{
			echo "\t\t<option value=\"".$row['driver_id']."\">".$row['driver']."</option>\n";
		}
		echo "<select></br>";

		echo '<input type="submit" value="送出表單">';
		echo '<input type="reset" value="清除表單">';
		echo '<input type ="button" onclick="javascript:location.href=\'index.html\'" value="回首頁"></input>';
	echo '</form>';

} else {

	$driver_id=$_GET['driver_id'];
	$date=$_GET['date'];

	include("mysql_connect.inc.php");
	$sql = 'SELECT * FROM fruit_database.order_list, fruit_database.trucking_list,fruit_database.shipper_list,
	fruit_database.consignee_list,fruit_database.driver_list
	where trucking_list.trucking_id=order_list.trucking_id 
	and shipper_list.shipper_id=order_list.shipper_id 
	and consignee_list.consignee_id=order_list.consignee_id 
	and driver_list.driver_id=order_list.driver_id
	and order_list.driver_id=\''.$driver_id.'\'
	and order_list.date=\''.$date.'\'
	order by order_list.driver_trip, order_list.consignee_id
	;';



	if ($result=mysqli_query($con, $sql)) {
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	mysqli_close($con);






#html開始

	echo "<h1>已分配的貨物名單</h1></br>\n";
	echo "<table border=\"1\">\n";
	echo "<tr><td>訂單成立時間</td><td>訂單編號</td><td>訂單日期</td><td>南部貨運商</td><td>車號</td><td>貨主</td><td>品名</td><td>數量</td><td>代收金</td><td>行口</td><td>市場</td><td>司機</td><td>趟次</td><td>派送</td></tr>";
	while($row = $result->fetch_array())
	{
		echo "<tr> <td>" . $row['timestamp'] . "</td><td>" . $row['order_id'] . "</td><td>" . $row['date'] . "</td> <td>" . $row['trucking']."</td><td>" . $row['carlicense'] . "</td><td>".$row['shipper'] . "</td><td>".$row['product'] . "</td><td>".$row['quantity'] . "</td><td>".$row['trucking_money'] . "</td><td>".$row['consignee'] . "</td><td>".$row['station'] . "</td><td>".$row['driver'] . "</td><td>".$row['driver_trip'] . "</td><td> <input  type=\"checkbox\" name=".$row['order_id'] . "></td></tr>\n";
	};
	echo "</table></br>\n";

}
?>

</body >
</html>
