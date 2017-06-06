<?php $errors = $_SESSION['errors']; ?>

<?php if(count($errors) > 0): ?>
    <?php foreach($errors as $error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endforeach; ?>
<?php endif; ?>