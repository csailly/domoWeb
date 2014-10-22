<!DOCTYPE html>
<html>

<head>
	<?php include $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
	<link rel="stylesheet" href="/css/signin.css" />
</head>

<body>

<?php

//TODO See http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL

include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/DataService.php';

if (session_status () !== PHP_SESSION_ACTIVE) {
	session_start ();
}

if ((isset ( $_SESSION ['login'] ) && $_SESSION ['login'] === 'ok') || isset($_COOKIE["logged"])) {
	$expire = 7*24*3600;//7 jours
	setcookie("logged",$_COOKIE["logged"],time()+$expire);
	header ( "Location: index.php" );
}
if (isset ( $_POST ["login"] , $_POST ["password"] )) {
	$login = $_POST ["login"];
	$password = $_POST ["password"];
	
	$dataService = new DataService (  );
	$response = $dataService->ckeckLogin ( $login, $password );
	
	if ($response ['success'] === true && $response ['result'] === true) {
		$_SESSION ['login'] = "ok";		
		if (isset ( $_POST ["remember-me"])){
			$expire = 7*24*3600;//7 jours
			setcookie("logged",$_POST ["login"],time()+$expire);
		}
		header ( "Location: index.php" );
	}else{
		header ( "Location: login.php" );
	}
}

?>

	<div class="container">
		<form id="loginForm" class="form-signin" role="form" method="post">
			<h2 class="form-signin-heading">Authentification</h2>
			<input type="hidden" name="action" value="authent">
			<input type="text" name="login" class="form-control" placeholder="Email" required autofocus> 
			<input type="password" name="password" class="form-control" placeholder="Mot de passe" required> 
			<label class="checkbox"> 
				<input name="remember-me" type="checkbox" value="remember-me"> Se souvenir de moi
			</label>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Me connecter</button>
		</form>
	</div>

<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>

<script>
	myLoading.hidePleaseWait();
</script>

</body>
</html>