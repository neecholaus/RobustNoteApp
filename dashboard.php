<?php

# Registration and login setting session
include('server.php');

# Setting session user
if (isset($_SESSION['username'])) {
    $user = $_SESSION['user'];
    $username = $user->username;
    
    $folders = array();
    $getFolderSql = "SELECT * FROM user_folders WHERE username='$username'";
    
    # Getting Rows
    $folder_result = mysqli_query($db, $getFolderSql);
    
    # Pushing folder names into array
    while ($row = mysqli_fetch_assoc($folder_result)) {
        array_push($folders, $row['folder_title']);
    }
    
}

# Head and app navbar
$pageTitle = "Dashboard | Robust Notes";
$specCSS = 'dashboard';
include('views/inc/partials/head.phtml');
include('views/inc/partials/app-navbar.phtml');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 bg-faded" id="folders">
            <p>Notes</p>
            <hr>
            <p>Folders</p>
            <hr>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10 p-5" id="display">
            <h4>Welcome<?= ' ' . $username; ?>!</h4>
            <hr>
            <button type="button" class="nav-link btn btn-success text-white" style="display: inline" id="new_note"><i class="fa fa-plus"></i> New Note</button>
            <button type="button" class="nav-link btn btn-success text-white" style="display: inline" id="new_folder"><i class="fa fa-folder"></i> New Folder</button>
            
            <!-- Errors -->
            <?php include('views/inc/function/errors.php'); ?>
            
            <!-- Add Note Dropdown -->
            <div class="bg-faded rounded mt-2 p-3" id="add_note_con">
                <form action="dashboard.php" method="POST">
                    <h5>New Note</h5>
                    <input type="text" class="form-control" name="note_title" id="note_title" placeholder="Title">
                    <select name="note_folder" id="note_folder" class="form-control" >
                        <option value="" selected="selected">Select Folder</option>
                        <?php foreach($folders as $name): ?>
                            <option value="<?= $name; ?>"><?= $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <textarea name="note_content" id="note_content" rows="5" placeholder="Enter note here..." class="form-control" style="resize: none"></textarea>
                    <div class="ml-auto">
                        <button type="reset" class="btn btn-secondary" style="display: inline" id="cancel_note">Cancel</button>
                        <button type="submit" class="btn btn-success" style="display: inline" name="save_note_btn" id="save_note_btn">Save</button>
                    </div>
                </form>
            </div>
            
            <!-- Add Folder Dopdown -->
            <div class="bg-faded rounded mt-2 p-3" id="add_folder_con">
                <form action="dashboard.php" method="POST">
                    <h5>New Folder</h5>
                    <input type="text" class="form-control" name="folder_title" id="folder_title" placeholder="Title">
                    <div class="ml-auto">
                        <button type="reset" class="btn btn-secondary" style="display: inline" id="cancel_folder">Cancel</button>
                        <button type="submit" class="btn btn-success" style="display: inline" name="save_folder_btn" id="save_folder_btn">Save</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

<script>
    /* global $ */
    $('#add_note_con').hide();
    $('#add_folder_con').hide();
    $('#new_note').click(function() {
       $('#add_note_con').show();
       $('#add_folder_con').hide();
    });
    $('#cancel_note').click(function() {
        $('#add_note_con').hide();
    });
    $('#new_folder').click(function() {
       $('#add_folder_con').show();
       $('#add_note_con').hide();
    });
    $('#cancel_folder').click(function() {
        $('#add_folder_con').hide();
    });
</script>


<?php

# Footer
include('views/inc/partials/footer.phtml');
?>