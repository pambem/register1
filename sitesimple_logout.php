<?php 

session_start();

$_SESSION['SIGNED'] = 0;
unset($_SESSION['SIGNED']);
session_unset($_SESSION['SIGNED']);
session_destroy();

session_start();

$_SESSION['error'] = 'Você foi desconectado com sucesso';

header('Location: sitesimple_login.php');

exit();