<?php
	if(!file_exists("db.php"))
	{?>
		<h2>Database configuration file admin/db.php does not exists</h2>
		<p style="font-family:'Courier New', Courier, monospace;font-size:22px">
			Click  <a href="installer.php?step=1">&gt;&gt;&gt;here</a> to create it</p>
	<?php 
	exit;
	}
?>
<?php
include 'authenticate.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Scratch Administrator</title>
</head>

<frameset rows="*" cols="280,*" framespacing="0" frameborder="1" >
  <frame src="leftframe.php" name="leftFrame" scrolling="No"  id="leftFrame" title="leftFrame" frameborder="1"  />
  <frame src="rightframe.php" name="mainFrame" id="mainFrame" title="mainFrame" frameborder="1"  />
</frameset>
<noframes><body>
</body>
</noframes></html>
