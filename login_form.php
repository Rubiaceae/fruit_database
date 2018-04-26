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
	echo "<script>setTimeout(function(){location.href='index.html';},".$s.");</script><!--五秒後自動回首頁-->";
}

start_session(6000);
if(isset($_SESSION['name']) && !empty($_SESSION['name'])) {
	echo "已經登入了,".$_SESSION['name'];
	returntoindex(500);
}else{
	echo '<form action="login_receive.php" method="post">';
	echo '帳號:<br>';
	echo '<input type="text" name="name"><br>';
	echo '密碼:<br>';
	echo '<input type="password" name="password"><br>';
	echo '<input type="submit" value="登入">';
	echo '</form>';
}
?>
