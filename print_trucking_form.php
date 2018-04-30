<!--#結清代收金的表單，如果有填車號，就分開結清，如果沒有填車號，就一起結清。

-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="theme.css">
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
		echo "開始日期 date";
		echo "<input type=\"date\" name=\"date\" value=\"".$today."\"></br>";
		echo "結束日期 date";
		echo "<input type=\"date\" name=\"enddate\" value=\"".$today."\"></br>";
				
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
	$enddate=$_GET['enddate'];
	$carlicense=$_GET['carlicense'];
	if(strtotime($enddate) - strtotime($date) < 0){
		echo "錯誤！結束日期早於開始日期！";	
		exit;	
	}
	include("mysql_connect.inc.php");
	if(empty($carlicense)){#判斷有沒有填車牌，沒有車號就全列，照order_id排
	$sql = 'SELECT * FROM fruit_database.order_list
		left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
		left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
		left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
		where order_list.trucking_id=\''.$trucking_id.'\'
		and order_list.date between \''.$date.'\' and \''.$enddate.'\' 
		order by order_list.order_id
		;';
	}else{#有車號就列，照order_id排
	$sql = 'SELECT * FROM fruit_database.order_list
		left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
		left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
		left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
		where order_list.trucking_id=\''.$trucking_id.'\'
		and order_list.date between \''.$date.'\' and \''.$enddate.'\' 
		and order_list.carlicense=\''.$carlicense.'\'
		order by or order_list.order_id
		;';
	}



	if ($result=mysqli_query($con, $sql)) {
	} else {
		echo "Error Getting List " . mysqli_error($con);
	}
	mysqli_close($con);

#	$sql2="select trucking from fruit_database.trucking_list where trucking_id =".$trucking_id." limit 1;";
#	
#	if ($result2=mysqli_query($con, $sql2)) {
#	} else {
#		echo "Error Getting List " . mysqli_error($con);
#	}
#	mysqli_close($con);	
#	while($row = $result2->fetch_array()){
#	$trucking=$row['trucking'];}

	$sum_trucking_money=0;




#html開始
	echo "<div class=\"print_table\">";
	echo "<h1>代收金明細表</h1>";
	echo "<h2><p id=data></p></h2>\n";
	echo "<h2><p id=data2></p></h2>\n";
	echo "<table id=table border=\"1\">\n";
	echo "<tr><td>項目</td><td>訂單編號</td><td>訂單日期</td><td width='60'>車號</td><td>貨主</td><td width='60'>品名</td><td>數量</td><td>代收金</td><td>行口</td><td>市場</td></tr>";
	$i=0;
	$c=0;
	while($row = $result->fetch_array())
	{
		$i=$i+1;
		echo "<tr> <td>" . $i . "</td><td>" . $row['order_id'] . "</td><td>" . $row['date'] . "</td><td>" . $row['carlicense'] . "</td><td>".$row['shipper'] . "</td><td>".$row['product'] . "</td><td>".$row['quantity'] . "</td><td>".$row['trucking_money'] . "</td><td>".$row['consignee'] . "</td><td>".$row['station'] . "</td></tr>\n";
	$trucking=$row['trucking'];
	if($row['carlicense']!==$carlicense){$c=$c+1;}
	$carlicense=$row['carlicense'];
		
	$sum_trucking_money=$sum_trucking_money+$row['trucking_money'];
	};
	echo "</table></br>\n";
	$data="開始日期: ".$date." 結束日期: ".$enddate;
	$data2="貨運行: ".$trucking." \t車號: ".$carlicense;
	echo "<script>";
	echo "document.getElementById(\"data\").innerHTML = '".$data."';";
	echo "document.getElementById(\"data2\").innerHTML = '".$data2."';";
	echo "</script>"; 
	if($c >= 2){echo "<h2>注意！車號並不統一！</h2></br>";}
	echo "<h2> 代收金總額=".$sum_trucking_money."</h2></br>";
	$bardate=substr($date,2,2).substr($date,5,2).substr($date,8,2);
	$bardays=str_pad(((strtotime($enddate) - strtotime($date))/86400),2,'0',STR_PAD_LEFT);;
	$bartrucking_id=substr($trucking_id,0,4);
	$barcode="t".$bardate.$bardays.$bartrucking_id;
	echo "<div class=barcode>";
	echo "	<IMG  SRC=\"barcode.php?barcode=".$barcode."&width=320&height=50\">";
	echo "</div>";
	echo "</div>";
}
?>

</body >
</html>
