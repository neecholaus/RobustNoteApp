<?php

include('server.php');

// // Check connection
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// } 
// echo "Connected successfully (".$db->host_info.")";


$pageTitle = 'Register | Robust Notes';
$specCSS = 'register';
include('views/inc/partials/head.phtml');
include('views/inc/partials/site-navbar.phtml');
?>

<div class="container-fluid">
    <div class="container pt-5 pb-5">
        <div class="row">
            <form class="col-xs-12 col-sm-10 col-md-8 col-lg-6 mx-auto" action="register.php" method="POST">
                <?php include('views/inc/function/errors.php'); ?>
                
                <h4>Register for Robust Notes</h4>
                <p>You're about to join the greatest note app ever!</p>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <input type="text" class="form-control" name="username" placeholder="Username"  />
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <input type="email" class="form-control" name="email" placeholder="Email"  />
                    </div>
                </div>
                <input type="password" class="form-control" name="password" placeholder="Password"  />
                <input type="password" class="form-control" name="verify_pass" placeholder="Verify Password"  />
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="login.php" target="_self">Login</a><span class="pull-right"><button type="submit" name="register_btn" class="btn btn-primary">Sign Up</button></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    
    $('input').change(function() {
        console.log($(this).val());
    });
</script>
