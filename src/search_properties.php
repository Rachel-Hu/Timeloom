<?php include '../includes/db.php'; ?>

<?php session_start(); ?>

<?php
    if(isset($_REQUEST["term"])){
        // Prepare a select statement
        $sql = "SELECT * FROM task_properties WHERE user_defined = 1 AND label LIKE ?";
        
        if($stmt = mysqli_prepare($connect, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_term);
            
            // Set parameters
            $param_term = $_REQUEST["term"] . '%';
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                
                // Check number of rows in the result set
                if(mysqli_num_rows($result) > 0){
                    // Fetch result rows as an associative array
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                        echo '<div>' . $row["label"] . "</div>";
                    }
                } else{
                    echo "<div>No matches found</div>";
                }
            } else{
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
            }
        }
        mysqli_stmt_close($stmt);
    }
?>