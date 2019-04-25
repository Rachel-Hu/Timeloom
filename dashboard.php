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
                    <h1>Task List <a class="btn add-task-btn" id="init-btn" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus fa-lg"></i></a></h1>                  
                    <h3>
                        Actions
                        <span class="manipulation action-btns">
                            <?php
                                if(!isset($_SESSION['listid'])){
                                    $_SESSION['listid'] = 2;
                                }
                                $list_id = $_SESSION['listid'];
                                $rank_up = '<i class="fas fa-arrow-circle-up" onclick="rankUp();" id="rank-up"></i>';
                                $rank_down = '<i class="fas fa-arrow-circle-down" onclick="rankDown();" id="rank-down"></i>';
                                $finish_btn = '<a class="btn btn-sm btn-outline-light list-btn text-btn" href="" id="finish-btn">Finish</i></a>';
                                $postpone_btn = '<a class="btn btn-sm btn-outline-light list-btn text-btn" href="" id="postpone-btn">Postpone</a>';
                                $resume_btn = '<a class="btn btn-sm btn-outline-light list-btn text-btn" href="" id="resume-btn">Resume</a> ';
                                $button = $rank_up.$rank_down.$finish_btn.$postpone_btn;
                                if($list_id == 1){
                                    $button = $rank_up.$rank_down.$finish_btn;
                                }
                                else if($list_id == 3){
                                    $button = $rank_up.$rank_down.$resume_btn;
                                }
                                echo $button;
                            ?>
                        </span>  
                    </h3>
                    <ul id="list-item">
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Task</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="src/add_task.php" method="post">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="task-label" class="col-form-label">New Task:</label>
                                                <input type="text" class="form-control" id="task-label" placeholder="Add new task" name="task">
                                            </div>  
                                        </div>
                                        <div class="modal-footer">
                                            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                            <button type="submit" class="btn btn-primary add-submit-btn" name="add-submit-btn" value="">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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

                            $prev_id = 0;
                            foreach($rows as $row){
                                $task = $row['display_label'];
                                $id = $row['id'];
                                $check = '<input type="checkbox" class="form-check-input check-box" id="check-'.$id.'" onclick="selectTask('.$id.', '.$list_id.');">';
                                // Design different buttons for different lists.
                                $add_button = '<a id="add-task-before-'.$id.'-and-after-'.$prev_id.'" class="btn list-btn text-btn add-task-btn" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus fa-lg add-task-btn"></i></a>';
                                // $finish_button = '<a class="btn btn-sm list-btn" href="src/move_task.php?id='.$id.'&list=3"><i class="fas fa-check"></i></a>';
                                // $delay_button = '<a class="btn btn-sm btn-outline-danger list-btn text-btn" href="src/move_task.php?id='.$id.'&list=1">Postpone</a>';
                                // $resume_button = '<a class="btn btn-sm btn-outline-danger list-btn text-btn" href="src/move_task.php?id='.$id.'&list=2">Resume</a>';
                                // $rank_up_button = '<i class="fas fa-arrow-circle-up" onclick="rankUp('.$id.', '.$list_id.');"></i>';
                                // $rank_down_button = '<i class="fas fa-arrow-circle-down" onclick="rankDown('.$id.', '.$list_id.');"></i>';
                                // $button = $finish_button.$add_button.$delay_button;
                                // if($list_id == 1){
                                //     $button = $finish_button;
                                // }
                                // else if($list_id == 3){
                                //     $button = $resume_button;
                                // }
                                // echo '<li><span class="task-main"><a class="delete-btn" href="src/delete_task.php?id='.$id.'"><i class="fas fa-trash"></i></a> '.$task.'</span><span class="manipulation"><span class="change-rank">'.$rank_up_button.$rank_down_button.'</span>'.$button.'</span></li> ';
                                echo '<li><span class="task-main"><a class="delete-btn" href="src/delete_task.php?id='.$id.'"><i class="fas fa-trash"></i></a> '.$task.'</span><span class="manipulation">'.$add_button.$check.'</span></li> ';
                                $prev_id = $id;
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

<?php include 'includes/footer.php'?>