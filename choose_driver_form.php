<!--#分配訂單給司機的表單，會並列出未分配的名單，和已分配的名單

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

function getorderlist(){#查詢所有表join，未有司機編號，照行口排序
	include("mysql_connect.inc.php");

	$sql = 'SELECT * FROM fruit_database.order_list
	left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
	left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
	left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
	where order_list.driver_id is NULL
	order by station,order_list.consignee_id
	;';
	if ($result=mysqli_query($con, $sql)) {
		#echo "Get List successful!";
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	mysqli_close($con);
	return $result;
};

function getorderlist_withdriver(){#查詢所有表join，已有司機編號，且小於17小時內的紀錄，照driver_id,driver_trip,consignee_id排列
	include("mysql_connect.inc.php");
	$sql = 'SELECT * FROM fruit_database.order_list
	left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
	left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
	left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
	where order_list.driver_id is not null
	and  order_list.timestamp > (NOW()- INTERVAL 17 HOUR)
	order by order_list.driver_id,order_list.driver_trip, order_list.consignee_id
	;';
	if ($result=mysqli_query($con, $sql)) {
		#echo "Get List successful!";
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	mysqli_close($con);
	return $result;
};



?>
<!--
#html開始
-->
<h1>選擇要分派的司機與趟次</h1>
<form action="choose_driver_receive.php" method="post">
<input type="hidden" name="token" value="xAD5l9weDCqKkYgZNd1ICxn4">
<input type="submit" value="送出表單">
<input type="reset" value="清除表單">
<input type ="button" onclick="javascript:location.href='index.html'" value="回首頁"></input></br>
司機名稱
<select name="driver_id">
	<?php
	$driver_list=getlist("driver_list");
	while($row = $driver_list->fetch_array())
	{
		echo "\t\t<option value=\"".$row['driver_id']."\">".$row['driver']."</option>\n";
	}
	echo "<select>";
	?>

司機趟次
<select name="driver_trip">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<select></br>


<h1>未分配的貨物名單</h1></br>
<table border="1">
<tr><td>訂單成立時間</td><td>訂單編號</td><td>訂單日期</td><td>南部貨運商</td><td>車號</td><td>貨主</td><td>品名</td><td>數量</td><td>代收金</td><td>行口</td><td>市場</td><td>派送</td></tr>
	<?php
	$order_list=getorderlist();
	while($row = $order_list->fetch_array())
	{
		echo "<tr> <td>" . $row['timestamp'] . "</td><td>" . $row['order_id'] . "</td><td>" . $row['date'] . "</td> <td>" . $row['trucking']."</td><td>" . $row['carlicense'] . "</td><td>".$row['shipper'] . "</td><td>".$row['product'] . "</td><td>".$row['quantity'] . "</td><td>".$row['trucking_money'] . "</td><td>".$row['consignee'] . "</td><td>".$row['station'] . "</td><td> <input  type=\"checkbox\" name=".$row['order_id'] . "></td></tr>\n";
	};
	?>
</table></br>

<h1>已分配的貨物名單</h1></br>
<table border=\"1\">
<tr><td>訂單成立時間</td><td>訂單編號</td><td>訂單日期</td><td>南部貨運商</td><td>車號</td><td>貨主</td><td>品名</td><td>數量</td><td>代收金</td><td>行口</td><td>市場</td><td>司機</td><td>趟次</td><td>派送</td></tr>
	<?php
	$order_list_withdriver=getorderlist_withdriver();
	while($row = $order_list_withdriver->fetch_array())
	{
		echo "<tr> <td>" . $row['timestamp'] . "</td><td>" . $row['order_id'] . "</td><td>" . $row['date'] . "</td> <td>" . $row['trucking']."</td><td>" . $row['carlicense'] . "</td><td>".$row['shipper'] . "</td><td>".$row['product'] . "</td><td>".$row['quantity'] . "</td><td>".$row['trucking_money'] . "</td><td>".$row['consignee'] . "</td><td>".$row['station'] . "</td><td>".$row['driver'] . "</td><td>".$row['driver_trip'] . "</td><td> <input  type=\"checkbox\" name=".$row['order_id'] . "></td></tr>\n";
	};
	?>
</table></br>
</form>


</body >
</html>
