<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* DESENVOLVIDO POR BRUNO ALMEIDA
*
* MAIO DE 2013
*/

class Opcoes extends CI_Controller {

	public function adicionar(){

		$tabela = $this->input->post('tabela');
		$campo  = $this->input->post('campo');
		$opcao  = $this->input->post('opcao');
		$tipo  	= $this->input->post('tipo');

		$inserir = array(
			'tabela' => $tabela,
			'campo'  => $campo,
			'opcao'  => $opcao
		);
		$this->db->insert('codemin_opcoes',$inserir);

		$return['tipo'] 	= $tipo;
		$return['valor'] 	= $opcao;
		$return['id'] 		= $this->db->insert_id();

		echo json_encode($return);

	}

	public function remover(){

		$id = $this->input->post('id');

		$this->db->where('id',$id);
		if($this->db->delete('codemin_opcoes')){
			$return['success'] = true;
		} else {
			$return['success'] = false;
		}

		echo json_encode($return);

	}

	public function ordenar(){

		$this->load->model('codemin_model');

		$tabela 		= $this->uri->segment(3);
		$registros 	= $this->input->post('registros');
		$resultado 	= $this->codemin_model->ordenar_registros($tabela,$registros);
		echo json_encode($resultado);

	}

}