<?php
 
namespace Inpsyde\Core;

// Block direct access to file

defined('ABSPATH') or die('Not Authorized!');

use Inpsyde\Lib\WpEngine\Page;
use Inpsyde\Lib\WpEngine\Form;
use Inpsyde\Lib\WpEngine\Template;
use Inpsyde\Lib\WpEngine\Path;
use Inpsyde\Lib\WpEngine\Cache;

/**
 * API page class, provides api data from the external endpoint and serves it via json response
 *
 * @author João Vieira
 * @license MIT
 */
class Api extends Page{
	
	/**
	 * Registers actions
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
		
	}
	
	/**
	 * Process Api request and serves it as json
	 *
	 * @method run
	 */
	public function run(){
		
		//Check if we are on page
		
		if(get_query_var('inpsyde_api') != 1)
			return;
		
		//Get Api Settings
		
		$settings = get_option('inpsyde_settings');
		
		$this->Api = (object)[
			'url' => (isset($settings['api_endpoint']) ? $settings['api_endpoint'] : ''),
			'cache' => [
				'enabled' => (isset($settings['cache_enabled']) ? $settings['cache_enabled'] : false),
				'expires' => (isset($settings['cache_expire']) ? $settings['cache_expire'] : 1),
			]
		];

		//Init Cache

		$this->Cache = new Cache($this->Api->cache['enabled'],$this->Api->cache['expires']);
			
		//Set response
		
		$response = (object)[
			'status' => 'ok',
			'msg' => null,
			'data' => null,
		];
		
		try{
			
			//Check model
			
			if(get_query_var('model') == '')
				throw new \Exception('Model is not defined');
			else
				$model = $this->normalize(get_query_var('model'));
			
			//Check action
			
			if(get_query_var('action') == '')
				throw new \Exception('Action is not defined');
			else
				$action = $this->normalize(get_query_var('action'));
			
			//Check if api model exists
			
			$path = Path::get('Plugin:Inpsyde/Core/Api/'.$model.'.php');

			if(!file_exists($path))
				throw new \Exception('Model not found');
			
			//Initialize class
			
			$className = 'Inpsyde\\Core\\Api\\'.$model;
			
			$model = new $className($this);
		
			//Execute
	
			$model->{$action}($response);
		
		}catch(\Exception $ex){
			
			//Patch response
			
			$response->status = 'error';
			$response->msg = $ex->getMessage();
		}
		
		//Set headers
		
		header('Content-Type: application/json');
		
		//Set response
		
		echo json_encode($response);
		
		//Stop execution;
		
		exit();
		
	}
	
	/**
	 * Adds rewrite rules to wordpress
	 *
	 * @method setRewrite
	 */
	public function setRewrite(){
		
		//Set api endpoint
		
		add_rewrite_rule('^inpsyde/api$','index.php?inpsyde_api=1','top');
		add_rewrite_rule('^inpsyde/api/([a-zA-Z0-9\_\-]+)/([a-zA-Z0-9\_\-]+)$','index.php?inpsyde_api=1&model=$matches[1]&action=$matches[2]','top');
		
		//flush rules
		
		flush_rewrite_rules();  
	}
	
	/**
	 * Adds query variables into wordpress whitelist
	 *
	 * @method setQuery
	 */
	public function setQuery($vars){
		
		//Add vars
		
		$vars[] = 'inpsyde_api';
		$vars[] = 'model';
		$vars[] = 'action';
		$vars[] = 'id';
		
		return $vars;
	}
	
	/**
	 * Converts strings to camelcase
	 *
	 * @method normalize
	 */
	private function normalize(String $name){
		
		$name = str_replace('-','_',$name);
		
		return str_replace('_', '', ucwords($name, '_'));
		
	}
	
}