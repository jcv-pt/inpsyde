<?php

use PHPUnit\Framework\TestCase;

use Inpsyde\Lib\WpEngine\Cache;

class CacheTest extends TestCase{
	
	public function setUp(){
		
        parent::setUp();

        $this->class_instance = new Cache();
    }
	
	public function testAdd(){
		
		//Model
		
		$model = ['ok'];
		
		//Add to cache
		
		$this->class_instance->add('test',$model);
		
		$this->assertTrue($this->arraysAreSimilar($model,wp_cache_get('test')));
	
	}
	
	public function testGet(){
		
		//Model
		
		$model = ['ok'];
		
		//Add to cache
		
		$this->class_instance->add('test',$model);
		
		$this->assertTrue($this->arraysAreSimilar($model,$this->class_instance->get('test')));
	
	}
	
	 /**
	 * Determine if two associative arrays are similar
	 *
	 * Both arrays must have the same indexes with identical values
	 * without respect to key ordering 
	 * 
	 * @param array $a
	 * @param array $b
	 * @return bool
	 */
	private function arraysAreSimilar($a, $b) {
		
		// if the indexes don't match, return immediately
		
		if (count(array_diff_assoc($a, $b))) {
			return false;
		}
		
		// we know that the indexes, but maybe not values, match.
		// compare the values between the two arrays
		
		foreach($a as $k => $v) {
			if ($v !== $b[$k]) {
				return false;
			}
		}
		
		// we have identical indexes, and no unequal values
		
		return true;
	}
	
}
?>