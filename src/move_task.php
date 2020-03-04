<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php 
    if(isset($_GET['id'])) {
        $id_str = $_GET['id'];
        $list = $_GET['list'];
        $prev_list = $_GET['prev'];
        // print_r($_GET);
        $ids = explode('_', $id_str);
        // Expired to active
        if($prev_list == 4 && $list == 2) {
            foreach ($ids as $id) {
                // Get properties
                $task_query = "SELECT * FROM task WHERE id = $id";
                $task_result = mysqli_query($connect, $task_query);
                if(!$task_result) {
                    die("QUERY FAILED ".mysqli.error($connect)).' '.msqli_errno($connect);
                }
                $task = mysqli_fetch_assoc($task_result);
                $properties = json_decode($task['properties']);
                $index = array_search("Due Date", array_column($properties, "name"));
                // Set new time
                $current = date("Y-m-d\TH:i");
                $default = date("Y-m-d\TH:i", strtotime('+2 day', strtotime($current)));
                $due_date = (array)$properties[$index];
                $due_date['value'] = $default;
                $properties[$index] = $due_date;
                $properties = json_encode($properties);
                $query = "UPDATE task SET task_list_id = $list, properties = '$properties' WHERE id = $id ";
                // echo $query;
                $move_task_query = mysqli_query($connect, $query);
                if(!$move_task_query) {
                    die("QUERY FAILED ".mysqli.error($connect)).' '.msqli_errno($connect);
                }
            }
        }
        // Move to completed
        else if($list == 3) {
            // Autofill done by if necessary
            foreach ($ids as $id) {
                // Get properties
                $task_query = "SELECT * FROM task WHERE id = $id";
                $task_result = mysqli_query($connect, $task_query);
                if(!$task_result) {
                    die("QUERY FAILED ".mysqli.error($connect)).' '.msqli_errno($connect);
                }
                $task = mysqli_fetch_assoc($task_result);
                if($task['model'] == '{}') $model = array();
                else $model = json_decode($task['model'], true);
                print_r($model);         
                // Set new time
                $current = date("Y-m-d\TH:i");
                $model['completed_time'] = $current;
                $model = json_encode($model);
                $query = "UPDATE task SET task_list_id = $list, model = '$model' WHERE id = $id ";
                // echo $query;
                $move_task_query = mysqli_query($connect, $query);
                if(!$move_task_query) {
                    die("QUERY FAILED ".mysqli.error($connect)).' '.msqli_errno($connect);
                }
            }
        }
        else {
            foreach ($ids as $id) {
                $query = "UPDATE task SET task_list_id = $list WHERE id = $id ";
                // echo $query;
                $move_task_query = mysqli_query($connect, $query);
                if(!$move_task_query) {
                    die("QUERY FAILED ".mysqli.error($connect)).' '.msqli_errno($connect);
                }
            }
        }

        $_SESSION['message'] = '<div class="alert alert-success" role="alert">Task Moved!</div>';
        $_SESSION['listid'] = $list;
        header('Refresh:0; url=../dashboard.php');
    }
?>