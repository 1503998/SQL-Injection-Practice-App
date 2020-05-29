<?php
include 'includes/dcon.php';

//Total Movies to show per page.
$numshow = "32";

$perpage = $numshow;
if (isset($_GET["page"])) {
    $page = intval($_GET["page"]);
} else {
    $page = 1;
}
$calc = $perpage * $page;
$start = $calc - $perpage;

//Get the passed variables.
$mterm = filter_input(INPUT_POST, 'tterm');
$cterm = filter_input(INPUT_POST, 'gterm');
$lterm = filter_input(INPUT_GET, 'gterm');
$yterm = filter_input(INPUT_GET, 'year');
$md = filter_input(INPUT_GET, 'md');
$actorname = filter_input(INPUT_GET, 'actor');
$directorname = filter_input(INPUT_GET, 'director');

// Search Query
if ($mterm > "") {
    $sql = "SELECT * FROM `Movie_List` WHERE `Movie_Title` LIKE '%" . $mterm . "%' ORDER BY `Movie_Title` ASC Limit $start, $perpage";
} elseif ($cterm > "") {
    $sql = "SELECT * FROM `Movie_List` WHERE `Genre` LIKE '%" . $cterm . "%' ORDER BY `Movie_Title` ASC Limit $start, $perpage";
} elseif ($lterm > "") {
    $sql = "SELECT * FROM `Movie_List` WHERE `Genre` LIKE '" . $lterm . "' ORDER BY `Movie_Title` ASC Limit $start, $perpage";
} elseif ($yterm > "") {
    $sql = "SELECT * FROM `Movie_List` WHERE `Release_Year` LIKE '%" . $yterm . "%' ORDER BY `Movie_Title` ASC Limit $start, $perpage";
} elseif ($md > "") {
    $sql = "SELECT * FROM `Movie_List` WHERE `Main_Dir` LIKE '" . $md . "' ORDER BY `Movie_Title` ASC Limit $start, $perpage";
} elseif ($actorname > "") {
    $sql = "SELECT * FROM `Movie_List` WHERE `Cast` LIKE '%" . $actorname . "%' ORDER BY `Movie_Title` ASC Limit $start, $perpage";
} elseif ($directorname > "") {
    $sql = "SELECT * FROM `Movie_List` WHERE `Directed_By` LIKE '%" . $directorname . "%' ORDER BY `Movie_Title` ASC Limit $start, $perpage";
} else {
    $sql = "SELECT * FROM `Movie_List` ORDER BY `Movie_Title`, `Movie_Image` DESC Limit $start, $perpage";
}
$query = mysqli_query($con, $sql);

// Getting Movie Totals
$sql2 = "SELECT COUNT(*) FROM `Movie_List`";
$query2 = mysqli_query($con, $sql2);

//using mysql to find out total records per search result
if ($mterm > "") {
    $ttl = mysqli_query($con, "select * from `Movie_List` WHERE `Movie_Title` LIKE '%" . $mterm . "%'");
} elseif ($cterm > "") {
    $ttl = mysqli_query($con, "select * from `Movie_List` WHERE `Genre` LIKE '%" . $cterm . "%'");
} elseif ($lterm > "") {
    $ttl = mysqli_query($con, "select * from `Movie_List` WHERE `Genre` LIKE '" . $lterm . "'");
} elseif ($yterm > "") {
    $ttl = mysqli_query($con, "select * from `Movie_List`  WHERE `Release_Year` LIKE '%" . $yterm . "%'");
} elseif ($md > "") {
    $ttl = mysqli_query($con, "select * from `Movie_List` WHERE `Main_Dir` LIKE '" . $md . "'");
} elseif ($actorname > "") {
    $ttl = mysqli_query($con, "select * from `Movie_List` WHERE `Cast` LIKE '%" . $actorname . "%'");
} elseif ($directorname > "") {
    $ttl = mysqli_query($con, "select * from `Movie_List` WHERE `Directed_By` LIKE '%" . $directorname . "%'");
} else {
    $ttl = mysqli_query($con, "select * from `Movie_List`");
    $ttlm = "All";
}
$rowcount = mysqli_num_rows($ttl);

include 'layouts/' . $lay_out . '_inc.php';
?>

<div class="container">
    <div class="row" style="margin: -0px 50px;">
        <div class="col-sm-12"><center>
                <?php
                // Page navagation
                if (isset($page)) {

                    if ($mterm > "") {
                        $result = mysqli_query($con, "select Count(*) As Total from Movie_List WHERE `Movie_Title` LIKE '%" . $mterm . "%'");
                    } elseif ($cterm > "") {
                        $result = mysqli_query($con, "select Count(*) As Total from Movie_List WHERE `Genre` LIKE '%" . $cterm . "%'");
                    } elseif ($lterm > "") {
                        $result = mysqli_query($con, "select Count(*) As Total from Movie_List WHERE `Genre` LIKE '" . $lterm . "'");
                    } elseif ($yterm > "") {
                        $result = mysqli_query($con, "select Count(*) As Total from Movie_List  WHERE `Release_Year` LIKE '%" . $yterm . "%'");
                    } elseif ($md > "") {
                        $result = mysqli_query($con, "select Count(*) As Total from Movie_List WHERE `Main_Dir` LIKE '" . $md . "'");
                    } elseif ($actorname > "") {
                        $result = mysqli_query($con, "select Count(*) As Total from Movie_List WHERE `Cast` LIKE '%" . $actorname . "%'");
                    } elseif ($directorname > "") {
                        $result = mysqli_query($con, "select Count(*) As Total from Movie_List WHERE `Directed_By` LIKE '" . $directorname . "'");
                    } else {
                        $result = mysqli_query($con, "select Count(*) As Total from Movie_List");
                    }

                    $rows = mysqli_num_rows($result);

                    if ($rows) {
                        $rs = mysqli_fetch_assoc($result);
                        $total = $rs["Total"];
                    }

                    $totalPages = ceil($total / $perpage);

                    if ($page <= 1) {
                        echo "<span id='page_links' style='font-weight: bold; color: #ddd; padding: 4px; margin: 4px 2px;'> Prev </span>";
                    } else {
                        $j = $page - 1;
                        echo "<span> <a id='page_a_link' href='index.php?tterm=$mterm&gterm=$cterm&gterm=$lterm&year=$yterm&md=$md&actor=$actorname&director=$directorname&page=$j'>Prev</a> </span>";
                    }
                    for ($i = 1; $i <= $totalPages; $i++) {

                        if ($i <> $page) {
                            echo "<span> <a id='page_a_link' href='index.php?tterm=$mterm&gterm=$cterm&gterm=$lterm&year=$yterm&md=$md&actor=$actorname&director=$directorname&page=$i'>$i</a> </span>";
                        } else {
                            echo "<span id='page_links' style='font-weight: bold; color: RED;'> $i </span>";
                        }
                    }

                    if ($page == $totalPages) {
                        echo "  <span id='page_links' style='font-weight: bold; color: #ddd; padding: 4px; margin: 4px 2px;'> Next </span>";
                    } else {
                        $j = $page + 1;
                        echo "  <span> <a id='page_a_link' href='index.php?tterm=$mterm&gterm=$cterm&gterm=$lterm&year=$yterm&md=$md&actor=$actorname&director=$directorname&page=$j'>Next</a> </span>";
                    }
                }
                ?>               
            </center>
        </div>
    </div>
</div>
<?php
// Close connection
mysqli_close($con);
?>