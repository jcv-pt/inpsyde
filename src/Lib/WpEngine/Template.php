<?php

namespace Inpsyde\Lib\WpEngine;

// Block direct access to file

defined('ABSPATH') or die('Not Authorized!');

use Inpsyde\Lib\WpEngine\Path;

/**
 * Template class, provides templating by rendering html
 *
 * @author João Vieira
 * @license MIT
 */
class Template{
	
	private static $extend = null; 
	
	/**
     * Renders a template file into a string
	 * @param  string $path Slug path string
	 * @param  array $params Array of data containing all variables to made available to template
     * @method render
	 * @throws \Exception
	 * @return string
     */
	public static function render(String $path, Array $params = []){

		//Compile & check if file exists

		$file = Path::get($path);
		
		if(!file_exists($file))
			throw new \Exception('Template file "'.$file.'" could not be loaded');
		
		//Make values in the associative array easier to access by extracting them

		extract($params);
	
		//Buffer and output
		
		self::start();
		
		include $file;

		return self::end();
	}
	
	/**
     * Starts ob
     * @method start
     */
	public static function start(){
	
		//Buffer start
		
		ob_start();

	}
	
	/**
     * Ends ob and returns result as string
	 * @return string
     * @method end
     */
	public static function end(){
	
		//Buffer end
		
		return ob_get_clean();

	}
	
	/**
     * Extends a template by loading the code of the ob into it
	 * @param  string $path Slug path string
     * @method extend
	 * @throws \Exception
	 * @return string
     */
	public static function extend(String $path){
	
		//Buffer start
		
		$content = self::end();
		
		return self::render($path,['content' => $content]);

	}
}