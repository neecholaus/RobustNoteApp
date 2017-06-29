<?php

# Registration and login setting session
include('server.php');

# Setting session user
if (isset($_SESSION['username'])) {
    $user = $_SESSION['user'];
    $username = $user->username;
    
    
    # Storing all users folders in array
    $folders = array();
    $get_folder_sql = "SELECT * FROM user_folders WHERE username='$username'";
    # Getting Rows
    $folder_result = mysqli_query($db, $get_folder_sql);
    # Pushing folder names into array
    while ($row = mysqli_fetch_assoc($folder_result)) {
        array_push($folders, $row['folder_title']);
    }
    
    
    # Storing all notes with no folder in array
    $plain_notes = array();
    $get_plain_notes_sql = "SELECT * FROM user_notes WHERE username='$username' AND note_folder=''";
    # Getting rows from db
    $plain_note_result = mysqli_query($db, $get_plain_notes_sql);
    while ($row = mysqli_fetch_assoc($plain_note_result)) {
        array_push($plain_notes, $row);
    }
    
    
    # Function to get all notes with param as note_folder
    function getNotes($db, $folder) {
        $sql = "SELECT * FROM user_notes WHERE note_folder='$folder'";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li role='button' data-title='" . $row['note_title'] . "' data-folder='" . $row['note_folder'] . "' data-content='" . $row['note_content'] . "' class='note-li'><i class='fa fa-sticky-note-o pr-3'></i>" . $row['note_title'] . "</li>";
        }
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
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 bg-faded p-0" id="folders">
            <div class="container">
                <button class="btn btn-secondary hidden-sm-up mobile-btn" id="hide-notes" type="button">Hide Notes</button>
            </div>
            <p class="pl-2 pt-3 mb-1"><b>Quick Notes</b></p>
            
            <!-- List of notes with no folder -->
            <div class="mt-2" id="notes-no-folder">
                <ul class="note-list">
                    <?php foreach($plain_notes as $key => $note): ?>
                        <li role="button" data-title="<?= $note['note_title']?>" data-folder="" data-content="<?= $note['note_content']?>" class="note-li"><i class="fa fa-sticky-note-o pr-3"></i><?= $note['note_title'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <hr>
                
            <p class="pl-2 mb-1"><b>Folders</b></p>
            <!-- List of notes in each folder -->
            <div class="mt-2" id="notes-in-folders">
                <ul class="folder-list">
                    <?php foreach($folders as $folder): ?>
                        <li id="<?= join('-', split(' ', $folder)) ?>" class="folder-li" role="button"><i class="fa fa-folder pr-3"></i><?= $folder ?> <span class="pull-right pr-2"><i class="fa fa-caret-down"></i></span></li>
                        <div id="<?= join('-', split(' ', $folder)) ?>-drop" class="folder-drop">
                            <?php getNotes($db, $folder); ?>
                        </div>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <hr>
        </div>
        
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10" id="display">
            <div class="container pt-3 pb-3">
                <h4>Welcome<?= ' ' . $username; ?>!</h4>
                <hr>
                <button class="btn btn-secondary hidden-sm-up mobile-btn" id="drop-notes" type="button">Show Notes</button>
                <button type="button" class="nav-link btn btn-success text-white mobile-btn" style="display: inline" id="new_note"><i class="fa fa-plus"></i> New Note</button>
                <button type="button" class="nav-link btn btn-success text-white mobile-btn" style="display: inline" id="new_folder"><i class="fa fa-folder"></i> New Folder</button>
                
                <!-- Errors -->
                <?php include('views/inc/function/errors.php'); ?>
            
                <!-- Add Note Dropdown -->
                <div class="bg-faded rounded mt-2 p-3" id="add_note_con">
                    <form action="dashboard.php" method="POST">
                        <h5>New Note</h5>
                        <input type="text" class="form-control" name="note_title" id="note_title" placeholder="Title">
                        <select name="note_folder" id="note_folder" class="form-control">
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
                
                <!-- Note Display -->
                <div class="bg-faded rounded mt-2 p-3" id="note-display">
                    <h4 class="text-muted" id="note-display-title"></h4>
                    <p id="note-display-folder"></p>
                    <textarea class="form-control" id="note-display-content"></textarea>
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-secondary" id="close-note-display">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /* global $ */
    
    $('#note-display').hide();
    $('#add_note_con').hide();
    $('#add_folder_con').hide();
    $('.folder-drop').hide();
    
    // Close note display window
    $('#close-note-display').click(function() {
        $('#note-display').hide();
    });
    
    // Opening note from tree
    $('.note-li').click(function(e) {
       var title = this.getAttribute('data-title');
       var folder = this.getAttribute('data-folder');
       if (folder != '') {
           folder = '<span class="text-muted"><i class="fa fa-folder"></i></span> <b>' + folder + '</b>';
       }
       var content = this.getAttribute('data-content');
       $('#note-display').show();
       $('#note-display-title').text(title);
       $('#note-display-folder').html(folder);
       $('#note-display-content').text(content);
    });
    
    // Add new note
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
    
    // Folder Tree View Drops
    $('.folder-li').click(function() {
        var folder_name = $(this).attr('id').split(' ').join('-');
        if ($('#' + folder_name).hasClass('on') == false) {
            $('.folder-drop').slideUp();
            $('.folder-li').removeClass('on');
            $('#' + folder_name).addClass('on');
            $('#' + folder_name + '-drop').slideDown();   
        } else if ($('#' + folder_name).hasClass('on') == true) {
            $('#' + folder_name).removeClass('on');
            $('#' + folder_name + '-drop').slideUp();
        }
    });
    
    // Show Notes and folders in mobile 
    $('#drop-notes').click(function() {
        $('#folders').slideDown();
        $('#drop-notes').hide();
    });
    $('#hide-notes').click(function() {
        $('#folders').slideUp();
        $('#drop-notes').show();
    });
    
    $(window).on('resize', function() {
        var width = window.innerWidth;
        if (width > 576) {
            $('#folders').show();
        }
    });
    
</script>


<?php

# Footer
include('views/inc/partials/footer.phtml');
?>