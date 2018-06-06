<!--
要在/var/www/html底下創一個/upload 資料夾 777權限
檔案會暫存在/upload/uploaded_file.txt
-->

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<title>上傳CSV訂單</title>
</head>
<body>

<?php

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

function mysqltoarray($mysqlob){
	$idarray=array();
	while($row = $mysqlob->fetch_array()){
		$idarray[$row[0]]=$row[1];
	}
	return $idarray;
};

function gettruckingid(){
	include("mysql_connect.inc.php");
	$sql="SELECT * FROM fruit_database.trucking_list;";
	if ($result=mysqli_query($con, $sql)) {
		#echo "Get List successful!";
	} else {
		echo "Error gettruckingid " . mysqli_error($con);
	}
	mysqli_close($con);
	return $result;


};

function getconsigneeid(){
	include("mysql_connect.inc.php");
	$sql="SELECT consignee_id,Concat(station, consignee)
	FROM fruit_database.consignee_list;";
	if ($result=mysqli_query($con, $sql)) {
		#echo "Get List successful!";
	} else {
		echo "Error getconsigneeid " . mysqli_error($con);
	}
	mysqli_close($con);
	return $result;


};

function utf8_fopen_read($fileName) { 
	$contents = file_get_contents("upload/uploaded_file.txt");
	$encoding =mb_detect_encoding($contents, array("BIG5","ASCII","UTF-8","GB2312","GBK"), true) ;
	echo "encoding:".$encoding."<br />";
	if ($encoding != false) {
		#$contents = iconv($encoding, 'UTF-8', $contents);
		$fc = iconv($encoding, "UTF-8",$contents); 
		#echo $fc;
		$handle=fopen($fileName, "w"); 
		fwrite($handle, $fc); 
		fseek($handle, 0); 
		fclose($handle); 
	} else {
	 	$fc = mb_convert_encoding($contents, 'UTF-8','auto');
		#echo $fc;
		$handle=fopen($fileName, "w"); 
		fwrite($handle, $fc); 
		fseek($handle, 0); 
		fclose($handle); 
	}
};



function showcsv($storagename){
	if ( $file = fopen(  $storagename , "r" ) ) {
		$result=gettruckingid();
		$truckingid=mysqltoarray($result);
		$result=getconsigneeid();
		$consigneeid=mysqltoarray($result);
		#$a=array_search("板橋小林",$consigneeid,true);

		echo "File opened.<br />";

		$firstline = fgets ($file, 4096 );
			//Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
		$num = strlen($firstline) - strlen(str_replace(",", "", $firstline));
			//消除n個逗號,總共n+1欄
			//save the different fields of the firstline in an array called fields
		$num=$num+2;
			//加入兩行id
		$fields = array();
		$fields = explode( ",", $firstline, ($num+1) );

			//斷開逗號寫入矩陣,總共n+1欄
		array_splice($fields,2, 0, '貨運行編號');
		array_splice($fields,10, 0, '行口編號');
		$postcol=array("0","2","3","4","5","6","7","10","11");
			//post時要傳出的行數
		$warring=0;
			//警告訊息，如果有則不能post;

		$line = array();
		$i = 0;

			//CSV: one line is one record and the cells/fields are seperated by ","
			//so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
		while ( $line[$i] = fgets ($file, 4096) ) {

			$dsatz[$i] = array();
			$dsatz[$i] = explode( ",", $line[$i], ($num+1) );

			$tid=array_search($dsatz[$i][1],$truckingid,true);
			#echo $dsatz[$i][7].$dsatz[$i][8];
			$cid=array_search($dsatz[$i][7].$dsatz[$i][8],$consigneeid,true);
			#echo $cid;
			if(empty($tid)){
				$tid="<font color=\"red\">查無貨運行編號!請先新增!</font>";
				$warring=1;
			};

			if(empty($cid)){
				$cid="<font color=\"red\">查無行口編號!請先新增!</font>";
				$warring=1;
			};

			array_splice($dsatz[$i],2, 0, $tid);
			array_splice($dsatz[$i],10, 0, $cid);

			$i++;
		}

			echo "<table border=1>";
			echo "<tr>";
		for ( $k = 0; $k != ($num+1); $k++ ) {
			echo "<td>" . $fields[$k] . "</td>";
		}
			echo "</tr>";


		foreach ($dsatz as $key => $number) {
					//new table row for every record
			echo "<tr>";
			foreach ($number as $k => $content) {
							//new table cell for every field of the record
				switch ($k){
				case 0:
					if(!validateDate($content)){
						$content="<font color=\"red\">".$content."</font>";
						$warring=1;
					}
					break;
				case 4:
					if(empty($content)){
						$content="<font color=\"red\">貨主為空</font>";
						$warring=1;
					}
					break;
				case 5:
					if(empty($content)){
						$content="<font color=\"red\">品名為空</font>";
						$warring=1;
					}
					break;
				case 6:
					if(empty($content)){
						$content="<font color=\"red\">數量為空</font>";
						$warring=1;
					}
					if(!is_numeric($content)){
						$content="<font color=\"red\">數量不是數字</font>";
						$warring=1;
					}
					break;
				case 7:
					if(!is_numeric(intval($content))){
						$content="<font color=\"red\">代收金不是數字</font>";
						$warring=1;
					}
					if(empty($content)){
						$content="<font color=\"red\">0</font>";
						$dsatz[$key][$k]=0;
						$warring=0;
					}

					break;
				}
				echo "<td>" . $content . "</td>";
				
			}
		}

		echo "</table>";

		echo "<form method=\"post\" action=\"uploadcsv_receive.php\">";
		foreach ($dsatz as $key => $number) {
					//new table row for every record
			echo "<input type=\"text\" name=\"order[]\" value='";
			foreach ($number as $k => $content) {
							//new table cell for every field of the record
				if (in_array($k, $postcol)) {
					echo  "\"".$content."\"" ;
					if($k!="11"){
					echo ",";
					}
				}
			}
			echo "'>";
		}
		if($warring==0){
			echo "<input type=\"hidden\" name=\"token\" value=\"xAD5l9weDCqKkYgZNd1ICxn4\">";
			echo "<input type=\"submit\" value=\"送出訂單\">";
		}else{
			echo '<input type="button" disabled="disabled" value="訂單有錯誤，無法送出">';
		}

		echo "</form>";
		fclose($file);
	}
}
?>

<table width="600">
	<form action="/uploadcsv.php" method="post" enctype="multipart/form-data">

	<tr>
		<td width="20%">Select file</td>
		<td width="80%"><input type="file" name="file" id="file" /></td>
	</tr>

	<tr>
		<td>Submit</td>
		<td><input type="submit" name="submit" /></td>
	</tr>

	</form>
</table>
<input type ="button" onclick="javascript:location.href='index.html'" value="回首頁"></input></br>

<?php
if ( isset($_POST["submit"]) ) {

	if ( isset($_FILES["file"])) {

			//if there was an error uploading the file
		if ($_FILES["file"]["error"] > 0) {
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

		}
		else {
				 //Print file details
			 echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			 echo "Type: " . $_FILES["file"]["type"] . "<br />";
			 echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			 echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

				 //if file already exists
			 if (file_exists("upload/" . $_FILES["file"]["name"])) {
			echo $_FILES["file"]["name"] . " already exists. ";
			 }
			 else {
					//Store file in directory "upload" with the name of "uploaded_file.txt"
			$storagename = "upload/uploaded_file.txt";
			move_uploaded_file($_FILES["file"]["tmp_name"], $storagename);
			echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
			echo $storagename."<br />";
			#echo mb_detect_encoding("12342523eg", array("ASCII","UTF-8","GB2312","GBK","BIG5"), true) ;
			utf8_fopen_read($storagename);
			//偵測並轉換編碼
			#echo file_get_contents($storagename);
			showcsv($storagename);
			

			
			}
		}
	 } else {
			 echo "No file selected <br />";
	 }
}


#$result=getconsigneeid();
#var_dump($result);

#

#var_dump($consigneeid);
#$a=array_search("板橋小林",$consigneeid,true);
#echo $a;

#$result=gettruckingid();
#var_dump($result);
?>


</body>
</html>
