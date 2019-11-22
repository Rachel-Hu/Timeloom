<?php include '../includes/db.php'; ?>
<?php session_start(); ?>
<?php 
    $add_display_score = json_decode($_POST['addDisplayScore']);
    // $taskIds = json_decode($_POST['taskIds']);
    $taskIds = $_POST['taskIds'];
    $listid = json_decode($_POST['listId']);
    $userid = $_SESSION['userid'];
    $timestamp = $_POST['timestamp'];
    $all_ids_str = $_POST['allIds'];

    $all_ids = explode(" ", $all_ids_str);
    // print_r($all_ids);

    $query = "SELECT *
                FROM task
                WHERE task.user_id = '$userid' AND task.task_list_id = $listid";
    $tasks = mysqli_query($connect, $query);
    $rows = [];

    // Fetch all rows and sort by display score in descending order.
    while($row = mysqli_fetch_assoc($tasks)) {
        array_push($rows, $row);
    }
    usort($rows, function($a, $b) {
        return -($a['display_score'] <=> $b['display_score']);
    });

    $sorted_tasks = array();
    foreach($taskIds as $id) {
        $index = array_search($id, array_column($rows, 'id'));
        array_push($sorted_tasks, array('id' => $rows[$index]['id'], 'score' => $rows[$index]['display_score']));
    }
    if($add_display_score > 0) {
        usort($sorted_tasks, function($a, $b) {
            return -($a['score'] <=> $b['score']);
        });
    }
    else {
        usort($sorted_tasks, function($a, $b) {
            return ($a['score'] <=> $b['score']);
        });
    }
    // print_r($sorted_tasks);
    $sorted_ids = array_column($sorted_tasks, 'id');
    // print_r($sorted_ids);
    $new_score = null;
    foreach($sorted_ids as $id) {
        // echo $id."\n";
        $ids = array_column($rows, 'id');
        $found_task = array_search($id, $ids);
        $hints = json_decode($rows[$found_task]['hint'], true);
        if(empty($hints)) $hints = Array();
        // Add the task itself
        $hint = Array();
        $hint['id'] = $id;
        $hint['time'] = $timestamp;
        $hint['move'] = $add_display_score;
        array_push($hints, $hint);
        // Add the neighbor affected
        $index = array_search($id, $all_ids);
        if($add_display_score > 0) {
            // Move up, add the one above
            if($index > 0) {
                $hint = Array();
                $hint['id'] = $all_ids[$index - 1];
                $hint['time'] = $timestamp;
                $hint['move'] = -1 * $add_display_score;
                array_push($hints, $hint);
            }
        } else {
            if($index < count($all_ids) - 1) {
                $hint = Array();
                $hint['id'] = $all_ids[$index + 1];
                $hint['time'] = $timestamp;
                $hint['move'] = -1 * $add_display_score;
                array_push($hints, $hint);
            }
        }
        $hints = json_encode($hints);
        $task_count = count($rows);
        
        // When the task is already the first one, do nothing.
        if($add_display_score > 0 && $found_task != 0){ 
            if($found_task > 1) {
                $prev_score = $rows[$found_task - 1]['display_score'];
                $prev_prev_score = $rows[$found_task - 2]['display_score'];
                $new_score = ($prev_score + $prev_prev_score) / 2;
            }
            else {
                $prev_score = $rows[$found_task - 1]['display_score'];
                $new_score = $prev_score + 1;
            }
        }
        else if($add_display_score < 0 && $found_task != $task_count - 1){
            if($found_task < $task_count - 2) {
                $prev_score = $rows[$found_task + 1]['display_score'];
                $prev_prev_score = $rows[$found_task + 2]['display_score'];
                $new_score = ($prev_score + $prev_prev_score) / 2;
            }
            else {
                $prev_score = $rows[$found_task + 1]['display_score'];
                $new_score = $prev_score - 1;
            }
        }
        
        // echo $new_score."\n";
        if($new_score != null) {
            $update_task_query = "UPDATE task 
                            SET display_score = $new_score, hint = '$hints'
                            WHERE id = $id";
            // echo $update_task_query."\n";
            $update_result = mysqli_query($connect, $update_task_query);
            if(!$update_result) {
                echo "Failed!";
                die("QUERY FAILED ".mysqli.error($connect));
            }
            $rows[$found_task]['display_score'] = $new_score;
        }
    }

    header('Location: ../dashboard.php');
?>