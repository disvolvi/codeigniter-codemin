<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* DESENVOLVIDO POR BRUNO ALMEIDA
*
* JULHO DE 2013
*/

class Uploader extends CI_Controller {

	function index(){

		$uri = $this->uri->segment_array();
		$tipo = $uri['3'];
		$tabela = $uri['4'];
		$registro = $uri['5'];
		$campo = $uri['6'];

		$file = null;
		if(isset($uri['7'])){
			$file = $uri['7'];
		}

		$diretorio = array(
			'tipo' => $tipo,
			'tabela' => $tabela,
			'registro' => $registro,
			'campo' => $campo,
			'file' => $file
		);

		$this->load->library('codemin/uploadhandler',$diretorio);
	}

}