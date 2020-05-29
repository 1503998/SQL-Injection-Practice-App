<?php
//////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////
?>
<div class="row" style="margin-top: 20px;">
    <div class="col-md-5">

    </div>
    <div class="col-md-7">
        <form class="form-inline" name="search" action="index.php" method="POST">
            <div class="form-group" style="margin-top: 10px; margin-bottom: 10px;">
                <label for="movie_search">Movie Search: </label>
                <input type="text" class="form-control" id="tterm" name="tterm" placeholder="Enter a search term here." value="">
                <input type="text" class="form-control" id="gterm" name="gterm" placeholder="Search by Genre" value="">
                <button type="submit" class="btn btn-default">Search</button>
            </div>
        </form>
    </div>
</div>