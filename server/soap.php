<?php

/*
 * SOAP -  Simple Object Access Protocol
 */

// Iniciando a sessão.
session_start();

// Incluindo obrigatóriamente uma vez as classes utilizadas em nosso protocolo
require_once('classes/answer.php');
require_once('classes/connectiondatainterface.php');
require_once('classes/cdis/snmcdi.php');
require_once('classes/dbservices.php');

require_once('classes/snm.php');

// Parâmetro 'q' é obrigatório pois identifica o recurso a ser acessado.
if( isset($_GET['q']) && !empty($_GET['q'])){
    $q = $_GET['q'];
} else if(isset($_POST['q']) && !empty($_POST['q'])){
    $q = $_POST['q'];
} else {
    // Retorna um erro. Parâmetro 'q' não contém valor ou não foi setado. 
    $resp = new Answer('Query string inválida. Finalizado.', 0, -1);
    echo Answer::JSON($resp);
    die();
}

$snm = new SNM(); // Instanciando um novo objeto SNM

/*
 * @return bool
 * @params string $campo
 * @desc Verifica se $campo está certo
 */
function check($campo){
    if ( (!isset($_POST[$campo]) || empty($_POST[$campo])) && 
         (!isset($_GET[$campo])  || empty($_GET[$campo]))) {
        return FALSE;
    }
    return TRUE;
}


/*
 * @return bool
 * @params array $required_fields_array Array contendo o nome dos campos a serem checados.
 * @desc Verifica um conjunto de campos de uma so vez. Retorna FALSE se o campos estiver 
 *       vazio ou não setado e TRUE se o teste passar.
 */
function check_array($required_fields_array){
    foreach($required_fields_array as $field) {
        if (empty($_POST[$field]) && empty($_GET[$field])) {
            return FALSE;
        }
    }
    return TRUE;
}

sleep(1); // dorme por 1 segundo antes de prosseguir. Utilizado apenas para testes em servidor local.

/*
 * Utilizamos o switch() para direcionar a requisição ao método correto.
 * 
 */
switch($q){
    
    case 'cadastrar_categoria':

        if(!check('categoria') || !check('desc')){
            $resp = new Answer('Existem valores inválidos ou faltantes.', 0, -1);    
            echo Answer::JSON($resp);    
            exit();
        }
    
        if($snm->cadastrarCategoria($_POST) == TRUE){
            $resp = new Answer('Cadastro realizado com sucesso.', 1, 0);
        } else {
            
            if($_SESSION['snm_error_code'] == 1){
                $resp = new Answer($_SESSION['snm_error_msg'], 0, 1);    
            } else {
                $resp = new Answer('Cadastro não pode ser realizado', 0, 1);    
            }
        }
        echo Answer::JSON($resp);
    
    break;

    case 'listar_categorias': 
    
        $categorias = $snm->listarCategorias();
        
        $resp = new Answer('Listagem de Categorias', 1, 0, $categorias);
        echo Answer::JSON($resp);    
    
    break;
    case 'deletar_marca':
    
        $id = (int)$_POST['id'];
    
        $ret = $snm->deletarMarca($id);
        if($ret == TRUE){
            $resp = new Answer('Marca removida com sucesso.', 1, 0);
        } else {
            $resp = new Answer('Erro: ' . $_SESSION['snm_error_msg'], 0, 1);  
        }
        echo Answer::JSON($resp);    
        
    break;
    case 'deletar_categoria':
    
        $id = (int)$_POST['id'];
    
        $ret = $snm->deletarCategoria($id);
        if($ret == TRUE){
            $resp = new Answer('Categoria removida com sucesso.', 1, 0);
        } else {
            $resp = new Answer('Erro: ' . $_SESSION['snm_error_msg'], 0, 1);  
        }
        echo Answer::JSON($resp);    
    
    break;
 

    default:
        die('Operação não permitida! Abortando...'); // um die() é o suficiente.
    
}

