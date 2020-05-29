<?php

//////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////

echo "<div class='container-fluid' style='margin-top: 15px;'>";
echo "<h1><span style='font-size: 90%; color: #999;'><span class='highlight'> ( ", $md, $lterm, $cterm, $yterm, $mterm, $ttlm, $actorname, $directorname, " ) </span> Movies: <small>( ", $rowcount, " ) Total</small></span></h1>";

while ($row = mysqli_fetch_array($query)) {

    if ($i == 0) {
        echo "<div class='row' style='margin-left: 5px; margin-top: 20px; width: 100%;'>";
    }
    echo "<div class='col-md-6'>";
    $addplus = preg_replace('/\s+/', '+', $row['Movie_Title']);
    $maindir = str_replace(' ', '_', $row['Main_Dir']);
    echo '<div class="tbuttonbk">';
    echo "<div class='row'>";
    echo "<div class='col-md-1'>";
    if ($row['Movie_Image'] == '') {
        echo "<img class='img' style='padding: 4px; width: 50px;' src='svg/si-glyph-easal.svg'>";
    } else {
        echo "<a class='imtrans' data-toggle='tooltip' data-placement='auto' title='", $row['Movie_Title'], " movie details.'  href='index.php?pid=minfo&Movie_Id=", $row['Movie_Id'], "'><img class='img' src='movieims/", $row['Movie_Image'], "' style='width: 50px;'></a>";
    }
    echo "</div>";
    echo "<div class='col-md-11'  style='padding-left: 20px;'>";
    echo "<a style='padding-left: 12px;' class='tbuttonsm' data-toggle='tooltip' data-placement='auto' title=' View Movie Info on Google. ' href='https://www.google.com/search?q=$addplus' target='_blank'><b>  ", $row['Movie_Title'], " </b>  </a>";
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' Edit Movie Info ' href='index.php?pid=emov&Movie_Id=", $row['Movie_Id'], "'> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-button-plus.svg'></svg-icon> </a>";
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' List all movies released this year. ' href='index.php?year=", $row['Release_Year'], "'> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-calendar-empty.svg'></svg-icon> ", $row['Release_Year'], "</a>";
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' List all movies in this Genre. ' href='index.php?gterm=", $row['Genre'], "'> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-slide-show.svg'></svg-icon> ", $row['Genre'], "</a>";
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' View the movie details. ' href='index.php?pid=minfo&Movie_Id=", $row['Movie_Id'], "'> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-circle-info.svg'></svg-icon> </a>";
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' List all movies in ths folder. ' href='index.php?md=", $row['Main_Dir'], "'> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-folder.svg'></svg-icon>  ", $row['Main_Dir'], "</a>";
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' Watch tis movie. ' href=media/", $maindir, "/", $row['Filename'], "> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-triangle-right.svg'/></svg-icon> </a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    if ($i == 1) {
        $i = 0;
        echo "</div>";
    } else {
        $i++;
    }
}
echo "</div>";
echo "</div>";
?>