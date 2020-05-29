<?php
//////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////
// Choose Layout: 1 or 2
$lay_out = "2";
// Choose Style: 1 or 2
$lay_style = "1";
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-110680549-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', 'UA-110680549-1');
        </script>
        <meta charset="UTF-8">
        <title>Movie Guide v2.0</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Dosis|Righteous|Comfortaa|Josefin+Sans|Gruppo" rel="stylesheet"> 
        <link rel="stylesheet" href="css/movies<?php echo $lay_style; ?>.css">
        <script src="sprite/iconwc.js"></script>
    </head>
    <body>
        <?php
        include 'includes/header_inc.php';
        $pn = filter_input(INPUT_GET, 'pid');
        if ($pn == "amov") {
            ?>
            <div class="container" style="margin-top: 80px;">
                <h1>Add a New Movie</h1>
            </div>
            <?php
            include 'add_movie.php';
        } elseif ($pn == "emov") {
            ?>
            <div class="container" style="margin-top: 80px;">
                <h1>Edit Movie</h1>
            </div>
            <?php
            include 'edit_movie.php';
        } elseif ($pn == "umov") {
            ?>
            <div class="container" style="margin-top: 80px;">
                <h1>Movie Updated</h1>
            </div>
            <?php
            $umovim = basename($_FILES["my_field"]["name"]);
            if ($umovim > '') {
                include 'eupload.php';
            } else {
                include 'update_movie.php';
            }
        } elseif ($pn == "inmov") {
            ?>
            <div class="container" style="margin-top: 80px;">
                <h1>New Movie Added</h1>
            </div>
            <?php
            $emovim = basename($_FILES["my_field"]["name"]);
            if ($emovim > '') {
                include 'aupload.php';
            } else {
                include 'movie_added.php';
            }
        } elseif ($pn == "minfo") {
            include 'includes/movie_info_inc.php';
        } else {
            echo '<div class="container" style="margin-top: 80px;">';
            include 'includes/movie_folders_inc.php';
            include 'layout.php';
            echo '</div>';
        }
        ?>

        <?php include 'includes/footer_inc.php'; ?>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
        <script src="js/bootstrap.min.js" ></script>
    </body>
</html>