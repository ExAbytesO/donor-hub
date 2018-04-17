<?php
/* User login process, checks if user exists and password is correct */

require_once("db.php");

// Escape email to protect against SQL injections
$email = $conn->escape_string($_POST['email']);
$result = $conn->query("SELECT * FROM users WHERE email='$email' AND active=1");

if ( $result->num_rows == 0 ){ // User doesn't exist or account inactive
    $_SESSION['message'] = "Invalid Details or account inactive";
    header("location: error.php");
}
else { // User exists
    $user = $result->fetch_assoc();

    if ( password_verify($_POST['password'], $user['password']) ) {
        
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['active'] = $user['active'];
        
        // This is how we'll know the user is logged in
        $_SESSION['logged_in'] = true;

        header("location: profile.php");
    }
    else {
        $_SESSION['message'] = "Invalid Details";
        header("location: error.php");
    }
}

