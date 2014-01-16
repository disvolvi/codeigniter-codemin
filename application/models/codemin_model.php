<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Codemin_model extends CI_Model {

	private $configuracoes = array();



	/**
	*
	* Função construct, descrever as funções caso tenha
	* @param null
	* @return null
	*
	**/
	public function __construct() {
		parent::__construct();

		// $this->configuracoes['key'] = 'value';
	}

	/**
	*
	* verificar_login
	* 
	* Verifica se o usuário logado mantém o mesmo id, login e senha
	* 
	*
	* @access	public
	* @param	null
	* @return	boolean
	*/

	public function verificar_login(){

		if($this->session->userdata('logado')){

			$this->db->where('id',	 $this->session->userdata('id'));
			$this->db->where('login',$this->session->userdata('login'));
			$this->db->where('senha',$this->session->userdata('senha'));
			$this->db->where('ativo', 1);

			if($this->db->get('codemin_usuarios')->num_rows() == 1){
				return true;
			}
			
		}

	}


	/**
	*
	* fazer_login
	* 
	* Verifica se o usuário existe pelo login e senha
	* 
	*
	* @access	public
	* @param	$string, $string
	* @return	boolean
	*/
	public function fazer_login($login,$senha){

		$this->db->where('ativo', 1);
		$this->db->where('login', $login);
    $this->db->where('senha', senha_usuario($senha));

    $retorno = $this->db->get('codemin_usuarios'); 

    if ($retorno->num_rows == 1) {

    	$usuario = $retorno->row();
    	$sessao = array(
      	'id'  					=> $usuario->id,
      	'login'     		=> $usuario->login,
      	'senha'    			=> $usuario->senha,
      	'nome'     			=> $usuario->nome,
      	'administrador'	=> $usuario->administrador,
      	'logado' 				=> TRUE
      );
      $this->session->set_userdata($sessao);

      $this->log_acessos();

      return true;

    }

	}

  /**
	*
	* verificar_permissao
	* 
	* Verifica qual o invel de permissão do usuário
	* 
	*
	* @access	public
	* @param	null
	* @return	int
	*/
	public function verificar_permissao(){

		if($this->session->userdata('administrador')){
			return 3;
		}

		$area = $this->uri->segment(2);

		$this->db->where('id_usuario',$this->session->userdata('id'));
		$this->db->where('area',$area);
		return $this->db->get('codemin_usuarios_permissoes')->row()->nivel;

	}

  /**
	*
	* adicionar
	* 
	* Verificar se tem um POST e insere no banco
	* 
	*
	* @access	public
	* @param	array, int
	* @return	boolean
	*/
	public function adicionar($dados,$nivel_acesso){

		$this->load->library('form_validation');
		
		foreach ($dados as $dado) {
			$rules = null;
			if(isset($dado['validacao'])){
				$rules = $dado['validacao'];
				$validacao = true;
			}
			$this->form_validation->set_rules($dado['campo'], $rules . '');
		}

		$resulatado_validacao = null;
		if(isset($validacao)){
			$resulatado_validacao = $this->form_validation->run();
		}

		// Se tiver um POST insere no banco
		if($_POST & $resulatado_validacao !== false){

			$tabela = $this->uri->segment(2);
			$inserir = array();

			// Insere o id do usuário
			$inserir['id_usuario'] = $this->session->userdata('id');

			// Grava os dados no banco
			foreach ($dados as $dado) {
				$campo 				= $dado['campo'];
				$tipo 				= $dado['tipo'];

				$url_amigavel = null;
				if(isset($dado['url_amigavel'])){
					$url_amigavel	= $dado['url_amigavel'];	
				}
				
				if($tipo == 'imagem'){
					$uploads[] = $dado;
				} elseif($tipo == 'checkbox' || $tipo == 'checkbox-dinamico') {
					$opcoes_selecionadas[$campo] = $this->input->post($campo);
				} else {
					$inserir[$campo] = $this->converter_para_banco($this->input->post($campo),$tipo,$campo);
					if($url_amigavel){
						$inserir['url_'.$campo] = $this->gerar_url_amigavel($tabela,$campo,$inserir[$campo]);
					}
				}
			}

			// Se o usuário tiver permissão para ativar o item
			if($nivel_acesso >= 2){
				$inserir['ativo'] = $this->input->post('ativo');
			}

			if($this->db->insert($tabela,$inserir)){

				$id = $this->db->insert_id();

				// Faz o upload no diretório correto, já com ID
				if(isset($uploads) || isset($opcoes_selecionadas)){

					if(isset($uploads)){

						foreach ($uploads as $dado) {
							$campo 		= $dado['campo'];
							$tipo 		= $dado['tipo'];
							$miniaturas = $dado['miniaturas'];
							$atualizar[$campo] = $this->converter_para_banco($this->input->post($campo),$tipo,$campo,$id,$miniaturas);
						}

						// Atualiza os dados na tabela com os arquivos upados
						$this->db->where('id',$id);
						$this->db->update($tabela,$atualizar);

					}
					if(isset($opcoes_selecionadas)){
						foreach ($opcoes_selecionadas as $campo => $opcoes) {
							$this->gravar_opcoes_selecionadas($opcoes,$campo,$id);
						}
					}

					$this->log_acoes($id,1);
					return true;

				} else {
					$this->log_acoes($id,1);
					return true;
				}

			} else {
				return false;
			}

		}

	}

  /**
	*
	* editar
	* 
	* Verificar se tem um POST e insere no banco
	* 
	*
	* @access	public
	* @param	array, int
	* @return	boolean
	*/
	public function editar($dados,$nivel_acesso){

		$id_usuario		= $this->session->userdata('id');

		// Pegar id e tabela
		$id 	= $this->uri->segment(4,1);
		$tabela = $this->uri->segment(2);

		$this->load->library('form_validation');
		
		foreach ($dados as $dado) {
			$rules = null;
			if(isset($dado['validacao'])){
				$rules = $dado['validacao'];
				$validacao = true;
			}
			$this->form_validation->set_rules($dado['campo'], $rules . '');
		}

		$resulatado_validacao = null;
		if(isset($validacao)){
			$resulatado_validacao = $this->form_validation->run();
		}

		// Se tiver POST atualiza
		if($_POST & $resulatado_validacao !== FALSE){

			$atualizar = array();
			foreach ($dados as $dado) {
				$campo 		= $dado['campo'];
				$tipo 		= $dado['tipo'];

				$miniaturas = array();
				if(isset($dado['miniaturas'])){
					$miniaturas = $dado['miniaturas'];
				}

				if($tipo == 'checkbox' || $tipo == 'checkbox-dinamico'){
					$this->gravar_opcoes_selecionadas($this->input->post($campo),$campo,$id);
				} else {
					$retorno = $this->converter_para_banco($this->input->post($campo),$tipo,$campo,null,$miniaturas);
				}

				// Confere se não foi enviado imagem, se não foi enviado não insere
				if((!$retorno AND $tipo != 'imagem' AND $tipo != 'arquivo') OR ($retorno)){
					$atualizar[$campo] = $retorno;
				}

			}

			// Se o usuário tiver permissão para ativar o item
			if($nivel_acesso >= 2){
				$atualizar['ativo'] = $this->input->post('ativo');
			}

			// Se o usuário não tiver acesso total ou for configurações do usuário,
			// edita somente os ítens dele
			if($nivel_acesso < 3){
				$this->db->where('id',$id);
				$this->db->where('id_usuario',$id_usuario);
			} elseif(isset($usuario)){
				$this->db->where('id_usuario',$id_usuario);
			}

			// Verifica se são configurações do usuário
			if($usuario){

				$this->db->where('id_usuario',$id_usuario);
				if($this->db->get($tabela)->num_rows() > 0){
					$this->db->where('id_usuario',$id_usuario);
					$atualizar = $this->db->update($tabela,$atualizar);
				} else {
					$atualizar['id_usuario'] = $id_usuario;
					$atualizar = $this->db->insert($tabela,$atualizar);
				}

			// Se forem configurações do sistema atualiza o id 1
			} else {
				$this->db->where('id',$id);
				$atualizar = $this->db->update($tabela,$atualizar);
			}

			if($atualizar){
				$this->log_acoes($id,2);
				return true;
			} else {
				return false;
			}

		}

	}

	/**
	* excluir
	* 
	* Excluiu um registro do banco de dados
	* 
	*
	* @access	public
	* @param	int
	* @return	boolean
	*/
	public function excluir($nivel_acesso){

		$id_usuario	= $this->session->userdata('id');

		$id 	= $this->uri->segment(4);
		$tabela = $this->uri->segment(2);

		if($nivel_acesso < 3){ // Se o usuário não tiver acesso total, excluí somente os ítens dele
			$this->db->where('id_usuario',$id_usuario);
		}
		$this->db->where('id',$id);
		if($this->db->delete($tabela)){
			$this->log_acoes($id,3);
			return true;
		} else {
			return false;
		}

	}

	/**
	* resultado
	* 
	* Pega um único resultado do banco de dados
	* 
	*
	* @access	public
	* @param	int
	* @return	obj
	*/
	public function resultado($nivel_acesso){

		$id_usuario = $this->session->userdata('id');

		// Pegar id e tabela
		$id 	= $this->uri->segment(4,1);
		$tabela = $this->uri->segment(2);

		// Busca os resultados atuais no banco de dados
		// Se o usuário não tiver acesso total ou for configurações do usuário,
		// mostra somente os ítens dele
		if($nivel_acesso < 3){
			$this->db->where('id_usuario',$id_usuario);
			$this->db->where('id',$id);

		} elseif(isset($usuario)){
			$this->db->where('id_usuario',$id_usuario);

		} else {
			$this->db->where('id',$id);
		}

		return $this->db->get($tabela)->row_array();
	}

	/**
	* resultados
	* 
	* Pega todos os resultados do banco de dados
	* 
	*
	* @access	public
	* @param	int
	* @return	obj
	*/
	public function resultados($listagem, $nivel_acesso){

		$return = new stdClass();

		$id_usuario		= $this->session->userdata('id');
		$tabela 			= $this->uri->segment(2);

		/*
		* Faz a conversão dos dados a serem exibidos
		* e faz o join da tabela para exibir os campos
		*/
		foreach ($listagem as $item) {
			if(is_array($item)){
				
				$tabela_estrangeira = $item['tabela'];
				$campo_estrangeiro	= $item['campo_estrangeiro'];
				$chave_estrangeira	= $item['chave_estrangeira'];
				$novo_titulo				= $item['titulo'];

				$this->db->select($tabela_estrangeira . '.' . $campo_estrangeiro . ' as ' . $novo_titulo);
				$this->db->join($tabela_estrangeira, $tabela_estrangeira.'.id = ' . $tabela . '.' . $chave_estrangeira, 'LEFT');

				$listagens[] = $novo_titulo;

			} else {
				$listagens[] = $item;
			}
		}

		$this->db->select('codemin_usuarios.nome as nome_usuario');
		$this->db->select($tabela.'.*');
		$this->db->join('codemin_usuarios','codemin_usuarios.id = ' . $tabela . '.id_usuario');

		// Pega todos os resultados
		if($nivel_acesso < 3){ // Se o usuário não tiver acesso total, mostra somente os ítens dele
			$this->db->where($tabela.'.id_usuario',$id_usuario);
		}

		$this->db->order_by($tabela.'.ordenar','asc');
		$this->db->order_by($tabela.'.id','desc');

		$return->resultados = $this->db->get($tabela)->result_array();
		$return->listagens 	= $listagens;

		return $return;

	}

	/**
	* converter_para_banco
	* 
	* Converte a string para o formato do db
	* quando houver necessidade
	* 
	*
	* @access	private
	* @param	$string, $string, $string, $string, $array
	* @return	$string
	*/
	private function converter_para_banco($valor, $tipo, $name = null, $id = null, $miniaturas = null){
		switch ($tipo) {
			case 'data':
				$valor = implode('-',array_reverse(explode('/',$valor)));
				break;

			case 'video-youtube':
				// Chama a função do helper
				$valor = youtube_video_id($valor);
				break;

			case 'video-vimeo':
				// Chama a função do helper
				$valor = vimeo_video_id($valor);
				break;

			case 'imagem':
				if(file_exists($_FILES[$name]['tmp_name'])){
					$valor = $this->codemin->imagem_upload($name,$id,$miniaturas);
				} else {
					$valor = null;
				}
				break;

			case 'monetario':
		    if (empty($valor)) {
		       $valor = '0.00';
		    } else {
	        $valor = str_replace('.', '', $valor);
	        $valor = str_replace(',', '.', $valor);
		    	$valor = number_format($valor, 2, '.', '');
		    }
		    break;
		}
		return $valor;

	}

	/**
	* converter_do_banco
	* 
	* Converte a string para formato humano
	* quando houver necessidade
	* 
	*
	* @access	public
	* @param	$string, $string
	* @return	$string
	*/
	public function converter_do_banco($valor,$tipo){
		switch ($tipo) {
			case 'data':
				$valor = implode('/',array_reverse(explode('-',$valor)));
				break;

			case 'video-youtube':
				// Chama a função do helper
				$valor = youtube_video_url($valor);
				break;

			case 'video-vimeo':
				// Chama a função do helper
				$valor = vimeo_video_url($valor);
				break;
		}
		return $valor;
	}


	/**
	* gerar_url_amigavel
	* 
	* Gera uma url amigável única
	* 
	*
	* @access	private
	* @param	$string, $string, $string
	* @return	$string
	*/
	private function gerar_url_amigavel($tabela,$campo,$variavel){

		$variavel = url_amigavel($variavel);

    $aux = 1;
    $disponivel = false;
    $newvariavel = $variavel;

    $this->db->where('url_'.$campo,$variavel);
    if($this->db->get($tabela)->num_rows() <= 0){

    	return $variavel;

    } else {

	    $aux = 1;
	    $found = false;
	    while($found == false){
	    	$newvariavel = $variavel.'-'.$aux;
    		$this->db->where('url_'.$campo,$newvariavel);

	    	if($this->db->get($tabela)->num_rows() <= 0){
	    		$found = true;
	    	} else {
	    		$aux++;
	    	}

	    }

	    return $newvariavel;

    }

	}

	/**
	* ordenar_registros
	* 
	* Grava a ação do usuário no banco de dados
	* 
	*
	* @access	public
	* @param	int, int
	* @return null
	*/
	public function ordenar_registros($tabela,$registros){

		$return = array();

		if(!$this->session->userdata('administrador')){
			$this->db->select('id');
			$this->db->where('nivel',3);
			$this->db->where('area',$tabela);
			$this->db->where('id_usuario',$this->session->userdata('id'));
			if($this->db->get('codemin_usuarios_permissoes')->num_rows() <= 0){
				$return['erro'] = true;
				return $return;
			}
		}
		

		$aux = 1;
		foreach ($registros as $registro) {
			if($registro){

				$explode = explode('registro_', $registro);
				$id = $explode[1];

				$update = array('ordenar' => $aux);
				$this->db->where('id',$id);
				if(!$this->db->update($tabela,$update)){
					$erro = true;
				}

				$aux++;

			}
		}
		if(isset($erro)){
			$return['erro'] = true;
		} else {
			$return['sucesso'] = true;
		}
		return $return;

	}

	/**
	* log_acoes
	* 
	* Grava a ação do usuário no banco de dados
	* 
	*
	* @access	private
	* @param	int, int
	* @return null
	*/
	private function log_acoes($id,$acao){

		$insert = array(
			'id_usuario' => $this->session->userdata('id'),
			'tabela' => $this->uri->segment(2),
			'id_registro' => $id,
			'acao' => $acao,
			'data' => date('Y-m-d H:i:s')
		);
		$this->db->insert('codemin_log_acoes',$insert);

	}

	/**
	* log_acessos
	* 
	* Grava os acessos do usuário no banco de dados
	* 
	*
	* @access	private
	* @param	int, int
	* @return null
	*/
	private function log_acessos(){

		$insert = array(
			'id_usuario' => $this->session->userdata('id'),
			'ip_usuario' => $this->session->userdata('ip_address'),
			'user_agent' => $this->session->userdata('user_agent'),
			'data' => date('Y-m-d H:i:s')
		);
		$this->db->insert('codemin_log_acessos',$insert);

	}

	/**
	* pegar_log_acoes
	* 
	* Grava a ação do usuário no banco de dados
	* 
	*
	* @access	public
	* @param	int
	* @return null
	*/
	public function pegar_log_acoes($limit,$todos=false){

		if($todos & !$this->session->userdata('administrador')){
			return null;
		}

		$this->db->select('codemin_log_acoes.id_registro, codemin_log_acoes.acao, codemin_log_acoes.tabela, codemin_log_acoes.data');

		if($todos){
			$this->db->select('codemin_usuarios.id, codemin_usuarios.nome');
			$this->db->join('codemin_usuarios','codemin_usuarios.id = codemin_log_acoes.id_usuario');
		} else {
			$this->db->where('id_usuario',$this->session->userdata('id'));
		}

		$this->db->limit($limit);
		$this->db->order_by('data','desc');
		return $this->db->get('codemin_log_acoes')->result();

	}

	/**
	* pegar_log_acoes
	* 
	* Grava a ação do usuário no banco de dados
	* 
	*
	* @access	public
	* @param	int
	* @return null
	*/
	public function pegar_log_acessos($limit,$todos=false){

		if($todos & !$this->session->userdata('administrador')){
			return null;
		}

		$this->db->select('codemin_log_acessos.ip_usuario, codemin_log_acessos.user_agent, codemin_log_acessos.data');

		if($todos){
			$this->db->select('codemin_usuarios.id, codemin_usuarios.nome');
			$this->db->join('codemin_usuarios','codemin_usuarios.id = codemin_log_acessos.id_usuario');
		} else {
			$this->db->where('id_usuario',$this->session->userdata('id'));
		}

		$this->db->limit($limit);
		$this->db->order_by('data','desc');
		return $this->db->get('codemin_log_acessos')->result();

	}

	/**
	* gravar_opcoes_selecionadas
	* 
	* Limpa todos os registros com a mesma tabela e id
	* e grava novamente os valores no banco
	* 
	*
	* @access	private
	* @param	int
	* @return null
	*/
	private function gravar_opcoes_selecionadas($array,$name,$id){

		$tabela = $this->uri->segment(2);

		$this->db->where('tabela',$tabela);
		$this->db->where('id_registro',$id);
		$this->db->where('campo',$name);
		$this->db->delete('codemin_opcoes_selecionadas');

		if(is_array($array)){
			$inserir = array();
			foreach ($array as $opcao) {
				$inserir[] = array(
					'tabela' => $tabela,
					'id_registro' => $id,
					'campo' => $name,
					'id_opcao' => $opcao
				);
			}
			$this->db->insert_batch('codemin_opcoes_selecionadas',$inserir);
		}

	}

	/**
	* migrate
	* 
	* Faz o migrate das tabelas no banco
	* de acordo com os inputs passados por array
	* 
	*
	* @access	public
	* @param	$array, boolaan
	* @return null
	*/
	public function migrate($dados,$config = false){

		$tabela = $this->uri->segment(2);

		// CRIAR A TABELA
		echo "<div class='alert alert-info'>CRIAR TABELA SE NAO EXISTIR '".$this->db->dbprefix($tabela)."'</div>";
		$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix($tabela)."` (
			`id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`),
		  	`ativo` tinyint(1) NULL,
		  	`ordenar` int(11) NOT NULL,
		  	`id_usuario` int(11) NOT NULL
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8");

		if($config){
			$this->db->where('id',1);
			if($this->db->get($tabela)->num_rows() <= 0){
				$this->db->query("INSERT INTO ".$this->db->dbprefix($tabela)." (`id`) VALUES (NULL)");
			}
		}

		$colunas = $this->db->list_fields($tabela);

		foreach ($dados as $dado) {
			$tipo 				= $dado['tipo'];
			$campo 				= $dado['campo'];

			if(!in_array($campo,$colunas)){

				switch ($tipo) {
					case 'input-text':
					case 'monetario':
					case 'imagem':
					case 'video':
					case 'video-vimeo':
					case 'video-youtube':
					case 'google-maps':
						$query = "ALTER TABLE `".$this->db->dbprefix($tabela)."` ADD `$campo` VARCHAR( 255 ) NOT NULL";
						$texto = "ADICIONAR COLUNA '".$this->db->dbprefix($tabela)."' ADD `$campo` VARCHAR( 255 )";
						break;

					case 'data':
						$query = "ALTER TABLE `".$this->db->dbprefix($tabela)."` ADD `$campo` DATE NOT NULL";
						$texto = "ADICIONAR COLUNA '".$this->db->dbprefix($tabela)."' ADD `$campo` DATE";
						break;

					case 'text-area':
					case 'text-area-rich':
					case 'text-area-rich-simple':
					case 'text-area-rich-full':
						$query = "ALTER TABLE `".$this->db->dbprefix($tabela)."` ADD `$campo` LONGTEXT NOT NULL";
						$texto = "ADICIONAR COLUNA '".$this->db->dbprefix($tabela)."' ADD `$campo` LONGTEXT";
						break;

					case 'select':
					case 'select-dinamico':
					case 'radio':
					case 'radio-dinamico':
						$query = "ALTER TABLE `".$this->db->dbprefix($tabela)."` ADD `$campo` INT( 11 ) NOT NULL";
						$texto = "ADICIONAR COLUNA '".$this->db->dbprefix($tabela)."' ADD `$campo` INT( 11 )";
						break;

					case 'imagens':
					case 'checkbox':
					case 'checkbox-dinamico':
						$query = "ALTER TABLE `".$this->db->dbprefix($tabela)."` ADD `$campo` TINYINT( 1 ) NULL DEFAULT NULL";
						$texto = "ADICIONAR COLUNA '".$this->db->dbprefix($tabela)."' ADD `$campo` TINYINT( 1 ) NULL DEFAULT NULL";
				}

				$this->db->query($query);

				echo "<div class='alert alert-info'>$texto</div>";

				if(isset($dado['url_amigavel'])& !in_array('url_'.$campo,$colunas)){
					$query = "ALTER TABLE `".$this->db->dbprefix($tabela)."` ADD `url_$campo` VARCHAR( 255 ) NOT NULL";
					$texto = "ADICIONAR COLUNA '".$this->db->dbprefix($tabela)."' ADD `url_$campo` VARCHAR( 255 )";

					$this->db->query($query);
					echo "<div class='alert alert-info'>$texto</div>";
				}

			}

		}

	}

	/**
	*
	* instalar
	* 
	* Cria as tabelas iniciais e adicionar o primeiro usuário administrador
	* 
	*
	* @access	public
	* @param	null
	* @return	null
	*/

	public function instalar(){

		// CRIAR A TABELA DE LOG DE ACESSOS (PADRÃO)
		echo "<div class='alert alert-success'>CRIAR TABELA SE NAO EXISTIR '".$this->db->dbprefix('codemin_log_acessos')."'</div>";
		$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix('codemin_log_acessos')."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_usuario` int(11) NOT NULL,
		  `ip_usuario` varchar(15) NOT NULL,
		  `user_agent` varchar(255) NOT NULL,
		  `data` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

		// CRIAR A TABELA DE LOG DE AÇÕES (PADRÃO)
		echo "<div class='alert alert-success'>CRIAR TABELA SE NAO EXISTIR '".$this->db->dbprefix('codemin_log_acoes')."'</div>";
		$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix('codemin_log_acoes')."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_usuario` int(11) NOT NULL,
		  `tabela` varchar(255) NOT NULL,
		  `id_registro` int(11) NOT NULL,
		  `acao` int(1) NOT NULL,
		  `data` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

		// CRIAR A TABELA DE OPÇÕES (PADRÃO)
		echo "<div class='alert alert-success'>CRIAR TABELA SE NAO EXISTIR '".$this->db->dbprefix('codemin_opcoes')."'</div>";
		$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix('codemin_opcoes')."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `tabela` varchar(255) NOT NULL,
		  `campo` varchar(255) NOT NULL,
		  `opcao` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

		// CRIAR A TABELA DE OPÇÕES SELECIONADAS (PADRÃO)
		echo "<div class='alert alert-success'>CRIAR TABELA SE NAO EXISTIR '".$this->db->dbprefix('codemin_opcoes_selecionadas')."'</div>";
		$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix('codemin_opcoes_selecionadas')."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `tabela` varchar(255) NOT NULL,
		  `campo` varchar(255) NOT NULL,
		  `id_registro` int(11) NOT NULL,
		  `id_opcao` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

		// CRIAR A TABELA DE UPLOADS (PADRÃO)
		echo "<div class='alert alert-success'>CRIAR TABELA SE NAO EXISTIR '".$this->db->dbprefix('codemin_uploads')."'</div>";
		$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix('codemin_uploads')."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `tipo` varchar(255) NOT NULL,
		  `tabela` varchar(255) NOT NULL,
		  `registro` varchar(255) NOT NULL,
		  `campo` varchar(255) NOT NULL,
		  `arquivo` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

		// CRIAR A TABELA DE USUÁRIOS (PADRÃO)
		echo "<div class='alert alert-success'>CRIAR TABELA SE NAO EXISTIR '".$this->db->dbprefix('codemin_usuarios')."'</div>";
		$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix('codemin_usuarios')."` (
		  `id` int(1) NOT NULL AUTO_INCREMENT,
		  `ativo` tinyint(1) NOT NULL,
		  `administrador` tinyint(1) NOT NULL,
		  `nome` varchar(255) NOT NULL,
		  `login` varchar(255) NOT NULL,
		  `senha` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

		// CRIAR A TABELA DE USUÁRIOS PERMISSÕES (PADRÃO)
		echo "<div class='alert alert-success'>CRIAR TABELA SE NAO EXISTIR '".$this->db->dbprefix('codemin_usuarios_permissoes')."'</div>";
		$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix('codemin_usuarios_permissoes')."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_usuario` int(11) NOT NULL,
		  `nivel` int(1) NOT NULL,
		  `area` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

		$nome 			= $this->input->post('nome');
		$login 			= $this->input->post('login');
		$senha			= $this->input->post('senha');
		$senha_hash	= senha_usuario($this->input->post('senha'));
		$inserir = array(
			'ativo' => 1,
			'administrador' => 1,
			'nome' => $nome,
			'login' => $login,
			'senha' => $senha_hash
		);
		$this->db->insert('codemin_usuarios',$inserir);
		echo "<div class='alert alert-success'>INSERIDO USUÁRIO $login COM A SENHA $senha NA TABELA '".$this->db->dbprefix('codemin_usuarios_permissoes')."'</div>";

	}

}