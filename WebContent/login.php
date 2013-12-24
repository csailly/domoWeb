<!DOCTYPE html>
<html>

<head>
	<?php include $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
	<!-- Site CSS -->
<link rel="stylesheet" href="/css/signin.css" />
</head>

<body>

<?php
if (session_status () !== PHP_SESSION_ACTIVE) {
	session_start ();
}
if ((isset ( $_SESSION ['login'] ) && $_SESSION ['login'] === 'ok')) {
	header ( "Location: index.php" );
}
if (isset ( $_GET ["login"] ) && isset ( $_GET ["password"] )) {
	$_SESSION ['login'] = "ok";
	header ( "Location: index.php" );
}

?>



<div class="container">
		<form class="form-signin" role="form">
			<h2 class="form-signin-heading">Please sign in</h2>
			<input type="text" name="login" class="form-control"
				placeholder="Email address" required autofocus> <input
				type="password" name="password" class="form-control"
				placeholder="Password" required> <label class="checkbox"> <input
				type="checkbox" value="remember-me"> Remember me
			</label>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign
				in</button>
		</form>
	</div>
	<!-- /container -->



<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>
	
</body>
</html>