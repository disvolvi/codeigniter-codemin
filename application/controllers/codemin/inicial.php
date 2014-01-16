<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicial extends CI_Controller {

	public function index(){

		$this->load->view('codemin/inicial/documentacao');

	}

	public function configuracao(){

		$this->load->model('codemin_model');

		if($_POST){
			$this->codemin_model->instalar();
		}
		$this->load->view('codemin/inicial/configuracao');

	}

	// Deixar como função como private após a instalação
	public function instalacao(){
	// private function instalar(){

		$this->load->model('codemin_model');

		$this->load->library('form_validation');

		$this->form_validation->set_rules('nome', 'Nome', 'required|min_length[6]|max_length[255]');
		$this->form_validation->set_rules('login', 'Login', 'required|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]|max_length[255]');

		if($this->form_validation->run() == TRUE){
			$this->codemin_model->instalar();
		}

		$this->load->view('codemin/inicial/instalacao');

	}

}