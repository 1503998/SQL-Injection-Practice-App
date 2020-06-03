<?
		/*
		* EvilBoard :: Configuration
		* Description: Change settings in EvilBoard
		* Arthor: Arne-Christian Blystad
		* Copyright: Copyrighted under the LGPL 2006
		*/
		## Define $template
		$template = new template;
		## Change title to "Evilboard Admin Control Panel - Forum Configuration :: Powered by EvilBoard"
		$template->top("EvilBoard Admin Control Panel - Forum Configuration :: Powered by EvilBoard");
		## Create the header with the dynamic drop down menus
		$template->_header();
		$db = new db;
		$db->connect();
		$conf->query = $db->query("SELECT * FROM eb_settings LIMIT 0 , 1;");
		$conf->narray = mysql_fetch_array($conf->query);
		if ( isset($_POST['Submit'])) {
			$f_name = $_POST['xm'] ;
			$f_domain = $_POST['d_m'];
			$f_reg = $_POST['r_m'];
			$f_path = $_POST['path'];
			$db->query("UPDATE `eb_settings` SET `forum_name` = '{$f_name}', `register` = '{$f_reg}', `e_domain` = '{$f_domain}', `path` = '{$f_path}'");
			$conf->r_t = "&nbsp;<span id=\"b\">Configuration updated</span>";
			$conf->r_txt = "<br>&nbsp;Configuration has been updated, click <a href=\"index.php?code=03\">here</a> to return to Configuration";
			return $conf->r_t . $conf->r_txt;
			
		}
		if ( $conf->narray['register'] == "1" ) {
		$conf->check_box = "<p>
            <label>
            <span class=\"eb_txt style1\">
  <input type=\"radio\" name=\"r_m\" value=\"0\">
  Mail Validation(Recommended)</span></label>
            <span class=\"eb_txt style1\"><br>
            <label>
            <input type=\"radio\" name=\"r_m\" value=\"1\" checked>
            Automaticly Validation </label>
            </span>
            <br>";
		}
		if ( $conf->narray['register'] == "0" ) {
		$conf->check_box = "<p>
            <label>
            <span class=\"eb_txt style1\">
  <input type=\"radio\" name=\"r_m\" value=\"0\" checked>
  Mail Validation(Recommended)</span></label>
            <span class=\"eb_txt style1\"><br>
            <label>
            <input type=\"radio\" name=\"r_m\" value=\"1\">
            Automaticly Validation </label>
            </span>
            <br>";
		}
		$t->b = "<style type=\"text/css\">
<!--
.style1 {color: #000000}
-->
</style>";
		$t->b .= "
<style type=\"text/css\">
<!--
.style1 {font-weight: bold}
-->
</style>
<form name=\"\"method=\"post\"><span id=\"b\">&nbsp;EvilBoard Forum Configuration</span> <br>
      <table width=\"100%\"  border=\"0\" cellspacing=\"0\">
        <tr>
          <td width=\"35%\" class=\"style1 eb_txt\"><strong>&nbsp;Forum Name:</strong></td>
          <td width=\"65%\"><input type=\"xm\" name=\"xm\" style=\"width:200px\" value=\"{$conf->narray[forum_name]}\"></td>
        </tr>
        <tr>
          <td class=\"style1 eb_txt\"><strong>&nbsp;Domain Name: </strong></td>
          <td><input type=\"d_m\" name=\"d_m\" style=\"width:200px\" value=\"{$conf->narray[e_domain]}\"></td>
        </tr>
        <tr>
          <td class=\"style1 eb_txt\"><strong>&nbsp;Forum Path:<br>
            </strong>&nbsp;Where the evilBoard forum are located </td>
          <td><input type=\"path\" name=\"path\" style=\"width:200px\" value=\"{$conf->narray[path]}\"></td>
        </tr>
        <tr>
          <td class=\"style1 eb_txt\"><strong>&nbsp;Register method: </strong></td>
          <td>
		  {$conf->check_box}
          </p></td>
        </tr>
        <tr>
          <td class=\"eb_txt\"><strong>&nbsp;Topics per page:</strong></td>
          <td><input type=\"text\" name=\"topic_per_page\" style=\"width:200px\" value=\"{$conf->narray[topic_per_page]}\"></td>
        </tr>
        <tr>
          <td class=\"eb_txt\"><strong>&nbsp;Posts per page: </strong></td>
          <td><input type=\"text\" name=\"topic_per_page\" style=\"width:200px\" value=\"{$conf->narray[post_per_page]}\"></td>
        </tr>
        <tr>
          <td class=\"eb_txt\"><strong>&nbsp;Members per page: </strong></td>
          <td><input type=\"text\" name=\"topic_per_page\" style=\"width:200px\" value=\"{$conf->narray[members_per_page]}\"></td>
        </tr>
        <tr>
          <td class=\"style1 eb_txt\">&nbsp;Badword replace: </td>
          <td><input type=\"text\" name=\"topic_per_page\" style=\"width:200px\" value=\"{$conf->narray[badword_replace]}\" maxlength=\"1\" /></td>
        </tr>
        <tr>
          <td colspan=\"2\" class=\"style1 eb_txt\"><div align=\"center\">
            <input type=\"submit\" name=\"Submit\" value=\"Update Settings\">
          </div></td>
        </tr>
      </table>
</form> <br>";
return $t->b;
?>