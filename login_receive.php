<!--登入頁面的接收，在這一頁編輯帳號密碼，目前登入6000秒
-->
<?php

function start_session($expire = 0)
{
    if ($expire == 0) {
        $expire = ini_get('session.gc_maxlifetime');
    } else {
        ini_set('session.gc_maxlifetime', $expire);
    }

    if (empty($_COOKIE['PHPSESSID'])) {
        session_set_cookie_params($expire);
        session_start();
    } else {
        session_start();
        setcookie('PHPSESSID', session_id(), time() + $expire);
    }
}

function returntoindex($s){
	echo "<script>setTimeout(function(){location.href='index.html';},".$s.");</script><!--五秒後自動回上一頁-->";
}

start_session(6000);
if(isset($_SESSION['name']) && !empty($_SESSION['name'])) {
	echo "已經登入了,".$_SESSION['name'];
	returntoindex(2000);
}else{
	if($_POST['name']=='admin' && $_POST['password']=='pw'){
		$_SESSION['name']="admin";
		echo "登入成功! ".$_POST['name'];
		if(isset( $_SESSION['refurl']) && !empty($_SESSION['refurl'])){
			echo "<script>setTimeout(function(){location.href='".$_SESSION['refurl']."';},1000);</script><!--五秒後自動回上一頁-->";	
		}else{
			echo "<script>setTimeout(function(){window.location = document.referrer;},1000);</script><!--五秒後自動回上一頁-->";
		}
		
	}else{
		echo "帳號密碼錯誤!";
		returntoindex(2000);
	}
}
?>
