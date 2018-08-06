<?php

/*
 * @desc Classe principal da aplicação e acessada pela interface SOAP
 */
Class SNM {
 
    public function __construct(){
        
    }
    
    
    
    /*
     * @return Bool
     * @params $a array com valores de email, cpf e rg a serem testados
     * @desc Esse método verifica se o cliente já está cadastrado checando o email, cpf e rg.
     */
    protected function categoriaExiste($a) {
        
        $db = new DBServices(new SNMCDI);
        $conn = $db->connect();
        
        $categoria = $a['categoria'];
        
        $sql = "SELECT count(*) as total FROM categorias WHERE categoria='{$categoria}'";
        
        $result = $conn->query($sql);
        if(!$result){
            return TRUE;
        }
        $row = $result->fetch_all(MYSQLI_ASSOC);
        if($row[0]['total'] == 0){
            return FALSE;
        }
        return TRUE;
    }
    
    /*
     * @return Bool
     * @params $campos array com valores da categoria
     * @desc método responsável por cadastrar novas categorias. Retorna TRUE ou FALSE. 
     */
    public function cadastrarCategoria($campos) {
        
        $db = new DBServices(new SNMCDI);
        $conn = $db->connect();

        $categoria = addslashes($campos['categoria']);
        $desc = addslashes($campos['desc']);
        
        if( $this->categoriaExiste($campos)){
            $_SESSION['snm_error_msg'] = "Categoria '{$categoria}' já está cadastrado!";
            $_SESSION['snm_error_code']= 1;
            $conn->close();
            return FALSE;
        }
         
        
        $sql  = "INSERT INTO categorias(categoria, `desc`) ";
        $sql .= " VALUES('{$categoria}', '{$desc}')";
        
        // caso haja problemas na query seta as sessoes com informações de erro.
        if(!$conn->query($sql)){
            $_SESSION['snm_error_msg'] = 'Problemas ao executar a query. Err: ' . $conn->error;
            $_SESSION['snm_error_code']= -1;
        
            $conn->close();
            return FALSE;
        }
        
        $conn->close();
        return TRUE;
        
        
    }
    
    
     /*
     * @desc método responsável por listar categorias cadastradas. 
     */
    public function listarCategorias(){
        
        $sscdi = new SNMCDI();
		$db = new DBServices($sscdi);
		$conn = $db->connect();
		
		$sql = "SELECT * FROM categorias";
		$query = $conn->query($sql);
		if($query){
			$result = $query->fetch_all(MYSQLI_ASSOC);
			return $result;
		}
		return array();
        
    }
    
    
    public function deletarCategoria($id) {
        
        $db = new DBServices(new SNMCDI);
        $conn = $db->connect();
        
        $sql = "DELETE FROM categorias WHERE idcategorias='{$id}' LIMIT 1";
        
        if(!$conn->query($sql)){
            $_SESSION['snm_error_msg'] = 'Não foi possível remover o registro. Err: ' . $conn->error;
            $_SESSION['snm_error_code']= -1;
        
            $conn->close();
            return FALSE;
        }
        
        $conn->close();
        return TRUE;
        
    }
    
    

    
 

} // Fim da Classe.

