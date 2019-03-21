<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php 
    if(isset($_POST['register'])) {
        $user_info['username'] = $_POST['username'];
        $user_info['password'] = $_POST['password'];
        $user_info['email'] = $_POST['email'];
        $user_info['birthdate'] = $_POST['birthdate'];
        $user_info['name'] = $_POST['name'];
        $user_info['gender'] = $_POST['gender'];
        $user_info['timezone'] = $_POST['timezone'];
        
        // Avoid SQL injection
        foreach($user_info as $key => $value) {
            $value = mysqli_real_escape_string($connect, $value);
        }
        
        // print_r($user_info);

        $query_1 = "INSERT INTO users (";
        $query_2 = "VALUES (";
        
        foreach($user_info as $key => $value) {
            $query_1 .= $key.", ";
            $query_2 .= "'".$value."'".", ";
        }
        $query_1 = rtrim($query_1, ', ');
        $query_2 = rtrim($query_2, ', ');
        $query = $query_1.") ".$query_2.");";
        echo $query;

        // $query = "SELECT * FROM users WHERE username = '{$username}' ";
        $register_query = mysqli_query($connect, $query);
        if(!$register_query) {
            die("QUERY FAILED ".mysqli.error($connect)).' '.msqli_errno($connect);
        }

        $_SESSION['message'] = '<div class="alert alert-success" role="alert">Sucessfully logged in!</div>';
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['username'] = $user_info['username'];
        header('Location: ../dashboard.php');

        // TODO: check if user already exists
        // TODO: avoid HTML injection
        // TODO: password encryption

    }
?>