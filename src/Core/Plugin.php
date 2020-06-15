<?php

namespace Inpsyde\Core;

// Block direct access to file

defined('ABSPATH') or die('Not Authorized!');

use Inpsyde\Lib\WpEngine\Bootstrap;

use Inpsyde\Core\Settings;
use Inpsyde\Core\Endpoint;
use Inpsyde\Core\Api;

/**
 * Bootstrap plugin class, declares the plugin entry point with wordpress. It also registers all the child pages and some required libs (3rd party software) to be loaded on the frontend. This class also contains methods to uninstall, activate & deactivate the plugin.
 *
 * @author João Vieira
 * @license MIT
 */
class Plugin extends Bootstrap{
	
	/**
	 * Sets plugin slug
	 *
	 * @method __construct
	 */
	public function __construct() {
		
		//Init parent with plugin name
		
		parent::__construct('Inpsyde/Inpsyde');
		
	}
	
	/**
	 * Intializes child pages and registers Jquery & Bootstrap libs
	 *
	 * @method run
	 */
	public function run(){
		
		//Register libs
		
		$this->registerLib('jquery',[
			'js' => [
				'https://code.jquery.com/jquery-3.5.1.min.js'
			],
		]);
		
		$this->registerLib('bootstrap',[
			'js' => [
				'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js'
			],
			'css' => [
				'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css'
			],
		]);
		
		$this->loadLib('jquery','frontend');
		$this->loadLib('bootstrap','frontend');
		
		//Init Settings
		
		$this->Settings = new Settings($this);
		
		//Init Endpoint
		
		$this->Endpoint = new Endpoint($this);
		
		//Init API
		
		$this->Api = new Api($this);
		
		
	}
	
	/**
     * Performs plugin activation and populates default settings in case they dont exist
     * @method activate
     */
	public function activate(){

		//Initialize default settings
		
		if(!get_option('inpsyde_settings')){
		
			$settings = [
				'endpoint' => '/users/list',
				'api_endpoint' => 'https://jsonplaceholder.typicode.com',
				'cache_enabled' => '1',
				'cache_expire' => '1'
			];
			
			add_option('inpsyde_settings',$settings);
		
		}
		
		return true;
		
	}
	
	/**
     * Performs uninstall of the plugin, removing all the settings defined
     * @method deactivate
     */
	public static function uninstall(){
		
		//Delete options
		
		delete_option('inpsyde_settings');
		
		return true;
		
	}
	
}