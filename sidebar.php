<?php
if(isset($_SESSION["tipo_usuario_id"])){
    $tipo_usuario_id = $_SESSION["tipo_usuario_id"];

    if($tipo_usuario_id == 1){
        include("administrador/sidebar.php");
    }
    else if($tipo_usuario_id == 2){
        include("usuario/sidebar.php");
    }
    else if($tipo_usuario_id == 3){
        include("administrador/sidebar.php");
    }
}
else{
    echo "Tipo de usuario no identificado";
}
?>