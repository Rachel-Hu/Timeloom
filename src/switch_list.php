<?php include '../includes/db.php'; ?>
<?php session_start(); ?>

<?php
    $_SESSION['listid'] = $_GET['listid'];
    header('Location: ../dashboard.php');
?>