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
                    DELETE FROM pp_fav WHERE gid= $_GET[g] AND user ='$_GET[u]'
               ");
			   

    header('Location: ' . $_SERVER['HTTP_REFERER'] );    
}


?>