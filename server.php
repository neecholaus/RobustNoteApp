<?php

session_start();
$username = '';
$email = '';
$errors = array();

# Connect to the database
$db = mysqli_connect(getenv('IP'), getenv('C9_USER'), '', 'c9', 3306);

# Register user
if (isset($_POST['register_btn'])) {
    $first_name = $db->real_escape_string($_POST['first_name']);
    $last_name = $db->real_escape_string($_POST['last_name']);
    $username = $db->real_escape_string($_POST['username']);
    $password = $db->real_escape_string($_POST['password']);
    $verify_pass = $db->real_escape_string($_POST['verify_pass']);
    $email = $db->real_escape_string($_POST['email']);
    
    # Making sure fields are not empty
    if ($username == '' OR $username == ' ') {
        array_push($errors, 'Username is required.');
    }
    if ($password == '' OR $password == ' ') {
        array_push($errors, 'Password is required.');
    }
    if ($email == '' OR $email == ' ') {
        array_push($errors, 'An email is required.');
    }
    
    # Making sure passwords match
    if ($password != $verify_pass) {
        array_push($errors, 'Passwords do not match.');
    }
    
    # If no errors, save user to database
    if (count($errors == 0)) {
        # Hashes password for security
        $password = md5($password);
        $sql = "INSERT INTO users (first_name, last_name, username, password, email) VALUES ('$first_name', '$last_name', '$username', '$password', '$email')";
        
        # Actual SQL injection
        mysqli_query($db, $sql);
        
        $_SESSION['username'] = $username;
        $_SESSION['success'] = 'You are now logged in as ' . $username . '.';
        header('location: dashboard.php');
    } else {
        $_SESSION['errors'] = $errors;
        header('location: register');
    }
}



# User Logout 
if (isset($_POST['logout_btn'])) {
    session_destroy();
    unset($_SESSION['username']);
    unset($_SESSION['first_name']);
    header('location: login.php');
}


# User Login
if (isset($_POST['login_btn'])) {
    $username = $db->real_escape_string($_POST['username']);
    $password = $db->real_escape_string($_POST['password']);
    
    if (empty($username)) {
        array_push($errors, 'Username is required');
    }
    if (empty($password)) {
        array_push($errors, 'Password is required');
    }
    
    # If no errors, go ahead and compare 
    if (count($errors) == 0) {
        $password = md5($password);
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($db, $sql);
        if (mysqli_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            header('location: dashboard');
        } else {
            array_push($errors, 'The username/password combination is incorrect.');
            $_SESSION['errors'] = $errors;
            header('location: login');
        }
    } else {
        $_SESSION['errors'] = $errors;
        header('location: login');
    }
}


?>