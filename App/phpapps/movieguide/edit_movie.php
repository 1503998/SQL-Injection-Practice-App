<?php
//////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////
?>
<?php
include 'includes/dcon.php';

$mterm = filter_input(INPUT_GET, 'Movie_Id');

$sql = "SELECT * FROM Movie_List WHERE Movie_Id = $mterm ";
$query = mysqli_query($con, $sql);
$row = mysqli_fetch_array($query);
?>
<div class="container">
    <form name="edit_movie" action="#" method="POST" enctype="multipart/form-data">
        <select class="form-control form-control-lg" id="Main_Dir" name="Main_Dir">
            <option selected="Movie Folder" value="<?php echo $row['Main_Dir'] ?>"><?php echo $row['Main_Dir'] ?></option>
            <option value="1-0">1-0</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="E">E</option>
            <option value="F">F</option>
            <option value="G">G</option>
            <option value="H">H</option>
            <option value="I">I</option>
            <option value="J">J</option>
            <option value="K">K</option>
            <option value="L">L</option>
            <option value="M">M</option>
            <option value="N">N</option>
            <option value="O">O</option>
            <option value="P">P</option>
            <option value="Q">Q</option>
            <option value="R">R</option>
            <option value="S">S</option>
            <option value="T">T</option>
            <option value="THE Movies">THE Movies</option>
            <option value="U">U</option>
            <option value="V">V</option>
            <option value="W">W</option>
            <option value="XYZ">XYZ</option>
        </select>
        <br>
        <input class="form-control form-control-lg" name="Movie_Title" value="<?php echo $row['Movie_Title'] ?>" type="text" placeholder="Movie Title w/Date YYYY"><br>
        <input class="form-control form-control-lg" name="Genre" value="<?php echo $row['Genre'] ?>" type="text" placeholder="Movie Genre(s)"><br>
        <input class="form-control form-control-lg" type="text" name="Release_Year" value="<?php echo $row['Release_Year'] ?>" placeholder="Year Movie Released ie. 2010"/><br>
        <textarea class="form-control form-control-lg" id="About" name="About" rows="3" placeholder="About Movie"><?php echo $row['About'] ?></textarea>
        <br><div class='label'>Format <span style="color: red">Actor-Character</span> no space beteween hypen.</div>
        <textarea class="form-control form-control-lg" id="cast" name="Cast" rows="3" placeholder="Movie Cast"><?php echo $row['Cast'] ?></textarea>
        <br><div class='label'>For more than 1 Director Format: <span style="color: red">Director 1,Director 2</span>  no space between the comma.</div>
        <input class="form-control form-control-lg" id="Directed_By" name="Directed_By" value="<?php echo $row['Directed_By'] ?>" type="text" placeholder="Directed By"><br>
        <input class="form-control form-control-lg" type="text" name="Date_Added" value="<?php echo $row['Date_Added'] ?>" placeholder="Today's Date yyyy/mm/dd"/><br>
        <?php
        if ($row['Have_Seen'] == "0") {
            $label = "Not Watched";
        } else {
            $label = "Watched";
        }
        ?>
        <select class="form-control form-control-lg" id="Have_Seen" name="Have_Seen">
            <option selected="Have_Seen" value="<?php echo $row['Have_Seen'] ?>"><?php echo $label ?></option>
            <option value="1">Watched</option>
            <option value="0">Not Watched</option>
        </select>
        <br>
        <?php
        echo '<input type="file" name="my_field" id="my_field"><br><br>';
        ?>
        <input class="form-control form-control-lg" type="text" name="Filename" value="<?php echo $row['Filename'] ?>" /><br>
        <br>
        <input type="hidden" name="Movie_Id" value="<?php echo $row['Movie_Id'] ?>">
        <div align="right"><input class="btn btn-outline-secondary btn-lg" type="submit" value="Edit Movie" name="edit_movie" style="width: 200px;"disabled/></div>
    </form>
</div>