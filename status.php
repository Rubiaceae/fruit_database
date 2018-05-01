<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<title>狀態列表</title>
</head>
<body >

<table border=1>
<?php
#今天訂單數量
#尚未分派司機的訂單數量/已分派司機的訂單數量o
#尚未分派司機薪水的訂單數量/已分派司機的訂單數量dm
#已編輯運金的訂單數量/未編輯運金的訂單數量cm

#尚未送出的貨物/已確認送出的訂單數量ob
#尚未付款的代收金/已確認付款的訂單數量tmb
#尚未收款的運金/已確認收款的訂單數量cmb
#尚未付司機薪水/已確認收款的訂單數量dmb
$num=0;
$o=0;
$dm=0;
$cm=0;
$ob=0;
$tmb=0;
$cmb=0;
$dmb=0;

function minus($num,$x){
return intval($num)-intval($x);
}

$date=$_GET['date'];
$enddate=$_GET['enddate'];
if(strtotime($enddate) - strtotime($date) < 0){
	echo "錯誤！結束日期早於開始日期！";	
	exit;	
}
	function getorderlist($date,$enddate){#查詢所有表join，
		include("mysql_connect.inc.php");

		$sql = 'SELECT * FROM fruit_database.order_list
		left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
		left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
		left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
		where order_list.date between \''.$date.'\' and \''.$enddate.'\' 
		order by order_list.order_id
		;';
		if ($result=mysqli_query($con, $sql)) {
			#echo "Get List successful!";
		} else {
			echo "Error Getting List " . mysqli_error($con);
		}
		mysqli_close($con);
		return $result;
	};

	$order_list=getorderlist($date,$enddate);
	while($row = $order_list->fetch_array())
	{
	$num=$num+1;#訂單數量echo $row['order_id'];
	if(empty($row['driver_id'])){ $o=$o+1;}#尚未分派司機
	if(empty($row['consignee_money'])){$cm=$cm+1;}#尚未分派運金
	if(empty($row['driver_money'])){$dm=$dm+1;}#尚未分派司機薪水

	if($row['order_settle']==0){$ob=$ob+1;}#尚未送出的貨物
	if($row['trucking_money_settle']==0){$tmb=$tmb+1;}#尚未付款的代收金
	if($row['consignee_money_settle']==0){$cmb=$cmb+1;}#尚未收款的運金
	if($row['driver_money_settle']==0){$dmb=$dmb+1;}#尚未付司機薪水
	

	}
	echo "開始日期: ".$date." 結束日期: ".$enddate."</br>";
	echo "總訂單: ".$num;
	echo "<tr><td> </td><td>分派司機</td><td>編輯運金</td><td>編輯司機薪水</td><td>送出貨物</td><td>代收金付款</td><td>運金收款</td><td>發司機薪水</td></tr></br>\n";
echo "<tr><td>尚未</td><td>".$o."</td><td>".$cm."</td><td>".$dm."</td><td>".$ob."</td><td>".$tmb."</td><td>".$cmb."</td><td>".$dmb."</td></tr></br>\n";
echo "<tr><td>已完成</td><td>".minus($num,$o)."</td><td>".minus($num,$cm)."</td><td>".minus($num,$dm)."</td><td>".minus($num,$ob)."</td><td>".minus($num,$tmb)."</td><td>".minus($num,$cmb)."</td><td>".minus($num,$dmb)."</td></tr></br>\n";

?>

</table>
</body>
</html>
