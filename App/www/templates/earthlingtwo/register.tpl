{include file="header.tpl"}
<div id="banner">{include file="ads.tpl"}</div>
			<div class="post">
				<h2 class="title"><a href="#">{$LAN_102}</a></h2>
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
				</div>
			<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
		<div id="sidebar">
			<ul>
			{include file="memberbar.tpl"}
					</ul>
				</li>
			</ul>
		</div>
		<!-- end #sidebar -->
{include file="footer.tpl"}