<?php include 'includes/db.php'?>
<?php session_start(); ?>
<?php
    $css = 'public/stylesheets/dashboard.css';
    require_once('includes/dropdown.php'); 
?>

        <div class="container">
            <div class="row">
                <div class="col-lg-6" id="list">
                    <form action="src/add_task.php" method="post">
                        <h1>Task List <button id="add-task" name="add-task"><i class="fas fa-plus"></i></button></h1>
                        <input type="text" placeholder="Add new task" name="task">
                    </form>
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
                                $id = $row['taskid'];
                                echo '<li><a class="delete-btn" href="src/delete_task.php?id='.$id.'"><i class="fas fa-trash"></i></a> '.$task.'<span class="change-rank"><i class="fas fa-arrow-circle-up"></i><i class="fas fa-arrow-circle-down"></i></span></li> ';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

<?php include 'includes/footer.php'?>