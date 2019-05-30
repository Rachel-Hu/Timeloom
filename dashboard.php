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
                        <ul class="nav nav-tabs flex-row justify-content-around">
                            <?php
                                $list_arr = array("Latent", "Active", "Completed", "Expired");
                                for($i = 0; $i < count($list_arr); ++$i) {
                                    if($i + 1 == $_SESSION['listid']) {
                                        echo '<li class="active current-list">
                                                <a href="#" onclick="switchList('.($i + 1).');">'.$list_arr[$i].'</a>
                                                </li>';
                                    }
                                    else {
                                        echo '<li class="active">
                                                <a href="#" onclick="switchList('.($i + 1).');">'.$list_arr[$i].'</a>
                                                </li>';
                                    }
                                }
                            ?>
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
                                            <div>
                                                <p class="add-properties"><i class="fas fa-plus-circle"></i> Add properties</p>
                                            </div> 

                                            <!-- Hidden forms -->
                                            <div class="form-group dynamic-element" style="display:none">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="property" class="col-form-label">Property:</label>
                                                        <input type="text" class="form-control" id="property" placeholder="Add new property">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="property-type" class="col-form-label">Type:</label>
                                                        <select class="form-control" id="property-type" class="property-type">
                                                            <option>Choose...</option>
                                                            <option value="text">Text</option>
                                                            <option value="datetime-local">Date and time</option>
                                                            <option value="number">Number</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <label for="property-value" class="col-form-label">Value:</label>
                                                        <input type="text" class="form-control" id="property-value" placeholder="New property value">
                                                    </div>
                                                    <!-- End of fields-->
                                                    <div class="col-md-2">
                                                        <i class="fas fa-minus-square fa-lg delete-properties align-content-center"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="dynamic-properties">
                                                <!-- Dynamic columns will appear here -->
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
                            $username = $_SESSION['username'];
                            if(!isset($_SESSION['userid'])){
                                $userid_query = "SELECT *
                                                FROM user
                                                WHERE user.username = '$username'";
                                $user_result = mysqli_query($connect, $userid_query);
                                if(!$user_result) {
                                    echo "Failed!";
                                    die("QUERY FAILED ".mysqli.error($connect));
                                }
                                $userid = mysqli_fetch_assoc($user_result)['id'];
                                $_SESSION['userid'] = $userid;
                            }
                            $userid = $_SESSION['userid'];
                            $query = "SELECT *
                                        FROM task
                                        WHERE task.user_id = '$userid' AND task.task_list_id = $list_id ORDER BY display_score DESC";
                            $tasks = mysqli_query($connect, $query);
                            $prev_row = null;

                            while($row = mysqli_fetch_assoc($tasks)) {
                                if($prev_row == null) {
                                    $prev_row = $row;
                                    continue;
                                }
                                else {
                                    $task = $prev_row['display_label'];
                                    $id = $prev_row['id'];
                                    $next_id = $row['id'];
                                    $check = '<input type="checkbox" class="form-check-input check-box" id="check-'.$id.'" onclick="selectTask('.$id.', '.$list_id.');">';
                                    // Design different buttons for different lists.
                                    $add_button = '<a id="add-task-before-'.$next_id.'-and-after-'.$id.'" class="btn list-btn text-btn add-task-btn" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus fa-lg add-task-btn"></i></a>';
                                    echo '<li><span class="task-main"><a class="delete-btn" href="src/delete_task.php?id='.$id.'"><i class="fas fa-trash"></i></a> '.$task.'</span><span class="manipulation">'.$add_button.$check.'</span></li> ';
                                    $prev_row = $row;
                                }
                            }
                            $task = $prev_row['display_label'];
                            $id = $prev_row['id'];
                            $next_id = 0;
                            $check = '<input type="checkbox" class="form-check-input check-box" id="check-'.$id.'" onclick="selectTask('.$id.', '.$list_id.');">';
                            // Design different buttons for different lists.
                            $add_button = '<a id="add-task-before-'.$next_id.'-and-after-'.$id.'" class="btn list-btn text-btn add-task-btn" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus fa-lg add-task-btn"></i></a>';
                            echo '<li><span class="task-main"><a class="delete-btn" href="src/delete_task.php?id='.$id.'"><i class="fas fa-trash"></i></a> '.$task.'</span><span class="manipulation">'.$add_button.$check.'</span></li> ';
                        ?>
                    </ul>
                </div>
            </div>
        </div>

<?php include 'includes/footer.php'?>