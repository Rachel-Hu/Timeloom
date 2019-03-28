<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php
    if(isset($_POST['task'])){
        if($_POST['task'] != ""){
            $task = $_POST['task'];
            $userid = $_SESSION['userid'];
            // $username = $_SESSION['username'];

            // Find the userid of current user.
            $userid_query = "SELECT *
                        FROM user
                        WHERE user.id = '$userid'";
            $user_result = mysqli_query($connect, $userid_query);
            if(!$user_result) {
                die("QUERY FAILED ".mysqli.error($connect));
            }
            print_r($user_result);
            $userid = mysqli_fetch_assoc($user_result)['id'];
            $add_task_query = "INSERT INTO task (display_label, score, hint, display_score, task_list_id, user_id) VALUES ('{$task}', 0, 0, 0, 2, ".$userid.")";
            $add_result = mysqli_query($connect, $add_task_query);
            if(!$add_result) {
                echo "Failed!";
                die("QUERY FAILED ".mysqli.error($connect));
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