<?php

namespace Inpsyde\Lib\WpEngine;

// Block direct access to file

defined('ABSPATH') or die('Not Authorized!');

use Inpsyde\Lib\WpEngine\Path;

/**
 * Bootstrap class, dispatcher for plugin
 *
 * @author João Vieira
 * @license MIT
 */
class Bootstrap{
	
	private $name = null;
	private $uid = null;
	private $paths = null;
	
	private $libs = null;
	
	/**
     * Initializes the plugin and registers hooks
	 * @param  string $uid The plugin slug
     * @method __construct
     */
	public function __construct(String $uid) {
		
		//Init parent
		
		$this->uid = strtolower($uid);
		$this->name = @end(explode('/',$this->uid));

		$this->paths = (object)[
			'file' => Path::get('Plugin:'.$this->name.'/.'.$this->name.'.php')
		];
		
		$this->libs = (object)[
			'collection' => [],
			'queue' => [],
			'load' => []
		];

    }
	
	/**
     * Initializes the plugin and registers hooks
     * @method initialize
     */
	public function initialize(){
		
		// Plugin uninstall hook
		
		register_uninstall_hook($this->paths->file, [$this->name.'\Core\Plugin','uninstall']);

        // Plugin activation/deactivation hooks

        register_activation_hook($this->paths->file, [$this, 'activate']);
        register_deactivation_hook($this->paths->file, [$this, 'deactivate']);
		
		//Check if is active

		if(!in_array($this->uid.'.php',get_option('active_plugins')))
			return true;
		
		//Runs plugin
		
		$this->run();
		
		//Enqueue libs
		
		$this->enqueue();
		
	}
	
	/**
     * Runs plugin
     * @method run
     */
	public function run(){
		
		return true;
		
	}
	
	/**
     * Performs plugin uninstall routine
     * @method uninstall
     */
	public static function uninstall(){
		
		return true;
		
	}
	
	/**
     * Performs activation / init routine
     * @method activate
     */
	public function activate(){
		
		return true;
		
	}
	
	/**
     * Performs plugin deactivate routine
     * @method deactivate
     */
	public function deactivate(){

		return true;
		
	}
	
    /**
     * Register Setting
	 * @param  string $group The setting group
	 * @param  mixed $value The data
     * @method registerSetting
     */
    public function registerSetting(String $group, $value) {
		
        register_setting($group,$value);
		
    }
	
	/**
     * Register Action with this class
	 * @param  string $name The action hook
	 * @param  string $method The method name
     * @method registerAction
     */
    public function registerAction(String $name, String $method) {
		
		add_action($name,[$this, $method]);
		
    }

	/**
     * Register a libs
	 * @param  string $name The lib name
	 * @param  array $files An array of string with file paths
     * @method registerLib
     */
    public function registerLib(String $name, Array $files) {
		
		$this->libs->collection[$name] = $files;
		
    }
	
	/**
     * Loads a specific lib
	 * @param  string $name The lib name
	 * @param  string $domain Weather frontend or backend
     * @method loadLib
     */
    public function loadLib(String $name,String $domain) {
		
		$this->libs->queue[$name] = (object)[
			'name' => $name,
			'domain' => $domain
		];
		
    }
	
	/**
     * Enqueue method to register libs to front and back end
     * @method enqueue
     */
    public function enqueue() {
		
		foreach($this->libs->queue as $name => &$lib)
			if($lib->domain == 'all' || $lib->domain == 'backend'){
				
				$this->registerAction('admin_enqueue_scripts','enqueueBackend');
				
				break;
			}
			
		foreach($this->libs->queue as $name => &$lib)
			if($lib->domain == 'all' || $lib->domain == 'frontend'){
				
				$this->registerAction('wp_enqueue_scripts','enqueueFrontend');
				
				break;
				
			}
		
    }
	
	/**
     * Enqueues lib files to wp frontend
     * @method enqueueBackend
     */
	public function enqueueFrontend() {

		foreach($this->libs->queue as $name => &$lib){
			
			if($lib->domain == 'all' || $lib->domain == 'frontend')
				$this->enqueueLib($name);
			
		}
		
	}
	
	/**
     * Enqueues lib files to wp backend
     * @method enqueueBackend
     */
	public function enqueueBackend() {
		
		foreach($this->libs->queue as $name => &$lib){
			
			if($lib->domain == 'all' || $lib->domain == 'backend')
				$this->enqueueLib($name);
			
		}
		
	}
	
	/**
     * Registers lib files to wp
	 * @param  string $name The lib name
     * @method enqueueLib
     */
    public function enqueueLib(String $name) {
		
		//Check if its registered
		
		if(!isset($this->libs->collection[$name]))
			throw new \Exception('Lib '.$name.' is not registered');
		
		//Check if has been loaded
		
		if(in_array($name,$this->libs->load))
			return true;

		//Set lib
		
		$lib = $this->libs->collection[$name];
		
		//Set file counter
		
		$counter = 0;
		
		foreach($lib as $type => $files){
			
			switch($type){
				
				case 'js':
				case  'css':
				
					foreach($files as $file){
						
						$fileOpts = (object)[
							'path' => null,
							'dependencies' => [],
							'version' => null,
						];
						
						//Patch if array
						
						if(is_array($file)){
							
							if(isset($file[0]))
								$fileOpts->path = $file[0];
							
							if(isset($file[1]))
								$fileOpts->dependencies = $file[1];
							
							if(isset($file[2]))
								$fileOpts->version = $file[2];
							
						}
						
						//Patch if string
						
						if(is_string($file))
							$fileOpts->path = $file;
						else
							throw new \Exception('Error loading lib "'.$name.'", file path is not valid');
						
						//Check if matches path slugs
						
						$url = Path::getUrl($fileOpts->path,false);
						
						if($url)
							$fileOpts->path = $url;
						
						//Set function type
						
						if($type == 'css'){
							
							//Set file uid
						
							$uid = $name.'-'.$counter;
						
							wp_register_style($uid, $fileOpts->path, $fileOpts->dependencies, $fileOpts->version, 'all');
							wp_enqueue_style($uid);
							
						}
						else{
							
							//Set file uid
						
							$uid = $name.'-'.$counter;
						
							wp_register_script($uid, $fileOpts->path, $fileOpts->dependencies, $fileOpts->version, true);
							wp_enqueue_script($uid);
							
						}

						//Increment counter
						
						$counter++;
					}
				
				break;
				
				case 'lib':
				
					foreach($files as $file)
						$this->enqueueLib($file);
				
				break;
				
			}
			
		}
		
		//Set as loaded
		
		$this->libs->load[] = $name;
		
    }
	
	/**
     * Get paths
     * @method getPaths
     */
	public function getPaths(){
		
		return $this->paths;
		
	}
	
	/**
     * Get registered libs
     * @method getLibs
     */
	public function getLibs(){
		
		return $this->paths;
		
	}

}