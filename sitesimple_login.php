<?php

session_start();

if(isset($_SESSION['error'])){
	$error = $_SESSION['error'];
} else {
	$error = null;
}

if($_SESSION['SIGNED'] === 1){
	
	if(isset($_SESSION['referer']) && !empty($_SESSION['referer'])){
		header( 'Location: ' . $_SESSION['referer']);
		exit();
	}
	header('Location: index.php');
	exit();
}

if(isset($_POST['username']) && isset($_POST['password']) ){
	
	
	if($_POST['username'] === 'admin' && $_POST['password'] === 'qq1500'){
		$_SESSION['SIGNED'] = 1;
		$_SESSION['username'] = $_POST['username'];
		
		header('Location: sitesimple_login.php');
		exit();	
	} else {
		$_SESSION['SIGNED'] = 0;
		$error = 'Usuário ou Senha inválidos. Tente novamente.';
	}
	
}
   
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>

    <title>Login - UFSC</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
	<meta name="author" content="" />

	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800" type="text/css">
	<link rel="stylesheet" href="./assets/css/font-awesome.min.css" type="text/css" />		
	<link rel="stylesheet" href="./assets/css/bootstrap.min.css" type="text/css" />	
	<link rel="stylesheet" href="./js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css" type="text/css" />	
	
	<link rel="stylesheet" href="./css/App.css" type="text/css" />
	<link rel="stylesheet" href="./assets/css/Login.css" type="text/css" />

	<link rel="stylesheet" href="./assets/css/custom.css" type="text/css" />
        
</head>

<body>

<div id="login-container">

	<div id="logo">
		<a href="./login.html">
			<img src="./assets/img/vertical_extenso_fundo_claro_ok.png" alt="Logo" />
		</a>
	</div>

	<div id="login">

		<h3>Bem vindo!</h3>

		<h5>Por favor entre suas credenciais</h5>
		<?php
   			if($error != null){
				echo '<h3 style="color: red">' . $error . '</h3>';	
			}
   		?>

		<form id="login-form" method="post" action="./sitesimple_login.php" class="form">

			<div class="form-group">
				<label for="login-username">Usuário</label>
				<input type="text" class="form-control" id="login-username" name="username" placeholder="Usuário">
			</div>

			<div class="form-group">
				<label for="login-password">Senha</label>
				<input type="password" class="form-control" name="password" id="login-password" placeholder="Senha">
			</div>

			<div class="form-group">

				<button type="submit" id="login-btn" class="btn btn-primary btn-block">Entrar &nbsp; </button>

			</div>
		</form>


		<!-- a href="javascript:;" class="btn btn-default">Esqueceu sua senha?</a -->

	</div> <!-- /#login -->

	<!-- a href="javascript:;" id="signup-btn" class="btn btn-lg btn-block">
		Create an Account
	</a -->


</div> <!-- /#login-container -->

<script src="./js/libs/jquery-1.9.1.min.js"></script>
<script src="./js/libs/jquery-ui-1.9.2.custom.min.js"></script>
<script src="./js/libs/bootstrap.min.js"></script>

<script src="./js/App.js"></script>

<script src="./js/Login.js"></script>

</body>
</html>