<!DOCTYPE html>
<html>

<head>
	<?php include $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
	<!-- Site CSS -->
<link rel="stylesheet" href="/css/signin.css" />
</head>

<body>

<?php

//TODO See http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL

include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/DataService.php';

if (session_status () !== PHP_SESSION_ACTIVE) {
	session_start ();
}
if ((isset ( $_SESSION ['login'] ) && $_SESSION ['login'] === 'ok')) {
	header ( "Location: index.php" );
}
if (isset ( $_POST ["login"] , $_POST ["password"] )) {
	$login = $_POST ["login"];
	$password = $_POST ["password"];
	
	$dataService = new DataService ( $databaseConnexion );
	$response = $dataService->ckeckLogin ( $login, $password );
	
	if ($response ['success'] === true && $response ['result'] === true) {
		$_SESSION ['login'] = "ok";
		header ( "Location: index.php" );
	}else{
		header ( "Location: login.php" );
	}
}

?>



	<div class="container">
		<form id="loginForm" class="form-signin" role="form" method="post">
			<h2 class="form-signin-heading">Please sign in</h2>
			<input type="hidden" name="action" value="authent">
			<input type="text" name="login" class="form-control" placeholder="Email address" required autofocus> 
			<input type="password" name="password" class="form-control" placeholder="Password" required> 
			<label class="checkbox"> 
				<input name="remember-me" type="checkbox" value="remember-me"> Remember me
			</label>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</form>
	</div>
	<!-- /container -->



<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>




</body>
</html>