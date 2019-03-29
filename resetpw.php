<?php include 'includes/db.php'; ?>
<?php
    $css = 'public/stylesheets/resetpw.css';
?>
<?php session_start(); ?>

<!-- If the user is newly registered with Google, then they will be forced to reset password. -->
<!-- Else, they will have the dropdown manu to navigate. -->
<?php 
    if(isset($_SESSION['userid'])){
        require_once('includes/dropdown.php'); 
    }
    else {
        include 'includes/header.php';
    }
?>
    
    </div>
</nav>

<!-- The login form -->

    <div class="container" id="login-form">
        <!-- To prompt the message if there is an error. -->
        <?php if(isset($_SESSION['message'])) {
            echo $_SESSION['message'];
        }?>
        <h1 class="form-header text-center" id="title">Reset Password</h1>
        <div class="row">
            <div class="col-sm-6 mx-auto">
                <form method="post" action="src/reset_pw_authen.php">
                    <div class="form-group row">
                        <label for="password" class="col-lg-3 col-form-label">Password: </label>
                        <div class="col-lg-9">
                            <input type="password" class="form-control" placeholder="Password" name="password">
                        </div>
                    </div>
                    <div class="col text-center" style="padding-top: 1em;">
                        <button type="submit" class="btn btn-danger" name="resetpw">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'includes/footer.php'?>