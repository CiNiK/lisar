<?php
use app\controllers\GalleryController;

class GalleryLoaderControllerTest extends \PHPUnit_Framework_TestCase{
	public function testGetImagePaths(){
		$controller = new GalleryController();
		$baseDir = dirname(dirname(dirname(__FILE__)));
		$inputDir = $baseDir.'/tests/input/';
		$this->assertEquals([$inputDir.'example_2016.jpg',$inputDir.'example.jpg',$inputDir.'images.json'],
							$controller->getImagePaths($inputDir) );
	}
}
