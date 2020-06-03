<? session_start(); ?>
<? 
if(!session_is_registered('first_name')){
	header("Refresh: 5; index.php");
	}
include("include/header.php"); ?>
<?
echo '<br>';
if(!isset($_REQUEST['logmeout'])){
    echo '<table width="100%"  border="0" cellspacing="0">
  <tr>
    <td class="eb_menu1"><div align="center">Logout</div></td>
  </tr>
  <tr>
    <td class="forum_footer"><div align="center">Are you sure you want to logout? <br>
        <span style="color: #00FF00; font-weight: bold;"><a href="?act=logout&logmeout=true">Yes</a></span> | <span style="color: #FF0000; font-weight: bold;"><a href="javascript:history.back()">No</a></span>    </div></td>
  </tr>
</table>';
} else {
    session_destroy();
    if(!session_is_registered('first_name')){
        echo '<table width="100%" border="0" cellspacing="0" align="center">
  <tr>
    <td height="1" class="eb_menu1"><div align="center">Logout</div></td>
  </tr>
  <tr>
    <td class="eb_footer_orgin"><div align="center">You have been logged out.</div></td>
  </tr>
</table>';
    }
}
?>
<br>
<? include("include/footer.php"); ?>