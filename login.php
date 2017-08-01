<?php

include('server.php');



$pageTitle = 'Login | Robust Notes';
$specCSS = 'login';
include('views/inc/partials/head.phtml');
include('views/inc/partials/site-navbar.phtml');
?>


<div class="container-fluid">
<div class="container pt-5 pb-5">
    <div class="row">
        <form class="col-xs-12 col-sm-10 col-md-8 col-lg-6 mx-auto" action="login.php" method="POST">
            <?php include('views/inc/function/errors.php'); ?>
            
            <h4>Login</h4>
            <p>Let's make some notes!</p>
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <input type="username" class="form-control" name="username" placeholder="Username" />
                </div>
                <div class="col-xs-12 col-sm-6">
                    <input type="password" class="form-control" name="password" placeholder="Password" />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <a href="register.php" target="_self">Sign Up</a><span class="pull-right"><button type="submit" name="login_btn" class="btn btn-primary">Login</button></span>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

