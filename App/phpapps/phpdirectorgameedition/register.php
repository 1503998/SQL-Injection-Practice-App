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
if(isset($_COOKIE["id"]))
{
     header("Location: index.php");
}
else
{
     if(isset($_POST["BT_Envoyer"]))
     {
          
          // Vérification de la validité des champs
          if(!ereg("^[A-Za-z0-9_]{4,20}$", $_POST["TB_Nom_Utilisateur"]))
          {
               $message = "You login must be longer than 4 and less thab 20 character<br />\n";
          
          }
          elseif(!ereg("^[A-Za-z0-9]{4,}$", $_POST["TB_Mot_de_Passe"]))
          {
               $message = "Your password must be longer than 4 character";
          }
          elseif($_POST["TB_Mot_de_Passe"] != $_POST["TB_Confirmation_Mot_de_Passe"])
          {
               $message = "Password don't match";
          }
          elseif(!ereg("^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]{2,}[.][a-zA-Z]{2,4}$",
               $_POST["TB_Adresse_Email"]))
          {
               $message = "Your E-mail is not valid";
          }
          else
          {

               $result = mysql_query("
                    SELECT user
                         , email
                    FROM pp_user
                    WHERE user = '" . $_POST["TB_Nom_Utilisateur"] . "'
                    OR email = '" . $_POST["TB_Adresse_Email"] . "'
               ");
               
               // Si une erreur survient
               if(!$result)
               {
                    $message = "Internal error";
               }
               else
               {
                    
                    // Si un enregistrement est trouvé
                    if(mysql_num_rows($result) > 0)
                    {
                         
                         while($row = mysql_fetch_array($result))
                         {
                              
                              if($_POST["TB_Nom_Utilisateur"] == $row["Nom_Utilisateur"])
                              {
                                   $message = "" . $_POST["TB_Nom_Utilisateur"];
                                   $message .= "os already used";
                              }
                              elseif($_POST["TB_Adresse_Email"] == $row["email"])
                              {
                                   $message = "This E-mail : " . $_POST["TB_Adresse_Email"];
                                   $message .= "is already used";
                              }
                              
                         }
                         
                    }
                    else
                    {
                         
                         
                         // Création du compte utilisateur
                         $result = mysql_query("
                              INSERT INTO pp_user(user,pass,email,register)
                              VALUES('$_POST[TB_Nom_Utilisateur]','".md5($_POST['TB_Mot_de_Passe'])."','$_POST[TB_Adresse_Email]',CURRENT_DATE())
                         ");
                         
                         // Si une erreur survient
                         if(!$result)
                         {
                              $message = "An internal error has occured";
                         }
                         else
                         {
                                   
                                   // Message de confirmation
                                   $message = "Congratulations ".$_POST['TB_Nom_Utilisateur'].", your account has been created, you can now start to play<br />\n";
                                   

                                   
                              }
                              
                         }
                         
                    }
                    
               }
               
          }
          
     }
     

$smarty->assign('message', $message);			
$smarty->display('register.tpl');
	


?>