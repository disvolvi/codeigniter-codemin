<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* DESENVOLVIDO POR BRUNO ALMEIDA
*
* MAIO DE 2013
*/

class Administrador extends CI_Controller {

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
		// Radio dinâmico
		$array_select = array(0 => 'Claro', 1 => 'Escuro', 2 => 'Neutro');
		$dados[] = array(
			'titulo' => 'Grupo 2',
			'campo' => 'grupo2',
			'tipo' => 'radio-dinamico',
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

	public function configuracoes(){

		$titulo = "Configurações";
		// Título do site
		$dados[] = array(
			'titulo' => 'Título do site',
			'campo' => 'titulo',
			'tipo' => 'input-text',
			'placeholder' => 'Codemin - Painel Administrativo',
			'dica' => 'Título estára disponível no title do site'
		);
		// Select com informações de outra tabela
		$array_select = $this->codemin->array_select('exemplos','titulo'); // parâmetro: nome da tabela, nome do campo (id vem por padrão)
		$dados[] = array(
			'titulo' => 'Exemplo Destaque',
			'campo' => 'exemplo',
			'tipo' => 'select',
			'dados' => $array_select,
			'dica' => 'Os dados desse select são os registros ativos da tabela exemplos'
		);

		/*
		* $this->codemin->montar_codemin_config = Cria um único registro com os input passados
		*
		* $dados = os inputs que o usuário irá usar para gerenciar o site
		*
		* $titulo = string com o título da página
		*/
		$this->codemin->montar_codemin_config($dados,$titulo);

	}

	public function perfil(){

		$titulo = "Perfil";
		$dados[] = array(
			'titulo' => 'Facebook',
			'campo' => 'facebook',
			'tipo' => 'input-text',
			'placeholder' => 'disvolvi',
			'dica' => 'Somente o que vier após http://facebook.com/'
		);
		$dados[] = array(
			'titulo' => 'Twitter',
			'campo' => 'twitter',
			'tipo' => 'input-text',
			'placeholder' => '@disvolvi',
			'dica' => 'Ex: "@disvolvi"'
		);

		/*
		* $this->codemin->montar_codemin_config (útlimo parâmetro true) = Cria um registro para cada usuário com os input passados
		*
		* $dados = os inputs que o usuário irá usar para gerenciar o site
		*
		* $titulo = string com o título da página
		*/
		$this->codemin->montar_codemin_config($dados,$titulo,true);

	}


	/* ================================
	*
	* Não modificar as funções abaixo
	*
	* =============================== */ 

	private $configuracoes = array();

	// Função padrão, não remover
	public function __construct() {
		parent::__construct();

		$metodosSistema = array('__construct','index','get_instance','login','logout','usuarios');

		$areas = array();
		$navlinks = array();

		if($this->session->userdata('administrador')){

			foreach(get_class_methods($this) as $metodo){
				if(!in_array($metodo, $metodosSistema)){
					$areas[$metodo] 		= str_replace("_"," ",ucfirst($metodo));
					$navlinks[$metodo] 	= str_replace("_"," ",ucfirst($metodo));
				}
			}

		} else {

			$this->db->where('id_usuario',$this->session->userdata('id'));
			$this->db->where('nivel >',0);
			$acessos = $this->db->get('codemin_usuarios_permissoes')->result();

			$permissoes = array();
			foreach ($acessos as $acesso) {
				$permissoes[] = $acesso->area;
			}

			foreach(get_class_methods($this) as $metodo){
				if(!in_array($metodo, $metodosSistema)){
					$areas[$metodo] = str_replace("_"," ",ucfirst($metodo));
				}
				if(!in_array($metodo, $metodosSistema) & in_array($metodo, $permissoes)){
					$navlinks[$metodo] = str_replace("_"," ",ucfirst($metodo));
				}
			}

		}

		$configuracoes = array(
			'areas' => $areas,
			'navlinks' => $navlinks,
			'activelink' => $this->uri->segment(2,null)
		);

		$this->configuracoes = $configuracoes;
		$this->load->library('codemin/codemin',$configuracoes);
	}

	// Função padrão, não remover
	public function index(){

		$data['activelink'] 		= null;
		$data['scriptFooter']		= array();
		$data['nivel_acesso']		= 0;
		$data['navlinks'] 			= $this->configuracoes['navlinks'];
		$data['log_acoes'] 			= $this->codemin_model->pegar_log_acoes(15,true);
		$data['minhas_acoes'] 	= $this->codemin_model->pegar_log_acoes(15);
		$data['log_acessos'] 		= $this->codemin_model->pegar_log_acessos(15,true);
		$data['meus_acessos'] 	= $this->codemin_model->pegar_log_acessos(15);

		$this->load->view('codemin/header_view',$data);
		$this->load->view('codemin/nav_view');
		$this->load->view('codemin/dashboard_view');
		$this->load->view('codemin/footer_view');

	}

	// Função padrão, não remover
	public function usuarios(){

		$titulo = "Usuários";
		$this->codemin->montar_codemin_usuarios($titulo);
		
	}

	// Função padrão, não remover
	public function login(){

		$data = array();

		$data['scriptFooter'] = array();
		$data['nivel_acesso'] = 0;

		if($this->input->post('enviado')){

			$login = $this->input->post('login');
			$senha = $this->input->post('senha');

			if($this->codemin_model->fazer_login($login,$senha)){
				redirect(base_url().$this->uri->segment(1),'location');
			} else {
				$data['erro'] = false;
				$data['login'] = $login;
			}
		}

		$this->load->view('codemin/header_view',$data);
		$this->load->view('codemin/login_view');
		$this->load->view('codemin/footer_view');

	}

	// Função padrão, não remover
	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url().$this->uri->segment(1).'/login','location');
	}

}