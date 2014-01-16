<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Codemin {

	private $configuracoes = array();
	private $gerenciador = array();
	public function __construct($configuracoes) {

		// $this->output->enable_profiler(TRUE);

		$this->load->model('codemin_model');

		$logado = $this->codemin_model->verificar_login();
		$area = $this->uri->segment(2);

		// Verifica se o usuário está logado
		if(!$logado & $area != 'login'){
			$this->session->set_flashdata('erro', 'Você precisa estar logado para acessar o sistema!');
			redirect(base_url().$this->uri->segment(1).'/login','location');
			$this->session->sess_destroy();

		} elseif($logado & $area == 'login') {
			$this->session->set_flashdata('sucesso', 'Você já está logado!');
			redirect(base_url().$this->uri->segment(1),'location');

		}

		/*
		*	Verifica se o usuário tem permissão para
		* acessar a área e qual o nível de permissão
		*/
		if($logado){
			$nivel = $this->codemin_model->verificar_permissao();
			if($nivel == 0 & $area != 'logout' & $area != null){
				// Redireciona para a home
				$this->session->set_flashdata('erro', 'Você não tem acesso para acessar essa área!');
				redirect(base_url().$this->uri->segment(1),'location');
			} else {
				$configuracoes['nivel_acesso'] = $nivel;
			}
		}

		/*
		* Verifica quais áreas o usuário tem ao menos
		* permissão de acesso para exibir na navbar
		*
		*/
		$this->db->where('id_usuario',$this->session->userdata('id'));
		$this->db->where('nivel >',0);
		$permissoes = $this->db->get('codemin_usuarios_permissoes')->result();

		$configuracoes['scriptFooter'] 	= array();
		$configuracoes['contentBody']		= array();

		$this->configuracoes = $configuracoes;
		return $this;

	}

	/**
	*
	* __get
	* 
	* Enables the use of CI super-global without having to define an extra variable.
	* 
	*
	* @access	public
	* @param	$var
	* @return	mixed
	*/
	public function __get($var){
	  return get_instance()->$var;
	}

  /**
	*
	* montar_codemin
	* 
	* Faz a montagem das views para o CRUD
	* 
	*
	* @access	public
	* @param	$array, $string, $array
	* @return	null
	*/
	public function montar_codemin($dados,$titulo,$listagem=null){

		$acao = $this->uri->segment(3);

		$data['navlinks'] 	= $this->configuracoes['navlinks'];
		$data['activelink'] = $this->configuracoes['activelink'];

		$data['nivel_acesso'] = $this->configuracoes['nivel_acesso'];

		$this->load->view('codemin/header_view',$data);
		$this->load->view('codemin/nav_view');

		/*
		*
		*   Cria a tabela com os campos do array $dados.
		*
		*   Ao colocar online comentar o if
		*   e em 'application/config/database.php'
		*   deixar o db_debug setado como false
		*
		*   $db['default']['db_debug'] = TRUE;
		*
		*/
		if($acao == 'migrate'){
			$this->codemin_model->migrate($dados);
		}

		switch ($acao) {
			case 'adicionar':
				$this->adicionar_codemin($dados,$titulo);
				break;

			case 'editar':
				$this->editar_codemin($dados,$titulo);
				break;

			case 'excluir':
				$this->excluir_codemin($dados);
				break;
			
			default:
				$this->listar_codemin($listagem,$titulo);
				break;
		}

		$footer['scriptFooter'] = $this->configuracoes['scriptFooter'];
		$this->load->view('codemin/footer_view',$footer);

	}


	/**
	* montar_codemin_config
	* 
	* Faz a montagem da view e cria o update
	* 
	*
	* @access	public
	* @param	$array, $string, boolean
	* @return	null
	*/
	public function montar_codemin_config($dados,$titulo,$usuario=false){

		/*
		*
		*   Cria a tabela com os campos do array $dados.
		*
		*   Ao colocar online comentar o if
		*   e em 'application/config/database.php'
		*   deixar o db_debug setado como false
		*
		*   $db['default']['db_debug'] = TRUE;
		*
		*/
		if($this->uri->segment(3) == 'migrate'){
			$this->codemin_model->migrate($dados,true);
		}

		$data['navlinks'] 	= $this->configuracoes['navlinks'];
		$data['activelink'] = $this->configuracoes['activelink'];
		$data['nivel_acesso'] = $this->configuracoes['nivel_acesso'];

		$this->load->view('codemin/header_view',$data);
		$this->load->view('codemin/nav_view');

		$this->editar_codemin($dados,$titulo,$usuario);

		$footer['scriptFooter'] = $this->configuracoes['scriptFooter'];
		$this->load->view('codemin/footer_view',$footer);

	}


	/**
	* montar_codemin_usuarios
	* 
	* Faz a montagem das views para o usuário,
	* busca as permissões no banco de dados e
	* cria os radios box para cada área.
	* 
	*
	* @access	public
	* @param	$string
	* @return	null
	*/
	public function montar_codemin_usuarios($titulo){

		$data['areas'] 			= $this->configuracoes['areas'];
		$data['navlinks'] 	= $this->configuracoes['navlinks'];
		$data['activelink'] = $this->configuracoes['activelink'];

		$this->configuracoes['navlinks']['usuarios'] = "Usuários";

		$data['contentBody'] = $this->configuracoes['contentBody'];
		$data['nivel_acesso'] = $this->configuracoes['nivel_acesso'];

		$this->load->view('codemin/header_view',$data);
		$this->load->view('codemin/nav_view');

		// Dados dos usuários
		$dados[] = array( // Nome do usuário
			'titulo' => 'Nome',
			'campo' => 'nome',
			'tipo' => 'input-text',
			'placeholder' => 'Nome Completo'
		);
		$dados[] = array( // Login
			'titulo' => 'Login',
			'campo' => 'login',
			'tipo' => 'input-text',
			'placeholder' => 'Login do usuário'
		);
		$dados[] = array( // Nome do usuário
			'titulo' => 'Senha',
			'campo' => 'senha',
			'tipo' => 'senha',
			'placeholder' => 'Senha do usuário'
		);
		$array = array(0 => 'Não', 1 => 'Sim');
		$dados[] = array( // Nome do usuário
			'titulo' => 'Administrador',
			'campo' => 'administrador',
			'tipo' => 'select',
			'dados' => $array,
			'placeholder' => 'Senha do usuário'
		);

		$data['titulo'] = $titulo;

		$tabela = 'codemin_usuarios';
		$acao 	= $this->uri->segment(3);
		$id 		= $this->uri->segment(4);

		$this->load->library('form_validation');		

		switch ($acao) {

			case 'adicionar':

				$this->form_validation->set_rules('nome', 'Nome', 'required|min_length[6]|max_length[255]');
				$this->form_validation->set_rules('login', 'Login', 'required|is_unique[codemin_usuarios.login]|min_length[4]|max_length[20]');
				$this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]|max_length[255]');

				if($_POST & $this->form_validation->run() == TRUE){

					$insert = array(
						'nome' 					=> $this->input->post('nome'),
						'login' 				=> $this->input->post('login'),
						'senha' 				=> senha_usuario($this->input->post('senha')),
						'administrador' => $this->input->post('administrador'),
						'ativo' => $this->input->post('ativo')
					);
					if($this->db->insert($tabela,$insert)){
						$this->session->set_flashdata('sucesso', 'Registro adicionado com sucesso!');
						redirect('/administrador/usuarios/','location');
					} else {
						$this->session->set_flashdata('erro', 'Houve um erro ao adicionar o registro!');
					}

				}

				// Inicia a montagem do formulário
				$data['dados']  = $this->montar_campos($dados);
				$data['contentBody'] = $this->configuracoes['contentBody'];
				$this->load->view('codemin/adicionar_view',$data);

				break;

			case 'editar':

				$this->form_validation->set_rules('nome', 'Nome', 'required|min_length[6]|max_length[255]');
				$this->form_validation->set_rules('login', 'Login', 'required|min_length[4]|max_length[20]');
				$this->form_validation->set_rules('senha', 'Senha', 'min_length[6]|max_length[255]');

				if($_POST & $this->form_validation->run() == TRUE){

					$update = array(
						'nome' 					=> $this->input->post('nome'),
						'login' 				=> $this->input->post('login'),
						'administrador' => $this->input->post('administrador'),
						'ativo' => $this->input->post('ativo')
					);
					if($this->input->post('senha')){
						$update['senha'] = senha_usuario($this->input->post('senha'));
					}
					$this->db->where('id',$id);
					if($this->db->update($tabela,$update)){

						// Permissões do usuário
						$this->db->where('id_usuario',$id);
						$this->db->delete($tabela.'_permissoes');
						foreach ($this->configuracoes['areas'] as $key => $value) {
							$insert = array(
								'area' => $key,
								'nivel' => $this->input->post('usuario-'.$key),
								'id_usuario' => $id
							);
							$this->db->insert($tabela.'_permissoes',$insert);
						}

						$this->session->set_flashdata('sucesso', 'Registro editado com sucesso!');
						redirect('/administrador/usuarios/','location');
					} else {
						$this->session->set_flashdata('erro', 'Houve um erro ao adicionar o registro!');
					}

				}

				// Pega os registros do banco
				$this->db->where('id',$id);
				$resultado = $this->db->get($tabela)->row_array();

				// Pega as permissoes
				$this->db->where('id_usuario',$id);
				$permissoes = array();
				foreach($this->db->get($tabela.'_permissoes')->result() as $permissao){
					$permissoes[$permissao->area]	= $permissao->nivel;
				}

				// Inicia a montagem do formulário
				$data['dados']  = $this->montar_campos($dados,$resultado,$permissoes);
				$data['contentBody'] = $this->configuracoes['contentBody'];
				$this->load->view('codemin/editar_view',$data);

				break;

			case 'excluir':

				$id 	= $this->uri->segment(4);

				$this->db->where('id',$id);
				$this->db->delete($tabela);

				redirect('/administrador/usuarios','location');

				break;
			
			default:

					$data['listagens'] 	= array('nome','login');
					$data['titulo']		= $titulo;

					// Pega todos os resultados
					$this->db->order_by('id','desc');
					$data['resultados'] = $this->db->get($tabela)->result_array();

					// Monta a view com a listagem
					$this->load->view('codemin/listagem_view',$data);

				break;
		}

		$footer['scriptFooter'] = $this->configuracoes['scriptFooter'];
		$this->load->view('codemin/footer_view',$footer);

	}

	/**
	*
	* adicionar_codemin
	* 
	* Exbir o formulário para adicionar e insere se tiver POST
	* 
	*
	* @access	private
	* @param	$array, $string
	* @return	null
	*/
	private function adicionar_codemin($dados,$titulo){

		$tabela = $this->uri->segment(2);
		$nivel_acesso = $this->configuracoes['nivel_acesso'];

		$resultado = $this->codemin_model->adicionar($dados,$nivel_acesso);

		if($resultado){
			$this->session->set_flashdata('sucesso', 'Registro adicionado com sucesso!');
			redirect('/administrador/'.$tabela,'location');
		} elseif($resultado === FALSE) {
			$this->session->set_flashdata('erro', 'Houve um erro ao adicionar o Registro!');
		}

		// Inicia a montagem do formulário
		$data['dados']  = $this->montar_campos($dados);
		$data['titulo'] = $titulo;

		$data['contentBody'] = $this->configuracoes['contentBody'];

		$this->load->view('codemin/adicionar_view',$data);
	}

	/**
	*
	* editar_codemin
	* 
	* Exbir o formulário para editar e atualiza se tiver POST
	* 
	*
	* @access	private
	* @param	$array, $string, boolean
	* @return	null
	*/
	private function editar_codemin($dados,$titulo,$usuario = false){

		// Pega os dados básicos
		$tabela 			= $this->uri->segment(2);
		$nivel_acesso = $this->configuracoes['nivel_acesso'];

		// Se tiver um post edita
		$editado = $this->codemin_model->editar($dados,$nivel_acesso,$usuario);
		if($editado){
			$this->session->set_flashdata('sucesso', 'Registro editado com sucesso!');
			redirect('/administrador/'.$tabela,'location');
		} elseif($editado === false) {
			$this->session->set_flashdata('erro', 'Houve um erro ao editar o registro!');
		}

		// Busca os resultado do item
		$resultado = $this->codemin_model->resultado($nivel_acesso);

		// Se não retornar nenhum item, dá mensagem de erro
		if(!$resultado & !$usuario){
			$this->session->set_flashdata('erro', 'Esse registro não existe ou você não tem permissão para editar');
			redirect('/administrador/'.$tabela,'location');
		}

		// Inicia a montagem da tabela
		$data['dados']  = $this->montar_campos($dados,$resultado);
		$data['titulo'] = $titulo;
		
		$data['contentBody'] = $this->configuracoes['contentBody'];

		$this->load->view('codemin/editar_view',$data);

	}

	/**
	*
	* excluir_codemin
	* 
	* Exclui o registro do banco de dados
	* 
	*
	* @access	private
	* @param	$array
	* @return	null
	*/
	private function excluir_codemin($dados){

		$nivel_acesso = $this->configuracoes['nivel_acesso'];
		$tabela 			= $this->uri->segment(2);

		$this->codemin_model->excluir($nivel_acesso);

		$this->session->set_flashdata('sucesso', 'Registro removido com sucesso!');
		redirect('/administrador/'.$tabela,'location');
	}

	/**
	*
	* listar_codemin
	* 
	* Lista todos os registros
	* 
	*
	* @access	private
	* @param	$array, $titulo
	* @return	null
	*/
	private function listar_codemin($listagem,$titulo){

		$nivel_acesso = $this->configuracoes['nivel_acesso'];

		$return = $this->codemin_model->resultados($listagem,$nivel_acesso);

		$data['nivel_acesso'] = $nivel_acesso;
		$data['titulo']				= $titulo;
		$data['listagens'] 		= $return->listagens;
		$data['resultados'] 	= $return->resultados;

		// Monta a view com a listagem
		$this->load->view('codemin/listagem_view',$data);

	}

	/**
	*
	* montar_campos
	* 
	* Monta a estrutura dos campos e chamada a função montar_campo para montar cada um individual
	* 
	*
	* @access	private
	* @param	$array, $array
	* @return	$string
	*/
	private function montar_campos($campos,$resultado=null,$permissoes=null){

		// Guarda algumas variáveis
		$nivel_acesso 	= $this->configuracoes['nivel_acesso'];
		$area 			= $this->uri->segment(2);
		$acao 			= ucfirst($this->uri->segment(3,'Editar'));

		// Abre o formulário
		$data = array(
			'class' => 'form-horizontal',
			'accept-charset' => 'utf8'
		);
		$return = form_open_multipart(null,$data);

		// Para cada campo monta o html básico e insere o campo formatado
		foreach ($campos as $campo) {

			$valor = null;
			if(isset($resultado)){
				$coluna = $campo['campo'];

				if(isset($resultado[$coluna])){
					$valor = $resultado[$coluna];
				}
				
			}
			$input = $this->montar_campo($campo,$valor);

			$dica = null;
			if(isset($campo['dica'])){
				$dica = "<span class='help-block'>" . $campo['dica'] . "</span>";
			}

			$return .= "<div class='control-group'>
				<label class='control-label'>" . $campo['titulo'] . ":</label>
				<div class='controls'>
					$input
					$dica
				</div>
			</div>";
		}

		if($permissoes !== null){
			$return .= '<hr/>';
			foreach ($this->configuracoes['areas'] as $key => $value) {
				$array = array(
					0 => 'Nenhum',
					1 => 'Acesso',
					2 => 'Acesso e Publicar',
					3 => 'Total'
				);
				$permissao = null;
				if(isset($permissoes[$key])){
					$permissao = $permissoes[$key];
				}
				$radios = $this->montar_radios($array,'usuario-'.$key,$permissao);
				$return .= "<div class='control-group'>
					<label class='control-label'>" . $value . ":</label>
					<div class='controls'>
						$radios
					</div>
				</div>";
			}
		}

		if($nivel_acesso >= 2){
				$array = array(
					1 => 'Ativo',
					0 => 'Inativo'
				);
				$radios = $this->montar_radios($array,'ativo',$resultado['ativo']);
				$return .= "
					<hr/><div class='control-group'>
					<label class='control-label'><b>Ativo:</b></label>
					<div class='controls'>
						$radios
					</div>
				</div>";
		}

		$excluir = null;
		if($this->uri->segment(3) == 'editar'){
			$excluir = "<a class='btn btn-danger confirmar-excluir' href='".base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/excluir/".$this->uri->segment(4)."'>Excluir</a>";
		}
		$return .= "<div class='form-actions'>
		    <input type='submit' value='$acao' class='btn btn-success' />
		    $excluir
		    <a href='".base_url()."administrador/".$this->uri->segment(2)."' value='$acao' class='btn' />Voltar</a>
	    </div>";
		$return .= form_close();

		return $return;

	}

	/**
	*
	* montar_campo
	* 
	* Monta cada campo indivídualmente,
	* insere script no rodapé ou código no
	* corpo do formulário quando necessário
	* 
	*
	* @access	private
	* @param	$array, $string
	* @return	$string
	*/
	private function montar_campo($campo,$valor=null){

		// Guarda algumas variáveis
		$acao 			= $this->uri->segment(3);
		$name 			= $campo['campo'];
		$tipo 			= $campo['tipo'];
		$titulo 		= $campo['titulo'];

		$placeholder = null;
		if (isset($campo['placeholder'])) {
			$placeholder 	= $campo['placeholder'];
		}

		// Se tiver algum registro, faz a conversão do banco
		if($valor){
			$valor = $this->codemin_model->converter_do_banco($valor,$tipo);
		}

		switch ($tipo) {

			// Input text comum
			case 'input-text':
			case 'video':
			case 'video-vimeo':
			case 'video-youtube':
				$data = array(
	              'name'        => $name,
	              'value'       => set_value($name,$valor),
	              'placeholder'	=> $placeholder,
	              'class'				=> 'span7'
	            );
				return form_input($data);
				break;

			// Input text com máscara para data
			case 'data':
				$data = array(
	              'name'        => $name,
	              'value'       => set_value($name,$valor),
	              'placeholder'	=> $placeholder,
	              'alt'			=> 'date'
	            );
				return form_input($data);
				break;

			// Input text com máscara para valor monetário
			case 'monetario':
				$data = array(
	              'name'        => $name,
	              'value'       => set_value($name,$valor),
	              'placeholder'	=> $placeholder,
	              'alt'			=> 'decimal'
	            );
				return form_input($data);
				break;

			// Input text com máscara para valor monetário
			case 'senha':
				$data = array(
	              'name'        => $name,
	              'placeholder'	=> $placeholder
	            );
				return form_password($data);
				break;

			// Textarea simples
			case 'text-area':
				$data = array(
	              'name'        => $name,
	              'placeholder'	=> $placeholder,
	              'value' => set_value($name,$valor)
	            );
				return form_textarea($data);
				break;

			//  Textarea com CKEDITOR
			case 'text-area-rich-simple':
			case 'text-area-rich':
			case 'text-area-rich-full':
				$botao = null;
				if(isset($campo['imagens'])){
					$botao = $this->banco_de_imagens();
				}
				$this->configuracoes['scriptFooter'][] = "<script>CKEDITOR.replace( '$name' , { baseHref: '" . base_url() . "', height : '600'} );</script>";
				return form_textarea($name, set_value($name,$valor)).$botao;
				break;

			// Select com opções vindas de array
			case 'select':
				return form_dropdown($name, $campo['dados'], set_value($name,$valor));
				break;

			// Select com gerenciador de categorias em AJAX
			case 'select-dinamico':
				$opcoes = $this->pegar_opcoes($name);
				$this->configuracoes['contentBody'][] = $this->modal_opcoes($name,$opcoes,$titulo);
				$this->configuracoes['scriptFooter'][] = "<script>
					$(document).ready(function(){
					    opcoes('".base_url()."opcoes/','$name','".$this->uri->segment(2)."','select');
					})
				</script>";
				return form_dropdown($name, $opcoes, set_value($name,$valor)).
				' <a href="#modal-'.$name.'" role="button" class="btn btn-primary btn-mini" data-toggle="modal">Gerenciar</a>';;
				break;

			// Select com opções vindas de array
			case 'google-maps':
				if(!isset($valor)){
					$valor = "-27.56848020860876, -48.614231379376236";
				}
				$this->configuracoes['scriptFooter']['google-maps'] = '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>';
				$this->configuracoes['scriptFooter'][] = "<script>
					$(document).ready(function(){
					    google_maps('$name',$valor);
					})
				</script>";
				$hidden_maps = array(
					'type' => 'hidden',
          'name'  => $name,
          'value' => set_value($name,$valor),
          'id' => "input-google-maps-$name"
        );
				return "<style>#google-maps-$name img { max-width: none; }</style><div id='google-maps-$name' style='width: 100%; height: 400px;'></div>" . 
				form_input($hidden_maps);
				break;

			// Radios
			case 'radio':
				return $this->montar_radios($campo['dados'],$name,$valor);
				break;

			// Select com gerenciador de categorias em AJAX
			case 'radio-dinamico':
				$opcoes = $this->pegar_opcoes($name);
				$this->configuracoes['contentBody'][] = $this->modal_opcoes($name,$opcoes,$titulo);
				$this->configuracoes['scriptFooter'][] = "<script>
					$(document).ready(function(){
					    opcoes('".base_url()."opcoes/','$name','".$this->uri->segment(2)."','radio');
					})
				</script>";
				return '<span id="label_' . $campo['campo'] . '">' . $this->montar_radios($opcoes,$name,$valor) . '</span>' .
				' <a href="#modal-'.$name.'" role="button" class="btn btn-primary btn-mini" data-toggle="modal">Gerenciar</a>';;
				break;

			// Checkbox
			case 'checkbox':
				return $this->montar_checkbox($campo['dados'],$name);
				break;

			// Select com gerenciador de categorias em AJAX
			case 'checkbox-dinamico':
				$opcoes = $this->pegar_opcoes($name);
				$this->configuracoes['contentBody'][] = $this->modal_opcoes($name,$opcoes,$titulo);
				$this->configuracoes['scriptFooter'][] = "<script>
					$(document).ready(function(){
					    opcoes('".base_url()."opcoes/','$name','".$this->uri->segment(2)."','checkbox');
					})
				</script>";
				return '<span id="label_' . $campo['campo'] . '">' . $this->montar_checkbox($opcoes,$name) . '</span>' .
				' <a href="#modal-'.$name.'" role="button" class="btn btn-primary btn-mini" data-toggle="modal">Gerenciar</a>';;
				break;

			// Upload de imagem no submit (sem ajax)
			case 'imagem':
				$imagem = null;
				if($valor){
					$imagem = "<img src='".base_url()."uploads/".$this->uri->segment(2)."/imagens/".$this->uri->segment(4,1)."/mini_thumbs/".$valor."' /> ";
				}
				return $imagem.form_upload($name);
				break;

			// Upload de imagens por ajax usando o Jquery Upload
			case 'imagens':

				if($acao == 'adicionar'){
					return '<span>Disponível em editar</span>';
				}

				$this->configuracoes['contentBody'][] = '<!-- Modal -->
				<div id="modal-'.$name.'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 980px; margin-left: -490px;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">Gerenciar Imagens para '.$titulo.'</h3>
					</div>
					<div class="modal-body">
						<form id="upload-'.$name.'" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
					        <div class="row fileupload-buttonbar">
					            <div class="span7">
					                <span class="btn btn-success fileinput-button">
					                    <i class="icon-plus icon-white"></i>
					                    <span>Adicionar...</span>
					                    <input type="file" name="files[]" multiple>
					                </span>
					                <button type="submit" class="btn btn-primary start">
					                    <i class="icon-upload icon-white"></i>
					                    <span>Iniciar upload</span>
					                </button>
					                <button type="reset" class="btn btn-warning cancel">
					                    <i class="icon-ban-circle icon-white"></i>
					                    <span>Cancelar upload</span>
					                </button>
					                <button type="button" class="btn btn-danger delete">
					                    <i class="icon-trash icon-white"></i>
					                    <span>Deletar</span>
					                </button>
					                <input type="checkbox" class="toggle">
					                <span class="fileupload-loading"></span>
					            </div>
					            <div class="span5 fileupload-progress fade">
					                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
					                    <div class="bar" style="width:0%;"></div>
					                </div>
					                <div class="progress-extended">&nbsp;</div>
					            </div>
					        </div>
					        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
					    </form>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Salvar e Fechar</button>
					</div>
				</div>';
				$this->configuracoes['scriptFooter'][] = "<script>
					$(document).ready(function(){
					    uploader('#upload-$name','".base_url()."uploader/index/imagem/".$this->uri->segment(2)."/".$this->uri->segment(4,1)."/".$name."');
					})
				</script>";
				return '<a href="#modal-'.$name.'" role="button" class="btn btn-primary" data-toggle="modal">Gerenciar Imagens</a>';
				break;
		}

	}


	/**
	*
	* banco_de_imagens
	* 
	* Cria um modal, a chamada para função
	* javacrip e retorna o botão para abrir
	* o modal do Banco de imagens
	* 
	*
	* @access	private
	* @param	null
	* @return	$string
	*/
	private function banco_de_imagens(){
		$this->configuracoes['contentBody']['banco-de-imagens'] = '<!-- Modal -->
		<div id="modal-banco-de-imagens" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 980px; margin-left: -490px;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Gerenciar Banco de Imagens</h3>
			</div>
			<div class="modal-body">
				<form id="upload-banco-de-imagens" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
			        <div class="row fileupload-buttonbar">
			            <div class="span7">
			                <span class="btn btn-success fileinput-button">
			                    <i class="icon-plus icon-white"></i>
			                    <span>Adicionar...</span>
			                    <input type="file" name="files[]" multiple>
			                </span>
			                <button type="submit" class="btn btn-primary start">
			                    <i class="icon-upload icon-white"></i>
			                    <span>Iniciar upload</span>
			                </button>
			                <button type="reset" class="btn btn-warning cancel">
			                    <i class="icon-ban-circle icon-white"></i>
			                    <span>Cancelar upload</span>
			                </button>
			                <button type="button" class="btn btn-danger delete">
			                    <i class="icon-trash icon-white"></i>
			                    <span>Deletar</span>
			                </button>
			                <input type="checkbox" class="toggle">
			                <span class="fileupload-loading"></span>
			            </div>
			            <div class="span5 fileupload-progress fade">
			                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
			                    <div class="bar" style="width:0%;"></div>
			                </div>
			                <div class="progress-extended">&nbsp;</div>
			            </div>
			        </div>
			        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
			    </form>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Salvar e Fechar</button>
			</div>
		</div>';
		$this->configuracoes['scriptFooter']['banco-de-imagens'] = "<script>
			$(document).ready(function(){
			    uploader('#upload-banco-de-imagens','".base_url()."uploader/index/imagem/banco_imagens/1/imagens');
			})
		</script>";
		return '<br/><a href="#modal-banco-de-imagens" role="button" class="btn btn-primary" data-toggle="modal">Gerenciar Galeria de Imagens</a>';
	}

	/**
	*
	* array_select
	* 
	* Cria um modal com um pequeno formulário
	* e um tabela abaixo com as opções gerenciadas
	* por ajax. Pode ser usada para select, checkbox
	* ou radio.
	* 
	*
	* @access	private
	* @param	$string, $string
	* @return	$array
	*/
	public function array_select($tabela,$campo,$order = 'asc'){

		$return = array();

		$this->db->where('ativo',1);
		$this->db->order_by($campo,$order);
		foreach ($this->db->get($tabela)->result_array() as $item) {
			$return[$item['id']] = $item[$campo];
		}
		return $return;

	}


	/**
	*
	* modal_opcoes
	* 
	* Cria um modal com um pequeno formulário
	* e um tabela abaixo com as opções gerenciadas
	* por ajax. Pode ser usada para select, checkbox
	* ou radio.
	* 
	*
	* @access	private
	* @param	$string, $array, $string
	* @return	$string
	*/
	private function modal_opcoes($name,$opcoes,$titulo){

		$opcoes_linhas = null;
		if(isset($opcoes)){
			foreach ($opcoes as $key => $value) {
				$opcoes_linhas .= "<tr><td>$value</td><td><input type='button' class='btn btn-danger' value='Remover'><input type='hidden' value='$key'/></tr>";
			}	
		}

		return '<!-- Modal -->
		<div id="modal-'.$name.'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Gerenciar Opções para '.$titulo.'</h3>
			</div>
			<div class="modal-body">
				<div class="controls-row">
					<input type="text" placeholder="Digite uma opção e clique em Adicionar" class="span5" /><input type="button" value="Adicionar" class="btn btn-success span2" />
				</div>
				<table class="table">
					<thead>
						<th>Nome</th>
						<th>Ações</th>
					</thead>
					<tbody>
						'.$opcoes_linhas.'
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Salvar e Fechar</button>
			</div>
		</div>';

	}

	private function montar_radios($valores,$name,$selecionado){

		$return = null;
		foreach ($valores as $key => $value) {

			$checado = null;
			if($key == $selecionado){
				$checado = "checked='checked'";
			}

			$return .= "<label class='radio'>
					<input type='radio' name='$name' value='$key' $checado>
					$value
				</label>";

		}

		return $return;

	}

	private function montar_checkbox($valores,$name){

		$this->db->where('tabela',$this->uri->segment(2));
		// $this->db->where('campo',$name);
		$this->db->where('id_registro',$this->uri->segment(4));
		$this->db->select('id_opcao');
		$marcados = array();
		foreach ($this->db->get('codemin_opcoes_selecionadas')->result() as $marcado) {
			$marcados[] = $marcado->id_opcao;
		}

		$return = null;
		foreach ($valores as $key => $value) {

			$checado = null;
			if(in_array($key, $marcados)){
				$checado = "checked='checked'";
			}

			$return .= "<label class='checkbox'>
					<input type='checkbox' name='$name"."[]' value='$key' $checado>
					$value
				</label>";

		}

		return $return;

	}

	/**
	* pegar_opcoes
	* 
	* Pega as opções do banco de dados e grava em uma array
	* 
	*
	* @access	private
	* @param	$string
	* @return	$array
	*/
	public function pegar_opcoes($name){

		$tabela = $this->uri->segment(2);

		$this->db->where('tabela',$tabela);
		$this->db->where('campo',$name);
		$opcoes = array();
		foreach ($this->db->get('codemin_opcoes')->result() as $opcao) {
			$opcoes[$opcao->id] = $opcao->opcao;
		}
		return $opcoes;
	}

	/**
	*
	* imagem_upload
	* 
	* Faz o uploade da imagem e chama outra função para criar as miniaturas
	* 
	*
	* @access	private
	* @param	$string, $string, $array
	* @return	$string
	*/
	public function imagem_upload($name,$id=null,$miniaturas){

		// print_r($miniaturas); exit;

		// PEGA AS INFORMAÇÕES DA IMAGEM
		$tabela = $this->uri->segment(2);
		$id 	= $this->uri->segment(4,$id);

		// CRIA O DIRETÓRIO DE UPLOAD
		if(!is_dir('./uploads')){
			mkdir('./uploads');
		}

		// CRIA O DIRETÓRIO DA TABELA
		if(!is_dir('./uploads/'.$tabela)){
			mkdir('./uploads/'.$tabela);
		}

		// CRIA O DIRETÓRIO PARA IMAGENS
		if(!is_dir('./uploads/'.$tabela.'/imagens/')){
			mkdir('./uploads/'.$tabela.'/imagens/');
		}

		// CRIA O DIRETÓRIO DO ID
		if(!is_dir('./uploads/'.$tabela.'/imagens/'.$id)){
			mkdir('./uploads/'.$tabela.'/imagens/'.$id);
		}

		// CRIA O DIRETÓRIO DA THUMB DO CODEMIN
		if(!is_dir('./uploads/'.$tabela.'/imagens/'.$id.'/mini_thumbs')){
			mkdir('./uploads/'.$tabela.'/imagens/'.$id.'/mini_thumbs');
		}

		// CRIA O DIRETÓRIO DA MICRO THUMB DO CODEMIN
		if(!is_dir('./uploads/'.$tabela.'/imagens/'.$id.'/micro_thumbs')){
			mkdir('./uploads/'.$tabela.'/imagens/'.$id.'/micro_thumbs');
		}

		foreach ($miniaturas as $miniatura) {
			// Cria o diretório para uma miniatura
			if(!is_dir('./uploads/'.$tabela.'/imagens/'.$id.'/'.$miniatura[0])){
				mkdir('./uploads/'.$tabela.'/imagens/'.$id.'/'.$miniatura[0]);
			}
		}

		$config['upload_path']   = './uploads/'.$tabela.'/imagens/'.$id; //Caminho onde será salvo
		$config['allowed_types'] = 'gif|jpg|png'; //Tipos de imagem aceito
		$config['overwrite']     = FALSE; //Não sobre-escrever o arquivo

		$file = $name; // Nome do campo INPUT do formulário
		$this->load->library('upload');
		$this->upload->initialize($config);

		

		$this->upload->do_upload($file);

		$dados = $this->upload->data();

		// INICIA A BIBLIOTECA DE IMAGEM
		$this->load->library('image_lib');

		// Cria a mini thubms
		$this->redimencionar_imagem('mini_thumbs',$tabela,$id,$dados,180,122,true);

		// Cria a micro thubms
		$this->redimencionar_imagem('micro_thumbs',$tabela,$id,$dados,50,35,true);

		foreach ($miniaturas as $miniatura) {
			// Cria uma thumb
			$this->redimencionar_imagem($miniatura[0],$tabela,$id,$dados,$miniatura[1],$miniatura[2],$miniatura[3]);
		}

		return $dados['file_name'];
	}

	/**
	*
	* redimencionar_imagem
	* 
	* Pega os dados do upload e redimenciona e corta
	* 
	*
	* @access	private
	* @param	$array
	* @return	null
	*/
	private function redimencionar_imagem($nome_miniatura,$tabela,$id,$dados,$largura,$altura,$recortar=false){

		if(!$recortar){

			// Dimencionar a imagem
			$config['image_library']  = 'GD2';
			$config['source_image']   = $dados['full_path'];
			$config['new_image']      = './uploads/'.$tabela.'/imagens/'.$id.'/'.$nome_miniatura.'/'.$dados['file_name'];
			$config['thumb_marker']   = null;
			$config['create_thumb']   = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']          = $largura;
			$config['height']         = $altura;
			$this->image_lib->initialize($config);
			$this->image_lib->resize();

		} else {

			// Pegar largura e altura da imagem
			$imgWid = $dados["image_width"];
			$imgHei = $dados["image_height"];

			// Definir altura e largura limites
			$widthExt  = $largura;
			$heightExt = $altura;
			// Fazer os cálculos das novas dimenções da imagem
			if((($imgHei*$widthExt)/$imgWid) >= $heightExt){
				$finalHeight = ($imgHei*$widthExt)/$imgWid;
				$finalWidth  = $widthExt;
				$finalY = ($finalHeight-$heightExt)/2;
				$finalX = 0;
			} else {
				$finalWidth = ($imgWid*$heightExt)/$imgHei;
				$finalHeight = $heightExt;
				$finalX = ($finalWidth-$widthExt)/2;
				$finalY = 0;
			}
			// Dimencionar a imagem
			$config['image_library']  = 'GD2';
			$config['source_image']   = $dados['full_path'];
			$config['new_image']      = './uploads/'.$tabela.'/imagens/'.$id.'/'.$nome_miniatura.'/'.$dados['file_name'];
			$config['thumb_marker']   = null;
			$config['create_thumb']   = TRUE;
			$config['width']          = $finalWidth;
			$config['height']         = $finalHeight;
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			// Fazer o corte
			$config['source_image']   = './uploads/'.$tabela.'/imagens/'.$id.'/'.$nome_miniatura.'/'.$dados['file_name'];
			$config['maintain_ratio'] = FALSE;
			$config['create_thumb']   = FALSE;
			$config['width']          = $widthExt;
			$config['height']         = $heightExt;
			$config['x_axis']         = $finalX;
			$config['y_axis']         = $finalY;
			$this->image_lib->initialize($config);
			$this->image_lib->crop();
			$this->image_lib->clear();

		}

	}

}

/* End of file ./application/libraries/codemin.php */ 