<html>


<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="udform.css">
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
	<td >貨主編號 shipper_id</td> 
	<td >
		<select name="shipper_id">
		<?php
		$shipper_list=getlist("shipper_list");
		while($row = $shipper_list->fetch_array())
		  {
		  echo "\t\t<option value=\"".$row['shipper_id']."\">".$row['shipper']."</option>\n";
		  }
		?>
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

