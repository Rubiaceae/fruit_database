<!--
新增訂單的頁面
-->

<html>


<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>新增訂單</title>
	<link rel="stylesheet" href="udform.css">
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

<body>
<grid><div class="col-md-1-2">

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
?>


<!--
#html開始
-->
<h1>訂單</h1>

<form action="order_list_receive.php" method="post">
<table>
<tr>
	<td>日期 date</td>
<?php
$today = date('Y-m-d' );
echo "<td>	  <input type=\"date\" name=\"date\" value=\"".$today."\">";
?>

	</td>
</tr>

<tr>
	<td >南部貨運編號 trucking_id</td> 
	<td >	
		<select name="trucking_id">
		<?php
		$trucking_list=getlist("trucking_list");
		while($row = $trucking_list->fetch_array())
		  {
		  echo "\t\t<option value=\"".$row['trucking_id']."\">".$row['trucking']."</option>\n";
		  }
		?>
		</select>
	</td>
</tr>

<tr>
	<td >車號 carlicense</td> 
	<td >	<input type="text" name="carlicense" maxlength="7" size="7"><br>
	</td>
</tr>

<tr>
	<td >貨主 shipper</td> 
	<td ><input type="text" name="shipper" maxlength="7" size="7"  required><br>
		
<!--
#		<select name="shipper_id">
#		<?php
#		$shipper_list=getlist("shipper_list");
#		while($row = $shipper_list->fetch_array())
#		  {
#		  echo "\t\t<option value=\"".$row['shipper_id']."\">".$row['shipper']."</option>\n";
#		  }
#		?>
-->
		</select>	
	</td>
</tr>

<tr>
	<td >品名 product</td> 
	<td >	<input type="text" name="product" maxlength="10" size="10"  required><br>
	</td>
</tr>

<tr>
	<td >數量 quantity</td> 
	<td >	<input type="text" name="quantity" maxlength="10" size="10" required><br>
	</td>
</tr>  

<tr>
	<td >代收金 trucking_money</td> 
	<td >	<input type="text" name="trucking_money" maxlength="10" size="10" required><br>
	</td>
</tr>  
<!--
<tr>
	<td >收貨人編號 consignee_id</td> 
	<td >
		<select name="consignee_id">
		<?php
		$consignee_list=getlist("consignee_list");
		while($row = $consignee_list->fetch_array())
		  {
		  echo "\t\t<option value=\"".$row['consignee_id']."\">".$row['consignee']."-".$row['station']."</option>\n";
		  }
		?>
		</select>	
	</td>
</tr> -->  

<tr>
	<td >行口地點 station</td> 
	<td >
		<select id="stations-list"></select>	
	</td>
</tr>
<tr>
	<td >行口名稱 consignee_id</td> 
	<td >
		<select name="consignee_id" id="cosignee-list"></select>	
	</td>
</tr>

<tr>
	<td >備註 note</td> 
	<td >	<input type="text" name="note" maxlength="100" size="100"><br>
	</td>
</tr>  
</table>


<input type="hidden" name="token" value="xAD5l9weDCqKkYgZNd1ICxn4"/>
<input type="submit" value="送出表單">
<input type="reset" value="清除表單">
<input type ="button" onclick="javascript:location.href='index.html'" value="回首頁"></input>

</form>
</div>
</grid>
</body>
</html>

