<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php
    if(isset($_POST['resetpw'])){
        // echo $_POST['password'];
        $new_pw = $_POST['password'];
        $query = "UPDATE user SET password = ".$new_pw."
                    WHERE username = '{$_POST['username']}'";
        $reset_query = mysqli_query($connect, $query);
        if(!$reset_query) {
            echo "Dead!";
            die("QUERY FAILED ".mysqli.error($connect)).' '.msqli_errno($connect);
        }
        $_SESSION['message'] = '<div class="alert alert-success" role="alert">Sucessfully resetted password!</div>';
        header('Location: ../dashboard.php');
    }
?>