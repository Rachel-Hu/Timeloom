<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php 
    if(isset($_POST['login'])) {
        $_SESSION['message'] = null;
        $username = $_POST['username'];
        $password = $_POST['password'];
        // Avoid SQL injection
        $username = mysqli_real_escape_string($connect, $username);
        $password = mysqli_real_escape_string($connect, $password);

        $query = "SELECT * FROM users WHERE username = '{$username}' ";
        $select_user_query = mysqli_query($connect, $query);
        if(!$select_user_query) {
            die("QUERY FAILED ".mysqli.error($connect));
        }


        while($row = mysqli_fetch_array($select_user_query)) {
            $db_id = $row['userid'];
            $db_username = $row['username'];
            $db_password = $row['password'];
            if($username == $db_username && $password == $db_password) {
                $_SESSION['message'] = '<div class="alert alert-success" role="alert">Sucessfully logged in!</div>';
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['userid'] = $db_id;
                header('Location: ../dashboard.php');
            }
            else {
                $_SESSION['username'] = $username;
                $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Sorry, the password is not correct.</div>';
                header('Location: ../login.php');
            }
        }

        if(!isset($_SESSION['message'])) {
            $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Sorry, the user does not exist.</div>';
            header('Location: ../login.php');           
        }

    }
?>

