<?php

namespace Inpsyde\Core;

// Block direct access to file

defined('ABSPATH') or die('Not Authorized!');

use Inpsyde\Lib\WpEngine\Page;
use Inpsyde\Lib\WpEngine\Form;
use Inpsyde\Lib\WpEngine\Template;

/**
 * Settings page class, responsible for creating a plugin settings menu entry in the backend and provide configuration settings
 *
 * @author João Vieira
 * @license MIT
 */
class Settings extends Page{
	
	/**
	 * Registers actions for the settings page
	 *
	 * @method register
	 */
	public function register(){
		
		$this->registerAction('admin_menu','createMenu');
		$this->registerAction('admin_init','createMenuSettings');
		
	}
	
	/**
	 * Creates the menu entry on the admin panel
	 *
	 * @method createMenu
	 */
	public function createMenu(){
		
		add_options_page(__('Inpsyde'), __('Inpsyde'), 'manage_options', 'inpsyde-plugin', [$this, 'render'] );
		
	}
	
	/**
	 * Creates the settings form in the configuration page
	 *
	 * @method createMenuSettings
	 */
	public function createMenuSettings(){
		
		//Create New form
		
		$form = new Form('inpsyde','inpsyde_settings');
		
		//Add section

		$form->addSection('inpsyde_settings',[
			'label' => __('Endpoint Settings'),
			'render' => function(){
				echo '<p>'.__('Here you can set all the options for the plugin endpoint').'</p>';
			}
		]);

		//Add fields
		
		$form->addField('endpoint',[
			'label' => __('Endpoint Url'),
			'type' => 'text',
			'section' => 'inpsyde_settings',
			'validate' => function($input){

				if(preg_match('/([a-z0-9\-\/]+)/m', $input, $matches) !== 1) 
					return __('Endpoint url is not a valid URI');
				
				//Check if starts with the following
				
				$forbidden = ['login','wp-admin','inpsyde'];
				
				foreach($forbidden as $url){
					if(substr($input, 0, strlen($url)) === $url)
						return __('Endpoint url is system reserved');
				}
				
				return true;
			},
			'style' => 'min-width:250px;',
			'note' => __('Only relative urls with chars (a-z,0-9,-,/)')
		]);
		
		$form->addField('api_endpoint',[
			'label' => __('Api Endpoint Url'),
			'type' => 'text',
			'section' => 'inpsyde_settings',
			'validate' => function($input){

				if(!filter_var($input, FILTER_VALIDATE_URL))
					return __('Api Endpoint url is not a valid URI');
				
				return true;
			},
			'style' => 'min-width:250px;',
		]);
		
		$form->addField('cache_enabled',[
			'label' => __('Cache Enable'),
			'type' => 'select',
			'section' => 'inpsyde_settings',
			'options' => [
				'1' => __('Yes'),
				'0' => __('No'),
			],
		]);
		
		$form->addField('cache_expire',[
			'label' => __('Cache Expires'),
			'type' => 'select',
			'section' => 'inpsyde_settings',
			'options' => [
				'1' => __('1 Minute'),
				'30' => __('30 Minutes'),
				'120' => __('2 Hours'),
			],
		]);
		
	}

	/**
	 * Render the settings page html
	 *
	 * @method render
	 * @return String
	 */
	public function render(){
		
		echo Template::render('Plugin:Inpsyde/Templates/Settings/form.php');

	}
	
}