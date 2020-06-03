<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{

?>

<div class="headermid">

<div class="header">
  <span class="headerfont">Software Information</span>
</div>

<div class="content">

<?php

$fileArray = array(array(), array());

function getFiles($checkdir){

	$counter = 0;

	if ($handle = opendir($checkdir))
	{
		// List all the files
		while (false !== ($file = readdir($handle)))
		{
			if(!is_dir($file)){ $fileArray[$checkdir]['path'] = $checkdir . $file; $counter++; }
		}

		closedir($handle);
	}

	//Hash found files
	$counter = 0;
	//foreach($fileArray as $value)
	//{
	//	foreach($value as $next)
	//	{
	//		$next = MD5_file($fileArray[$counter][0]);
	//		$counter++;
	//	}
	//}
}

getFiles('./');
getFiles('./ajax');
getFiles('./forms');
getFiles('./forms/ajax');
getFiles('./post');
getFiles('./validate');

print_r($fileArray);

echo '<b>Board Version:</b> ' . $board_version . '<br/><br/>';
echo "\n";

echo '<b>File Versions</b><br/><br/>';
echo "\n";

?>

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