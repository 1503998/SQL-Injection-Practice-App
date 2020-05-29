<li>
					<h2>Member area</h2>
{if isset($smarty.cookies.id)}
<img src="avatar/{$avatar}" width="50" haigh="50" align="left">&nbsp;&nbsp;Hi <strong>{$user}</strong><br />
<p>
	<ul>
		<li><a href="avatar.php">{$LAN_97}</a></li>
		<li><a href="member.php">{$LAN_116}</a></li>
		<li><a href="favorit.php">{$LAN_96}</a></li>
		<li><a href="message.php">{$LAN_110}{if $nbmess gte 1}(<font color="red">{$nbmess}</font>){else}({$nbmess}){/if}</a></li>
		<li><a href="logout.php">{$LAN_92}</a></li>
	</ul></p>
{else}
<form action="login.php" method="post">
          {$LAN_89} : <input type="text" name="TB_Nom_Utilisateur" /><br />
          {$LAN_90} : <input type="password" name="TB_Mot_de_Passe" /><br />
          <input type="submit" name="BT_Envoyer" value="{$LAN_91}" /><br />
	 <p><a href="register.php">{$LAN_93}</a></p>
</form>
{/if}
</li>
