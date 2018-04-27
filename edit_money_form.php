<!--#分配訂單的金錢，包括行口與司機的

-->

<?php
session_start();

function getorderlist_withconsignee_date($consignee_id,$date){#查詢所有表join，有特定行口編號與日期，尚未設定金錢
	include("mysql_connect.inc.php");
	$sql = 'SELECT * FROM fruit_database.order_list
left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
left join fruit_database.shipper_list  on shipper_list.shipper_id=order_list.shipper_id 
left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
where order_list.consignee_id=\''.$consignee_id.'\'
and order_list.date=\''.$date.'\'
and consignee_money is null
order by order_list.driver_trip, order_list.consignee_id
;';
	if ($result=mysqli_query($con, $sql)) {
		#echo "Get List successful!";
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	mysqli_close($con);
	return $result;

}


function getorderlist_withconsignee_date_money($consignee_id,$date){#查詢所有表join，有特定行口編號與日期，已設定金錢
	include("mysql_connect.inc.php");
	$sql = '
SELECT * FROM fruit_database.order_list
left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
left join fruit_database.shipper_list  on shipper_list.shipper_id=order_list.shipper_id 
left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
where order_list.consignee_id=\''.$consignee_id.'\'
and order_list.date=\''.$date.'\'
and consignee_money is not null
order by order_list.driver_trip, order_list.consignee_id
;';
	if ($result=mysqli_query($con, $sql)) {
		#echo "Get List successful!";
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	mysqli_close($con);
	return $result;

}

function getsum_money($consignee_id,$date){#查尋特定行口編號與日期，已設定金錢的加總
	include("mysql_connect.inc.php");
	$sql = 'SELECT sum(trucking_money) as sum_trucking_money,sum(consignee_money) as sum_consignee_money FROM fruit_database.order_list
where order_list.consignee_id=\''.$consignee_id.'\'
and order_list.date=\''.$date.'\'
and consignee_money is not null
order by order_list.driver_trip, order_list.consignee_id
;';
	if ($result=mysqli_query($con, $sql)) {
		#echo "Get List successful!";
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	mysqli_close($con);
	return $result;

}

function returntoindex($s){
	echo "<script>setTimeout(function(){location.href='index.html';},".$s.");</script><!--五秒後自動回首頁-->";
}

?>
<!--
html開始
-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
</head>
<body >

<?php
$consignee_id=$_GET['consignee_id'];
$date=$_GET['date'];

if(!isset($_SESSION['name']) || empty($_SESSION['name'])){
	echo "尚未登入!";
	echo "<script>setTimeout(function(){location.href='login_form.php';},".$s.");</script><!--五秒後自動回首頁-->";
}elseif(isset($_GET['consignee_id']) && isset($_GET['date'])) {
	$result=getorderlist_withconsignee_date($consignee_id,$date);
	echo '<form action="edit_money_receive.php" method="post">';
	echo "<h1>未決定金額的訂單</h1></br>\n";
	echo "<table border=\"1\">\n";
	echo "<tr><td>訂單成立時間</td><td>訂單編號</td><td>訂單日期</td><td>南部貨運商</td><td>車號</td><td>貨主</td><td>品名</td><td>數量</td><td>代收金</td><td>運金</td><td>行口</td><td>市場</td><td>司機</td><td>趟次</td></tr>";
	while($row = $result->fetch_array())
	{
		echo "<tr> <td>" . $row['timestamp'] . "</td><td>" . $row['order_id'] . "</td><td>" . $row['date'] . "</td> <td>" . $row['trucking']."</td><td>" . $row['carlicense'] . "</td><td>".$row['shipper'] . "</td><td>".$row['product'] . "</td><td>".$row['quantity'] . "</td><td>".$row['trucking_money'] . '</td><td> <input  type="text" name='.$row['order_id'] . " placeholder=".$row['quantity']*$row['trucking_money'] ."></td><td>".$row['consignee'] . "</td><td>".$row['station'] . "</td><td>".$row['driver'] . "</td><td>".$row['driver_trip'] . "</td></tr>\n";
	};
	echo "</table></br>\n";

	echo "<input type=\"hidden\" name=\"token\" value=\"xAD5l9weDCqKkYgZNd1ICxn4\">\n";
	echo "<input type=\"submit\" value=\"決定運金\">\n";
	echo "<input type=\"reset\" value=\"清除表單\">\n";
	echo '<input type ="button" onclick="javascript:location.href=\'index.html\'" value="回首頁"></input></br>';
	echo '</form>';

	$result=getorderlist_withconsignee_date_money($consignee_id,$date);

	if($result->num_rows === 0)#判斷result是否為空。
	{
		echo '尚未有已決定金額的訂單';
	}else{
		echo '<form action="edit_money_receive.php" method="post">';
		echo "<h1>已決定金額的訂單</h1></br>\n";
		echo "<table border=\"1\">\n";
		echo "<tr><td>訂單成立時間</td><td>訂單編號</td><td>訂單日期</td><td>南部貨運商</td><td>車號</td><td>貨主</td><td>品名</td><td>數量</td><td>代收金</td><td>運金</td><td>行口</td><td>市場</td><td>司機</td><td>趟次</td></tr>";
		while($row = $result->fetch_array())
		{
			echo "<tr> <td>" . $row['timestamp'] . "</td><td>" . $row['order_id'] . "</td><td>" . $row['date'] . "</td> <td>" . $row['trucking']."</td><td>" . $row['carlicense'] . "</td><td>".$row['shipper'] . "</td><td>".$row['product'] . "</td><td>".$row['quantity'] . "</td><td>".$row['trucking_money'] . '</td><td> <input  type="text" name='.$row['order_id'] . " placeholder=".$row['consignee_money'] .'></td><td>'.$row['consignee'] . "</td><td>".$row['station'] . "</td><td>".$row['driver'] . "</td><td>".$row['driver_trip'] . "</td></tr>\n";
		};
		echo "</table></br>\n";
		echo "<input type=\"hidden\" name=\"token\" value=\"xAD5l9weDCqKkYgZNd1ICxn4\">\n";
		echo "<input type=\"submit\" value=\"修改運金\">\n";
		echo "<input type=\"reset\" value=\"清除表單\">\n";
		echo '<input type ="button" onclick="javascript:location.href=\'index.html\'" value="回首頁"></input></br>';
		echo '</form>';
	
		$sum_money=getsum_money($consignee_id,$date);
		while($row = $sum_money->fetch_array())
		{
			echo "代收金總額=".$row['sum_trucking_money']."</br>";
			echo "行口運金總額=".$row['sum_consignee_money']."</br>";
			$total=intval($row['sum_trucking_money']) + intval($row['sum_consignee_money']);
			echo "總計=".$total ."</br>";
		} 

	}


}elseif(isset($_GET['consignee_id'])){



}elseif(isset($_GET['date'])){


}else{

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



	echo '<form action="edit_money_form.php" method="get">';
		$today = date('Y-m-d' );
		echo "日期 date";
		echo "<input type=\"date\" name=\"date\" value=\"".$today."\"></br>";
		echo "行口名稱<select name=\"consignee_id\">";
		$consignee_list=getlist("consignee_list");
		while($row = $consignee_list->fetch_array())
		{
			echo "\t\t<option value=\"".$row['consignee_id']."\">".$row['consignee']."</option>\n";
		}
		echo "<select></br>";

		echo '<input type="submit" value="送出表單">';
		echo '<input type="reset" value="清除表單">';
		echo '<input type ="button" onclick="javascript:location.href=\'index.html\'" value="回首頁"></input>';
	echo '</form>';
}


?>
</body >
</html>
