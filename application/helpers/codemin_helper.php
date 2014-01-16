<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| HELPER CODEMIN
| -------------------------------------------------------------------------
| Desenvolvido por Bruno Almeida
|
*/

function senha_usuario($senha){
	$hash = "codemin_hash";
	return md5($senha.$hash);
}

/**
* is_youtube
* 
* Verifica se a url é do youtube
* 
*
* @param	$string
* @return	boolean
*/
function is_youtube($url){
	return (preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url));
}

/**
* youtube_video_id
* 
* Pega a url do youtube e retorna o ID do video
* 
*
* @param	$string
* @return	$string
*/
function youtube_video_id($url){
	if(is_youtube($url)){
		$pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
		preg_match($pattern, $url, $matches);
		if (count($matches) && strlen($matches[7]) == 11){
			return $matches[7];
		}
	}
	return '';
}

/**
* is_vimeo
* 
* Verifica se a url é do vimeo
* 
*
* @param	$string
* @return	boolean
*/
function is_vimeo($url){
	return (preg_match('/vimeo\.com/i', $url));
}

/**
* vimeo_video_id
* 
* Pega a url do vimeo e retorna o ID do video
* 
*
* @param	$string
* @return	$string
*/
function vimeo_video_id($url){
	if(is_vimeo($url)){
		$pattern = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
		preg_match($pattern, $url, $matches);
		if (count($matches)){
			return $matches[2];
		}
	}
	return '';
}

/**
* youtube_video_url
* 
* Pega o ID do youtube e retorna a url video
* 
*
* @param	$string
* @return	$string
*/
function youtube_video_url($url){
	return "http://www.youtube.com/watch?v=".$url;
}

/**
* vimeo_video_url
* 
* Pega o ID do vimeo e retorna a url video
* 
*
* @param	$string
* @return	$string
*/
function vimeo_video_url($url){
	return "http://vimeo.com/".$url;
}

/**
* texto_acao
* 
* Pega o ID do vimeo e retorna a url video
* 
*
* @param	$string
* @return	$string
*/
function texto_acao($id){
	switch ($id) {
		case 1:
			return "Adicionou";
			break;

		case 2:
			return "Editou";
			break;

		case 3:
			return "Removeu";
			break;
	}
}

/**
* data_log
* 
* Retorna a data corretamente
* 
*
* @param	$string
* @return	$string
*/
function data_log($data){

	$aux = explode(" ", $data);
	$data = $aux[0];
	$hora = $aux[1];

	if($data == date('Y-m-d')){
		$data = "Hoje";
	} elseif ($data == date('Y-m-d', strtotime('- 1 day') )) {
		$data = "Ontem";
	} else {
		$data = implode('/',array_reverse(explode('-',$data)));
	}

	return $data . ' às ' . $hora;

}


/**
* url_amigavel
* 
* Retira acentos, substitui espaço por - e
* deixa tudo minúsculo
* 
*
* @param	$string
* @return	$string
*/
function url_amigavel($variavel){
		$procurar 	= array('à','ã','â','é','ê','í','ó','ô','õ','ú','ü','ç',);
		$substituir = array('a','a','a','e','e','i','o','o','o','u','u','c',);
		$variavel = strtolower($variavel);
		$variavel	= str_replace($procurar, $substituir, $variavel);
		$variavel = htmlentities($variavel);
    $variavel = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml);/", "$1", $variavel);
    $variavel = preg_replace("/([^a-z0-9]+)/", "-", html_entity_decode($variavel));
    return trim($variavel, "-");
}