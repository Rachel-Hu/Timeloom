<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php 
    if(isset($_POST['add-task'])){
        $task = $_POST['task'];

        $username = $_SESSION['username'];

        // Find the userid of current user.
        $userid_query = "SELECT *
                    FROM users
                    WHERE users.username = '$username'";
        $user_result = mysqli_query($connect, $userid_query);
        if(!$user_result) {
            die("QUERY FAILED ".mysqli.error($connect));
        }
        $userid = mysqli_fetch_assoc($user_result)['userid'];
        $add_task_query = "INSERT INTO tasks (userid, taskname) VALUES (".$userid.", '{$task}')";
        $add_result = mysqli_query($connect, $add_task_query);
        if(!$add_result) {
            die("QUERY FAILED ".mysqli.error($connect));
        }

        $_SESSION['message'] = '<div class="alert alert-success" role="alert">Successfully added a task!</div>';
        header('Location: ../dashboard.php');
    }
?>