<?php include 'includes/db.php'?>
<?php include 'includes/header.php'?>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#"><i class="fas fa-user"></i> Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <div class="container" style="margin: 20px auto;">
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