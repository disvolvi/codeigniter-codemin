<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf8">
    <title>Configuração - Codemin - Painel Administrativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

    <h1>Configuração do Codemin</h1>

    <ul class="breadcrumb">
      <li><a href="./documentacao">Documentação</a> <span class="divider">></span></li>
      <li class="active">Configuração <span class="divider">></span></li>
      <li><a href="./instalacao">Instalação</a></li>
    </ul>

    <h2>Configuração</h2>

    <h3>Confiugurar o Codemin para a <a href="./instalacao">Instalação</a></h3>

    <h4>1. Importar arquivos e diretório para upload</h4>

    <ul>
      <li>Criar na raiz do projeto um diretório com nome <code>uploads</code> com permissão total <code>0777</code> na raiz do projeto;</li>
      <li>Copiar o arquivo <code>.htaccess</code> e os dois diretórios <code>application</code> e <code>public</code> na raiz do projeto;</li>
    </ul>

    <h4>2. Configuração do Autoload</h4>

    <p>
      Adicionar <code>database</code> e <code>session</code> as libraries do Codeigniter.<br/>
      Adicionar <code>url</code>, <code>form</code> e <code>codemin</code> aos helpers do Codeigniter.
    </p>

    <code>application/config/autoload.php</code>

    <br/><br/>

    <pre>
      $autoload['libraries'] = array('database','session');
      $autoload['helper'] = array('url','form','codemin');
    </pre>

    <h4>3. Configuração da Aplicação</h4>

    <p>
      Configurar o <code>base_url</code> e <code>encryption_key</code>;
    </p>

    <code>application/config/config.php</code>

    <br/><br/>

    <pre>
      $config['base_url'] = 'http://localhost/seu_projeto/';
      $config['encryption_key'] = 'chave_de_criptografia_aleatoria';
    </pre>

    <h4>4. Configuração de Rotas</h4>

    <p>Adicionar ao final do arquivo.</p>

    <code>application/config/routes.php</code>

    <br/><br/>

    <pre>
      $route['administrador'] = "codemin/administrador";
      $route['administrador/(:any)'] = "codemin/administrador/$1";
      $route['opcoes'] = "codemin/opcoes";
      $route['opcoes/(:any)'] = "codemin/opcoes/$1";
      $route['uploader'] = "codemin/uploader";
      $route['uploader/(:any)'] = "codemin/uploader/$1";
      $route['configuracao'] = "codemin/inicial/configuracao";
      $route['instalacao'] = "codemin/inicial/instalacao";
      $route['documentacao'] = "codemin/inicial";
    </pre>

    <h4>5. Configurar o Acesso de Banco de Dados e Prefixo das Tabelas</h4>

    <ul>
      <li>Configurar o  banco de dados: <code>hostname</code>, <code>username</code>, <code>password</code> e <code>database</code> em <code>application/config/database.php</code></li>
      <li>Configurar o prefixo das tabelas: <code>dbprefix</code> em <code>application/config/database.php</code></li>
      <li>Ir para a <a href="./instalacao">instalação</a> <code>http://localhost/seu_projeto/instalacao</code> para criar as tabelas e primeiro usuário</li>
    </ul>

    <br/><br/><br/>

  </div>


  <!-- JS -->
  <script src="./public/codemin/js/bootstrap.min.js"></script>

  </body>
</html>