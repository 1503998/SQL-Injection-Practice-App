<?php
include 'header.php';
// Redirige l'utilisateur s'il n'est pas identifié
if(empty($_COOKIE["id"]))
{
     header("Location: index.php");
}
else
{

     $result = mysql_query("
                    INSERT INTO pp_fav (gid,user) VALUES ($_GET[g],'$_GET[u]')
               ");
			   

    header('Location: ' . $_SERVER['HTTP_REFERER'] );    
}


?>