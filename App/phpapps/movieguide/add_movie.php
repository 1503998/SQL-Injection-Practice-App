<?php

//////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////

$date = date('Y-m-d');
?>
<div class="container">
    <form name="add_movie" action="#" method="POST" enctype="multipart/form-data">
        <select class="form-control form-control-lg" id="Main_Dir" name="Main_Dir"  required>
            <option selected="Main_Dir" value="">Select Movie Folder</option>
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
        <input class="form-control form-control-lg" name="Movie_Title" value="" type="text" placeholder="Movie Title w/Date YYYY" required><br>
        <input class="form-control form-control-lg" name="Genre" value="" type="text" placeholder="Movie Genre(s)"><br>
        <input class="form-control form-control-lg" type="text" name="Release_Year" value="" placeholder="Year Movie Released ie. 2010"/><br>
        <textarea class="form-control form-control-lg" id="About" name="About" rows="3" placeholder="About Movie"></textarea>
        <br><div class='label'>Format <span style="color: red">Actor-Character</span> no space beteween hypen.</div>
        <textarea class="form-control form-control-lg" id="Cast" name="Cast" rows="3" placeholder="Actor-Character"></textarea>
        <br><div class='label'>For more than 1 Director Format: <span style="color: red">Director 1,Director 2</span>  no space between the comma.</div>
        <input class="form-control form-control-lg" id="Directed_By" name="Directed_By" value="" type="text" placeholder="Directed By"><br>
        <input class="form-control form-control-lg" type="text" name="Date_Added" value="<?php echo $date; ?>" placeholder="Today's Date yyyy/mm/dd"/><br>
        <select class="form-control form-control-lg" id="Have_Seen" name="Have_Seen">
            <option selected="Have_Seen" value="0" name="Have_Seen">Not Seen</option>
            <option value="1">Watched this movie.</option>
        </select>
        <br>
        <input type="file" name="my_field" id="my_field">

        <br><br>

        <input class="form-control form-control-lg" name="Filename" value="" type="text" placeholder="movie_title.mp4"><br><br>

        <div align="right"><input class="btn btn-outline-secondary btn-lg" type="submit" value="Add Movie" name="add_movie" style="width: 200px;" disabled/></div>
    </form>
</div>