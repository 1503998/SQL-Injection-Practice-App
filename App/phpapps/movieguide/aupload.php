<?php
//////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////
?>
<div class='container'>
    <?php
    $mdir = filter_input(INPUT_POST, 'Main_Dir');
    $mtitle = addslashes(filter_input(INPUT_POST, 'Movie_Title'));
    $ryear = filter_input(INPUT_POST, 'Release_Year');
    $genre = filter_input(INPUT_POST, 'Genre');
    $cast = addslashes(filter_input(INPUT_POST, 'Cast'));
    $dirby = addslashes(filter_input(INPUT_POST, 'Directed_By'));
    $dadd = filter_input(INPUT_POST, 'Date_Added');
    $hseen = filter_input(INPUT_POST, 'Have_Seen');
    $afile = addslashes(filter_input(INPUT_POST, 'Filename'));
    $amov = addslashes(filter_input(INPUT_POST, 'About'));

// we first include the upload class, as we will need it here to deal with the uploaded file
    require ('includes/class.upload.php');

    $dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'movieims');
    $dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);

    // ---------- IMAGE UPLOAD ----------
    // we create an instance of the class, giving as argument the PHP object
    // corresponding to the file field from the form
    // All the uploads are accessible from the PHP object $_FILES
    $handle = new Upload($_FILES['my_field']);

    // then we check if the file has been uploaded properly
    // in its *temporary* location in the server (often, it is /tmp)
    if ($handle->uploaded) {

        // yes, the file is on the server
        // below are some example settings which can be used if the uploaded file is an image.
        $handle->image_resize = true;
        $handle->image_ratio_x = true;
        $handle->image_y = 600;
        $handle->jpeg_quality = 90;
        $handle->png_compression = 9;
        $handle->file_safe_name = true;
        $handle->allowed = array('image/*');

        // now, we start the upload 'process'. That is, to copy the uploaded file
        // from its temporary location to the wanted location
        // It could be something like $handle->Process('/home/www/my_uploads/');
        $handle->Process($dir_dest);

        include 'includes/dcon.php';

        // we check if everything went OK
        if ($handle->processed) {
            // everything was fine !
            $mid = $handle->file_dst_name;
            $sql = "INSERT INTO Movie_List (`Main_Dir`,`Movie_Title`,`Release_Year`,`Genre`,`About`,`Cast`,`Directed_By`,`Date_Added`,`Have_Seen`,`Filename`,`Movie_Image`) VALUES ('" . $mdir . "','" . $mtitle . "','" . $ryear . "','" . $genre . "','" . $amov . "','" . $cast . "','" . $dirby . "','" . $dadd . "','" . $hseen . "','" . $afile . "','" . $mid . "')";

            if (mysqli_query($con, $sql)) {
                echo '<div align="center"><h1 style="border-bottom: 0px dotted #cc9999;">Success!</h1><br /></div>';
            } else {

                echo '<div align="center"><h2 style="color: red;">Error:</h2>Opps, there is a problem.</div>';
            }

            echo '<div class="result" align="center">';
            echo '  <img src="' . $dir_pics . '/' . $handle->file_dst_name . '" />';
            $info = getimagesize($handle->file_dst_pathname);
            echo '  <br />File: <a href="' . $dir_pics . '/' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a><br/>';
            echo '   (' . $info['mime'] . ' - ' . $info[0] . ' x ' . $info[1] . ' -  ' . round(filesize($handle->file_dst_pathname) / 256) / 4 . 'KB)';
            echo '<div class="container" style="text-align: left;">';
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
            echo '<br /><b>Movie Image:</b>  ' . $mim;
            echo '</div>';
            echo '</div>';
            echo '<a href=index.php class="btn btn-outline-secondary btn-lg" style="width: 100%;">Home</a>';
            echo '</div>';
        } else {
            // one error occured
            echo '<p class="result">';
            echo '  <b>File not uploaded to the wanted location</b><br />';
            echo '  Error: ' . $handle->error . '';
            echo '</p>';
        }
    }
    ?>
</div>