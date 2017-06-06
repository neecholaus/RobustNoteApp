<?php

# Registration and login setting session
include('views/inc/function/server.phtml');

# Setting session user
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $first_name = $_SESSION['first_name'];
    
}


# Head and app navbar
include('views/inc/partials/head.phtml');
include('views/inc/partials/app-navbar.phtml');
?>




<?php

# Footer
include('views/inc/partials/footer.phtml');
?>