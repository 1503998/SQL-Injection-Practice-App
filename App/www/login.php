<?php
/*
+ ----------------------------------------------------------------------------+
|     PHPDirector.
|		$License: GNU General Public License
|		$Website: phpdirector.co.uk
+----------------------------------------------------------------------------+
*/
require('header.php');
include 'db.php';
 if(isset($_COOKIE["ID_UTILISATEUR"]))
{
     header("Location: index.php");
}
else
{
     if(isset($_POST["BT_Envoyer"]))
     {
          
               
               
			   
               
               // S�lection de l'utilisateur concern�
               $result = mysql_query("
                    SELECT id, user, pass
                    FROM pp_user
                    WHERE user = '" . $_POST["TB_Nom_Utilisateur"] . "'
               ");
               
               // Si une erreur survient
               if(!$result)
               {
                    $message = "Log in error";
               }
               else
               {
                    
                    // Si aucun utilisateur n'a �t� trouv�
                    if(mysql_num_rows($result) == 0)
                    {
                         $message = "" . $_POST["TB_Nom_Utilisateur"] . " is not a member of this site";
                    }
                    else
                    {
                         
                         // R�cup�ration des donn�es
                         $row = mysql_fetch_array($result);
                         
                              
                              // V�rification du mot de passe
                              if(md5($_POST["TB_Mot_de_Passe"]) != $row["pass"])
                              {
                                   $message = "Password error";
                              }
                              else
                              {
                                   
                                   // D�finition du temps d'expiration des cookies
                                   $expiration =
                                        empty($_POST["CB_Connexion_Automatique"]) ? 0 : time() + 90 * 24 * 60 * 60;
                                   
                                   // Cr�ation des cookies
                                   setcookie("id", $row["id"], $expiration, "/");
                                   setcookie("user", $row["user"], $expiration, "/");
                                  
                                   
                                   // Redirection de l'utilisateur
                                   header("Location: index.php");
                                   
                              }
                              
                         }
                         
                    }
                    
               }
               
          }
          
$smarty->assign('message', $message);			
$smarty->display('login.tpl');
	


?>