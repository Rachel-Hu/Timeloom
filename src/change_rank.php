<?php include '../includes/db.php'; ?>
<?php session_start(); ?>
<?php 
    $add_display_score = json_decode($_POST['addDisplayScore']);
    $id = json_decode($_POST['taskId']);
    $userid = $_SESSION['userid'];

    // Find the task and change its display score.
    $task_query = "SELECT *
                FROM tasks
                WHERE tasks.taskid = $id";
    $task_result = mysqli_query($connect, $task_query);
    $task = mysqli_fetch_assoc($task_result);
    $score = $task['display_score'] + $add_display_score;
    $update_task_query = "UPDATE tasks 
                         SET display_score = $score, hint = 1
                         WHERE taskid = $id";
    echo $update_task_query;
    $update_result = mysqli_query($connect, $update_task_query);
    if(!$update_result) {
        echo "Failed!";
        die("QUERY FAILED ".mysqli.error($connect));
    }

    header('Location: ../dashboard.php');
?>