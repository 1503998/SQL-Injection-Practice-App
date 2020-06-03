<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{

?>

<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='1|or|2|Please enter an IP address or valid email address to ban.';
rules[1]='ip|required';
rules[2]='banemail|required';
</script>

<div class="headermid">

<div id="header_banform" class="header">
  <span class="headerfont">Ban List</span>
</div>

<div class="content">

<div id="ajaxStatus"></div>
<div id="errorsDiv"></div>

      <p align="center">Please enter an IP address, email address or both.</p>
      <form method="POST" name="banform" id="banform">
      <p align="center">IP Address
      <p align="center"><input id="ip" class="textbox" size="40"></p>
      <p align="center">Email Address
      <p align="center"><input id="banemail" class="textbox" size="40"></p>
      <p align="center">Ban Until
      <p align="center">
      <select id="banmonth" class="textbox">
      <option value="1">January</option>
      <option value="2">February</option>
      <option value="3">March</option>
      <option value="4">April</option>
      <option value="5">May</option>
      <option value="6">June</option>
      <option value="7">July</option>
      <option value="8">August</option>
      <option value="9">September</option>
      <option value="10">October</option>
      <option value="11">November</option>
      <option value="12">December</option>
      </select>
      <select id="banday" class="textbox">
      
<?php

for($i='1'; $i<='31'; $i++)
{
    echo '<option value="' . $i . '">' . $i . '</option>';
}

?>

      </select>
      <select id="banyear" class=textbox>
      <option value="2007">2007</option>
      <option value="2008">2008</option>
      <option value="2009">2009</option>
      <option value="2010">2010</option>
      <option value="2011">2011</option>
      </select>
      AT
      <select id="banhour" class="textbox">
      
<?php

for($i='0'; $i<='24'; $i++)
{
    echo '<option value="' . $i . '">' . $i . '</option>';
}

?>

      </select>
      :
      <select id="banminute" class="textbox">
      
<?php

for($i='0'; $i<='59'; $i++)
{
    echo '<option value="' . $i . '">' . $i . '</option>';
}

?>

      </select>
      :
      <select id="bansecond" class="textbox">

<?php

for($i='0'; $i<='59'; $i++)
{
    echo '<option value="' . $i . '">' . $i . '</option>';
}

?>

      </select>
      </p>
      <p align="center">Ban Reason
      <p align="center"><textarea id="banreason" class="textbox" rows="10" cols="50"></textarea></p>
      <p align="center"><button type="button" class="button" onClick="gbxAjaxReq('banform', true, 'forms/ajax/banmember.php', 'ip=' + document.getElementById('ip').value + '&banemail=' + document.getElementById('banemail').value + '&banmonth=' + document.getElementById('banmonth').value + '&banday=' + document.getElementById('banday').value + '&banyear=' + document.getElementById('banyear').value + '&banhour=' + document.getElementById('banhour').value + '&banminute=' + document.getElementById('banminute').value + '&bansecond=' + document.getElementById('bansecond').value + '&banreason=' + document.getElementById('banreason').value);">Ban Member</button></p>
      </form>

<table>
  <tr>
    <td width="6%">
      <span class="small"><b>Ban #</b></span>
    </td>
    <td width="20%">
      <span class="small"><b>IP Address</b></span>
    </td>
    <td width="30%">
      <span class="small"><b>Email Address</b></span>
    </td>
    <td width="20%">
      <span class="small"><b>Ban Date</b></span>
    </td>
    <td width="20%">
      <span class="small"><b>Banned Until</b></span>
    </td>
    <td width="4%">
      <span class="small"><b>Unban</b></span>
    </td>
  </tr>

<?php

    $banlist = mysql_query("SELECT * FROM " . $prefix . "banned") OR DIE("Gravity Board X was unable to retreive the information requested from the ban database: " . mysql_error());
    while($banned = mysql_fetch_assoc($banlist))
    {
        if($banned['banuntil'] < time())
        {
            $banexpired = true;
        }else
        {
            $banexpired = false;
        }

?>

  <tr>
    <td width="6%">
      <span class="small"><b><?php echo $banned['id']; ?></b></span>
    </td>
    <td width="20%">
      <span class="small"><b><?php echo $banned['ip']; ?></b></span>
    </td>
    <td width="20%">
      <span class="small"><b><?php echo $banned['email']; ?></b></span>
    </td>
    <td width="20%">
      <span class="small"><b>
      
<?php

        echo date ("m/d/Y h:i:s A",$banned['bandate']);

?>

      </b></span>
    </td>
    <td width="20%" class=row3>
      <span class="small"<?php if($banexpired == true){ echo ' style="color: #FF0000;"'; } ?>><b>

<?php

        echo date ("m/d/Y h:i:s A",$banned['banuntil']);

?>

      </b></span>
    </td>
    <td width="4%">
      <span class="small"><b><a href="index.php?action=unban&id=<?php echo $banned['id']; ?>">Unban</a></b></span>
    </td>
  </tr>

<?php

	}

?>

</table>

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
