<?php include '../includes/db.php'; ?>
<?php session_start(); ?>

<?php
    $list_id = json_decode($_POST['list']);
    $_SESSION['listid'] = $list_id;
?>