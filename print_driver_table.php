<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="theme.css">

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
	$sql = 'SELECT * FROM fruit_database.order_list
	left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
	left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
	left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
	where order_list.driver_id=\''.$driver_id.'\'
	and order_list.date=\''.$date.'\'
	order by order_list.driver_trip, order_list.consignee_id
	;';



	if ($result=mysqli_query($con, $sql)) {
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	mysqli_close($con);






#html開始
	echo "<div class=print_table>";
	echo "<h1>司機送貨明細</h1>\n";
	echo "<h2><p id=data></p></h2>\n";
	echo "<table border=\"1\">\n";
	echo "<tr><td>項目</td><td>訂單編號</td><td>南部貨運行</td><td>車號</td><td>貨主</td><td>品名</td><td>數量</td><td>代收金</td><td>行口</td><td>市場</td><td>趟次</td></tr>";
	$i=0;
	while($row = $result->fetch_array())
	{
		$i=$i+1;
		echo "<tr> <td>" . $i . "</td><td>" . $row['order_id'] . "</td> <td>" . $row['trucking']."</td><td>" . $row['carlicense'] . "</td><td>".$row['shipper'] . "</td><td>".$row['product'] . "</td><td>".$row['quantity'] . "</td><td>".$row['trucking_money'] . "</td><td>".$row['consignee'] . "</td><td>".$row['station'] . "</td><td>".$row['driver_trip'] . "</td></tr>\n";
		$driver=$row['driver'];
	};
	echo "</table></br>\n";
	$data="日期: ".$date." 司機: ".$driver;
	echo "<script>";
	echo "document.getElementById(\"data\").innerHTML = '".$data."';";
	echo "</script>";

	#include("barcode39.php"); 
	#$codename="d".$date."1";
	#$bc = new Barcode39($codename); 
	#$bc->draw("barcode".$codename."gif");
	$bardate=substr($date,2,2).substr($date,5,2).substr($date,8,2);
	$bardriver_id=substr($driver_id,2,2);
	$barcode="o".$bardate.$bardriver_id;
	echo "<div class=barcode>";
	echo "	<IMG  SRC=\"barcode.php?barcode=".$barcode."&width=320&height=50\">";
	echo "</div>";
	echo "</div>";
}
?>

</body >
</html>
