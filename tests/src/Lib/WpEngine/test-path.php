<?php

use PHPUnit\Framework\TestCase;

use Inpsyde\Lib\WpEngine\Path;

class PathTest extends TestCase{
	
	public function testGet1(){
		
		$this->assertEquals(WP_CONTENT_DIR.'/plugins/inpsyde/src/Core',Path::get('Plugin:Inpsyde/Core'));
	
	}
	
	public function testGet2(){
		
		$this->assertEquals(WP_CONTENT_DIR.'/themes/inpsyde/src/Core',Path::get('Theme:Inpsyde/Core'));
	
	}
	
	public function testGetUrl1(){
		
		$this->assertEquals('/wp-content/plugins/inpsyde/src/Assets/css/main.css',Path::url('Plugin:Inpsyde/Assets/css/main.css'));
	
	}
	
	public function testGetUrl2(){
		
		$this->assertEquals('/wp-content/themes/inpsyde/src/Assets/css/main.css',Path::url('Theme:Inpsyde/Assets/css/main.css'));
	
	}
}
?>