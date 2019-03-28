<?php include '../includes/db.php'; ?>
<?php
    session_start();
    require_once '../googleapi/vendor/autoload.php';
    $client = new Google_Client();
    $client->setApplicationName("Timeloom");
    $client->setAuthConfig('credentials.json');
    $client->setRedirectUri("https://timeloom.mcs.cmu.edu/dashboard.php");
    $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
    $payload = $client->verifyIdToken($_POST['idtoken']);
    // print_r($payload);
    if ($payload) {
        $useremail = $payload["email"];
        $query = "SELECT * FROM user WHERE email = '{$useremail}' ";
        $select_user_query = mysqli_query($connect, $query);
        if(!$select_user_query) {
            die("QUERY FAILED ".mysqli.error($connect));
        }

        while($row = mysqli_fetch_array($select_user_query)) {
            // print_r($row);
            $id = $row['id'];
            $username = $row['username'];            
            $_SESSION['message'] = '<div class="alert alert-success" role="alert">Sucessfully logged in!</div>';
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['userid'] = $id;
            $redirectURL = 'dashboard.php';
            echo $redirectURL;
        }

        if(!isset($_SESSION['message'])) {
            $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Sorry, the user does not exist.</div>';
            $redirectURL = 'login.php';
            echo $redirectURL;  
        }

    }
?>