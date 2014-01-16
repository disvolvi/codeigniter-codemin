<div class="navbar navbar-fixed-top">

  <div class="navbar-inner">

      <div class="nav-collapse">

        <ul class="nav">
	        <li <?php if(!$activelink){ echo 'class="active"'; } ?>><a href="<?=base_url()?>administrador"><i class="icon-home"></i></a></li>

	        <li><a target="_blank" href="<?=base_url()?>"><i class="icon-globe"></i> Visualizar Site</a></li><li class="divider-vertical"></li>

	        <?php foreach ($navlinks as $key => $value) { ?>
	        	<li <?php if($activelink == $key){ echo 'class="active"'; } ?>><a href="<?=base_url()?>administrador/<?=$key?>"><i class="icon-edit"></i> <?=$value?></a></li>
	        <?php } ?>

	      </ul>

        <ul class="nav pull-right">

	      	<li <?php if($activelink == 'usuarios'){ echo 'class="active"'; } ?>>
	      		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i></a> 
	      		<ul class="dropdown-menu">
	      			<?php if($this->session->userdata('administrador')){ ?>
	      			<li><a href="<?=base_url()?>administrador/usuarios"><i class="icon-user"></i> Usuários</a></li>
	      			<?php } ?>
	      			<li><a href="<?=base_url()?>administrador/logout"><i class="icon-remove"></i> Sair do Sitema</a></li>
	      		</ul>
	      	</li>

	      </ul>

      </div><!-- /.nav-collapse -->

  </div><!-- /.navbar-inner -->

</div><!-- /.navbar -->

<div style="width: 98%; margin-left: 1%;">

	<?php
	if($this->session->flashdata('sucesso')){
		echo "<p class='alert alert-success'>" . $this->session->flashdata('sucesso') . "<button type='button' class='close' data-dismiss='alert'>&times;</button><p>";
	}
	if($this->session->flashdata('erro')){
		echo "<p class='alert alert-error'>" . $this->session->flashdata('erro') . "<button type='button' class='close' data-dismiss='alert'>&times;</button><p>";
	}
?>