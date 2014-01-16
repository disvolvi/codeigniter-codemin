<h1>Adicionar <?=$titulo?></h1>

<hr/>

<?= validation_errors('<div class="alert alert-error">', '</div>'); ?>

<?= $dados ?>

<?php foreach ($contentBody as $content) {
    echo $content;
} ?>