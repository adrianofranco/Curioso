<?php
/**
 * Curioso
 *
 * Curioso, in portugues is curious, that library is a small scraper component, 
 * using YQL to return the html data in XML, JSON, PHP Array and PHP Object
 *
 * PHP 5
 *
 * Duke Khaos
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     
 * @link          http://github.com/dukex Duke 
 * @package       hacks
 * @subpackage    hacks.curioso
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Parser HTML in JSON, XML, Array and Object of page in web
 *
 *
 * @package       hacks
 * @subpackage    hacks.curioso
 */
class Curioso{
	/**
	 * curl
 	 */
	var $ch;

	/**
 	 * YQL url
	 */
	var $url_yql;

	/**
 	 * Return format
 	 * Can be xml,array,json,object 
	 * default is array
 	 */
	var $format = "array"; 

	/**
	 * Waking the Curious
	 *
	 * @return void
	 * @access public
	 */
	function __construct() {
		$this->ch = curl_init();
		curl_setopt($this->ch ,CURLOPT_CONNECTTIMEOUT,0);
	}

	/**
	 * Vamos curiar
	 *
	 * @params $url      string   address for curious following
	 * @params $xpath    string   xpath for search
	 *
	 * @return (xml|json|array|object)
	 * 
	 */
	function scrape($url, $xpath = '//htm/body'){
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
	 * Spying...
	 * 
	 * @return $data (json|xml)
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

	/**
	 * Sleeping  now
	 *
	 * @param object $controller Instantiating controller
	 * @access public
	 */
	function __destruct(&$controller) {
		curl_close($this->ch);
	}
}
