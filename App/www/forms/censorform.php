<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{
    $censorquery = mysql_query("SELECT * FROM " . $prefix . "censor WHERE id = '1'") OR DIE("Gravity Board X was unable to retrieve the data you requested: " . mysql_error());
    while($censor = mysql_fetch_assoc($censorquery))
    {
        if($censor['enabled'] == '0')
        {
            $checked = '';
        }elseif($censor['enabled'] == '1')
        {
		      $checked = 'checked';
	     }
	     $wordlist = $censor['wordlist'];
    }

?>

<div class="headermid">

<div class="header">
  <span class="headerfont">Set Censored Words</span>
</div>

<div class="content">

<div id="ajaxStatus"></div>
<div id="errorsDiv"></div>

<table>
  <tr>
    <td>
	   Enter the words to censor below, separated by commas with no spaces:<br><textarea class="textbox" rows="10" cols="75" id="wordlist" name="wordlist"><?php echo $wordlist; ?></textarea>
	   <br><input type="checkbox" name="enabled" id="enabled" value="checked" <?php echo $checked; ?>>Enable Word Censoring
    </td>
  </tr>
  <tr>
    <td>
      <p align="center"><button type="button" class="button" onClick="gbxAjaxReq('', false, 'forms/ajax/censor.php', 'wordlist=' + document.getElementById('wordlist').value + '&enabled=' + document.getElementById('enabled').checked);">Save Changes</button>
    </td>
  </tr>
</table>

</div>

</div>

<div class="headerbot">
</div>

</div>

<?php

}else
{
    accessdenied();
}

?>
