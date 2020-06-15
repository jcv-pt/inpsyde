<?php

namespace Inpsyde\Lib\WpEngine;

// Block direct access to file

defined('ABSPATH') or die('Not Authorized!');

use Inpsyde\Lib\WpEngine\Bootstrap;

/**
 * Page class, provides page logic by being extended
 *
 * @author João Vieira
 * @license MIT
 */
class Page{
	
	protected $Plugin = null;
	
	private $run_hook = 'parse_query';
	
	/**
     * Initializes a new page and assigns the main plugin class
	 * @param  Bootstrap $plugin Plugin main class
     * @method __construct
     */
	public function __construct(Bootstrap &$plugin) {
		
		//Set bootstrap
		
		$this->Plugin = $plugin;
		
		//Initializes
		
		$this->initialize();

    }
	
	/**
     * Initializes the plugin and registers hooks
     * @method initialize
     */
	public function initialize(){
		
		//Register
		
		$this->register();
		
	}
	
	/**
     * Runs page
     * @method run
	 * @return bool
     */
	public function run(){
		
		return true;
		
	}
	
    /**
     * Registers actions
     * @method register
	 * @return bool
     */
	public function register(){
		
		//Register the run method
		
		$this->registerAction($this->run_hook,'run');
		
		return true;
		
	}
	
	/**
     * Renders view
     * @method render
	 * @return bool
     */
	public function render(){
		
		return true;
		
	}
	
	/**
     * Register Action
     * @method registerAction
     */
    public function registerAction(String $name, String $method) {
		
		add_action($name,[$this, $method]);
		
    }
	
	/**
     * Register Filter
     * @method registerAction
     */
    public function registerFilter(String $name, String $method) {
		
		add_filter($name,[$this, $method]);
		
    }

}