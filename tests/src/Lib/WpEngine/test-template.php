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
			
			file_put_contents($this->testFile,'ok');
		
		}
		
		
    }
	
	public function testRender(){
		
		ob_start();Template::render($this->testSlug);$out = ob_get_contents();ob_end_clean();
		
		$this->assertEquals($this->testContent,$out);
	
	}

}
?>