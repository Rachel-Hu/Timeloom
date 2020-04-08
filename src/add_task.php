<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php
    if(isset($_POST['task'])){
        if($_POST['task'] != ""){
            $task = htmlspecialchars($_POST['task'], ENT_QUOTES);
            $description = "";
            if(strlen($task) > 40) {
                $description = substr($task, 40);
                $task = substr($task, 0, 40);
            }

            // print_r($_POST);
            // print_r($_SESSION);
            $userid = $_SESSION['userid'];
            $listid = $_SESSION['listid'];
            $ids = explode("and", $_POST['add-submit-btn']);
            $prev_taskid = (int)$ids[0];
            $next_taskid = (int)$ids[1];
            
            $task_query = "SELECT * FROM task WHERE display_label = '$task' AND user_id = '$userid'";
            $search_result = mysqli_query($connect, $task_query);
            if($search_result->num_rows != 0) {
                $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Task already existed!</div>';
                header('Location: ../dashboard.php');
            }
            else {
                // Separate properties
                $properties = array();
                $property = array();
                $user_properties = array();
                $user_property_names = array();
                foreach($_POST as $key => $value) {
                    if (strpos($key, 'property-') !== false && (strpos($key, 'property-value-') === false && strpos($key, 'property-type-') === false)) {
                        $property['name'] = $value;
                    }
                    else if(strpos($key, 'property-type-') !== false) {
                        $property['type'] = $value;
                    }
                    else if(strpos($key, 'property-value-') !== false) {
                        $property['value'] = $value;
                    }
                    else if(strpos($key, 'user-defined-') !== false) {
                        if($value == 'true') {
                            $property['user_defined'] = true;
                            if(array_search(strtolower($property['name']), $user_property_names) === false) {
                                array_push($user_property_names, strtolower($property['name']));
                                array_push($user_properties, $property);
                            }
                            // Update the property by the latter one
                            else {
                                $index = array_search(strtolower($property['name']), $user_property_names);
                                $user_properties[$index] = $property;
                            } 
                        }
                        else {
                            $property['user_defined'] = false;
                            array_push($properties, $property);
                        }
                        $property = array();
                    }
                }
                // Sort and eliminate replicated properties
                $names = array_column($user_properties, 'name');
                $names_lowercase = array_map('strtolower', $names);
                array_multisort($names_lowercase, SORT_ASC, $user_properties);
                $properties = array_merge($properties, $user_properties);

                // Put extra task label (> 40 characters) into description
                if($description != "") {
                    $index = array_search("Description", array_column($properties, 'name'));
                    $original_description = $properties[$index]["value"];
                    $properties[$index]["value"] = $description." Original decription: ".$original_description;
                }
                
                if(count($properties) == 0) $json = '{}';
                else $json = json_encode($properties);

                // echo $json;

                // Update property table. Only user defined properties need to be added.
                foreach($properties as $property){
                    $name = $property['name'];
                    $type = $property['type'];
                    $property_query = "SELECT * FROM task_properties WHERE label = '$name'";
                    $search_result = mysqli_query($connect, $property_query);
                    if(!$search_result) {
                        die("QUERY FAILED ".mysqli.error($connect));
                        $_SESSION['message'] = '<div class="alert alert-success" role="alert">Property not found!</div>';
                        header('Location: ../dashboard.php');
                    }
                    $entry = mysqli_fetch_assoc($search_result);
                    if($entry == null){
                        $add_property_query = "INSERT INTO task_properties (label, type, user_defined, keywords, default_value, frequency) VALUES ('$name', '$type', 1, '', '', 1)";
                        $add_property_result = mysqli_query($connect, $add_property_query);
                        if(!$add_property_result) {
                            die("QUERY FAILED ".mysqli.error($connect));
                            $_SESSION['message'] = '<div class="alert alert-success" role="alert">Cannot add new property!</div>';
                            header('Location: ../dashboard.php');
                        }
                    }
                    // Update user defined property
                    else if($entry['user_defined'] == 1) {
                        $count = $entry['frequency'] + 1;
                        $update_property_query = "UPDATE task_properties SET frequency = {$count} WHERE label = '$name'";
                        $update_property_result = mysqli_query($connect, $update_property_query);
                        if(!$update_property_result) {
                            die("QUERY FAILED ".mysqli.error($connect));
                        }
                    }
                }
                
                // Insert above the first task
                if($prev_taskid == 0 && $next_taskid == 0) {
                    $find_highest_query = "SELECT * FROM task ORDER BY display_score DESC";
                    $find_highest_result = mysqli_query($connect, $find_highest_query);
                    if(!$find_highest_result) {
                        die("QUERY FAILED ".mysqli.error($connect));
                    }                
                    $highest = mysqli_fetch_assoc($find_highest_result)['display_score'];
                    $task_score = $highest + 1;
                    $add_task_query = "INSERT INTO task (display_label, score, hint, display_score, task_list_id, user_id, properties, model) VALUES ('{$task}', 0, '{}', ".$task_score.", ".$listid.", ".$userid.", '".$json."', '{}')";
                    echo $add_task_query;
                    $add_result = mysqli_query($connect, $add_task_query);
                    if(!$add_result) {
                        die("QUERY FAILED ".mysqli.error($connect));
                        $_SESSION['message'] = '<div class="alert alert-success" role="alert">Adding task failed!</div>';
                        header('Location: ../dashboard.php');
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
                    $add_task_query = "INSERT INTO task (display_label, score, hint, display_score, task_list_id, user_id, properties, model) VALUES ('{$task}', 0, '{}', ".$task_score.", ".$listid.", ".$userid.", '".$json."', '{}')";
                    echo $add_task_query;
                    $add_result = mysqli_query($connect, $add_task_query);
                    if(!$add_result) {
                        die("QUERY FAILED ".mysqli.error($connect));
                        $_SESSION['message'] = '<div class="alert alert-success" role="alert">Adding task failed!</div>';
                        header('Location: ../dashboard.php');
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
                    $add_task_query = "INSERT INTO task (display_label, score, hint, display_score, task_list_id, user_id, properties, model) VALUES ('{$task}', 0, '{}', ".$task_score.", ".$listid.", ".$userid.", '".$json."', '{}')";
                    echo $add_task_query;
                    $add_result = mysqli_query($connect, $add_task_query);
                    if(!$add_result) {
                        die("QUERY FAILED ".mysqli.error($connect));
                        $_SESSION['message'] = '<div class="alert alert-success" role="alert">Adding task failed!</div>';
                        header('Location: ../dashboard.php');
                    }
                }
                $_SESSION['message'] = '<div class="alert alert-success" role="alert">Successfully added a task!</div>';
                header('Location: ../dashboard.php');
            }
        }
        else {
            $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Task cannot be empty!</div>';
            header('Location: ../dashboard.php');
        }
    }
?>