<?php include '../includes/db.php'; ?>
<?php session_start(); ?>
<?php 
    $add_display_score = json_decode($_POST['addDisplayScore']);
    $id = json_decode($_POST['taskId']);
    $userid = $_SESSION['userid'];

    // Find the task and change its display score.
    $task_query = "SELECT *
                FROM task
                WHERE task.id = $id";
    $task_result = mysqli_query($connect, $task_query);
    $task = mysqli_fetch_assoc($task_result);
    $curr_score = $task['display_score'];
    $prev_hint = $task['hint'];
    $new_hint = $prev_hint + $add_display_score;
    $new_score = $curr_score;
    // If it is to promote the rank, then we need to obtain its neighbor's  
    // display score whose rank is higher than it.
    if($add_display_score > 0){
        $neighbor_query = "SELECT display_score FROM task 
                            WHERE task.display_score >= $curr_score AND task.user_id = $userid";
        $neighbor_score_result = mysqli_query($connect, $neighbor_query);
        $neighbor_score = mysqli_fetch_assoc($neighbor_score_result)['display_score'];
        while($row = mysqli_fetch_assoc($neighbor_score_result)) {
            if($row['display_score'] < $neighbor_score) {
                $neighbor_score = $row['display_score'];
            }
        }
        $new_score = $neighbor_score + 1;
    }
    else {
        $neighbor_query = "SELECT display_score FROM task 
                            WHERE task.display_score <= $curr_score AND task.user_id = $userid";
        $neighbor_score_result = mysqli_query($connect, $neighbor_query);
        $neighbor_score = mysqli_fetch_assoc($neighbor_score_result)['display_score'];
        while($row = mysqli_fetch_assoc($neighbor_score_result)) {
            if($row['display_score'] > $neighbor_score) {
                $neighbor_score = $row['display_score'];
            }
        }
        $new_score = $neighbor_score - 1;
    }
    echo $new_score;
    $update_task_query = "UPDATE task 
                         SET display_score = $new_score, hint = $new_hint
                         WHERE id = $id";
    echo $update_task_query;
    $update_result = mysqli_query($connect, $update_task_query);
    if(!$update_result) {
        echo "Failed!";
        die("QUERY FAILED ".mysqli.error($connect));
    }

    header('Location: ../dashboard.php');
?>