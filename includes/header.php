<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="google-signin-client_id" content="567891438558-711qk2r8ut09sslc7qvhbotlkvcdgm30.apps.googleusercontent.com">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <link rel="shortcut icon" href="public/img/favicon.ico">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <!-- Include the css and js file specifically for this page -->
    <?php
        if(isset($css)){
            printf('<link rel="stylesheet" type="text/css" href="%s" />', $css);
        }    
    ?>
    <script type="text/javascript">const PREDEFINED = <?= $predefined ?>;</script>
    <?php
        if(isset($js)){
            printf('<script type="text/javascript" src="%s"></script>', $js);
        } 
    ?>
    <title>Time Loom</title>
</head>
<body>


<!-- This is the nav bar. -->
<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="far fa-calendar-check"></i> Time Loom</a>