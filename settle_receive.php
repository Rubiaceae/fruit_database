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
		$sql=	'update fruit_database.order_list set consignee_money_settle=now() 
			where order_list.consignee_id=\''.$id.'\' 
			and order_list.date between \''.$date.'\' and \''.$enddate.'\'
			and order_list.consignee_money_settle is null;';
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
	$ubarcode=remove_empty($_POST['ubarcode']);
	#print_r($ubarcode);
	$type=strtoupper(substr($ubarcode[0],0,1));
	#條碼數量	
	$barlen=count($ubarcode);
	#echo $type;
	#所有單號同一類別
	foreach($ubarcode as $barcode){
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
	#讀取mysql	
	include("mysql_connect.inc.php");
	
	#撰寫sql
	switch($type){
	case 'O':
		echo '司機送貨條碼<br>';
		break;
	case 'T':
		echo '南部代收金條碼<br>';
		break;
	case 'C':
		#echo '行口運金條碼<br>';
		foreach($ubarcode as $barcode){
			$sql=barcode_to_sql($barcode);
			if (mysqli_query($con, $sql)) {
				echo $barcode." updated successfully!</br>";
			} else {
				echo "Error updating record: " . mysqli_error($con);
			}
		}

		break;
	case 'D':
		echo '司機薪資條碼<br>';
		break;
	}
	mysqli_close($con);

	



}else{
	echo "ERROR! Wrong token!";
}


?>
</table>

<input type ="button" onclick="window.location = document.referrer;" value="回到上一頁"></input>
</html>
