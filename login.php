<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>
<?php session_start(); ?>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#"><i class="fas fa-user-plus"></i> Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

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
            $db_id = $row['user_id'];
            $db_username = $row['username'];
            $db_password = $row['password'];
            if($username == $db_username && $password == $db_password) {
                $_SESSION['message'] = '<div class="alert alert-success" role="alert">Sucessfully logged in!</div>';
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['username'] = $username;
                header('Location: dashboard.php');
            }
            else {
                $_SESSION['username'] = $username;
                $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Sorry, the password is not correct.</div>';
                header('Location: login.php');
            }
        }

        if(!isset($_SESSION['message'])) {
            $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Sorry, the user does not exist.</div>';
            header('Location: login.php');           
        }

    }
?>

<!-- The login form -->

    <div class="container" style="margin: 20px auto;">
        <?php if(isset($_SESSION['message'])) {
            echo $_SESSION['message'];
        }?>
        <h1 class="form-header text-center">Login</h1>
        <div class="row">
            <div class="col-sm-6 mx-auto">
                <form method="post">
                    <div class="form-group row">
                        <label for="username" class="col-lg-3 col-form-label">Username: </label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" placeholder="Enter username" name="username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-lg-3 col-form-label">Password: </label>
                        <div class="col-lg-9">
                            <input type="password" class="form-control" placeholder="Password" name="password">
                        </div>
                    </div>
                    <div class="col text-center" style="padding-top: 1em;">
                        <button type="submit" class="btn btn-danger" name="login">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'?>