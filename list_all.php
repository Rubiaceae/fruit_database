<!--#所有訂單列表，每頁可以查20行

-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<title>所有訂單列表</title>
</head>
<body >



<table border=1>
<?php
	function getorderlist($page){#查詢所有表join，
		include("mysql_connect.inc.php");
		$page-20;
		$sql = 'SELECT * FROM fruit_database.order_list
		left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
		left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
		left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
		order by order_list.order_id desc
		limit '. strval($page*20) .' ,20 
		;';
		if ($result=mysqli_query($con, $sql)) {
			#echo "Get List successful!";
		} else {
			echo "查詢List資料失敗 " . mysqli_error($con);
		}
		mysqli_close($con);
		return $result;
	};

$page=intval($_GET['page']);
$i=0;
#echo $page;

if(empty($page)){
	$page=0;

}elseif($page < 0){
	$page = 0;

}else{

}

$result=getorderlist($page);
$url1="list_all.php?page=".  strval($page-1) ."";
$url2="list_all.php?page=". strval($page+1) ."";
echo "<input type =\"button\" onclick=\"javascript:location.href='".$url1."'\" value=\"上一頁\"></input>";
echo "<input type =\"button\" onclick=\"javascript:location.href='".$url2."'\" value=\"下一頁\"></input>";
echo "<input type =\"button\" onclick=\"javascript:location.href='index.html'\" value=\"回首頁\"></input></br>";

echo "<tr><td>項次</td><td>訂單成立時間</td><td>訂單編號</td><td>訂單日期</td><td>南部貨運商</td><td>車號</td><td>貨主</td><td>品名</td><td>數量</td><td>代收金</td><td>運金</td><td>行口</td><td>市場</td><td>司機</td><td>趟次</td><td>確認出貨</td><td>確認付代收金</td><td>確認收運金</td><td>確認付司機薪水</td></tr>";
	while($row = $result->fetch_array())
	{	$i=$i+1;
		echo  "<tr><td>".$i."</td><td>".$row['timestamp']."</td><td>".$row['order_id']."</td><td>".$row['date']."</td><td>".$row['trucking']."</td><td>".$row['carlicense']."</td><td>".$row['shipper']."</td><td>".$row['product']."</td><td>".$row['quantity']."</td><td>".$row['trucking_money']."</td><td>".$row['consignee_money']."</td>\n<td>".$row['consignee']."</td><td>".$row['station']."</td><td>".$row['driver']."</td><td>".$row['driver_trip']."</td><td>".$row['order_settle']."</td><td>".$row['trucking_money_settle']."</td><td>".$row['consignee_money_settle']."</td><td>".$row['driver_money_settle']."</td></tr>\n";
	}

?>



</table>
</body>
</html>

