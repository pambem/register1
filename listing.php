<?php


class Listing  {
    
   public function _connect($host,$user,$pass,$dbname) {
        
    $_c = new mysqli($host, $user, $pass, $dbname);


    if ($_c->connect_error) {

        die('Connect Error (' . $_c->connect_errno . ') ' . $_c->connect_error);

    }  else {
            
            /* if all ok return connection object */
            return $_c;
            
        }

   }

   public function _listing($_c, $type) {

        $r = $_c->query("SELECT * FROM categorias") or die("Data not found");

        if($r->num_rows > 0) {

            $html = "";

            for($i = 0; $i < $r->num_rows; $i++) {

                $row = $r->fetch_assoc();

                if($type == "1")  {

                    $html .= "

                    <tr style='cursor: pointer' id='" . $row['idcategorias'] . "'>
                    <td>" . $row['categoria'] ."</td>
                    <td>" . $row['desc'] ."</td>
                    <td><div id='delete' onClick=\"$.fn.delete('" . $row['idcategorias'] . "')\">excluir</div></td>
                    </tr>
                    
                    ";

                } else {

                    $html .= "

                    <tr style='cursor: pointer' id='" . $row['idcategorias'] . "'>
                    <td>" . $row['categoria'] ."</td>
                    <td>" . $row['desc'] ."</td>
                    <td>" . $row['created'] ."</td>
                    <td>" . $row['modified'] ."</td>
                    </tr>
                    
                    ";

                }



            }

            return $html;

        } else {

            return false;
        }
    

   }

   public function _delete($_c, $id) {

        $r = $_c->query("DELETE FROM categorias WHERE idcategorias='$id'") or die("Data not found");

        if($_c->affected_rows > 0) {

            return true;

        } else {

            return false;
        }

   }

}



if(isset($_POST['_new_consult'])) {

    if($_POST['OPT']=='delete') {

        $id = $_POST['id'];

        $l  = new Listing();
        $c  = $l->_connect("localhost","root","","snm");
        $r  = $l->_delete($c, $id);

        if($r) {

            echo "true";

        } else {

            echo "false";
        }
    }
    
}