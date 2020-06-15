<?php

namespace Inpsyde\Lib\WpEngine;

// Block direct access to file

defined('ABSPATH') or die('Not Authorized!');

/**
 * Cache class, a wrapper around the wordpress native cache
 *
 * @author João Vieira
 * @license MIT
 */
class Cache{
	
	/**
     * Initializes the plugin and registers hooks
	 * @param  bool $enabled Weather the cache is enabled or not
	 * @param  int $expires The expiration time after cache expires
     * @method __construct
     */
	public function __construct(Bool $enabled = true, Int $expires = 1) {

		//Set settings
		
		$this->enabled = $enabled;
		$this->expires = $expires;
	
	}
	
	/**
     * Gets data from cache
	 * @param  string $key Key of the cache
	 * @param  string $group Group of the cache
     * @method get
     */
	public function get(String $key, String $group = ''){
		
		//Check if is enabled

		if(!$this->enabled)
			return false;

		//Return if exists
		
		return wp_cache_get($key,$group);
		
	}
	
	/**
     * Sets data to cache
	 * @param  string $key Key of the cache to be stored
	 * @param  mixed $data Data 
	 * @param  string $group Group of the cache
     * @method add
     */
	public function add(String $key, $data, String $group = ''){
		
		//Check if is enabled

		if(!$this->enabled)
			return true;

		//Return if exists
		
		wp_cache_add($key,$data,$group,60*$this->expires);
		
	}

}