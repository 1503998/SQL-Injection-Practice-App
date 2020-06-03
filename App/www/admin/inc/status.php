    <? $acp = new admin; ?>

	<div align="center"><div class="a_inf" align="left"><p><span id="b">&nbsp;Welcome<br>
    </span> &nbsp;Welcome to EvilBoard Admin panel, the menu is right on top of this document, <br>
    &nbsp;Feel free to report bugs in EvilBoard to arne.christian.b [@] gmail.com <br>
    &nbsp;To get back too this page click General on the menu above. <br>
    &nbsp;<span id="b">Forum Statistics</span>
    <br>
    <br>    
    <table width="69%"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="2%" rowspan="5" height="20">&nbsp;</td>
        <td width="36%" class="eb_header" style="border-right-width: 0;" height="20"><div align="center"><strong>Statistic</strong></div></td>
        <td width="62%" class="eb_header" height="20"><div align="center"><strong>Value</strong></div></td>
      </tr>
      <tr>
        <td class="forum_footer" style="border-right-width: 0;"><div align="right">Number of posts: </div></td>
        <td class="forum_footer">&nbsp;<? $acp->posts = $acp->posts(); echo "{$acp->posts}"; ?></td>
      </tr>
      <tr>
        <td class="forum_footer" style="border-right-width: 0;"><div align="right">Number of topics: </div></td>
        <td class="forum_footer">&nbsp;<? $acp->topics = $acp->topics(); echo "{$acp->topics}"; ?></td>
      </tr>
      <tr>
        <td class="forum_footer" style="border-right-width: 0;"><div align="right">Database Size: </div></td>
        <td class="forum_footer">&nbsp;<? $acp->dbsize = $acp->dbsize(); echo "{$acp->dbsize}"; ?></td>
      </tr>
      <tr>
        <td class="forum_footer" style="border-right-width: 0;"><div align="right">Registered users: </div></td>
        <td class="forum_footer">&nbsp;<? $acp->ruser = $acp->ruser(); echo "{$acp->ruser}"; ?></td>
      </tr>
    </table>
    <br>
    <span id="b">&nbsp;Forum Version</span><strong><br>
<? $acp->version = $acp->version(); echo "{$acp->version}"; ?></div></div>
