<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php
    // print_r($_POST);
    if(isset($_POST['task'])){
        if($_POST['task'] != ""){
            $task = $_POST['task'];
            $userid = $_SESSION['userid'];
            $listid = $_SESSION['listid'];
            $id = $_POST['edit-submit-btn'];

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

            if(count($properties) == 0) $json = '{}';
            else $json = json_encode($properties);

            // Update property table. Only user defined properties need to be added.
            foreach($properties as $property){
                if($property['user_defined']) {
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
                }
            }

            // Update task
            $update_query = "UPDATE task SET display_label = '{$task}', properties = '$json' WHERE id = $id";
            // echo $json;
            $update_result = mysqli_query($connect, $update_query);
            if(!$update_result) {
                echo "Failed!!";
                die("QUERY FAILED ".mysqli.error($connect));
                $_SESSION['message'] = '<div class="alert alert-success" role="alert">Edit failed!</div>';
                header('Location: ../dashboard.php');
            }
            $_SESSION['message'] = '<div class="alert alert-success" role="alert">Successfully edited a task!</div>';
            header('Location: ../dashboard.php');
        }
        else {
            $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Task cannot be empty!</div>';
            header('Location: ../dashboard.php');
        }
    }
?>