<?php include '../includes/db.php'; ?>
<?php session_start(); ?>
<?php 
    $add_display_score = json_decode($_POST['addDisplayScore']);
    // $taskIds = json_decode($_POST['taskIds']);
    $taskIds = $_POST['taskIds'];
    $listid = json_decode($_POST['listId']);
    $userid = $_SESSION['userid'];

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
        $hint = $rows[$found_task]['hint'];
        $new_hint = $hint + $add_display_score;
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
                            SET display_score = $new_score, hint = $new_hint
                            WHERE id = $id";
            echo $update_task_query."\n";
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