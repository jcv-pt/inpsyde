<?php

use PHPUnit\Framework\TestCase;

use Inpsyde\Lib\WpEngine\Cache;

class CacheTest extends TestCase{
	
	public function setUp(){
		
        parent::setUp();

        $this->class_instance = new Cache();
    }
	
	public function testAdd(){
		
		//Add to cache
		
		$this->class_instance->add('test','ok');
		
		$this->assertEquals('ok',wp_cache_get('test'));
	
	}
	
	public function testGet(){
		
		//Add to cache
		
		$this->class_instance->add('test','ok');
		
		$this->assertEquals('ok',$this->class_instance->get('test'));
	
	}
	
}
?>