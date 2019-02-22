<?php include 'includes/db.php'?>
<?php session_start(); ?>
<?
    $css = 'public/stylesheets/dashboard.css';
    require_once('includes/dropdown.php'); 
?>

        <div class="container">
            <div class="row">
                <div class="col-lg-6" id="list">
                    <h1>Task List</h1>
                    <input type="text">
                    <ul>
                        <li><span>X</span> Cache Lab</li> 
                        <li><span>X</span> LeetCode</li>
                        <li><span>X</span> 10601 Homework 4</li>
                    </ul>
                </div>
            </div>
        </div>

<?php include 'includes/footer.php'?>