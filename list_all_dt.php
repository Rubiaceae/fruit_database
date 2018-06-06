<!--#所有訂單列表，每頁可以查20行

-->
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>所有訂單列表</title>

	<link rel="stylesheet" type="text/css" href="http://140.112.57.110/jquery.dataTables.min.css">
		<!--
		https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css
		-->
	<style type="text/css" class="init">
	
	</style>
	
	<script type="text/javascript" language="javascript" src="http://140.112.57.110/jquery-3.3.1.js"></script>
	<script type="text/javascript" language="javascript" src="http://140.112.57.110/jquery.dataTables.min.js"></script>
		<!--
		https://code.jquery.com/jquery-3.3.1.js
		https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js
		-->
	<script type="text/javascript" class="init">
	/* Custom filtering function which will search data in column four between two values */
	$.fn.dataTable.ext.search.push(
	    function( settings, data, dataIndex ) {
		var inptimestamp = $('#inptimestamp').val().replace(/\s/g, '');
	 	var timestamp = data[1].replace(/\s/g, '');

		var inpdate = $('#inpdate').val().replace(/\s/g, '');
	 	var date = data[3].replace(/\s/g, '');

		var inptrucking = $('#inptrucking').val().replace(/\s/g, '');
	 	var trucking = data[4].replace(/\s/g, '');

		var inpcarlicense = $('#inpcarlicense').val().replace(/\s/g, '').toUpperCase();
	 	var carlicense = data[5].replace(/\s/g, '').toUpperCase();

		var inpshipper = $('#inpshipper').val().replace(/\s/g, '');
	 	var shipper = data[6].replace(/\s/g, '');

		var inpproduce = $('#inpproduce').val().replace(/\s/g, '');
	 	var produce = data[7].replace(/\s/g, '');

		var minq = parseInt( $('#minq').val(), 10 );
		var maxq = parseInt( $('#maxq').val(), 10 );
		var quantity = parseFloat( data[8] ) || 0; // use data for the age column

		var mintm = parseInt( $('#mintm').val(), 10 );
		var maxtm = parseInt( $('#maxtm').val(), 10 );
		var truckingmoney = parseFloat( data[9] ) || 0; // use data for the age column

		var mincm = parseInt( $('#mincm').val(), 10 );
		var maxcm = parseInt( $('#maxcm').val(), 10 );
		var consigneemoney = parseFloat( data[10] ) || 0; // use data for the age column


		var inpconsignee = $('#inpconsignee').val().replace(/\s/g, '');
	 	var consignee = data[11].replace(/\s/g, '');

		var inpstation = $('#inpstation').val().replace(/\s/g, '');
	 	var station = data[12].replace(/\s/g, '');

		var inpdriver = $('#inpdriver').val().replace(/\s/g, '');
	 	var driver = data[13].replace(/\s/g, '');

		var inptrip = $('#inptrip').val().replace(/\s/g, '');
	 	var trip = data[14].replace(/\s/g, '');

		if (inptimestamp === "" || timestamp.includes(inptimestamp)){
			if(inpdate === "" || date.includes(inpdate)){
				if( (inptrucking === "")  || ( trucking.includes(inptrucking))){
					if( (inpcarlicense === "")  || ( carlicense.includes(inpcarlicense))){
						if( (inpshipper === "")  || ( shipper.includes(inpshipper))){
							if( (inpshipper === "")  || ( shipper.includes(inpshipper))){
								if( (inpproduce === "")  || ( produce.includes(inpproduce))){
									if ( ( isNaN( minq ) && isNaN( maxq ) )	||
									( isNaN( minq ) && quantity <= maxq ) ||
									( minq <= quantity   && isNaN( maxq ) ) ||
									( minq <= quantity   && quantity <= maxq ) ){
										if ( ( isNaN( mintm ) && isNaN( maxtm ) )	||
										( isNaN( mintm ) && truckingmoney <= maxtm ) ||
										( mintm <= truckingmoney   && isNaN( maxtm ) ) ||
										( mintm <= truckingmoney   && truckingmoney <= maxtm ) ){
											if ( ( isNaN( mincm ) && isNaN( maxcm ) )	||
											( isNaN( mincm ) && consigneemoney <= maxcm ) ||
											( mincm <= consigneemoney   && isNaN( maxcm ) ) ||
											( mincm <= consigneemoney   && consigneemoney <= maxcm ) ){
												if( (inpconsignee === "")  || ( consignee.includes(inpconsignee))){
													if( (inpstation === "")  || ( station.includes(inpstation))){
														if( (inpdriver === "")  || ( driver.includes(inpdriver))){
															if( (inptrip === "")  || ( trip.includes(inptrip))){
																return true;
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}

				}
			}
		}

			
		return false;
	    }
	);
	$(document).ready(function() {
	    var table = $('#example').DataTable();
	     
	    // Event listener to the two range filtering inputs to redraw on input
	    $('#inptimestamp,#inpdate,#inptrucking,#inpcarlicense,#inpshipper,#inpproduce,#minq,#maxq,#mintm,#maxtm,#mincm,#maxcm,#inpconsignee,#inpstation,#inpdriver,#inptrip').keyup( function() {
		table.draw();
	    } );
	} );
	
	$(document).ready(function() {
		$('#example').DataTable();
	} );


	</script>
</head>

<body >
<input type ="button" onclick="javascript:location.href='index.html'" value="回首頁"></input>




<table border="0" cellspacing="1" cellpadding="1">
<tbody>
	<tr>
		<td>訂單成立時間:</td>
		<td>訂單日期:</td>
		<td>貨運行:</td>
		<td>車號:</td>
		<td>貨主:</td>
		<td>品名:</td>
		<td>最小數量:</td>
		<td>最大數量:</td>
		<td>最小代收金金額:</td>
		<td>最大代收金金額:</td>
		<td>最小運金金額:</td>
		<td>最大運金金額:</td>
		<td>行口:</td>
		<td>市場:</td>
		<td>司機:</td>
		<td>趟次:</td>
        </tr>
	<tr>
		<td><input type="text" id="inptimestamp" name="inptimestamp" size="10"></td>
		<td><input type="text" id="inpdate" name="inpdate" size="10"></td>
		<td><input type="text" id="inptrucking" name="inptrucking" size="5" ></td>
		<td><input type="text" id="inpcarlicense" name="inpcarlicense" size="5" ></td>
		<td><input type="text" id="inpshipper" name="inpshipper" size="5" ></td>
		<td><input type="text" id="inpproduce" name="inpproduce" size="5" ></td>
		<td><input type="text" id="minq" name="minq" size="5" ></td>
		<td><input type="text" id="maxq" name="maxq" size="5" ></td>
		<td><input type="text" id="mintm" name="mintm" size="5" ></td>
		<td><input type="text" id="maxtm" name="maxtm" size="5" ></td>
		<td><input type="text" id="mincm" name="mincm" size="5" ></td>
		<td><input type="text" id="maxcm" name="maxcm" size="5" ></td>
		<td><input type="text" id="inpconsignee" name="inpconsignee" size="5" ></td>
		<td><input type="text" id="inpstation" name="inpstation" size="5" ></td>
		<td><input type="text" id="inpdriver" name="inpdriver" size="5" ></td>
		<td><input type="text" id="inptrip" name="inptrip" size="3" ></td>
        </tr>
	</tbody>
</table>


<table id="example" class="display compact">
<?php
	function getorderlist(){#查詢所有表join，
		include("mysql_connect.inc.php");
		$sql = 'SELECT * FROM fruit_database.order_list
		left join fruit_database.trucking_list  on trucking_list.trucking_id=order_list.trucking_id 
		left join fruit_database.consignee_list  on consignee_list.consignee_id=order_list.consignee_id
		left join fruit_database.driver_list  on driver_list.driver_id=order_list.driver_id
		order by order_list.order_id desc;';
		if ($result=mysqli_query($con, $sql)) {
			#echo "Get List successful!";
		} else {
			echo "查詢List資料失敗 " . mysqli_error($con);
		}
		mysqli_close($con);
		return $result;
	};



#echo $page;


$result=getorderlist();

echo "<thead><tr><td>項次</td><td>訂單成立時間</td><td>訂單編號</td><td>訂單日期</td><td>南部貨運商</td><td>車號</td><td>貨主</td><td>品名</td><td>數量</td><td>代收金</td><td>運金</td><td>行口</td><td>市場</td><td>司機</td><td>趟次</td><td>確認出貨</td><td>確認付代收金</td><td>確認收運金</td><td>確認付司機薪水</td></tr></thead>";
echo "<tbody>";
	while($row = $result->fetch_array())
	{	$i=$i+1;
		echo  "<tr><td>".$i."</td><td>".$row['timestamp']."</td><td>".$row['order_id']."</td><td>".$row['date']."</td><td>".$row['trucking']."</td><td>".$row['carlicense']."</td><td>".$row['shipper']."</td><td>".$row['product']."</td><td>".$row['quantity']."</td><td>".$row['trucking_money']."</td><td>".$row['consignee_money']."</td>\n<td>".$row['consignee']."</td><td>".$row['station']."</td><td>".$row['driver']."</td><td>".$row['driver_trip']."</td><td>".$row['order_settle']."</td><td>".$row['trucking_money_settle']."</td><td>".$row['consignee_money_settle']."</td><td>".$row['driver_money_settle']."</td></tr>\n";
	}

echo "</tbody>";

?>



</table>
</body>
</html>

