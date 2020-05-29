
<?php
////////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////

$con = mysqli_connect('localhost', 'root', 'hacklab2019', 'am_movieguide');
if (!$con) {
    echo "Error: " . mysqli_connect_error();
    exit();
}
?>
