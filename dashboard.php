<?php include 'includes/db.php'?>
<?php session_start(); ?>
<?php
    $css = 'public/stylesheets/dashboard.css';
    require_once('includes/dropdown.php'); 
?>

        <div class="container">
            <div class="row">
                <div class="col-lg-6" id="list">
                    <h1>Task List</h1>
                    <input type="text" placeholder="Add new task">
                    <ul>
                        <?php
                            $username = $_SESSION['username'];
                            // Return the tasks of current user.
                            $query = "SELECT *
                                        FROM users
                                        RIGHT JOIN tasks ON (users.userid = tasks.userid)
                                        WHERE users.username = '$username'";
                            $tasks = mysqli_query($connect, $query);

                            while($row = mysqli_fetch_assoc($tasks)) {
                                $task = $row['taskname'];
                                echo '<li><span><i class="fas fa-trash"></i></span> '.$task.'<span class="change-rank"><i class="fas fa-arrow-circle-up"></i><i class="fas fa-arrow-circle-down"></i></span></li> ';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

<?php include 'includes/footer.php'?>