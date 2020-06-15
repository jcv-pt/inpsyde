<?php

namespace Inpsyde\Core;

// Block direct access to file

defined('ABSPATH') or die('Not Authorized!');

use Inpsyde\Lib\WpEngine\Page;
use Inpsyde\Lib\WpEngine\Form;
use Inpsyde\Lib\WpEngine\Template;
use Inpsyde\Lib\WpEngine\Path;

/**
 * Endpoint page class, responsible for deliver main view for the user list on the url specified in the configuration settings
 *
 * @author João Vieira
 * @license MIT
 */
class Endpoint extends Page{
	
	/**
	 * Registers actions & filters for the endpoint page
	 *
	 * @method register
	 */
	public function register(){
		
		//Register run
		
		parent::register();
		
		//Register rewrite
		
		$this->registerAction('init','setRewrite');
		
		//Register query vars
		
		$this->registerAction('query_vars','setQuery');

		//Register template
		
		$this->registerFilter('template_include','setTemplate');
		
	}
	
	/**
	 * Registers CSS and JS dependencies and loads them to the frontend
	 *
	 * @method run
	 */
	public function run(){
		
		//Check if we are on page
		
		if(get_query_var('inpsyde_enpoint') != 1)
			return;
		
		//Register & load js
		
		$this->Plugin->registerLib('inpsyde_enpoint',[
			'js' => [
				'Plugin:Inpsyde/Assets/js/pages/endpoint.js'
			],
			'css' => [
				'Plugin:Inpsyde/Assets/css/pages/endpoint.css',
				'Plugin:Inpsyde/Assets/libs/loader/loader.css'
			],
		]);
		
		$this->Plugin->registerLib('loader',[
			'css' => [
				'Plugin:Inpsyde/Assets/libs/loader/loader.css'
			],
		]);
		
		$this->Plugin->loadLib('inpsyde_enpoint','frontend');
		$this->Plugin->loadLib('loader','frontend');

	}
	
	/**
	 * Adds rewrite rules to wordpress
	 *
	 * @method setRewrite
	 */
	public function setRewrite(){
		
		//Get settings
		
		$settings = get_option('inpsyde_settings');
		
		if(!isset($settings['endpoint']))
			return;
		
		//Normalize url
		
		$url = trim($settings['endpoint'],'/');
		
		add_rewrite_rule('^'.$url.'$','index.php?inpsyde_enpoint=1','top');
		
		//flush rules
		
		flush_rewrite_rules();  
	}
	
	/**
	 * Adds query variables into wordpress whitelist
	 *
	 * @method setQuery
	 * @return Array $vars
	 */
	public function setQuery($vars){
		
		//Add vars
		
		$vars[] = 'inpsyde_enpoint';
		
		return $vars;
	}

	/**
	 * Renders the page template into a string
	 *
	 * @method setTemplate
	 * @return String $template
	 */
	public function setTemplate($template){

		if(get_query_var('inpsyde_enpoint'))
			$template = Path::get('Plugin:Inpsyde/Templates/Endpoint/table.php');

		return $template;

	}
	
}