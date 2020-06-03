<?php
class template
{
	function top($title)
	{
	return "<html>
			<head>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
			<title>{$title}</title>
			</head>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"Themes/Default/styles.css\">
			<body>";
	}
	function smallbox($title,$text,$center = TRUE)
	{
	if ( $center == TRUE ) { $title = "<div align=\"center\"><strong>{$title}</strong></div>"; }
	elseif ( $center == FALSE ) { $title = "&nbsp;<strong>{$title}</strong>"; }
	return "<table width=\"800px\"  border=\"0\" cellspacing=\"0\" align=\"center\">
			  <tr>
				<td class=\"eb_forum\">{$title}</td>
			  </tr>
			  <tr>
				<td class=\"forum_footer\"><div align=\"center\">{$text}</div></td>
			  </tr>
			</table>";
	}
}