<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf8">
    <title>Documentação - Codemin - Painel Administrativo</title>
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

    <h1>Documentação do Codemin</h1>

    <ul class="breadcrumb">
      <li class="active">Documentação <span class="divider">></span></li>
      <li><a href="./configuracao">Configuração</a> <span class="divider">></span></li>
      <li><a href="./instalacao">Instalação</a></li>
    </ul>

    <h2>Tipos de entrada de dados Disponíveis</h2>

    <h3>Input Text</h3>
    <p><b>Tipo:</b> <code>input-text</code></p>

    <p>Entrada de texto simples, gera no banco de dados um campo varchar de 255.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> URL Amigável <code>url_amigavel (boolean)</code></li>
      <li> Place Holder <code>placeholder (string)</code></li>
      <li> Dica <code>dica (string)</code></li>
      <li> Validação <code>validacao (string)</code></li>
    </ul>

    <hr/>

    <h3>Vídeo do Vimeo</h3>
    <p><b>Tipo:</b> <code>video-vimeo</code></p>

    <p>Entrada de texto simples, gera no banco de dados um campo varchar de 255. Aceita somente link de vídeo do vimeo, ao gravar limpa a URL e grava somente o id do vídeo.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Place Holder <code>placeholder (string)</code></li>
      <li> Dica <code>dica (string)</code></li>
      <li> Validação <code>validacao (string)</code></li>
    </ul>

    <hr/>

    <h3>Vídeo do Youtube</h3>
    <p><b>Tipo:</b> <code>video-youtube</code></p>

    <p>Entrada de texto simples, gera no banco de dados um campo varchar de 255. Aceita somente link de vídeo do youtube, ao gravar limpa a URL e grava somente o id do vídeo.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Place Holder <code>placeholder (string)</code></li>
      <li> Dica <code>dica (string)</code></li>
      <li> Validação <code>validacao (string)</code></li>
    </ul>

    <hr/>

    <h3>Data</h3>
    <p><b>Tipo:</b> <code>data</code></p>

    <p>Entrada de texto simples com máscara para data no formato dd/mm/aaaa usando o meioMask. Faz a conversão para aaaa-mm-dd para guardar no banco em um campo gerado do tipo date.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Place Holder <code>placeholder (string)</code></li>
      <li> Dica <code>dica (string)</code></li>
      <li> Validação <code>validacao (string)</code></li>
    </ul>

    <hr/>

    <h3>Monetário</h3>
    <p><b>Tipo:</b> <code>monetario</code></p>

    <p>Entrada de texto simples com máscara para decimal no formato 999.999.999.999,99 usando o meioMask. Faz a conversão para 999999999999.99 para guardar no banco em um campo gerado do tipo decimal 10,2.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Dica <code>dica (string)</code></li>
      <li> Validação <code>validacao (string)</code></li>
    </ul>

    <hr/>

    <h3>Textarea</h3>
    <p><b>Tipo:</b> <code>text-area</code></p>

    <p>Entrada de texto textarea. Cria campo no banco de dados do tipo longtext.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Place Holder <code>placeholder (string)</code></li>
      <li> Dica <code>dica (string)</code></li>
      <li> Validação <code>validacao (string)</code></li>
    </ul>

    <hr/>

    <h3>Textarea Rich</h3>
    <p><b>Tipo:</b> <code>text-area-rick</code></p>

    <p>Entrada de texto textarea com o editor CKEDITOR e galeria de imagens. Cria campo no banco de dados do tipo longtext.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Imagens<code>imagens (boolean)</code></li>
      <li> Dica <code>dica (string)</code></li>
      <li> Validação <code>validacao (string)</code></li>
    </ul>

    <hr/>

    <h3>Select Estático</h3>
    <p><b>Tipo:</b> <code>select</code></p>

    <p>Select com array manual ou vindo de outra tabela com a função <code>$this->codemin->array_select('nome_tabela','nome_campo')</code>.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
      <li> Dados <code>dados (array)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Dica <code>dica (string)</code></li>
      <li> Validação <code>validacao (string)</code></li>
    </ul>

    <hr/>

    <h3>Select Dinâmico</h3>
    <p><b>Tipo:</b> <code>select-dinamico</code></p>

    <p>Select com opções gerenciáveis pelo usuário</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Dica <code>dica (string)</code></li>
      <li> Validação <code>validacao (string)</code></li>
    </ul>

    <hr/>

    <h3>Checkbox Estático</h3>
    <p><b>Tipo:</b> <code>checkbox</code></p>

    <p>Checkboxes com array manual ou vindo de outra tabela com a função <code>$this->codemin->array_select('nome_tabela','nome_campo')</code>.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
      <li> Dados <code>dados (array)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Dica <code>dica (string)</code></li>
    </ul>

    <hr/>

    <h3>Checkbox Dinâmico</h3>
    <p><b>Tipo:</b> <code>checkbox-dinamico</code></p>

    <p>Checkboxes com opções gerenciáveis pelo usuário</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Dica <code>dica (string)</code></li>
    </ul>

    <hr/>

    <h3>Radio Estático</h3>
    <p><b>Tipo:</b> <code>radio</code></p>

    <p>Radios com array manual ou vindo de outra tabela com a função <code>$this->codemin->array_select('nome_tabela','nome_campo')</code>.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
      <li> Dados <code>dados (array)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Dica <code>dica (string)</code></li>
      <li> Validação <code>validacao (string)</code></li>
    </ul>

    <hr/>

    <h3>Imagem</h3>
    <p><b>Tipo:</b> <code>imagem</code></p>

    <p>Upload de imagem comum. Pode-se passar um array com os parâmetros nome da miniatura(string), largura(int), altura(int) e recortar(boolean) <code>array('nome',400,100,true)</code>.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Miniaturas <code>miniaturas (array)</code></li>
      <li> Dica <code>dica (string)</code></li>
    </ul>

    <hr/>

    <h3>Imagens</h3>
    <p><b>Tipo:</b> <code>imagens</code></p>

    <p>Galeria de imagens por ajax em um modal. Disponível somente na edição de um registro.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Dica <code>dica (string)</code></li>
    </ul>

    <hr/>

    <h3>Google Maps</h3>
    <p><b>Tipo:</b> <code>google-map</code></p>

    <p>Traz a api do Google Maps com marcador que pode ser arrastado para definir as coordenadas.</p>

    <h4>Obrigatório</h4>

    <ul>
      <li> Título <code>titulo (string)</code></li>
      <li> Campo <code>campo (string)</code></li>
    </ul>

    <h4>Opções</h4>

    <ul>
      <li> Dica <code>dica (string)</code></li>
    </ul>

    <hr/>

    <h2>Exemplo de uma função para o controller administrador</h2>

    <pre>
  public function exemplos(){

    // Título
    $dados[] = array(
      'titulo' => 'Título',
      'campo' => 'titulo',
      'url_amigavel' => true,
      'tipo' => 'input-text',
      'validacao' => 'required|',
      'dica' => 'O Título deve conter no máximo 250 caractéres',
      'placeholder' => 'Título da Galeria'
    );
    // Data
    $dados[] = array(
      'titulo' => 'Data de Publicação',
      'campo' => 'data',
      'tipo' => 'data',
      'placeholder' => '19/07/2013',
      'dica' => 'Os exemplos serão ordenados no site pela data',
    );
    // Texto da chamada
    $dados[] = array(
      'titulo' => 'Chamada',
      'campo' => 'chamada',
      'tipo' => 'text-area',
      'placeholder' => 'Texto para a chamada',
      'dica' => 'O texto deve ser curto para chamar o visitante'
    );
    // Texto do corpo
    $dados[] = array(
      'titulo' => 'Texto',
      'campo' => 'texto',
      'imagens' => true, // Botão para galeria de imagens abaixo do editor de texto
      'tipo' => 'text-area-rich',
      'placeholder' => 'Texto completo',
      'dica' => 'Texto completo com imagens, iframes e tudo o que sua criatividade e o html permitirem'
    );
    // Vídeo do youtube
    $dados[] = array(
      'titulo' => 'Video Youtube',
      'campo' => 'video_youtube',
      'tipo' => 'video-youtube',
      'placeholder' => 'http://www.youtube.com/watch?v=_cPxKq-gMDo',
      'dica' => 'Deve ser uma url absoltua do youtube'
    );
    // Vídeo do vimeo
    $dados[] = array(
      'titulo' => 'Video vimeo',
      'campo' => 'video_vimeo',
      'tipo' => 'video-vimeo',
      'placeholder' => 'http://vimeo.com/79888071',
      'dica' => 'Deve ser uma url absoltua do vimeo'
    );
    // Localização
    $dados[] = array(
      'titulo' => 'Localização',
      'campo' => 'localizacao',
      'tipo' => 'google-maps',
      'dica' => 'Arraste o marcador para definir um local'
    );
    // Valor
    $dados[] = array(
      'titulo' => 'Valor',
      'campo' => 'valor',
      'tipo' => 'monetario',
      'dica' => 'Digite apenas número, sem pontos ou vírgulas'
    );
    // Select estático
    $array_select = array(0 => 'Norte', 1 => 'Sul');
    $dados[] = array(
      'titulo' => 'Situação',
      'campo' => 'local',
      'tipo' => 'select',
      'dados' => $array_select,
      'dica' => 'Os dados desse select foram passados por um array estático'
    );
    // Select dinâmico
    $dados[] = array(
      'titulo' => 'Categoria',
      'campo' => 'categoria',
      'tipo' => 'select-dinamico',
      'dica' => 'Clique em gerenciar para adicionar ou remover categorias'
    );
    // Checkbox estático
    $array_select = array(0 => 'Azul', 1 => 'Verde', 2 => 'Vermelho');
    $dados[] = array(
      'titulo' => 'Cor',
      'campo' => 'cor',
      'tipo' => 'checkbox',
      'dados' => $array_select,
      'dica' => 'Os dados desses checkboxes foram passados por um array estático'
    );
    // Checkbox dinâmico
    $dados[] = array(
      'titulo' => 'Subcategora',
      'campo' => 'subcategoria',
      'tipo' => 'checkbox-dinamico',
      'dica' => 'Clique em gerenciar para adicionar ou remover categorias'
    );
    // Radio estático
    $array_select = array(0 => 'Claro', 1 => 'Escuro', 2 => 'Neutro');
    $dados[] = array(
      'titulo' => 'Grupo',
      'campo' => 'grupo',
      'tipo' => 'radio',
      'dados' => $array_select,
      'dica' => 'Os dados desses radios foram passados por um array estático'
    );
    // Imagem
    $miniaturas[] = array('corte',400,100,true); // criar versão de exatos 400x100px
    $miniaturas[] = array('nao_corte',400,400); // criar versão de no máximo 400x400px
    $dados[] = array(
      'titulo' => 'Capa',
      'campo' => 'capa',
      'tipo' => 'imagem',
      'miniaturas' => $miniaturas,
      'dica' => 'Selecione um arquivo .png, .jpg, .jpeg ou .gif'
    );
    // Imagens (galeria de imagens)
    $dados[] = array(
      'titulo' => 'Imagens da Notícia',
      'campo' => 'imagens',
      'tipo' => 'imagens',
      'dica' => 'Clique em Gerenciar Imagens para selecionar imagens para upload'
    );

    $listagem = array('titulo','data'); // O que vai aparecer na listagem

    $titulo = "Exemplos"; // Titulo da página

    /*
    * $this->codemin->montar_codemin = Cria um CRUD com os input passados para vários registros
    *
    * $dados = os inputs que o usuário irá usar para gerenciar o site
    *
    * $titulo = string com o título da página
    *
    * $listagem = array com os campos que vão ser exibidos na listagem
    */
    $this->codemin->montar_codemin($dados,$titulo,$listagem);

  }
    </pre>

  </div>


  <!-- JS -->
  <script src="./public/codemin/js/bootstrap.min.js"></script>

  </body>
</html>