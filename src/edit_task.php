<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php
    print_r($_POST);
    if(isset($_POST['task'])){
        if($_POST['task'] != ""){
            $task = $_POST['task'];
            $userid = $_SESSION['userid'];
            $listid = $_SESSION['listid'];
            $id = $_POST['edit-submit-btn'];
            $username = $_SESSION['username'];

            // Separate properties
            $properties = array();
            $due_date = array('name' => 'due_date', 'type' => 'datetime-local', 'value' => $_POST['due-date'], 'user_defined' => false);
            array_push($properties, $due_date);
            $priority = array('name' => 'priority', 'type' => 'text', 'value' => $_POST['priority'], 'user_defined' => false);
            array_push($properties, $priority);
            $property = array();
            $property_names = array();
            foreach($_POST as $key => $value) {
                if (strpos($key, 'property-') !== false && (strpos($key, 'property-value-') === false && strpos($key, 'property-type-') === false)) {
                    $property['name'] = $value;
                    array_push($property_names, $value);
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

            // Update property table. Only user defined properties need to be added.
            foreach($property_names as $name){
                $property_query = "SELECT * FROM task_properties WHERE label = '$name'";
                $search_result = mysqli_query($connect, $property_query);
                if(!$search_result) {
                    die("QUERY FAILED ".mysqli.error($connect));
                }
                $entry = mysqli_fetch_assoc($search_result);
                if($entry == null){
                    $add_property_query = "INSERT INTO task_properties (label, user_defined, count) VALUES ('$name', 1, 1)";
                    $add_property_result = mysqli_query($connect, $add_property_query);
                    if(!$add_property_result) {
                        die("QUERY FAILED ".mysqli.error($connect));
                    }
                }
            }

            // Find the userid of current user.
            $userid_query = "SELECT *
                        FROM user
                        WHERE user.id = '$userid'";
            $user_result = mysqli_query($connect, $userid_query);
            if(!$user_result) {
                die("QUERY FAILED ".mysqli.error($connect));
            }
            $userid = mysqli_fetch_assoc($user_result)['id'];

            // Update task
            $update_query = "UPDATE task SET display_label = '{$task}', properties = '{$json}' WHERE id = $id";
            $update_result = mysqli_query($connect, $update_query);
            if(!$update_result) {
                die("QUERY FAILED ".mysqli.error($connect));
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