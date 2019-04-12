<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php
    if(isset($_POST['task'])){
        if($_POST['task'] != ""){
            $task = $_POST['task'];
            print_r($_POST);
            $userid = $_SESSION['userid'];
            $listid = $_SESSION['listid'];
            $ids = explode("and", $_POST['add-submit-btn']);
            $prev_taskid = (int)$ids[0];
            $next_taskid = (int)$ids[1];
            $username = $_SESSION['username'];

            // Find the userid of current user.
            $userid_query = "SELECT *
                        FROM user
                        WHERE user.id = '$userid'";
            $user_result = mysqli_query($connect, $userid_query);
            if(!$user_result) {
                die("QUERY FAILED ".mysqli.error($connect));
            }
            $userid = mysqli_fetch_assoc($user_result)['id'];
            // If the clicked task is not the first one, the new task will
            // be placed on top of it.
            if($prev_taskid != 0){
                $prev_score_query = "SELECT * FROM task WHERE id = $prev_taskid";
                $prev_score_result = mysqli_query($connect, $prev_score_query);
                if(!$prev_score_result) {
                    die("QUERY FAILED ".mysqli.error($connect));
                }
                $prev_task_score = mysqli_fetch_assoc($prev_score_result)['display_score']; 
                $next_score_query = "SELECT * FROM task WHERE id = $next_taskid";
                $next_score_result = mysqli_query($connect, $next_score_query);
                if(!$next_score_result) {
                    die("QUERY FAILED ".mysqli.error($connect));
                }
                $next_task_score = mysqli_fetch_assoc($next_score_result)['display_score'];
                $task_score = ($prev_task_score + $next_task_score) / 2;
                $add_task_query = "INSERT INTO task (display_label, score, hint, display_score, task_list_id, user_id) VALUES ('{$task}', 0, 0, ".$task_score.", ".$listid.", ".$userid.")";
                echo $add_task_query;
                $add_result = mysqli_query($connect, $add_task_query);
                if(!$add_result) {
                    echo "Failed!";
                    die("QUERY FAILED ".mysqli.error($connect));
                }
            }
            // If the task clicked is the first one, add the new task on top of the list.
            else {
                $next_score_query = "SELECT * FROM task WHERE id = $next_taskid";
                $next_score_result = mysqli_query($connect, $next_score_query);
                if(!$next_score_result) {
                    die("QUERY FAILED ".mysqli.error($connect));
                }
                $next_task_score = mysqli_fetch_assoc($next_score_result)['display_score'];
                $task_score = $next_task_score + 1;
                $add_task_query = "INSERT INTO task (display_label, score, hint, display_score, task_list_id, user_id) VALUES ('{$task}', 0, 0, ".$task_score.", ".$listid.", ".$userid.")";
                echo $add_task_query;
                $add_result = mysqli_query($connect, $add_task_query);
                if(!$add_result) {
                    echo "Failed!";
                    die("QUERY FAILED ".mysqli.error($connect));
                }
            }
            $_SESSION['message'] = '<div class="alert alert-success" role="alert">Successfully added a task!</div>';
            header('Location: ../dashboard.php');
        }
        else {
            $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Task cannot be empty!</div>';
            header('Location: ../dashboard.php');
        }
    }
?>