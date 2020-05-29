<?php
$outfile = $_GET['outfile'];
header("Content-Type: text/plain");
header("Content-length: " . filesize("../upload/" . $outfile));
header("Content-Disposition: attachment; filename=" . $outfile);
readfile("../upload/" . $outfile);
?>