<?php include 'includes/db.php'?>
<?php session_start(); ?>
<?php
    $css = 'public/stylesheets/dashboard.css';
    $js = 'public/js/dashboard.js';
    require_once('includes/dropdown.php'); 
?>

        <div class="container">
            <?php if(isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                $_SESSION['message'] = null;
            }?>
            <div class="row">
                <div class="col-lg-6" id="list">
                    <form action="src/add_task.php" method="post">
                        <h1>Task List <button id="add-task"><i class="fas fa-plus"></i></button></h1>
                        <input type="text" placeholder="Add new task" name="task">
                    </form>
                    <ul>
                        <?php
                            $userid = $_SESSION['userid'];
                            $query = "SELECT *
                                        FROM tasks
                                        WHERE tasks.userid = '$userid'";
                            $tasks = mysqli_query($connect, $query);
                            $rows = [];

                            while($row = mysqli_fetch_assoc($tasks)) {
                                array_push($rows, $row);
                            }
                            usort($rows, function($a, $b) {
                                return -($a['display_score'] <=> $b['display_score']);
                            });
                            foreach($rows as $row){
                                $task = $row['taskname'];
                                $id = $row['taskid'];
                                echo '<li><span class="task-main"><a class="delete-btn" href="src/delete_task.php?id='.$id.'"><i class="fas fa-trash"></i></a> '.$task.'</span><span class="change-rank"><i class="fas fa-arrow-circle-up" onclick="rankUp('.$id.');"></i><i class="fas fa-arrow-circle-down" onclick="rankDown('.$id.');"></i></span></li> ';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

<?php include 'includes/footer.php'?>