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
                                if(!isset($_SESSION['listid'])) $_SESSION['listid'] = 2;
                                for($i = 0; $i < count($list_arr); ++$i) {
                                    if($i + 1 == $_SESSION['listid']) {
                                        echo '<li class="active current-list">
                                                <a href="src/switch_list.php?listid='.($i + 1).'">'.$list_arr[$i].'</a>
                                                </li>';
                                    }
                                    else {
                                        echo '<li class="active">
                                                <a href="src/switch_list.php?listid='.($i + 1).'">'.$list_arr[$i].'</a>
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
                                $finish_btn = '<a class="dropdown-item" href="" id="finish-btn">Completed</i></a>';
                                $postpone_btn = '<a class="dropdown-item" href="" id="postpone-btn">Latent</a>';
                                $resume_btn = '<a class="dropdown-item" href="" id="resume-btn">Active</a>';
                                $expire_btn = '<a class="dropdown-item" href="" id="expire-btn">Expired</a>';
                                $delete_btn = '<a href="" id="delete-btn"><i class="fas fa-trash"></i></a>';
                                $button = $finish_btn.$postpone_btn.$expire_btn;
                                if($list_id == 1){
                                    $button = $finish_btn.$resume_btn.$expire_btn;
                                }
                                else if($list_id == 3){
                                    $button = $resume_btn.$postpone_btn.$expire_btn;
                                }
                                else {
                                    $button = $postpone_btn.$resume_btn.$finish_btn;
                                }
                                $dropdown = '<div class="dropdown move-task">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="moveTask" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Move to...
                                                </button>
                                            <div class="dropdown-menu" aria-labelledby="moveTask">'.$button.'</div>
                                            </div>';
                                echo $rank_up.$rank_down.$delete_btn.$dropdown;
                            ?>
                        </span>  
                    </h3>
                    <ul id="list-item">
                        <!-- form to add task -->
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
                                                <!-- Fixed properties -->
                                                <?php
                                                    $property_query = "SELECT * FROM task_properties WHERE user_defined = 0";
                                                    $properties = mysqli_query($connect, $property_query);
                                                    $count = 0;
                                                    $current = date("Y-m-d\TH:i");
                                                    while($row = mysqli_fetch_assoc($properties)) {
                                                        switch($row['label']) {
                                                            // For due date, the default needs to be set as 2 day after current time
                                                            case 'Due Date':
                                                                $default = date("Y-m-d\TH:i", strtotime('+2 day', strtotime($current)));
                                                                $value_input = '<input type="datetime-local" class="form-control" name="property-value-'.$count.'" value="'.$default.'" step="1">';
                                                                break;
                                                            // Start time is the current time
                                                            case 'Start Time':
                                                                $default = date("Y-m-d\TH:i");
                                                                $value_input = '<input type="datetime-local" class="form-control" name="property-value-'.$count.'" value="'.$default.'" step="1">';
                                                                break;
                                                            // Done by is the last time the task will be valid. The default is 5 days later.
                                                            case 'Done by':
                                                                $default = date("Y-m-d\TH:i", strtotime('+5 day', strtotime($current)));
                                                                $value_input = '<input type="datetime-local" class="form-control" name="property-value-'.$count.'" value="'.$default.'" step="1">';
                                                                break;
                                                            // For priority, it is a dropdown selection menu
                                                            case 'Priority':
                                                                $value_input = '<select class="form-control" class="property-type" name="property-value-'.$count.'"'.$row['label'].'">
                                                                                    <option value="medium">Medium</option>
                                                                                    <option value="urgent">Urgent</option>
                                                                                    <option value="high">High</option>
                                                                                    <option value="low">Low</option>
                                                                                </select>';
                                                                break;
                                                            // For repeat, it is also a dropdown selection menu
                                                            case 'Repeat':
                                                                $value_input = '<select class="form-control" class="property-type" name="property-value-'.$count.'"'.$row['label'].'">
                                                                                    <option value="never">Never</option>
                                                                                    <option value="daily">Every Day</option>
                                                                                    <option value="weekly">Every week</option>
                                                                                    <option value="biweekly">Every 2 Weeks</option>
                                                                                    <option value="monthly">Every Month</option>
                                                                                    <option value="yearly">Every Year</option>
                                                                                    <option value="custom">Custom</option>
                                                                                </select>';
                                                                break;
                                                            case 'Elasticity':
                                                                $value_input = '<input type="number" step="any" class="form-control" name="property-value-'.$count.'" value=0.5>';
                                                                break;
                                                            case 'Difficulty':
                                                                $value_input = '<input type="number" step="any" class="form-control" name="property-value-'.$count.'" value=0.5>';
                                                                break;
                                                            case 'Enjoyable':
                                                                $value_input = '<input type="number" step="any" class="form-control" name="property-value-'.$count.'" value=0.5>';
                                                                break;
                                                            case 'Tags':
                                                                $value_input = '<input type="text" class="form-control" name="property-value-'.$count.'">';
                                                                break;
                                                            case 'Description':
                                                                $value_input = '<textarea class="form-control" rows="1" name="property-value-'.$count.'"></textarea>';
                                                                break;
                                                        }
                                                        $columns = '<div class="row">
                                                                        <div class="col-md-6 fixed-properties">                                            
                                                                            <input type="text" class="form-control" name="property-'.$count.'" value="'.$row['label'].'" readonly>
                                                                        </div>
                                                                        <input type="hidden" name="property-type-'.$count.'" value="'.$row['type'].'">
                                                                        <div class="col-md-6 fixed-property-values">                                                    
                                                                            '.$value_input.'
                                                                        </div>
                                                                        <input type="hidden" name="user-defined-'.$count.'" value="false">
                                                                    </div>';
                                                        echo $columns;
                                                        $count += 1;
                                                    }
                                                ?>
                                            </div> 
                                            <!-- User defined properties -->
                                            <div>
                                                <p class="add-properties"><i class="fas fa-plus-circle"></i> Add properties</p>
                                            </div> 

                                            <!-- Hidden forms -->
                                            <div class="form-group dynamic-element" style="display:none">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="property" class="col-form-label">Property:</label>
                                                        <div class="search-box">
                                                            <input type="text" class="form-control" id="property" autocomplete="off" placeholder="Add new property">
                                                            <div class="result"></div>
                                                        </div>
                                                        <!-- <input type="text" class="form-control" id="property" placeholder="Add new property"> -->
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
                                                    <input type="hidden" value="true">
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

                        <!-- form to edit task -->
                        <div class="modal fade" id="edit-form" tabindex="-1" role="dialog" aria-labelledby="edit-form-label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="edit-form-label">Edit Task</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetEditForm()">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="src/edit_task.php" method="post">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="task-label" class="col-form-label">Task:</label>
                                                <input type="text" class="form-control" id="task-label-edit" placeholder="Add new task" name="task">
                                                <!-- Fixed properties -->
                                                <?php
                                                    $property_query = "SELECT * FROM task_properties WHERE user_defined = 0";
                                                    $properties = mysqli_query($connect, $property_query);
                                                    $count = 0;
                                                    while($row = mysqli_fetch_assoc($properties)) {
                                                        $value_input = '<input type="'.$row['type'].'" class="form-control" name="property-value-'.$count.'">';
                                                        if($row['type'] == 'float') {
                                                            $value_input = '<input type="number" step="any" class="form-control" name="property-value-'.$count.'">';
                                                        }
                                                        else if($row['type'] == 'string array') {
                                                            $value_input = '<input type="text" class="form-control" name="property-value-'.$count.'">';
                                                        }
                                                        else if($row['type'] == 'string') {
                                                            $value_input = '<textarea class="form-control" rows="1" name="property-value-'.$count.'"></textarea>';
                                                        }
                                                        // For due date, the default needs to be set as 1 day after current time
                                                        else if($row['label'] == 'Due Date') {
                                                            $current = date("Y-m-d\TH:i");
                                                            $default = date("Y-m-d\TH:i", strtotime('+1 day', strtotime($current)));
                                                            $value_input = '<input type="datetime-local" class="form-control" name="property-value-'.$count.'" value="'.$default.'">';
                                                        }
                                                        // For priority, it is a dropdown selection menu
                                                        else if($row['label'] == 'Priority') {
                                                            $value_input = '<select class="form-control" class="property-type" name="property-value-'.$count.'"'.$row['label'].'">
                                                                                <option value="medium">Medium</option>
                                                                                <option value="urgent">Urgent</option>
                                                                                <option value="high">High</option>
                                                                                <option value="low">Low</option>
                                                                            </select>';
                                                        }
                                                        // For repeat, it is also a dropdown selection menu
                                                        else if($row['label'] == 'Repeat') {
                                                            $value_input = '<select class="form-control" class="property-type" name="property-value-'.$count.'"'.$row['label'].'">
                                                                                <option value="never">Never</option>
                                                                                <option value="daily">Every Day</option>
                                                                                <option value="weekly">Every week</option>
                                                                                <option value="biweekly">Every 2 Weeks</option>
                                                                                <option value="monthly">Every Month</option>
                                                                                <option value="yearly">Every Year</option>
                                                                                <option value="custom">Custom</option>
                                                                            </select>';
                                                        }
                                                        $columns = '<div class="row">
                                                                        <div class="col-md-6 fixed-properties">
                                                                            <input type="text" class="form-control" id="'.$row['label'].'" name="property-'.$count.'" value="'.$row['label'].'" readonly>
                                                                        </div>
                                                                        <input type="hidden" name="property-type-'.$count.'" value="'.$row['type'].'">
                                                                        <div class="col-md-6 fixed-property-values-edit">
                                                                            '.$value_input.'
                                                                        </div>
                                                                        <input type="hidden" name="user-defined-'.$count.'" value="false">
                                                                    </div>';
                                                        echo $columns;
                                                        $count += 1;
                                                    }
                                                ?>
                                            </div>    
                                            <div>
                                                <p class="add-properties-edit"><i class="fas fa-plus-circle"></i> Add properties</p>
                                            </div> 

                                            <!-- Hidden forms -->
                                            <div class="form-group dynamic-element-edit" style="display:none">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label for="property" class="col-form-label">Property:</label>
                                                        <div class="search-box">
                                                            <input type="text" class="form-control" id="property" autocomplete="off" placeholder="Add new property">
                                                            <div class="result"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="property-value" class="col-form-label">Value:</label>
                                                        <input type="text" class="form-control" id="property-value" placeholder="New property value">
                                                    </div>
                                                    <!-- End of fields-->
                                                    <div class="col-md-2">
                                                        <i class="fas fa-minus-square fa-lg delete-properties align-content-center"></i>
                                                    </div>
                                                    <input type="hidden" value="true">
                                                </div>
                                            </div>

                                            <div class="dynamic-properties-edit">
                                                <!-- Dynamic columns will appear here -->
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                            <button type="submit" class="btn btn-primary edit-submit-btn" name="edit-submit-btn" value="">Save</button>
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
                                    $properties = htmlspecialchars($prev_row['properties'], ENT_QUOTES);
                                    if($properties == null) $properties = '{}';
                                    $id = $prev_row['id'];
                                    $next_id = $row['id'];
                                    $check = '<input type="checkbox" class="form-check-input check-box" id="check-'.$id.'" onclick="selectTask('.$id.', '.$list_id.');">';
                                    // Design different buttons for different lists.
                                    $hidden = '<input type="hidden" id="properties-'.$id.'" name="'.$task.'" value="'.$properties.'">';
                                    $add_button = '<a id="add-task-before-'.$next_id.'-and-after-'.$id.'" class="btn list-btn text-btn add-task-btn" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus fa-lg add-task-btn"></i></a>';
                                    $edit_btn = '<a data-toggle="modal" data-target="#edit-form" class="edit-btn"><i class="fas fa-edit" id="edit-btn-'.$id.'" onclick="editTask('.$id.');"></i></a>';
                                    echo '<li><span class="task-main">'.$edit_btn.$task.'</span><span class="manipulation">'.$add_button.$check.'</span>'.$hidden.'</li> ';
                                    $prev_row = $row;
                                }
                            }
                            if($prev_row) {
                                $task = $prev_row['display_label'];
                                $properties = htmlspecialchars($prev_row['properties'], ENT_QUOTES);
                                if($properties == null) $properties = '{}';
                                $id = $prev_row['id'];
                                $next_id = 0;
                                $check = '<input type="checkbox" class="form-check-input check-box" id="check-'.$id.'" onclick="selectTask('.$id.', '.$list_id.');">';
                                $hidden = '<input type="hidden" id="properties-'.$id.'" name="'.$task.'" value="'.$properties.'">';
                                $edit_btn = '<a data-toggle="modal" data-target="#edit-form" class="edit-btn"><i class="fas fa-edit" id="edit-btn-'.$id.'" onclick="editTask('.$id.');"></i></a>';
                                $add_button = '<a id="add-task-before-'.$next_id.'-and-after-'.$id.'" class="btn list-btn text-btn add-task-btn" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus fa-lg add-task-btn"></i></a>';
                                echo '<li><span class="task-main">'.$edit_btn.$task.'</span><span class="manipulation">'.$add_button.$check.'</span>'.$hidden.'</li> ';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

<?php include 'includes/footer.php'?>