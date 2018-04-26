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
		echo "Login success!, ".$_POST['name'];
		echo "<script>setTimeout(function(){window.location = document.referrer;},2000);</script><!--五秒後自動回上一頁-->";

		
	}else{
		echo "Wrong password!";
		returntoindex(2000);
	}
}
?>
