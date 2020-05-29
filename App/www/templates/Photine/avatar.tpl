{include file="header.tpl"}
<div class="left">
<h4>{$LAN_97}</h4><br />
<u>{$LAN_98}:</u><br />
{$LAN_101}
<p>&nbsp;</p>
<form method="POST" action="avatar.php" enctype="multipart/form-data">
     <!-- On limite le fichier à 100Ko -->
     <input type="hidden" name="MAX_FILE_SIZE" value="100000">
     Fichier : <input type="file" name="avatar">
     <input type="submit" name="envoyer" value="{$LAN_99}">
</form>
{$erreur}
</div>
{include file="footer.tpl"}