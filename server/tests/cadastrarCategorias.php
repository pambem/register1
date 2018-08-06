<?php 



session_start();

require_once('../classes/answer.php');
require_once('../classes/connectiondatainterface.php');
require_once('../classes/cdis/snmcdi.php');
require_once('../classes/dbservices.php');

require_once('../classes/snm.php');

$snm = new SNM();

echo "====================================================<br>";
echo "Iniciando teste unitário<br>";
echo "Classe: " . get_class($snm) . "<br>";
echo "Método: cadastrarCliente()<br>";



$categorias['categoria'] = "Categoria Teste";
$categorias['desc'] = "Descricao Teste";




echo "Dados: <br>";
var_dump($categorias);

$ret = $snm->cadastrarCategoria($categorias);

echo "Valor de Retorno: " . $ret . "<br>";

if($ret == FALSE){   
    echo 'snm_error_msg: ' . $_SESSION['snm_error_msg'] . "<br>";
    echo 'snm_error_code: ' . $_SESSION['snm_error_code'] . "<br>";
} else {
    echo "<br><br>[SUCCESS] Teste passou.<br><Br>";
}


echo "Teste unitário finalizado. " . date('d-m-Y h:i:s') . "<br>";
echo "====================================================<br>";


exit();