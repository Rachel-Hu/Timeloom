<?php session_start(); ?>

<?php
    // Clear the session.
    $_SESSION['username'] = null;
    $_SESSION['password'] = null;
    $_SESSION['isLoggedIn'] = null;
    $_SESSION['message'] = null;
    // And redirect to the homepage.
    header("Location: index.php");
?>
