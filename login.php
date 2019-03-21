<?php include 'includes/db.php'; ?>
<?php
    $css = 'public/stylesheets/login.css';
    $js = 'public/js/login.js';
    $login = true;
?>
<?php include 'includes/header.php'; ?>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i> Register</a>
            </li>
        </ul>
    </div>
</nav>

<!-- The login form -->

    <div class="container" id="login-form">
        <?php if(isset($_SESSION['message'])) {
            echo $_SESSION['message'];
        }?>
        <h1 class="form-header text-center" id="title">Login</h1>
        <div class="row">
            <div class="col-sm-6 mx-auto">
                <form method="post" action="src/login_authen.php">
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

                <div class="mx-auto" id="g-signin">
                    Or, sign in with Google
                    <div class="g-signin2" data-onsuccess="onSignIn" id="g-signin-btn"></div>
                </div>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'?>