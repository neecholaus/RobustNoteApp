
<?php if(count($errors) > 0): ?>
    <?php foreach($errors as $error): ?>
        <div class="alert alert-danger mt-2"><i class="fa fa-exclamation-circle"></i> <?= $error ?></div>
    <?php endforeach; ?>
<?php endif; ?>