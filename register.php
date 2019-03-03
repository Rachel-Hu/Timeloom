<?php include 'includes/db.php'?>
<?php include 'includes/header.php'?>
<?php session_start(); ?>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="login.php"><i class="fas fa-user"></i> Login</a>
            </li>
        </ul>
    </div>
</nav>

    <div class="container" style="margin: 20px auto;">
        <h1 class="form-header text-center">Register</h1>
        <div class="row">
            <div class="col-sm-6 mx-auto">
                <form method="post" action="src/register_authen.php">
                    <div class="form-group row">
                        <label for="username" class="col-lg-3 col-form-label">Username: </label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" placeholder="Enter username" name="username" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-lg-3 col-form-label">Password: </label>
                        <div class="col-lg-9">
                            <input type="password" class="form-control" placeholder="Password" name="password" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="birthdate" class="col-lg-3 col-form-label">Birth Date: </label>
                        <div class="col-lg-9">
                            <input type="date" class="form-control" placeholder="Birth Date" name="birthdate" required>
                        </div>
                    </div>
                    <!-- editable: name, gender, time zone (updated at every start) -->
                    <div class="form-group row">
                        <label for="name" class="col-lg-3 col-form-label">Name: </label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" placeholder="Name" name="name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="gender" class="col-lg-3 col-form-label">Gender: </label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" placeholder="Gender" name="gender">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="timezone" class="col-lg-3 col-form-label">Timezone: </label>
                        <div class="col-lg-9">
                            <select class="form-control bfh-timezones" data-country="US" name="timezone"></select>
                        </div>
                    </div>
                    <div class="col text-center" style="padding-top: 1em;">
                        <button type="submit" class="btn btn-danger" name="register">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'?>