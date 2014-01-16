<style>
	table thead tr th {
		height: 30px;
		line-height: 30px!important;
	}
	table thead tr th input.input-busca {
		display: none;
	}
	table thead tr th button.btn-cancelar {
		margin-top: 2px;
		margin-left: 5px;
	}
</style>

<?php if($nivel_acesso == 3){ ?>
	<p id="ordenar-sucesso" class="alert alert-success hide">Ordenação gravada com sucesso</p>
	<p id="ordenar-erro" class="alert alert-error hide">Houve um erro ao gravar a ordenação ou você não tem permissão para isso</p>
	<p id="ordenar-carregando" class="alert alert-info hide">Gravando ordenação, aguarde</p>
<?php } ?>

<h1>Listar <?=$titulo?></h1>

<hr/>
<p><b><?= count($resultados) ?> registros encontrados.</b></p>

<a class="btn btn-success" href="<?=base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/adicionar"?>">Adicionar</a>


<table id="registros" class="table table-striped table-bordered">

	<?php if($nivel_acesso == 3){ ?>
	<caption class="text-info">Clique e arraste para ordenar os registros</caption>
	<?php } else { ?>
	<caption class="text-info">Você tem permissão para visualizar e editar somente os seus registros</caption>
	<?php } ?>

	<thead>
		<tr>
			<th>Id</th>
			<?php foreach($listagens as $listagem){ ?>
			<th>
				<?=ucfirst($listagem)?>
				<button class="btn btn-info btn-small pull-right btn-busca"><i class="icon-search icon-white"></i></button>
				<button class="btn btn-info btn-small pull-right btn-cancelar hide"><i class="icon-remove icon-white"></i></button>
				<input type="text" id="busca-<?= $listagem ?>" placeholder="Buscar por <?=ucfirst($listagem)?>" class="pull-right input-medium search-query input-busca" />
			</th>
			<?php } ?>
			<th>Criado por</th>
			<th>Status</th>
			<th>Ações</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($resultados as $resultado) { ?>
		<tr id="registro_<?=$resultado['id']?>">
			<td>
				<a href="<?=base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/editar/".$resultado['id']?>"><?=$resultado['id']?></a>
				<?php
				if(isset($resultado['capa'])){
					if($resultado['capa'] != ""){
						echo " <img src='".base_url()."uploads/".$this->uri->segment(2)."/imagens/".$resultado['id']."/micro_thumbs/".$resultado['capa']."' class='img-polaroid' width='50' /> ";
					}
				} ?>
			</td>
			<?php foreach($listagens as $listagem){ ?>
			<td><?=$resultado[$listagem]?></td>
			<?php } ?>
			<td><?php
				if(isset($resultado['nome_usuario'])){
					echo $resultado['nome_usuario'];
				} else {
					echo "Sistema";
				}
			?></td>
			<td>
				<?php if($resultado['ativo']){ ?>
					 	<span class="label label-success">Ativo</span>
				<?php } else { ?>
					 	<span class="label label-inverse">Inativo</span>
				<?php } ?>
			</td>
			<td> 
				<div class="btn-group">
					<a class="btn btn-danger confirmar-excluir" href="<?=base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/excluir/".$resultado['id']?>"><i class="icon-remove icon-white"></i></a>
					<a class="btn btn-primary" href="<?=base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/editar/".$resultado['id']?>"><i class="icon-edit icon-white"></i></a>
				</div>
				<!-- <button class='btn btn-info'><i class="icon-move icon-white"></i></button> -->
			</td>
		</tr>
		<?php } ?>
	</tbody>

</table>

<a class="btn btn-success" href="<?=base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/adicionar"?>">Adicionar</a>