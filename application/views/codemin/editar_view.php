<h1>Editar <?=$titulo?></h1>

<?= validation_errors('<div class="alert alert-error">', '</div>'); ?>

<?=$dados?>

<?php foreach ($contentBody as $content) {
    echo $content;
} ?>