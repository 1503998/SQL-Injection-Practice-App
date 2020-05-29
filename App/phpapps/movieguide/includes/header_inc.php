<?php
//////////////////////////////////////////////////////////////////////
//                                                                  //
//   Copyright: Appplebite Media. (except where noted)              //
//   Author: Michelle Brooks (except where noted)                   //
//   Contact: http://www.applebitemedia.com/index.php?pid=contact   // 
//                                                                  //
//////////////////////////////////////////////////////////////////////
?>
<nav class="navbar fixed-top navbar-expand-sm navbar-inverse bg-inverse">
    <ul class="navbar-nav mr-auto">
    <li>
    <a class="navbar-brand btn btn-outline-light" href="index.php">Movie Guide V.2</a>
    </li>
    </ul>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon btn btn-outline-dark"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li>

            </li>
        </ul>
        <ul class="navbar-nav mr-auto">
            <li>
                <form class="form-inline my-2 my-lg-0" name="search" action="index.php" method="POST" enctype="multipart/form-data">
                    <input class="form-control mr-sm-2" id="tterm" name="tterm" type="search" placeholder="Enter a search term here." aria-label="Search">
                    <input type="text" class="form-control mr-sm-2" id="gterm" name="gterm" placeholder="Search by Genre" value="" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li>
                <a class="btn btn-outline-light" href="index.php?pid=amov" data-toggle="tooltip" data-placement="auto" title="Add a movie.">( + ) ADD A MOVIE</a>
            </li>
        </ul>
    </div>
</nav>