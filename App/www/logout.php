<?php

// Redirige l'utilisateur s'il n'est pas identifié
if(empty($_COOKIE["id"]))
{
     header("Location: index.php");
}
else
{
     
     // Suppression des cookies
     setcookie("id", "", time() - 1, "/");
     setcookie("user", "", time() - 1, "/");
     
     // Redirection de l'utilisateur
     header("Location: index.php");
     
}

?>