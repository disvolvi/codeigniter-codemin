
<h1>Bem Vindo <?= $this->session->userdata('nome') ?></h1>

<div style="clear:both"></div>
<hr/>

<?php if(isset($minhas_acoes)){ ?>
<div id="log_acoes" class="well" style="width: 45%; margin-left: 1%; float: left;">
	<h2>Minhas últimas ações</h2>
	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th>Ação</th>
				<th>Registro</th>
				<th>Seção</th>
				<th>Data</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($minhas_acoes as $log) { ?>
			<tr>
				<td><?= texto_acao($log->acao) ?></td>
				<td>
					<?php if($log->acao == 3){ ?>
						<?= $log->id_registro ?>
					<?php } else { ?>
						<a href="<?= base_url() ?>administrador/<?= $log->tabela ?>/editar/<?= $log->id_registro ?>"><?= $log->id_registro ?></a>
					<?php } ?>
				</td>
				<td><a href="<?= base_url() ?>administrador/<?= $log->tabela ?>"><?= $log->tabela ?></a></td>
				<td><?= data_log($log->data); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	</ul>
</div><!-- #log_acoes -->
<?php } ?>

<?php if($meus_acessos){ ?>
<div id="log_acessos" class="well" style="width: 45%; margin-left: 2%; float: left;">
	<h2>Meus últimos acessos</h2>
	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th>IP Usuário</th>
				<th>Agente</th>
				<th>Data</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($meus_acessos as $log) { ?>
			<tr>
				<td><?= $log->ip_usuario ?></td>
				<td><?= $log->user_agent ?></td>
				<td><?= data_log($log->data); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	</ul>
</div><!-- #log_acessos -->
<?php } ?>

<div style="clear:both"></div>
<hr/>

<?php if($log_acoes){ ?>
<div id="log_acoes" class="well" style="width: 45%; margin-left: 1%; float: left;">
	<h2>Últimas ações de todos os usuários</h2>
	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th>Usuário</th>
				<th>Ação</th>
				<th>Registro</th>
				<th>Seção</th>
				<th>Data</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($log_acoes as $log) { ?>
			<tr>
				<td><a href="<?= base_url() ?>administrador/usuarios/editar/<?= $log->id ?>"><?= $log->nome ?></a></td>
				<td><?= texto_acao($log->acao) ?></td>
				<td>
					<?php if($log->acao == 3){ ?>
						<?= $log->id_registro ?>
					<?php } else { ?>
						<a href="<?= base_url() ?>administrador/<?= $log->tabela ?>/editar/<?= $log->id_registro ?>"><?= $log->id_registro ?></a>
					<?php } ?>
				</td>
				<td><a href="<?= base_url() ?>administrador/<?= $log->tabela ?>"><?= $log->tabela ?></a></td>
				<td><?= data_log($log->data); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	</ul>
</div><!-- #log_acoes -->
<?php } ?>

<?php if($log_acessos){ ?>
<div id="log_acessos" class="well" style="width: 45%; margin-left: 2%; float: left;">
	<h2>Últimos acessos de todos os usuários</h2>
	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th>Usuário</th>
				<th>IP Usuário</th>
				<th>Agente</th>
				<th>Data</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($log_acessos as $log) { ?>
			<tr>
				<td><a href="<?= base_url() ?>administrador/usuarios/editar/<?= $log->id ?>"><?= $log->nome ?></a></td>
				<td><?= $log->ip_usuario ?></td>
				<td><?= $log->user_agent ?></td>
				<td><?= data_log($log->data); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	</ul>
</div><!-- #log_acessos -->
<?php } ?>