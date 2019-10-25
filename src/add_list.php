<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php
    print_r($_POST);
    if(isset($_POST['newlist'])){
        if($_POST['newlist'] != ""){
            $userid = $_SESSION['userid'];
            $newlist = $_POST['newlist'];
            $add_list_query = "INSERT INTO task_list (name, user_id) VALUES ('{$newlist}', ".$userid.")";
            echo $add_list_query;
            $add_result = mysqli_query($connect, $add_list_query);
            if(!$add_result) {
                die("QUERY FAILED ".mysqli.error($connect));
                $_SESSION['message'] = '<div class="alert alert-success" role="alert">Adding new list failed!</div>';
                header('Location: ../dashboard.php');
            }
            $_SESSION['message'] = '<div class="alert alert-success" role="alert">Successfully added a new list!</div>';
            header('Location: ../dashboard.php');
        }
        else {
            $_SESSION['message'] = '<div class="alert alert-danger" role="alert">List name cannot be empty!</div>';
            header('Location: ../dashboard.php');
        }
    }
?>