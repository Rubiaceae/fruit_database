<!--#條碼單號輸入

-->

<?php
session_start();


?>
<!--
html開始
-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
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
<!--	<script language="Javascript" type="text/javascript">
	$(".inputs").keyup(function () {
		if (this.value.length == this.maxLength) {
			var $next = $(this).next('.inputs');
			if ($next.length)
				$(this).next('.inputs').focus();
			else
				$(this).blur();
		}
	});
	</script>
-->
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
<!--
	<script>
	window.onload = function () {	
		if((document.getElementById('barcode0').value)=="text")
		{
			 alert('Hi, I am alert box.');
		}
	}
	</script>
-->
<?php

if(!isset($_SESSION['name']) || empty($_SESSION['name'])){
	echo "尚未登入!";
	echo "<script>setTimeout(function(){location.href='login_form.php';},2000);</script><!--五秒後自動回首頁-->";
}else{

	echo '<form name=barcodeform action="settle_receive.php" method="post">';
	echo "<h1>輸入條碼單號</h1></br>\n";
	echo '<input type="button" value="增加欄位"      onClick="addInput(\'dynamicInput\');"></br></br>';
#	echo "1.<input type=\"text\" name=\"barcode1\" ></br></br>";
#	echo "<p id=form_list></p></br>\n";
	echo "<input type=\"hidden\" name=\"token\" value=\"xAD5l9weDCqKkYgZNd1ICxn4\">\n";
#	echo "<input type=\"buttom\" value=\"增加欄位\" >\n";

	echo '<div id="dynamicInput">';
#	echo '1. <input class="input" type="text" name="barcode[]" onchange="addInput(\'dynamicInput\')"  data-maxlength="4">';
#	echo '1. <input class="inputs" type="text" name="barcode[]" onClick="addInput(\'dynamicInput\')"  data-maxlength="4">';	
#	echo '1. <input class="inputs" type="text" name="barcode[]" data-maxlength="4"></br>';
#	echo '2. <input class="inputs" type="text" name="barcode[]" data-maxlength="4"></br>';
#	echo '3. <input class="inputs" type="text" name="barcode[]" data-maxlength="4"></br>';
#	echo '4. <input class="inputs" type="text" name="barcode[]" data-maxlength="4"></br>';
#	echo '5. <input class="inputs" type="text" name="barcode[]" data-maxlength="4"></br>';
#	echo '6. <input class="inputs" type="text" name="barcode[]" data-maxlength="4"></br>';
#	echo '7. <input class="inputs" type="text" name="barcode[]" data-maxlength="4"></br>';
#	echo '8. <input class="inputs" type="text" name="barcode[]" data-maxlength="4"></br>';
#	echo '9. <input class="inputs" type="text" name="barcode[]" data-maxlength="4"></br>';
#	echo '10. <input class="inputs" type="text" name="barcode[]" data-maxlength="4">';


	$i=0;
	for($i=0 ; $i<10 ; $i++ ){
	echo strval($i+1).'. <input type="text" id= barcode'.$i.' name="barcode[]" size=13 onKeyup="autotab(this, \'barcode'.strval($i+1).'\')" maxlength=13><br><br>';
	}
	echo '</div>';
	#echo '<input type="text" name="barcode0" size=10 onKeyup="autotab(this, document.barcodeform.barcode1)" maxlength=1><br>';
	#echo '<input type="text" name="barcode1" size=10 onKeyup="autotab(this, document.barcodeform.barcode2)" maxlength=3><br>' ;
	#echo '<input type="text" name="barcode2" size=10 onKeyup="autotab(this, document.barcodeform.barcode3)" maxlength=3><br>';
	#echo '<input type="text" name="barcode3" size=5 maxlength=4>';



	echo "<input type=\"submit\" value=\"送出條碼\">\n";
	echo "<input type=\"reset\" value=\"清除表單\">\n";
	echo '<input type ="button" onclick="javascript:location.href=\'index.html\'" value="回首頁"></input>';
	echo '</form>';
	
}


?>
</body >
</html>
