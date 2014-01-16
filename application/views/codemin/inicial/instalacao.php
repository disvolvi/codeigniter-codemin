<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf8">
    <title>Instalação - Codemin - Painel Administrativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?=base_url()?>">

    <!-- CSS -->
    <link rel="stylesheet" href="./public/codemin/css/bootstrap.css">
    <style>
      .cointainer {
        margin: 0 auto;
        width: auto;
        max-width: 880px;
      }
    </style>

  </head>
  <body>

  <div class="cointainer">

    <h1>Instalação do Codemin</h1>

    <ul class="breadcrumb">
      <li><a href="./documentacao">Documentação</a> <span class="divider">></span></li>
      <li><a href="./configuracao">Configuração</a> <span class="divider">></span></li>
      <li class="active">Instalação</li>
    </ul>

    <h2>Instalação</h2>
    <ul>
      <li>Criar as tabelas básicas com prefixo <code><?= substr_replace($this->db->dbprefix('_'),"",-1); ?></code> configurado anteriormente, que serão responáveis pelo funcionamento do codemin</li>
      <li>Criar um usuário administrador</li>
    </ul>

    <hr/>

    <h3>Tabelas a serem criadas:</h3>
    <ul>
      <li><code><?= $this->db->dbprefix('codemin_log_acessos') ?></code> Guarda os registros de acesso ao codemin</li>
      <li><code><?= $this->db->dbprefix('codemin_log_acoes') ?></code> Guarda os registro de ações do usuários</li>
      <li><code><?= $this->db->dbprefix('codemin_opcoes') ?></code> Guarda as opções de gerenciáveis de checkboxes, radios e selects</li>
      <li><code><?= $this->db->dbprefix('codemin_opcoes_selecionadas') ?></code> Guarda as opções selecionadas de multipla opção (checkbox)</li>
      <li><code><?= $this->db->dbprefix('codemin_uploads') ?></code> Guarda os arquivos de uploads (imagens, documentos, etc...)</li>
      <li><code><?= $this->db->dbprefix('codemin_usuarios') ?></code> Guarda os usuários do sistema</li>
      <li><code><?= $this->db->dbprefix('codemin_usuarios_permissoes') ?></code> Guarda as pemissões dos usuários no sistema</li>
    </ul>

    <hr/>
    
    <h3>Antes de começar...</h3>
    <p>Para que tudo funcione corretamente, verifique se fez todas as configurações descritas na <a href="./configuracao">Configuração</a></p>
    <ul>
      <li>Adicionar <code>database</code> e <code>session</code> as libraries em <code>application/config/autoload.php</code></li>
      <li>Adicionar <code>url</code>, <code>form</code> e <code>codemin</code> aos helpers em <code>application/config/autoload.php</code></li>
      <li>Configurar o <code>base_url</code> e <code>encryption_key</code> em <code>application/config/config.php</code></li>
      <li>Adicionar as rotas em <code>application/config/routes.php</code> <small>Veja as rotas na <a href="./configuracao">Configuração</a></small></li>
      <li>Configurar o <code>hostname</code>, <code>username</code>, <code>password</code>, <code>database</code> e <code>dbprefix</code> para acesso ao banco de dados em <code>application/config/database.php</code></li>
    </ul>

    <hr/>
    
    <h3>Mãos à obra</h3>
    <p></p>
    <form class="form-horizontal" method="POST">

      <?php echo validation_errors("<div class='alert alert-error'>","</div>"); ?>

      <!-- Text input-->
      <div class="control-group">
        <label class="control-label" for="nome">Nome</label>
        <div class="controls">
          <input id="nome" name="nome" placeholder="Nome Completo" class="input-xlarge" type="text" value="<?php echo set_value('nome'); ?>">
          <p class="help-block">Digite o nome do usuário</p>
        </div>
      </div>

      <!-- Text input-->
      <div class="control-group">
        <label class="control-label" for="login">Login</label>
        <div class="controls">
          <input id="login" name="login" placeholder="login" class="input-xlarge" type="text" value="<?php echo set_value('login'); ?>">
          <p class="help-block">Digite um login com namespace</p>
        </div>
      </div>

      <!-- Password input-->
      <div class="control-group">
        <label class="control-label" for="senha">Senha</label>
        <div class="controls">
          <input id="senha" name="senha" placeholder="Senha" class="input-xlarge" type="password" value="<?php echo set_value('senha'); ?>">
          <p class="help-block">Digite uma senha </p>
        </div>
      </div>

      <!-- Button -->
      <div class="control-group">
        <label class="control-label">Tudo pronto?</label>
        <div class="controls">
          <button name="singlebutton" class="btn btn-success">Criar Usuário e Tabelas</button>
        </div>
      </div>

    </form>


  </div>


  <!-- JS -->
  <script src="./public/codemin/js/bootstrap.min.js"></script>

  </body>
</html>