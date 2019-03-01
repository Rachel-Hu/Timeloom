<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>
<?php session_start(); ?>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i> Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- The login form -->

    <div class="container" style="margin: 20px auto;">
        <?php if(isset($_SESSION['message'])) {
            echo $_SESSION['message'];
        }?>
        <h1 class="form-header text-center">Login</h1>
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
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'?>