<!--#新增行口編號、司機編號等功能所需要的表單，會有新增的選項，並列出已有的

-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
</head>
<body >
<input type ="button" onclick="javascript:location.href='index.html'" value="回首頁"></input>
<h1>新增南部</h1>
<form action="add_new_receive.php" method="post">
	<table border=1>
		<tr><td>南部貨運名稱</td>
		
		<td><input type="text" name="trucking_new" maxlength="10" size="10" required></td>
		</tr>
	</table>
	<input type="hidden" name="token" value="xAD5l9weDCqKkYgZNd1ICxn4"/>
	<input type="submit" value="送出表單">
	<input type="reset" value="清除表單">
</form>


<h1>新增貨主</h1>
<form action="add_new_receive.php" method="post">
	<table border=1>
		<tr><td>貨主名稱</td>
		
		<td><input type="text" name="shipper_new" maxlength="10" size="10" required></td>
		</tr>
	</table>
	<input type="hidden" name="token" value="xAD5l9weDCqKkYgZNd1ICxn4"/>
	<input type="submit" value="送出表單">
	<input type="reset" value="清除表單">
</form>

<h1>新增行口</h1>
<form action="add_new_receive.php" method="post">
	<table border=1>
		<tr><td>行口名稱</td>
		
		<td><input type="text" name="consignee_new" maxlength="10" size="10" required></td>
		</tr>
		<tr><td>行口市場位置</td>
		
		<td><input type="text" name="station_new" maxlength="10" size="10" required></td>
		</tr>
	</table>
	<input type="hidden" name="token" value="xAD5l9weDCqKkYgZNd1ICxn4"/>
	<input type="submit" value="送出表單">
	<input type="reset" value="清除表單">
</form>

<h1>新增司機</h1>
<form action="add_new_receive.php" method="post">
	<table border=1>
		<tr><td>司機名稱</td>
		
		<td><input type="text" name="driver_new" maxlength="10" size="10" required></td>
		</tr>

	</table>
	<input type="hidden" name="token" value="xAD5l9weDCqKkYgZNd1ICxn4"/>
	<input type="submit" value="送出表單">
	<input type="reset" value="清除表單">
</form>


<?php
#列出已有的所有名單
#定義函數，取得名單資料表
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

$trucking_list=getlist("trucking_list");
$shipper_list=getlist("shipper_list");
$consignee_list=getlist("consignee_list");
$driver_list=getlist("driver_list");
echo "南部貨運編號 </br>\n";
echo "<table border=\"1\">\n";
while($row = $trucking_list->fetch_array())
	{
	echo "<tr> <td>" . $row['trucking_id'] . "</td> <td>" . $row['trucking']."</td> </tr>\n";
	};
echo "</table></br>\n";

echo "貨主編號 </br>\n";
echo "<table border=\"1\">\n";
while($row = $shipper_list->fetch_array())
	{
	echo "<tr> <td>" . $row['shipper_id'] . "</td> <td>" . $row['shipper']."</td> </tr>\n";
	};
echo "</table></br>\n";

echo "行口編號 </br>\n";
echo "<table border=\"1\">\n";
while($row = $consignee_list->fetch_array())
	{
	echo "<tr> <td>" . $row['consignee_id'] . "</td> <td>" . $row['consignee']."</td><td>".$row['station']."</td> </tr>\n";
	};
echo "</table></br>\n";

echo "司機編號 </br>\n";
echo "<table border=\"1\">\n";
while($row = $driver_list->fetch_array())
	{
	echo "<tr> <td>" . $row['driver_id'] . "</td> <td>" . $row['driver']."</td> </tr>\n";
	};
echo "</table></br>\n";

?>


</body>
</html>

