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
?>


<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
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
	
	echo "條碼數量 " .$barlen. " 個";

    
}else{
echo "ERROR! Wrong Station!";
}


?>
</table>

<input type ="button" onclick="window.location = document.referrer;" value="回到上一頁"></input>
</html>
