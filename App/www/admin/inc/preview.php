<? 
/*
* Forum Preview
* Description: File will create a iFrame (includeFrame) and display the forum inside it
* Arthor: Arne-Christian Blystad
* Copyright: Copyrighed under the LGPL 2006
*/
## Define $template
$template = new template;
## Change title to "Evilboard Admin Control Panel - Forum Preview :: Powered by EvilBoard"
$template->top("EvilBoard Admin Control Panel - Forum Preview :: Powered by EvilBoard");
## Create the header with the dynamic drop down menus
$template->_header();
## Create the iFrame with the forum

return '<iframe src="../index.php" frameborder="0" height="500" width="100%">Your browser do not support iFrames, please download a browser wich support iFrames like <a href="http://www.getfirefox.com">Firefox</a></iframe>';
?>