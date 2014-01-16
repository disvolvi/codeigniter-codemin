
<div class="row-fluid" style="margin-top: 150px;">

    <div class="span6 offset1">
    	<h1>Bem vindo ao Codemin</h1>
    </div>

    <div class="span3 offset1">
	    <div class="well">
		    <legend>Acessar o Codemin</legend>
		    <?= form_open('') ?>
		    	<?php if(isset($erro)){ ?>
			    <div class="alert alert-error">
			    	<a class="close" data-dismiss="alert" href="#">x</a>Login ou senha incorretos!
			    </div>
			    <?php } ?>

			    <div class="input-prepend">
				    <span class="add-on"><i class='icon-user'></i></span>
				    <?php
				    if(!isset($login)){
				    	$login = null;
				    }
						$data = array(
							'name'        	=> 'login',
							'value'       	=> $login,
							'placeholder'	=> 'Login',
							'class'			=> 'span12',
							'autofocus' => true
					    );
						echo form_input($data); ?>
				</div>

				<div class="input-prepend">
				    <span class="add-on"><i class='icon-off'></i></span>
					<?php
					$data = array(
						'name'        	=> 'senha',
						'placeholder'	=> 'Senha',
						'class'			=> 'span12'
				    );
					echo form_password($data); ?>
				</div>

				<?= form_hidden('enviado',1); ?>

				<?php
				$data = array(
					'value'	=> 'Acessar',
					'class' => 'btn btn-success span12'
			    );
				echo form_submit($data); ?>

		    <?= form_close() ?>
	    </div>
    </div>

</div>