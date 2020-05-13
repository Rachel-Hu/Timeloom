// Sign in through Google Auth
function onSignIn(googleUser) {
    var id_token = googleUser.getAuthResponse().id_token;
    var xhr = new XMLHttpRequest();
    // Sent to google_callback.php for authentication
    xhr.open('POST', 'https://timeloom.mcs.cmu.edu/src/google_callback.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        var redirectURL = "https://timeloom.mcs.cmu.edu/" + xhr.responseText;
        window.location.replace(redirectURL);
    };
    xhr.send('idtoken=' + id_token);
    signOut();
}

// Sign out through Google Auth
function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        console.log('User signed out.');
    });
}

