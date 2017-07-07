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
                            <span>
                                <form action="dashboard.php" method="POST">
                                    <input type="hidden" name="folder_name" value="<?= $folder ?>">
                                    <div class="w-100 text-right">
                                        <button type="submit" name="delete_folder" class="ml-auto text-muted" style="font-size:14px;border:none;background:transparent;" role="button"><i class="fa fa-trash"></i></button>
                                    </div>
                                </form>
                            </span>
                            <?php getNotes($db, $folder); ?>
                        </div>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <hr>
        </div>
        
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10" id="display">
            <div class="pt-3 pb-3">
                <h4>Welcome<?= ' ' . $username; ?>!</h4>
                <hr>
                <button class="btn btn-secondary hidden-sm-up mobile-btn" id="drop-notes" type="button">Show Notes</button>
                <button type="button" class="nav-link btn btn-success text-white mobile-btn" style="display: inline" id="new_note"><i class="fa fa-plus"></i> New Note</button>
                <button type="button" class="nav-link btn btn-success text-white mobile-btn" style="display: inline" id="new_folder"><i class="fa fa-folder"></i> New Folder</button>
                
                <!-- Errors -->
                <?php include('views/inc/function/errors.php'); ?>
                <?php if($_GET['success']): ?>
                    <div class="alert alert-success mt-2">
                        <i class="fa fa-check"></i> <?= base64_decode($_GET['success']) ?>
                    </div>
                <?php endif; ?>
                <?php if($_GET['error']): ?>
                    <div class="alert alert-danger mt-2">
                        <i class="fa fa-exclamation-circle"></i> <?= base64_decode($_GET['error']) ?>
                    </div>
                <?php endif; ?>
            
                <!-- Add Note Dropdown -->
                <div class="bg-faded rounded mt-2 p-3" id="add_note_con">
                    <form action="dashboard.php" method="POST">
                        <h5>New Note</h5>
                        <input type="text" class="form-control" name="note_title" id="note_title" placeholder="Title">
                        <span><i class="fa fa-info-circle"></i> Leave folder empty to create a Quick Note</span>
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
                    <h4 class="text-muted"><span id="note-display-title"></span>
                        <form action="dashboard" method="POST" style="display: inline-block">
                            <input type="hidden" name="note_delete_title" class="note_edit_title">
                            <input type="hidden" name="note_delete_folder" class="note_edit_folder">
                            <button type="submit" class="btn btn-danger" name="delete_note"><i class="fa fa-trash"></i></button>
                        </form>
                            </h4>
                    <p id="note-display-folder"></p>
                    <form action="dashboard" method="POST">
                        <textarea class="form-control" name="note_save_content" rows="8" id="note-display-content"></textarea>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-secondary" id="close-note-display" style="display: inline-block">Close</button>
                                    <input type="hidden" name="note_save_title" class="note_edit_title">
                                    <input type="hidden" name="note_save_folder" class="note_edit_folder">
                                    <input type="hidden" class="note_edit_folder">
                                    <button type="submit" class="btn btn-primary" name="edit_note"><i class="fa fa-floppy-o"></i></button>
                            </div>
                        </div>
                    </form>
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
        $('#note-display-title').text('');
        $('#note-display-folder').html('');
        $('#note-display-content').text('');
    });
    
    // Opening note from tree
    $('.note-li').click(function(e) {
       $('#add_folder_con').hide();
       $('#add_note_con').hide();
       var title = this.getAttribute('data-title');
       var folder = this.getAttribute('data-folder');
       if (folder != '') {
           folderText = '<span class="text-muted"><i class="fa fa-folder"></i></span> <b>' + folder + '</b>';
       } else {
           folderText = '';
       }
       var content = this.getAttribute('data-content');
       $('#note-display').show();
       $('#note-display-title').text(title);
       $('#note-display-folder').html(folderText);
       $('#note-display-content').text(content);
       // Hidden fields for deleting
       $('.note_edit_title').val(title);
       $('.note_edit_folder').val(folder);
    });
    
    // Add new note
    $('#new_note').click(function() {
       $('#add_note_con').show();
       $('#add_folder_con').hide();
       $('#note-display').hide();
    });
    $('#cancel_note').click(function() {
        $('#add_note_con').hide();
    });
    $('#new_folder').click(function() {
       $('#add_folder_con').show();
       $('#add_note_con').hide();
       $('#note-display').hide();
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
    
    // Hiding success alert
    $(window).click(function() {
        $('.alert-success, .alert-danger').fadeOut();
    });
    
</script>


<?php

# Footer
include('views/inc/partials/footer.phtml');
?>