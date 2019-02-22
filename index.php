<?php include 'includes/db.php'?>
<?php
    $css = 'public/stylesheets/welcome.css';
    require_once('includes/header.php'); 
?>
<?php session_start(); ?>

        </div>
    </div>
</nav>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div id="home-content">
                        <h1>Time Loom</h1>
                        <h3>Organize Your Task Efficiently</h3>
                        <hr>
                        <?php 
                            if(!isset($_SESSION['isLoggedIn'])) $url = "login.php"; 
                            else $url = "dashboard.php";
                        ?>
                        <!-- <button class="btn btn-danger"><i class="fas fa-stopwatch"></i> Get Started!</button> -->
                        <a class="btn btn-danger" href=<?php echo $url ?> role="button"><i class="fas fa-stopwatch"></i> Get Started!</a>
                    </div>
                </div>
            </div>
        </div>

<?php include 'includes/footer.php'?>