<?php
//////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////

include 'includes/dcon.php';

// Some Query
$sql = "SELECT * FROM `folders` ORDER BY `folder_id`";
$query = mysqli_query($con, $sql);

echo "<div class='container' style='margin-top: 70px;margin-bottom: -35px;max-width: 900px;' align='center'>";
while ($row = mysqli_fetch_array($query)) {
    $addplus = preg_replace('/\s+/', '+', $row['folder_name']);
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' style='margin: 4px 3px;' data-toggle='tooltip' data-placement='auto' title=' List all movies in ths folder. ' href='index.php?md=", $row['folder_name'], "'><svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-folder.svg'></svg-icon>   ", $row['folder_name'], "</a>";
}
echo "<a type='button' class='btn btn-outline-secondary btn-sm' style='margin: 4px 3px;' data-toggle='tooltip' data-placement='auto' title=' List all movies in ths folder. ' href='index.php'><svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-folder.svg'></svg-icon>   ALL Movies</a>";
echo "</div>";
// Close connection
mysqli_close($con);
?>
<br>
<div align="center"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- MovieGuide -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-6705286754327333"
         data-ad-slot="5654107044"
         data-ad-format="auto"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script></div>