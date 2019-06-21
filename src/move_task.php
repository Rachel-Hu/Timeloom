<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php 
    if(isset($_GET['id'])) {
        $id_str = $_GET['id'];
        $list = $_GET['list'];
        print_r($_GET);
        $ids = explode('_', $id_str);
        foreach ($ids as $id) {
            $query = "UPDATE task SET task_list_id = $list WHERE id = $id ";
            echo $query;
            $move_task_query = mysqli_query($connect, $query);
            if(!$move_task_query) {
                die("QUERY FAILED ".mysqli.error($connect)).' '.msqli_errno($connect);
            }
        }

        $_SESSION['message'] = '<div class="alert alert-success" role="alert">Task Moved!</div>';
        $_SESSION['listid'] = $list;
        header('Location: ../dashboard.php');
    }
?>