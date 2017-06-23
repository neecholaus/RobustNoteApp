<?php

session_start();
$username = '';
$email = '';
$errors = array();
$error = '';

# Connect to the database
$db = mysqli_connect(getenv('IP'), getenv('C9_USER'), 'testpass', 'c9', 3306);

# Register user
if (isset($_POST['register_btn'])) {
    $username = $db->real_escape_string($_POST['username']);
    $password = $db->real_escape_string($_POST['password']);
    $verify_pass = $db->real_escape_string($_POST['verify_pass']);
    $email = $db->real_escape_string($_POST['email']);
    
    # Making sure fields are not empty
    if (empty($username)) {
        array_push($errors, 'Username is required.');
    }
    if (empty($password)) {
        array_push($errors, 'Password is required.');
    }
    if (empty($email)) {
        array_push($errors, 'An email is required.');
    }
    
    # Making sure passwords match
    if ($password != $verify_pass) {
        array_push($errors, 'Passwords do not match.');
    }
    
    # If no errors, save user to database
    if (count($errors) == 0) {
        # Hashes password for security
        $password = md5($password);
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        
        # Actual SQL injection
        mysqli_query($db, $sql);
        
        $getUserSql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($db, $getUserSql);
        $user = mysqli_fetch_object($result);
        $_SESSION['user'] = $user;
        $_SESSION['username'] = $username;
        $_SESSION['success'] = 'You are now logged in as ' . $username . '.';
        header('location: dashboard');
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
            $user = mysqli_fetch_object($result);
            $_SESSION['user'] = $user;
            $_SESSION['username'] = $username;
            header('location: dashboard');
        } else {
            array_push($errors, 'The username/password combination is incorrect.');
        }
    }
}




# Add new note 
if (isset($_POST['save_note_btn'])) {
    $username = $_SESSION['username'];
    $note_title   = $db->real_escape_string($_POST['note_title']);
    $note_folder  = $db->real_escape_string($_POST['note_folder']);
    $note_content = $db->real_escape_string($_POST['note_content']);
    
    if (empty($note_title)) {
        array_push($errors, "Your note needs a title.");
    }
    if (empty($note_content)) {
        array_push($errors, "Your note is empty.");
    }
    $users_notes = "SELECT * FROM user_notes WHERE note_title='$note_title' and note_folder='$note_folder' and username='$username'";
    $users_notes = mysqli_query($db, $users_notes);
    if (mysqli_num_rows($users_notes) != 0) {
        $errors[] = "You already have a note called '$note_title' in the selected folder.";
        header('location: dashboard');
    }
    
    if (count($errors) == 0) {
        $sql = "INSERT INTO user_notes (username, note_title, note_folder, note_content) VALUES ('$username', '$note_title', '$note_folder', '$note_content')";
        
        # Injecting SQL
        mysqli_query($db, $sql);
    }
    
    header('location: dashboard?error=' . base64_encode($error));
}



# Add new folder
if (isset($_POST['save_folder_btn'])) {
    $username = $_SESSION['username'];
    $folder_title = $db->real_escape_string($_POST['folder_title']);
    
    if (empty($folder_title)) {
        array_push($errors, "Your folder has no name.");
    }
    $user_folders = "SELECT * FROM user_folders WHERE folder_title='$folder_title' and username='$username'";
    $user_folders = mysqli_query($db, $user_folders);
    if (mysqli_num_rows($user_folders) > 0) {
        $error = base64_encode('You have already made a folder with this name.');
        header('location: dasboard?error=' . $error);
    }
    
    $checkName = "SELECT * FROM user_folders WHERE username='$username' AND folder_title='$folder_title'";
    $result = mysqli_query($db, $checkName);
    if (mysqli_num_rows($result) == 0) {
        if (count($errors) == 0) {
            $sql = "INSERT INTO user_folders (username, folder_title) VALUES ('$username', '$folder_title')";
            # Actual injection
            mysqli_query($db, $sql);
        }
    }
}


?>