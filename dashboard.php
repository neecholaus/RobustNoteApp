<?php

# Registration and login setting session
include('server.php');

# Setting session user
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    
}


# Head and app navbar
$pageTitle = "Dashboard | Robust Notes";
$specCSS = 'dashboard';
include('views/inc/partials/head.phtml');
include('views/inc/partials/app-navbar.phtml');
?>




<?php

# Footer
include('views/inc/partials/footer.phtml');
?>