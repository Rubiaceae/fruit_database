<!--#條碼單號輸入

-->

<?php
session_start();
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
			and order_list.consignee_money_settle is null
			group by order_list.consignee_id
			;';
		break;
	}
	return $sql;
}

?>
<!--
html開始
-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="theme.css">
	<title>條碼單號輸入</title>
	<script src="jquery.min.js"></script> 
	<script>
        $(document).ready(function(){
            $(".inputs").keyup(function () {
                $this=$(this);
                if ($this.val().length >=$this.data("maxlength")) {
                  if($this.val().length>$this.data("maxlength")){
                    $this.val($this.val().substring(0,4));
                  }
                  $this.next(".inputs").focus();
                }
             });
        });
	</script>

</head>
<body >
	<script src="/addInput.js" language="Javascript" type="text/javascript"></script>
	<script>
	
	function autotab(current,to){
		if (current.getAttribute && 
			current.value.length==current.getAttribute("maxlength")) {
      			  document.getElementById(to).focus() 
		}
	}
	</script>

	<script>
	window.onload = function () {	
	document.barcodeform.barcode0.focus()
	}
	</script>

<?php

if(!isset($_SESSION['name']) || empty($_SESSION['name'])){
	echo "尚未登入!";
	echo "<script>setTimeout(function(){location.href='login_form.php';},2000);</script><!--五秒後自動回首頁-->";
}elseif($_POST['token'] == "xAD5l9weDCqKkYgZNd1ICxn4"){
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
		echo "<tr><th>條碼</th><th>開始日期</th><th>結束日期</th><th>行口</th><th>市場</th><th>代收金總額</th><th>運金總額</th><th>總額</th></tr>";
		$i=0;
		$sum_trucking_money=0;
		$sum_consignee_money=0;
		$sum_money=0;
		$update_barcode=[];
		foreach($cbarcode as $barcode){
#			echo $barcode;
			$date='20'.substr($barcode,1,2).'-'.substr($barcode,3,2).'-'.substr($barcode,5,2);
			$days=substr($barcode,7,2);
			$enddate=addDayswithdate($date,$days);

			$sql=barcode_to_sql($barcode);
#			echo $sql.'<br>';
			if ($result=mysqli_query($con, $sql)) {
#				echo " info updated successfully!</br>";
				#echo num_rows($result);
							
			} else {
				echo "Error sql " . mysqli_error($con);
			}

			if($result ->num_rows === 0){
				echo "<tr> <td class='warring'>" .$barcode."</td> <td  class='warring' colspan=\"7\">查無此單號,或是已經輸入過。</td></tr>";
							
			}else{
			
				while ($row = $result->fetch_array()) {
		  			echo "<tr> <td>" . $barcode . "</td> <td>" . $date . "</td><td>" . $enddate . "</td><td>" . $row['consignee']."</td> <td>" . $row['station']."</td>  <td>" . $row['sum(trucking_money)']."</td><td>" . $row['sum(consignee_money)']."</td><td>" . strval(floatval($row['sum(consignee_money)'])+floatval($row['sum(trucking_money)']))."</td></tr>\n";
				$sum_trucking_money=$sum_trucking_money+floatval($row['sum(trucking_money)']);
				$sum_consignee_money=$sum_consignee_money+floatval($row['sum(consignee_money)']);
				$sum_money=$sum_money+floatval($row['sum(trucking_money)'])+floatval($row['sum(consignee_money)']);
				};
				
				array_push($update_barcode,$barcode);
			}

		$i++;
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td>".$sum_trucking_money."</td><td>".$sum_consignee_money."</td><td>".$sum_money."</td></tr>";
		mysqli_close($con);
		#print_r($update_barcode);


		break;
	case 'D':
		echo '司機薪資條碼<br>';
		break;
	}
	echo "</table></br>";

		
	#sent to receive to update;
	echo '<form name=updatebarcodeform action="settle_receive.php" method="post">';	
	#print_r($update_barcode);
	foreach($update_barcode as $ubarcode) {
		echo "<input type=\"hidden\" name=\"ubarcode[]\" value=\"".$ubarcode."\">\n";
	}
	echo "<input type=\"hidden\" name=\"token\" value=\"xAD5l9weDCqKkYgZNd1ICxn4\">\n";
	echo '<input type ="button" onclick="window.location = document.referrer;" value="回到上一頁"></input>';
	echo "<input type=\"submit\" value=\"確認金額正確\">\n";
	echo '</form>';

}else{

	echo '<form name=barcodeform action="settle_form.php" method="post">';
	echo "<h1>輸入條碼單號</h1></br>\n";
	echo '<input type="button" value="增加欄位"      onClick="addInput(\'dynamicInput\');"></br></br>';
	echo "<input type=\"hidden\" name=\"token\" value=\"xAD5l9weDCqKkYgZNd1ICxn4\">\n";
	echo '<div id="dynamicInput">';

	$i=0;
	for($i=0 ; $i<10 ; $i++ ){
	echo strval($i+1).'. <input type="text" id= barcode'.$i.' name="barcode[]" size=13 onKeyup="autotab(this, \'barcode'.strval($i+1).'\')" maxlength=13><br><br>';
	}
	echo '</div>';
	
	echo "<input type=\"submit\" value=\"送出條碼\">\n";
	echo "<input type=\"reset\" value=\"清除表單\">\n";
	echo '<input type ="button" onclick="javascript:location.href=\'index.html\'" value="回首頁"></input>';
	echo '</form>';
	
}


?>
</body >
</html>
