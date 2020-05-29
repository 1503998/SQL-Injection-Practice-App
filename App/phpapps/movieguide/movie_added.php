<?php
//////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////
?>
<div class="container">
    <?php
    include 'includes/dcon.php';
    $mdir = filter_input(INPUT_POST, 'Main_Dir');
    $mtitle = filter_input(INPUT_POST, 'Movie_Title');
    $ryear = filter_input(INPUT_POST, 'Release_Year');
    $genre = filter_input(INPUT_POST, 'Genre');
    $amov = addslashes(filter_input(INPUT_POST, 'About'));
    $cast = addslashes(filter_input(INPUT_POST, 'Cast'));
    $dirby = addslashes(filter_input(INPUT_POST, 'Directed_By'));
    $dadd = filter_input(INPUT_POST, 'Date_Added');
    $hseen = filter_input(INPUT_POST, 'Have_Seen');
    $afile = filter_input(INPUT_POST, 'Filename');

    $sql = "INSERT INTO Movie_List (`Main_Dir`,`Movie_Title`,`Release_Year`,`Genre`,`About`,`Cast`, `Directed_By`, `Date_Added`,`Have_Seen`,`Filename`) VALUES ('" . $mdir . "','" . $mtitle . "','" . $ryear . "','" . $genre . "','" . $amov . "','" . $cast . "','" . $dirby . "','" . $dadd . "','" . $hseen . "','" . $afile . "')";

    if (mysqli_query($con, $sql)) {
        echo '<div class="container" style="margin-top: -45px;">';
        echo '<br /><b>Main Directory:</b>  ' . $mdir;
        echo '<br /><b>Movie Title:</b>  ' . $mtitle;
        echo '<br /><b>Release Year:</b>  ' . $ryear;
        echo '<br /><b>Genre:</b>  ' . $genre;
        echo '<br /><b>About Movie:</b><br /> ' . $amov;
        echo '<br /><b>Cast:</b><br />  ' . $cast;
        echo '<br /><b>Directed By:</b><br />  ' . $dirby;
        echo '<br /><b>Date Added:</b>  ' . $dadd;
        echo '<br /><b>Have Seen?:</b>  ' . $hseen;
        echo '<br /><b>Filename:</b>  ' . $afile;
        echo '</div>';
        echo '<a href=index.php class="btn btn-outline-secondary btn-lg" style="width: 100%;">Home</a>';
    } else {
        print 'Error : (' . $mysqli->errno . ') Something is fucked up somewhere' . $mysqli->error . '<br>';
    }
    ?>
</div>