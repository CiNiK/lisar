<?php
namespace test\models;

use app\models\GalleryItem;
use app\models\Image;

class GalleryItemTest extends \PHPUnit_Framework_TestCase{
	
	public function testToArray(){
		$item = new GalleryItem('img','2016','pathToFull','pathToLow');
		$this->assertEquals($item->toArray(),[
			'description' => 'img',
			'category' => '2016',
			'fullsrc' => 'pathToFull',
			'lowsrc' => 'pathToLow'
		]);
	}

}
