<!--#行口的出貨結帳表。可輸入兩個日期
-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="theme.css">
	<script src="http://127.0.0.1/jquery.min.js"></script>

        <script>
		$(document).ready(function(){
		<?php
			function getconsignee($station){
				include("mysql_connect.inc.php");
				$sql="select * from consignee_list where station ='".$station."'";
				if ($result=mysqli_query($con, $sql)) {
					#echo "Get List successful!";
				} else {
					echo "Error Getting List " . mysqli_error($con);
				}
				mysqli_close($con);
				return $result;
			};

			$sql="SELECT station FROM fruit_database.consignee_list group by station;";#選出所有地點
			include("mysql_connect.inc.php");
			if ($station_sql=mysqli_query($con, $sql)) {
				} else {
					echo "Error Getting List " . mysqli_error($con);
			}
			$station_array=array();
			$stations="'";
			while($row = $station_sql->fetch_array())
				{
					#echo $row['station'];
					array_push($station_array,$row['station']);
					$stations=$stations."','".$row['station'];
				} 
			#print_r( $station_array);

			$stations=$stations."'";
			$stations=substr($stations,3,strlen($stations)-1);
			#echo $stations;
			#echo $station_array[0];
			$consignees_id=array();
			for ($i=0;$i<count($station_array);$i++){
				$consignee_sql=getconsignee($station_array[$i]);
				#$consignee_array=array();
				#print_r($consignee_sql);
				#$consignees=array();
				$consignees[$i]="'";
				$consignees_id[$i]="'";
				while($row = $consignee_sql->fetch_array())
					{
						#echo $row['station'];
						#array_push($consignee_array,$row['consignee']);
						$consignees[$i]=$consignees[$i]."','".$row['consignee'];
						$consignees_id[$i]=$consignees_id[$i]."','".$row['consignee_id'];
					} 
				$consignees[$i]=$consignees[$i]."'";
				$consignees[$i]=substr($consignees[$i],3,strlen($consignees[$i])-1);
				$consignees_id[$i]=$consignees_id[$i]."'";
				$consignees_id[$i]=substr($consignees_id[$i],3,strlen($consignees_id[$i])-1);
				#echo $consignees[$i];
				#echo $consignees_id[$i];
			}

			echo "stations=[".$stations."];";

		?>
                var inner="";
                for(var i=0;i<stations.length;i++){
                    inner=inner+'<option value=i>'+stations[i]+'</option>';
                }
                $("#stations-list").html(inner)
                
                var consignees=new Array();
                var consignees_id=new Array();
		<?php

			for ($j=0;$j <count($station_array);$j++){
				echo  "consignees[".$j."]=[".$consignees[$j]."];\n";
				echo  "consignees_id[".$j."]=[".$consignees_id[$j]."];\n";
			}
		?>

                $("#stations-list").change(function(){
                    index=this.selectedIndex;
                    var Sinner="";
                    for(var i=0;i<consignees[index].length;i++){
                        Sinner=Sinner+'<option value='+consignees_id[index][i]+'>'+consignees[index][i]+'</option>';
                    }
                    $("#cosignee-list").html(Sinner);
                });
                $("#stations-list").change();
            });
        </script>
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


if(!isset($_GET['consignee_id']) && !isset($_GET['date'])) {
	echo "<h1>選擇要列印表單的日期與貨運行</h1>";
	echo '<form action="print_consignee_form.php" method="get">';
		$today = date('Y-m-d' );
		echo "開始日期 date";
		echo "<input type=\"date\" name=\"date\" value=\"".$today."\"></br>";
		echo "結束日期 date";
		echo "<input type=\"date\" name=\"enddate\" value=\"".$today."\"></br>";
		echo "行口位置<select id=\"stations-list\"></select>";
		echo "行口名稱<select name=\"consignee_id\" id=\"cosignee-list\"></select></br>";
#		echo "行口名稱<select name=\"consignee_id\">";
#		$consignee_list=getlist("consignee_list");
#		while($row = $consignee_list->fetch_array())
#		{
#			echo "\t\t<option value=\"".$row['consignee_id']."\">".$row['consignee']."</option>\n";
#		}
#		echo "<select></br>";
		echo '<input type="submit" value="送出表單">';
		echo '<input type="reset" value="清除表單">';
		echo '<input type ="button" onclick="javascript:location.href=\'index.html\'" value="回首頁"></input>';
	echo '</form>';

} else {

	$consignee_id=$_GET['consignee_id'];
	$date=$_GET['date'];
	$enddate=$_GET['enddate'];
	$carlicense=$_GET['carlicense'];
	if(strtotime($enddate) - strtotime($date) < 0){
		echo "錯誤！結束日期早於開始日期！";	
		exit;	
	}
	include("mysql_connect.inc.php");
	#照order_id排
	$sql = 'SELECT * FROM fruit_database.order_list
		left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
		left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
		left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
		where order_list.consignee_id=\''.$consignee_id.'\'
		and order_list.date between \''.$date.'\' and \''.$enddate.'\' 
		order by order_list.order_id
		;';
	



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
	$sum_consignee_money=0;




#html開始
	echo "<div class=\"print_table\">";
	echo "<h1>出貨明細表</h1>";
	echo "<h2><p id=data></p></h2>\n";
	echo "<h2><p id=data2></p></h2>\n";
	echo "<table id=table border=\"1\">\n";
	echo "<tr><td>項目</td><td>訂單編號</td><td>訂單日期</td><td width='60'>車號</td><td>貨主</td><td width='60'>品名</td><td>數量</td><td>代收金</td><td>運金</td><td>行口</td><td>市場</td></tr>";
	$i=0;

	while($row = $result->fetch_array())
	{
		$i=$i+1;
		echo "<tr> <td>" . $i . "</td><td>" . $row['order_id'] . "</td><td>" . $row['date'] . "</td><td>" . $row['carlicense'] . "</td><td>".$row['shipper'] . "</td><td>".$row['product'] . "</td><td>".$row['quantity'] . "</td><td>".$row['trucking_money'] . "</td><td>".$row['consignee_money'] . "</td><td>".$row['consignee'] . "</td><td>".$row['station'] . "</td></tr>\n";
	$consignee=$row['consignee'];

	$carlicense=$row['carlicense'];
		
	$sum_trucking_money=$sum_trucking_money+$row['trucking_money'];
	$sum_consignee_money=$sum_consignee_money+$row['consignee_money'];
	$station=$row['station'];	
	};
	echo "</table></br>\n";
	$data="開始日期: ".$date." 結束日期: ".$enddate;
	$data2="行口: ".$consignee." \t市場: ".$station;
	echo "<script>";
	echo "document.getElementById(\"data\").innerHTML = '".$data."';";
	echo "document.getElementById(\"data2\").innerHTML = '".$data2."';";
	echo "</script>"; 
	$sum_money=intval($sum_trucking_money)+intval($sum_consignee_money);
	$text="<h2> 代收金總額 = ".$sum_trucking_money." 運金總額 = ".$sum_consignee_money."</h2>";
	echo $text;
#	echo "<h2> 代收金總額=".$sum_trucking_money."</h2></br>";
#	echo "<h2> 運金總額=".$sum_consignee_money."</h2></br>";
	echo "<h2> 總計=".$sum_money."</h2></br>";
	$bardate=substr($date,2,2).substr($date,5,2).substr($date,8,2);
	$bardays=str_pad(((strtotime($enddate) - strtotime($date))/86400),2,'0',STR_PAD_LEFT);;
	$barconsignee_id=substr($consignee_id,0,4);
	$barcode="t".$bardate.$bardays.$barconsignee_id;
	echo "<div class=barcode>";
	echo "	<IMG  SRC=\"barcode.php?barcode=".$barcode."&width=320&height=50\">";
	echo "</div>";
	echo "</div>";
}
?>

</body >
</html>
