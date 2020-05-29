{if isset($smarty.cookies.id)}
<img src="avatar/{$avatar}" width="50" haigh="50" align="left">&nbsp;&nbsp;Hi <strong>{$user}</strong><br />
	&nbsp;&nbsp;<a href="avatar.php">{$LAN_97}</a> - <a href="member.php">{$LAN_116}</a> - <a href="favorit.php">{$LAN_96}</a> - <a href="message.php">{$LAN_110}{if $nbmess gte 1}(<font color="red">{$nbmess}</font>){else}({$nbmess}){/if}</a> - <a href="logout.php">{$LAN_92}</a>
{else}
<form action="login.php" method="post">
          {$LAN_89} : <input type="text" name="TB_Nom_Utilisateur" />
          {$LAN_90} : <input type="password" name="TB_Mot_de_Passe" />
          <input type="submit" name="BT_Envoyer" value="{$LAN_91}" />
	 <p><a href="register.php">{$LAN_93}</a></p>
</form>
{/if}
