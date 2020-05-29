{include file="header.tpl"}
<div class="left">
<h4>{$LAN_102}</h4><br />
<form action="register.php" method="post">
     <p>
          {$LAN_89} : <input type="text" name="TB_Nom_Utilisateur" />
     </p>
     <p>
         {$LAN_90}  : <input type="password" name="TB_Mot_de_Passe" />
     </p>
     <p>
          {$LAN_103} : <input type="password" name="TB_Confirmation_Mot_de_Passe" />
     </p>
     <p>
          {$LAN_104} : <input type="text" name="TB_Adresse_Email" />
     </p>
     <p>
          <input type="submit" name="BT_Envoyer" value="{$LAN_105}" />
     </p>
</form>
<br />{$message}
{include file="footer.tpl"}