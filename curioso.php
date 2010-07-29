<?php
/**
 * Curioso
 *
 * Busca dados de uma pagina, precisando apenas passar o Xpath
 *
 * PHP 5
 *
 * Emerson Vinicius
 * No Copyright, okay!!! ;-)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     No Copyright, okay!!! ;-)
 * @link          http://evinicius.com/projects Emerson Vinicius projects
 * @package       duke
 * @subpackage    duke.curioso
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Retorna Conteudo em JSON ou XML de alguma pagina
 *
 *
 * @package       duke
 * @subpackage    duke.curioso
 */
class Curioso{
/**
 * Criando instancia do curl
 */
	var $ch;

/**
 * Url do YQL
 */
	var $url_yql;

/**
 * Formato de returno
 * Valores possiveis xml,array,json,object
 */
	var $format = "array"; 
/**
 * Acordando o Curioso
 *
 * @return void
 * @access public
 */
	function __construct() {
		$this->ch = curl_init();
		curl_setopt($this->ch ,CURLOPT_CONNECTTIMEOUT,0);
	}

/**
 * Vamos curiar :D
 *
 * @params $url      string   endereço para nosso curioso ir
 * @params $xpath    string   XPath que quer buscar
 * @params $is_array bollean  Se true retorna dados em Array, se false returna um Objeto
 * 
 */
	function scrape($url = "http://www.google.com.br/ig?hl=pt-BR", $xpath = '//htm/body'){
		$yql = urlencode("select * from html where url=\"{$url}\" AND xpath='{$xpath}' and browser=0");
	
		if($this->format == "xml"):
			$this->url_yql = "http://query.yahooapis.com/v1/public/yql?q={$yql}&format={$this->format}&callback=";	
		else:
			$this->url_yql = "http://query.yahooapis.com/v1/public/yql?q={$yql}&format=json&callback=";	
		endif;
	
		switch($this->format):
			case "json":
				return $this->espia();
				break;
			case "object":
				return json_decode($this->espia(), false);
				break;
			case "xml":
				return $this->espia();
				break;
			case "array":
			default:
				return json_decode($this->espia(), true);
				break;
		endswitch;
	}
	
/**
 * Curiando...
 * 
 * @return $data json
 */
	public function espia()
	{
		curl_setopt($this->ch, CURLOPT_URL, $this->url_yql);
		curl_setopt($this->ch, CURLOPT_FAILONERROR, true);
		curl_setopt($this->ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
		$data = curl_exec($this->ch);
		if (!$data) {
			echo "<br />cURL error number:" .curl_errno($this->ch);
			echo "<br />cURL error:" . curl_error($this->ch);
			exit;
		}
		return $data;
	}
	public function tratar($data)
	{
		return str_replace(array("\n","\r","\t"),null, $data);
	}
/**
 * Botando Curiso para dormir
 *
 * @param object $controller Instantiating controller
 * @access public
 */
	function __destruct(&$controller) {
		curl_close($this->ch);
	}
}