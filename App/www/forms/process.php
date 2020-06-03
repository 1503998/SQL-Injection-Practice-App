<?php
function processBoardsOrder($key)
{
    if (!isset($_POST[$key]) || !is_array($_POST[$key]))
        return;

    $boards = getBoards();
    $queries = array();
    $ranking = 1;

    foreach ($_POST[$key] as $board_id) {
        if (!array_key_exists($board_id, $boards))
            continue;

        $query = sprintf('update movies set ranking = %d where movie_id = %d',
                         $ranking,
                         $board_id);

        mysql_query($query);
        $ranking++;
    }
}

processBoardsOrder('editBoards');
?>