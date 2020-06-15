<?php

namespace Inpsyde\Lib\WpEngine;

// Block direct access to file

defined('ABSPATH') or die('Not Authorized!');

use Inpsyde\Lib\WpEngine\Template;

/**
 * Form class, provides form elements registration and rendering
 *
 * @author João Vieira
 * @license MIT
 */
class Form{
	
	private $fields = null;
	private $sections = null;
	
	/**
     * Initializes a new form instance with a given name
	 * @param  string $name Form name
	 * @param  string $group Form group
     * @method __construct
     */
	public function __construct(String $name, String $group) {
		
		//Initializes
		
		$this->name = $name;
		$this->group = $group;
		$this->fields = [];
		$this->sections = [
			'default' => []
		];
		
		//Set defaults
		
		$this->_sectionDefaults = [
			'render' => null,
			'label' => '',
		];

		$this->_fieldDefaults = [
			'id' => null,
			'name' => null,
			'section' => 'default',
			'label' => '',
			'type' => 'textfield',
			'template' => null,
			'value' => null,
			'validate' => null,
		];
		
		//Register
		
		register_setting($this->group, $this->group,[$this,'validate']);
    }
	
	/**
     * Adds section into collection & registers with wp
	 * @param  string $name Section name
	 * @param  string $params Section params
     * @method addSection
     */
	public function addSection(String $name,Array $params = []){
		
		//Set args
		
		$params = array_replace_recursive($this->_sectionDefaults,$params);
		
		//Adds
		
		$this->sections[$name] = $params;
		
		//Register
		
		add_settings_section($name, $params['label'], $params['render'], $this->name);
		
	}
	
	/**
     * Adds field into collection & registers with wp
	 * @param  string $name Field name
	 * @param  string $params Field params
     * @method run
     */
	public function addField(String $name,Array $params = []){
		
		//Set params
		
		$params = array_replace_recursive($this->_fieldDefaults,$params);
		
		//Set group & path
		
		$params['path'] = $name;
		$params['group'] = $this->group;
		$params['slug'] = str_replace(['.','-'],'_',$params['group'].'.'.$params['path']);
		
		//Set name & id
		
		if($params['name'] == null)
			$params['name'] = $this->dotToPath($this->group.'.'.$name);
		
		if($params['id'] == null)
			$params['id'] = str_replace(['.','_'],'-',$this->group.'.'.$name);
		
		//Sets template
		
		if($params['template'] == null)
			$params['template'] = 'Plugin:Inpsyde/Lib/WpEngine/Form/'.$params['type'].'.php';

		//Ads to collection
		
		$this->fields[$name] = $params;

		//Register
		
		add_settings_field($params['slug'], $params['label'], function() use ($params){
			
			//Gets value
			
			if(isset($_POST[$params['path']]))
				$params['value'] = $_POST[$params['path']];
			else
				$params['value'] = $this->getValue($params['path']);
			
			echo Template::render($params['template'],$params);
			
		}, $this->name, $params['section']);
		
		return true;
		
	}
	
	/**
     * Method to process field validation should it be defined in field params
	 * @param  string $inputs Wordpress input list
     * @method run
     */
	function validate($inputs) {
		
		$output = [];
		
		foreach($inputs as $key => $input){
			
			$current = $this->getValue($key);
			
			if(isset($this->fields[$key]) && $this->fields[$key]['validate'] !== null){
				
				$result = $this->fields[$key]['validate']($input);
				
				if($result === true)
					$output[$key] = $input;
				else{
					
					$output[$key] = $current;
					
					add_settings_error($this->fields[$key]['slug'],$this->fields[$key]['slug'].'_error',$result,'error');
					
				}
				
			}
			else
				$output[$key] = $input;
			
		}
		
		return $output;
	}
	
	/**************** PRIVATE METHODS ****************/
	
	/**
     * Converts dotted path to array representation
	 * @param  string $path Dotted file path
	 * @param  string $sepator Separator char
     * @method run
     */
	private function dotToPath($path, $separator = '.') {
		
		$keys = explode($separator, $path);
		
		$path = current($keys);

		array_shift($keys);
		
		foreach ($keys as $key)
			$path .= '['.$key.']';

		return $path;
	}
	
	/**
     * Gets field stored value
	 * @param  string $name Filed name
     * @method run
     */
	private function getValue($name){
		
		$values = get_option($this->group);
			
		$bits = explode('.',$name);
		
		$tmp = $values;
		
		foreach($bits as $bit)
			if(isset($tmp[$bit]))
				$tmp = $tmp[$bit];
		
		if(is_string($tmp))
			return $tmp;
		else
			return null;
	}

}