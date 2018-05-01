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

<?php
#選單區
$modificator = '-1 day';
$yesterday = date('Y-m-d', strtotime($date . $modificator));
#echo "$yesterday";
$url1="/status.php?date=".$yesterday."&enddate=".$yesterday;

$modificator = '+1 day';
$tomorrow = date('Y-m-d', strtotime($date . $modificator));
#echo "$tomorrow";
$url2="/status.php?date=".$tomorrow."&enddate=".$tomorrow;
echo "<input type =\"button\" onclick=\"javascript:location.href='".$url1."'\" value=\"前一天\"></input>";
echo "<input type =\"button\" onclick=\"javascript:location.href='".$url2."'\" value=\"後一天\"></input>";
echo "<input type =\"button\" onclick=\"javascript:location.href='index.html'\" value=\"回首頁\"></input></br>";
?>

<?php

function tablelist($result){
	$i=0;

	echo "<table border=1>";
	echo "<tr><td>項次</td><td>訂單成立時間</td><td>訂單編號</td><td>訂單日期</td><td>南部貨運商</td><td>車號</td><td>貨主</td><td>品名</td><td>數量</td><td>代收金</td><td>運金</td><td>行口</td><td>市場</td><td>司機</td><td>趟次</td><td>確認出貨</td><td>確認付代收金</td><td>確認收運金</td><td>確認付司機薪水</td></tr>";
	while($row = $result->fetch_array())
	{	$i=$i+1;
		echo  "<tr><td>".$i."</td><td>".$row['timestamp']."</td><td>".$row['order_id']."</td><td>".$row['date']."</td><td>".$row['trucking']."</td><td>".$row['carlicense']."</td><td>".$row['shipper']."</td><td>".$row['product']."</td><td>".$row['quantity']."</td><td>".$row['trucking_money']."</td><td>".$row['consignee_money']."</td>\n<td>".$row['consignee']."</td><td>".$row['station']."</td><td>".$row['driver']."</td><td>".$row['driver_trip']."</td><td>".$row['order_settle']."</td><td>".$row['trucking_money_settle']."</td><td>".$row['consignee_money_settle']."</td><td>".$row['driver_money_settle']."</td></tr>\n";
	}

	echo "</table>";}
#詳細列表
#1未分派司機
include("mysql_connect.inc.php");
$nodriversql='SELECT * FROM fruit_database.order_list
left join fruit_database.trucking_list on trucking_list.trucking_id=order_list.trucking_id
left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
where order_list.date between \''.$date.'\' and \''.$enddate.'\' 
and order_list.driver_id is null
order by order_list.order_id;';
#echo $nodriversql;

if ($result=mysqli_query($con, $nodriversql)) {

} else {
	echo "Error Getting List " . mysqli_error($con);
}

mysqli_close($con);

	echo "<h2>1.尚未分派司機的訂單</h2>";
	tablelist($result);

#2未編輯運金
include("mysql_connect.inc.php");
$noconsignee_moneysql='SELECT * FROM fruit_database.order_list
left join fruit_database.trucking_list on trucking_list.trucking_id=order_list.trucking_id
left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
where order_list.date between \''.$date.'\' and \''.$enddate.'\' 
and order_list.consignee_money is null
order by order_list.order_id;';
#echo $noconsignee_moneysql;

if ($result=mysqli_query($con, $noconsignee_moneysql)) {

} else {
	echo "Error Getting List " . mysqli_error($con);
}

mysqli_close($con);

	echo "<h2>2.尚未編輯運金的訂單</h2>";
	tablelist($result);


#3未編輯司機薪水
include("mysql_connect.inc.php");
$nodriver_moneysql='SELECT * FROM fruit_database.order_list
left join fruit_database.trucking_list on trucking_list.trucking_id=order_list.trucking_id
left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
where order_list.date between \''.$date.'\' and \''.$enddate.'\' 
and order_list.driver_money is null
order by order_list.order_id;';
#echo $nodriver_moneysql;

if ($result=mysqli_query($con, $nodriver_moneysql)) {

} else {
	echo "Error Getting List " . mysqli_error($con);
}

mysqli_close($con);
	echo "<h2>3.尚未編輯司機薪水</h2>";
	tablelist($result);

#4未確認出貨
include("mysql_connect.inc.php");
$noorder_settlesql='SELECT * FROM fruit_database.order_list
left join fruit_database.trucking_list on trucking_list.trucking_id=order_list.trucking_id
left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
where order_list.date between \''.$date.'\' and \''.$enddate.'\' 
and order_list.order_settle = \'0\'
order by order_list.order_id;';
#echo $noorder_settlesql;

if ($result=mysqli_query($con, $noorder_settlesql)) {

} else {
	echo "Error Getting List " . mysqli_error($con);
}

mysqli_close($con);
	echo "<h2>4.尚未確認出貨</h2>";
	tablelist($result);
#5未付代收金
include("mysql_connect.inc.php");
$notrucking_money_settlesql='SELECT * FROM fruit_database.order_list
left join fruit_database.trucking_list on trucking_list.trucking_id=order_list.trucking_id
left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
where order_list.date between \''.$date.'\' and \''.$enddate.'\' 
and order_list.trucking_money_settle = \'0\'
order by order_list.order_id;';
#echo $notrucking_money_settlesql;

if ($result=mysqli_query($con, $notrucking_money_settlesql)) {

} else {
	echo "Error Getting List " . mysqli_error($con);
}

mysqli_close($con);
	echo "<h2>5.尚未付代收金</h2>";
	tablelist($result);

#6未收運金
include("mysql_connect.inc.php");
$noconsignee_money_settlesql='SELECT * FROM fruit_database.order_list
left join fruit_database.trucking_list on trucking_list.trucking_id=order_list.trucking_id
left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
where order_list.date between \''.$date.'\' and \''.$enddate.'\' 
and order_list.consignee_money_settle = \'0\'
order by order_list.order_id;';
#echo $noconsignee_money_settlesql;

if ($result=mysqli_query($con, $noconsignee_money_settlesql)) {

} else {
	echo "Error Getting List " . mysqli_error($con);
}

mysqli_close($con);
	echo "<h2>6.尚未收運金</h2>";
	tablelist($result);

#7未付司機薪水
include("mysql_connect.inc.php");
$nodriver_money_settlesql='SELECT * FROM fruit_database.order_list
left join fruit_database.trucking_list on trucking_list.trucking_id=order_list.trucking_id
left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
where order_list.date between \''.$date.'\' and \''.$enddate.'\' 
and order_list.consignee_money_settle = \'0\'
order by order_list.order_id;';
#echo $nodriver_money_settlesql;

if ($result=mysqli_query($con, $nodriver_money_settlesql)) {

} else {
	echo "Error Getting List " . mysqli_error($con);
}

mysqli_close($con);
	echo "<h2>7.尚未付司機薪水</h2>";
	tablelist($result);
?>
</body>
</html>
