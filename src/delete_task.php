<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php 
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        
        $query = "DELETE FROM task WHERE id = '{$id}' ";
        $delete_task_query = mysqli_query($connect, $query);
        if(!$delete_task_query) {
            die("QUERY FAILED ".mysqli.error($connect)).' '.msqli_errno($connect);
        }

        $_SESSION['message'] = '<div class="alert alert-success" role="alert">Task Deleted!</div>';
        header('Location: ../dashboard.php');
    }
?>