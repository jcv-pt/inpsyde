<?php

use PHPUnit\Framework\TestCase;

use Inpsyde\Lib\WpEngine\Path;
use Inpsyde\Lib\WpEngine\Template;

class TemplateTest extends TestCase{
	
	public function setUp(){
		
        parent::setUp();

		//Set vars
		
		$this->testSlug = 'Plugin:Inpsyde/Lib/WpEngine/Form/text.php';
		
		$this->testFile = Path::get($this->testSlug);
		
		$this->testContent = 'ok';
		
		//Create dummy test file
		
		if(!file_exists($this->testFile)){
			
			if(!file_exists(dirname($this->testFile)))
				mkdir(dirname($this->testFile), 0777, true);
			
			file_put_contents($this->testFile,'<?= (isset($content) ? $content : "");?>ok');
		
		}
		
		
    }
	
	public function testRender(){
		
		$this->assertEquals($this->testContent,Template::render($this->testSlug));
	
	}
	
	public function testSection(){
		
		//Make section
		
		Template::start();?>ok<?php $content = Template::end();
		
		$this->assertEquals($this->testContent,$content);
	
	}
	
	public function testExtend(){
		
		//Make section
		
		Template::start();?>ok<?php $content = Template::extend($this->testSlug);
		
		$this->assertEquals($this->testContent.'ok',$content);
	
	}
}
?>