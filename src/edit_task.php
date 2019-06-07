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
            $properties['fixed'] = array('due_date' => $_POST['due-date'], 'priority' => $_POST['priority']);
            $properties['user'] = array();
            $property = array();
            $property_value = '';
            foreach($_POST as $key => $value) {
                if (strpos($key, 'property-') !== false && (strpos($key, 'property-value-') === false && strpos($key, 'property-type-') === false)) {
                    $property['name'] = $value;
                }
                else if(strpos($key, 'property-type-') !== false) {
                    $property['type'] = $value;
                }
                else if(strpos($key, 'property-value-') !== false) {
                    $property['value'] = $value;
                    array_push($properties['user'], $property);
                    $property = array();
                }

            }
            if(count($properties) == 0) $json = '{}';
            else $json = json_encode($properties);

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