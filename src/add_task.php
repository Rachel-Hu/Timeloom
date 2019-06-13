<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php
    if(isset($_POST['task'])){
        if($_POST['task'] != ""){
            $task = $_POST['task'];
            // print_r($_POST);
            // print_r($_SESSION);
            $userid = $_SESSION['userid'];
            $listid = $_SESSION['listid'];
            $ids = explode("and", $_POST['add-submit-btn']);
            $prev_taskid = (int)$ids[0];
            $next_taskid = (int)$ids[1];
            $username = $_SESSION['username'];

            // Separate properties
            $properties = array();
            $due_date = array('name' => 'due_date', 'type' => 'datetime-local', 'value' => $_POST['due-date'], 'user_defined' => false);
            array_push($properties, $due_date);
            $priority = array('name' => 'priority', 'type' => 'text', 'value' => $_POST['priority'], 'user_defined' => false);
            array_push($properties, $priority);
            $property = array();
            foreach($_POST as $key => $value) {
                if (strpos($key, 'property-') !== false && (strpos($key, 'property-value-') === false && strpos($key, 'property-type-') === false)) {
                    $property['name'] = $value;
                }
                else if(strpos($key, 'property-type-') !== false) {
                    $property['type'] = $value;
                }
                else if(strpos($key, 'property-value-') !== false) {
                    $property['value'] = $value;
                    $property['user_defined'] = true;
                    array_push($properties, $property);
                    $property = array();
                }

            }
            if(count($properties) == 0) $json = '{}';
            else $json = json_encode($properties);

            echo $json;

            // Find the userid of current user.
            $userid_query = "SELECT *
                        FROM user
                        WHERE user.id = '$userid'";
            $user_result = mysqli_query($connect, $userid_query);
            if(!$user_result) {
                die("QUERY FAILED ".mysqli.error($connect));
            }
            $userid = mysqli_fetch_assoc($user_result)['id'];
            
            // Insert above the first task
            if($prev_taskid == 0 && $next_taskid == 0) {
                $find_highest_query = "SELECT * FROM task ORDER BY display_score DESC";
                $find_highest_result = mysqli_query($connect, $find_highest_query);
                if(!$find_highest_result) {
                    echo "Failed!";
                    die("QUERY FAILED ".mysqli.error($connect));
                }                
                $higest = mysqli_fetch_assoc($find_highest_result)['display_score'];
                $task_score = $higest + 1;
                $add_task_query = "INSERT INTO task (display_label, score, hint, display_score, task_list_id, user_id, properties) VALUES ('{$task}', 0, 0, ".$task_score.", ".$listid.", ".$userid.", '".$json."')";
                echo $add_task_query;
                $add_result = mysqli_query($connect, $add_task_query);
                if(!$add_result) {
                    echo "Failed!";
                    die("QUERY FAILED ".mysqli.error($connect));
                }
            }            

            // If the clicked task is not the last one, the new task will
            // be placed below it.
            else if($prev_taskid != 0){
                $prev_score_query = "SELECT * FROM task WHERE id = $prev_taskid";
                $prev_score_result = mysqli_query($connect, $prev_score_query);
                if(!$prev_score_result) {
                    die("QUERY FAILED ".mysqli.error($connect));
                }
                $prev_task_score = mysqli_fetch_assoc($prev_score_result)['display_score']; 
                $next_score_query = "SELECT * FROM task WHERE id = $next_taskid";
                $next_score_result = mysqli_query($connect, $next_score_query);
                if(!$next_score_result) {
                    die("QUERY FAILED ".mysqli.error($connect));
                }
                $next_task_score = mysqli_fetch_assoc($next_score_result)['display_score'];
                $task_score = ($prev_task_score + $next_task_score) / 2;
                $add_task_query = "INSERT INTO task (display_label, score, hint, display_score, task_list_id, user_id, properties) VALUES ('{$task}', 0, 0, ".$task_score.", ".$listid.", ".$userid.", '".$json."')";
                echo $add_task_query;
                $add_result = mysqli_query($connect, $add_task_query);
                if(!$add_result) {
                    echo "Failed!";
                    die("QUERY FAILED ".mysqli.error($connect));
                }
            }
            // If the task clicked is the last one, add the new task at the bottom of the list.
            else {
                $next_score_query = "SELECT * FROM task WHERE id = $prev_taskid";
                $next_score_result = mysqli_query($connect, $next_score_query);
                if(!$next_score_result) {
                    die("QUERY FAILED ".mysqli.error($connect));
                }
                $next_task_score = mysqli_fetch_assoc($next_score_result)['display_score'];
                $task_score = $next_task_score - 1;
                $add_task_query = "INSERT INTO task (display_label, score, hint, display_score, task_list_id, user_id, properties) VALUES ('{$task}', 0, 0, ".$task_score.", ".$listid.", ".$userid.", '".$json."')";
                echo $add_task_query;
                $add_result = mysqli_query($connect, $add_task_query);
                if(!$add_result) {
                    echo "Failed!";
                    die("QUERY FAILED ".mysqli.error($connect));
                }
            }
            $_SESSION['message'] = '<div class="alert alert-success" role="alert">Successfully added a task!</div>';
            header('Location: ../dashboard.php');
        }
        else {
            $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Task cannot be empty!</div>';
            header('Location: ../dashboard.php');
        }
    }
?>