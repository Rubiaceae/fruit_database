<!--#確認barcode並上傳。

-->
<?php
session_start();
if(!isset($_SESSION['name']) || empty($_SESSION['name'])){
	echo "尚未登入!";
	echo "<script>setTimeout(function(){location.href='index.html';},1000);</script><!--五秒後自動回上一頁-->";
	exit;
}

function remove_empty($array) {
  return array_values(array_unique(array_filter($array, 'strlen')));
}

function addDayswithdate($date,$days){

    $date = strtotime("+".$days." days", strtotime($date));
    return  date("Y-m-d", $date);

}

function barcode_to_sql($barcode){
	$date='20'.substr($barcode,1,2).'-'.substr($barcode,3,2).'-'.substr($barcode,5,2);
	$days=substr($barcode,7,2);
	$enddate=addDayswithdate($date,$days);
	$id=substr($barcode,9,4);
	switch(strtoupper(substr($barcode,0,1))){
	case 'O':
		$sql=	'SELECT * FROM fruit_database.order_list
			left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
			left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
			left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
			where order_list.driver_id=\''.$id.'\'
			and order_list.date between \''.$date.'\' and \''.$enddate.'\'
			order by order_list.order_id
			;';
		break;

	case 'C':
		$sql=	'SELECT sum(trucking_money),sum(consignee_money), consignee_list.consignee,consignee_list.station  FROM fruit_database.order_list 
			left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
			left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
			left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
			where order_list.consignee_id=\''.$id.'\'
			and order_list.date between \''.$date.'\' and \''.$enddate.'\'
			group by order_list.consignee_id
			;';
		break;
	}
	return $sql;
}
?>


<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="theme.css">
</head>



<table>
<?php 
#判定新增的表格是哪一種，建立$sql語法

if( $_POST['token'] == "xAD5l9weDCqKkYgZNd1ICxn4"){
	$cbarcode=remove_empty($_POST['barcode']);
	#print_r($cbarcode);
	$type=strtoupper(substr($cbarcode[0],0,1));
	#條碼數量	
	$barlen=count($cbarcode);
	#echo $type;
	#所有單號同一類別
	foreach($cbarcode as $barcode){
		$newtype=strtoupper(substr($barcode,0,1));
		if($type != $newtype){
			echo "有不同型態的單號";
			exit;		
		}
		if(strlen($barcode)!=13){
			echo "錯誤的條碼長度";
			exit;	
		}
	
	}
	#單號類型符合
	if($type != 'O' && $type != 'T' && $type != 'C' && $type != 'D'){
		echo '錯誤的條碼型態';
		exit;
	}

	#顯示單號類型
	switch($type){
	case 'O':
		echo '司機送貨條碼<br>';
		break;
	case 'T':
		echo '南部代收金條碼<br>';
		break;
	case 'C':
		echo '行口運金條碼<br>';
		break;
	case 'D':
		echo '司機薪資條碼<br>';
		break;
	}
	
	echo "條碼數量 " .$barlen. " 個</br>";
	#讀取mysql	
	include("mysql_connect.inc.php");
	
	
#	if (mysqli_query($con, $sql)) {
#		echo $station." info updated successfully!</br>";
#	} else {
#		echo "Error updating record: " . mysqli_error($con);
#	}
#	mysqli_close($con);

	echo "<table id=\"settle_table\" border=1>";
	switch($type){
	case 'O':
		echo '司機送貨條碼<br>';
		break;
	case 'T':
		echo '南部代收金條碼<br>';
		break;
	case 'C':
#		echo '行口運金條碼<br>';
		echo "<tr><td>條碼</td><td>開始日期</td><td>結束日期</td><td>行口</td><td>市場</td><td>代收金總額</td><td>運金總額</td><td>總額</td></tr>";
		$i=0;
		$sum_trucking_money=0;
		$sum_consignee_money=0;
		$sum_money=0;
		foreach($cbarcode as $barcode){
#			echo $barcode;
			$date='20'.substr($barcode,1,2).'-'.substr($barcode,3,2).'-'.substr($barcode,5,2);
			$days=substr($barcode,7,2);
			$enddate=addDayswithdate($date,$days);

			$sql=barcode_to_sql($barcode);
#			echo $sql.'<br>';
			if ($result=mysqli_query($con, $sql)) {
#				echo " info updated successfully!</br>";
				echo num_rows($result);
				if(num_rows($result)=== 0){
					echo $barecode."查無此單號,或是已經輸入過。";
				exit;
			}
			} else {
				echo "Error sql " . mysqli_error($con);
			}

			
			while ($row = $result->fetch_array()) {
	  			echo "<tr> <td>" . $barcode . "</td> <td>" . $date . "</td><td>" . $enddate . "</td><td>" . $row['consignee']."</td> <td>" . $row['station']."</td>  <td>" . $row['sum(trucking_money)']."</td><td>" . $row['sum(consignee_money)']."</td><td>" . strval(floatval($row['sum(consignee_money)'])+floatval($row['sum(trucking_money)']))."</td></tr>\n";
			$sum_trucking_money=$sum_trucking_money+floatval($row['sum(trucking_money)']);
			$sum_consignee_money=$sum_consignee_money+floatval($row['sum(consignee_money)']);
			$sum_money=$sum_money+floatval($row['sum(trucking_money)'])+floatval($row['sum(consignee_money)']);
			};

		$i++;
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td>".$sum_trucking_money."</td><td>".$sum_consignee_money."</td><td>".$sum_money."</td></tr>";
		mysqli_close($con);
		break;
	case 'D':
		echo '司機薪資條碼<br>';
		break;
	}
	echo "</table>";

}else{
	echo "ERROR! Wrong token!";
}


?>
</table>

<input type ="button" onclick="window.location = document.referrer;" value="回到上一頁"></input>
</html>
