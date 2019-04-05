<?php include 'includes/db.php'?>
<?php session_start(); ?>
<?php
    $css = 'public/stylesheets/dashboard.css';
    $js = 'public/js/dashboard.js';
    include 'includes/dropdown.php'; 
?>

        <div class="container">
            <?php if(isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                $_SESSION['message'] = null;
            }?>
            <div class="row">
                <div class="col-lg-6" id="list">
                    <div id="tab" class="container">	
                        <ul class="nav nav-tabs flex-row justify-content-center">
                            <li class="active mx-auto">
                                <a href="#" onclick="switchList(2);">Active</a>
                            </li>
                            <li class="mx-auto">
                                <a href="#" onclick="switchList(1);">Latent</a>
                            </li>
                            <li class="mx-auto">
                                <a href="#" onclick="switchList(3);">Completed</a>
                            </li>
                            <li class="mx-auto">
                                <a href="#" onclick="switchList(4);">Expired</a>
                            </li>
                        </ul>
                    </div>
                    <form action="src/add_task.php" method="post">
                        <h1>Task List <button id="add-task"><i class="fas fa-plus"></i></button></h1>
                        <input type="text" placeholder="Add new task" name="task">
                    </form>
                    <ul id="list-item">
                        <?php
                            // Fetch the user id if not in session.
                            if(!isset($_SESSION['listid'])){
                                $_SESSION['listid'] = 2;
                            }
                            $list_id = $_SESSION['listid'];
                            if(!isset($_SESSION['userid'])){
                                $userid_query = "SELECT *
                                                FROM user
                                                WHERE user.username = '$username'";
                                $user_result = mysqli_query($connect, $userid_query);
                                if(!$user_result) {
                                    die("QUERY FAILED ".mysqli.error($connect));
                                }
                                $userid = mysqli_fetch_assoc($user_result)['id'];
                                $_SESSION['userid'] = $userid;
                            }
                            $userid = $_SESSION['userid'];
                            $query = "SELECT *
                                        FROM task
                                        WHERE task.user_id = '$userid' AND task.task_list_id = $list_id";
                            $tasks = mysqli_query($connect, $query);
                            $rows = [];

                            while($row = mysqli_fetch_assoc($tasks)) {
                                array_push($rows, $row);
                            }
                            usort($rows, function($a, $b) {
                                return -($a['display_score'] <=> $b['display_score']);
                            });

                            foreach($rows as $row){
                                $task = $row['display_label'];
                                $id = $row['id'];
                                // Design different buttons for different lists.
                                $finish_button = '<a class="btn btn-sm list-btn" href="src/move_task.php?id='.$id.'&list=3"><i class="fas fa-check"></i></a>';
                                $delay_button = '<a class="btn btn-sm btn-outline-danger list-btn text-btn" href="src/move_task.php?id='.$id.'&list=1">Delay</a>';
                                $resume_button = '<a class="btn btn-sm btn-outline-danger list-btn text-btn" href="src/move_task.php?id='.$id.'&list=2">Resume</a>';
                                $button = $finish_button.$delay_button;
                                if($list_id == 1){
                                    $button = $finish_button;
                                }
                                else if($list_id == 3){
                                    $button = $resume_button;
                                }
                                echo '<li><span class="task-main"><a class="delete-btn" href="src/delete_task.php?id='.$id.'"><i class="fas fa-trash"></i></a> '.$task.'</span><span class="manipulation"><span class="change-rank"><i class="fas fa-arrow-circle-up" onclick="rankUp('.$id.');"></i><i class="fas fa-arrow-circle-down" onclick="rankDown('.$id.');"></i></span>'.$button.'</span></li> ';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

<?php include 'includes/footer.php'?>