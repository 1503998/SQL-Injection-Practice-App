<?php
//////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////

include 'includes/dcon.php';

$mid = filter_input(INPUT_GET, 'Movie_Id');
$sql = "SELECT * FROM `Movie_List` WHERE `Movie_Id` = '" . $mid . "'";
$query = mysqli_query($con, $sql);

if (!$con) {
    echo "Error: " . mysqli_connect_error();
    exit();
}

$row = mysqli_fetch_array($query);
$addplus = preg_replace('/\s+/', '+', $row['Movie_Title']);
$acast = str_replace("\r\n", "<br />", $row['Cast']);
$dirby = addslashes(filter_input(INPUT_POST, 'Directed_By'));
$maindir = str_replace(' ', '_', $row['Main_Dir']);
?>
<div class="container" style='margin-top: 95px;'>
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
    <br>
    <h1 style='margin-bottom: 50px;'><?php echo $row['Movie_Title'] ?> (<?php echo $row['Release_Year'] ?>) - <small><?php echo $row['Genre'] ?></small></h1>
    <div class="row container">
        <div class="col-md-4">
            <img class='img-fluid' src="movieims/<?php echo $row['Movie_Image'] ?>">
        </div>
        <div class="col-md-8">
            <p><?php echo $row['About'] ?></p>
            <p><b>Directed by:</b><br>
            <blockquote style="padding-left: 15px; margin-top: 6px;">                
                <?php
                //Seperate data into rows.
                $drow = list($dir) = preg_split("/[,]/", $row['Directed_By']);
                //Seperate and list the values from the row.
                if ($dir > "") {
                    echo '<ul>';
                    foreach ($drow as $director) {
                        list($DB) = preg_split("/[,]/", $director);
                        echo "<li><a style='color: #e33e32; font-size: 14px;' href='index.php?director=" . $DB . "'data-toggle='tooltip' data-placement='auto' title=' List all " . $DB . " movies. '>" . $DB . "</a></li>";
                    }
                    echo '</ul>';
                } else {
                    echo 'Not Added Yet.';
                }
                ?>
            </blockquote>
            <b>Main Cast:</b><br>
            <blockquote style="padding-left: 15px; margin-top: 6px;">
                <?php
                //Seperate data into rows.
                $c_row = list($all) = explode("\n", $row['Cast']);
                //Seperate and list the values from the row.
                echo '<ul>';
                foreach ($c_row as $cast) {
                    list($actor, $char) = preg_split("/[-]/", $cast);
                    if ($char == "") {
                        $char = "Not Added Yet.";
                    }
                    echo "<li><a style='color: #e33e32; font-size: 14px;' href='index.php?actor=" . $actor . "'data-toggle='tooltip' data-placement='auto' title=' List all " . $actor . " movies. '>" . $actor . "</a>&nbsp;&nbsp;:&nbsp;&nbsp;" . $char . " </li>";
                }
                echo '</ul>';
                ?>
            </blockquote>
            <br>
            <?php
            echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' Edit Movie Info ' href='index.php?pid=emov&Movie_Id=", $row['Movie_Id'], "'> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-button-plus.svg'></svg-icon> </a>";
            echo " - <a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' List all movies released this year. ' href='index.php?year=", $row['Release_Year'], "'> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-calendar-empty.svg'></svg-icon> ", $row['Release_Year'], "</a>";
            echo " - <a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' List all movies in this Genre. ' href='index.php?gterm=", $row['Genre'], "'> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-slide-show.svg'></svg-icon> ", $row['Genre'], "</a> - ";
            echo "<a type='button' class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' Search Google for more about ", $row['Movie_Title'], ". ' href='https://cse.google.com/cse?cx=partner-pub-6705286754327333:4261134323&q=$addplus&oq=$addplus&gs_l=partner.3...3229.8425.0.8924.0.0.0.0.0.0.0.0..0.0.gsnos%2Cn%3D13...0.5194j2893432j18..1ac.1.25.partner..0.0.0.#gsc.tab=0&gsc.q=$addtoo&gsc.page=1' target='_blank'> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-zoom-in.svg'></svg-icon> Search on Google.</a> - ";
            echo "<a type='button'  class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' List all movies in ths folder. ' href='index.php?md=", $row['Main_Dir'], "'> <svg-icon class='glyph-icon si-glyph'><src href='svg/si-glyph-folder.svg'></svg-icon>  ", $row['Main_Dir'], "</a>";
            echo " - <a type='button'  class='btn btn-outline-secondary btn-sm' data-toggle='tooltip' data-placement='auto' title=' Watch tis movie. ' href=media/", $maindir, "/", $row['Filename'], '> <svg-icon class="glyph-icon si-glyph"><src href="svg/si-glyph-triangle-right.svg"/></svg-icon> </a>';
            echo '<br><br><a href="#" onclick="history.go(-1);return false;" class="btn btn-outline-secondary btn-lg" style="width: 100%;">- Back -</a>';
            echo '</div>';
            ?>
        </div>
    </div>