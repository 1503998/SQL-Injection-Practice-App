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
    echo "<div class='col-md-3' style='display: block;  border-radius: 6px;'>";
    $addplus = preg_replace('/\s+/', '+', $row['Movie_Title']);
    $maindir = str_replace(' ', '_', $row['Main_Dir']);
    echo '<div class="tbuttonbk" align="center" style="display: inline-block; height: 100%; width: 100%; border-radius: 6px;">';
    echo "<a class='tbuttonsm' data-toggle='tooltip' data-placement='auto' title=' Search for more about ",   $row['Movie_Title']   ," on Google. ' href='https://cse.google.com/cse?cx=partner-pub-6705286754327333:4261134323&q=$addplus&oq=$addplus&gs_l=partner.3...3229.8425.0.8924.0.0.0.0.0.0.0.0..0.0.gsnos%2Cn%3D13...0.5194j2893432j18..1ac.1.25.partner..0.0.0.#gsc.tab=0&gsc.q=$addtoo&gsc.page=1' target='_blank'><b>  ", $row['Movie_Title']," </b>  </a>";
    if ($row['Movie_Image'] == '') {
        echo "<div style='height: 300px; margin-bottom: 11px;' align='center'><br><a data-toggle='tooltip' data-placement='auto' title=' View the movie details. ' href='index.php?pid=minfo&Movie_Id=", $row['Movie_Id'], "'><img class='img-fluid' style='padding: 20px; margin-left: 8px;' src='svg/si-glyph-circle-error.svg'></a><br>No Image.</div>";
    } else {
        echo "<a class='imtrans' data-toggle='tooltip' data-placement='auto' title='", $row['Movie_Title'], " movie details.'  href='index.php?pid=minfo&Movie_Id=", $row['Movie_Id'], "'><img class='img-fluid coverim' src='movieims/", $row['Movie_Image'], "' style='margin-bottom: 16px;'></a><br>";
    }
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' Edit Movie Info ' href='index.php?pid=emov&Movie_Id=", $row['Movie_Id'], "'><svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-button-plus.svg'></svg-icon></a>";
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' List all movies in this Genre. ' href='index.php?gterm=", $row['Genre'], "'><svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-slide-show.svg'></svg-icon> ", $row['Genre'], "</a><br>";
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' List all movies released this year. ' href='index.php?year=", $row['Release_Year'], "'><svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-calendar-empty.svg'></svg-icon> ", $row['Release_Year'], "</a>";
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' List all movies in ths folder. ' href='index.php?md=", $row['Main_Dir'], "'><svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-folder.svg'></svg-icon>  ", $row['Main_Dir'], "</a>";
    echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' View the movie details. ' href='index.php?pid=minfo&Movie_Id=", $row['Movie_Id'], "'><svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-circle-info.svg'></svg-icon></a><br>";
    echo "<a type='button' style='width: 100%' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' Watch tis movie. ' href=media/", $maindir, "/", $row['Filename'], "><svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-triangle-right.svg'/></svg-icon> Play Movie</a>";
    echo "</div>";
    echo "</div>";
    if ($i == 3) {
        $i = 0;
        echo "</div>";
    } else {
        $i++;
    }
}
echo "</div>";
echo "</div>";
?>