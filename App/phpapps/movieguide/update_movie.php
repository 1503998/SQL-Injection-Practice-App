<div class="container">
    <?php
    include 'includes/dcon.php';
    $mid = addslashes(filter_input(INPUT_POST, 'Movie_Id'));
    $mdir = addslashes(filter_input(INPUT_POST, 'Main_Dir'));
    $mtitle = addslashes(filter_input(INPUT_POST, 'Movie_Title'));
    $ryear = addslashes(filter_input(INPUT_POST, 'Release_Year'));
    $genre = addslashes(filter_input(INPUT_POST, 'Genre'));
    $cast = addslashes(filter_input(INPUT_POST, 'Cast'));
    $dirby = addslashes(filter_input(INPUT_POST, 'Directed_By'));
    $dadd = addslashes(filter_input(INPUT_POST, 'Date_Added'));
    $hseen = addslashes(filter_input(INPUT_POST, 'Have_Seen'));
    $afile = addslashes(filter_input(INPUT_POST, 'Filename'));
    $amov = addslashes(filter_input(INPUT_POST, 'About'));

    $sql = "UPDATE `Movie_List` Set `Main_Dir` = '$mdir', `Movie_Title` = '$mtitle', `Release_Year` = '$ryear', `Genre` = '$genre', `About` = '$amov',  `Cast` = '$cast', `Directed_By` = '$dirby', `Date_Added` = '$dadd', `Have_Seen` = '$hseen', `Filename` = '$afile' WHERE `Movie_Id` = '$mid'";

    if (mysqli_query($con, $sql)) {
        echo '<div class="container" style="margin-top: -45px;">';
        echo '<br /><b>Movie ID:</b>  ' . $mid;
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
        echo '<br /><br /><a href="index.php" class="btn btn-outline-secondary btn-lg" style="width: 100%;">Home</a>';
        echo '</div>';
    } else {
        print 'Error : (' . $mysqli->errno . ') Something is fucked up somewhere' . $mysqli->error . '<br>';
    }
    ?>
</div>