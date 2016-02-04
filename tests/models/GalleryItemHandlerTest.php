<?php

use app\models\Image;
use app\models\GalleryItem;
use app\models\GalleryItemCreator;
define('ROOT', dirname(dirname(__FILE__)));
class GalleryItemHandlerTest extends \PHPUnit_Framework_TestCase{
	private $config;
	private $inputDir;
	private $outputDir;
	private $inputImageName;


	public function setUp(){
		$this->inputDir = ROOT.'/input/';
		$this->outputDir = ROOT.'/output/';
		$this->clearOutputFolder();
		$this->inputImageName = 'example_2016.jpg';
		$outputDir = ROOT.'/tests/output/';
		$this->config = [
			'root'			=> $this->outputDir,
			'images'		=> [
				'json' 		=> '/images.json',	
				'delimeter'	=> '_',
				'uploadDir'	=> '/files/',
				'full'		=> [
					'path' 		=> '/output/',
					'height'	=> 1200,
					'width'		=> 1900,
					'ext'		=> 'jpg'
				],
				'low'		=> [
					'path'		=> '/output/',
					'height'	=> 200,
					'width'		=> 200,
					'ext'		=> 'png'
				]
			]	
		];		
	}

	protected function clearOutputFolder()
    {
        $files = glob($this->outputDir.'*'); // get all file names
		foreach($files as $file){ // iterate files
  			if(is_file($file))
    			unlink($file); // delete file
		}
    }
 
	public function testCreate(){
		$name = pathinfo($this->inputImageName,PATHINFO_FILENAME);
		$fullsrc = $this->config['images']['full']['path'].$name.'.'.$this->config['images']['full']['ext'];
		$lowsrc = $this->config['images']['low']['path'].$name.'.'.$this->config['images']['low']['ext'];
		$item = new GalleryItem('example', '2016', $fullsrc, $lowsrc);
		$handler = new GalleryItemCreator();
		$handler->setFullImageProps($this->config['images']['full']);
		$handler->setLowImageProps($this->config['images']['low']);
		$this->assertEquals($item, $handler->create($this->inputDir.$this->inputImageName));
		$this->assertFileExists(ROOT.$fullsrc);
		$this->assertFileExists(ROOT.$lowsrc);
	}

    public function testDelete(){
        $name = pathinfo($this->inputImageName,PATHINFO_FILENAME);
        $fullsrc = $this->config['images']['full']['path'].$name.'.'.$this->config['images']['full']['ext'];
        $lowsrc = $this->config['images']['low']['path'].$name.'.'.$this->config['images']['low']['ext'];
        $handler = new GalleryItemCreator();
        $handler->setFullImageProps($this->config['images']['full']);
        $handler->setLowImageProps($this->config['images']['low']);
        $item = $handler->create($this->inputDir.$this->inputImageName);
        $this->assertFileExists(ROOT.$fullsrc);
		$this->assertFileExists(ROOT.$lowsrc);
        $handler->delete($item);
        $this->assertFileNotExists(ROOT.$fullsrc);
		$this->assertFileNotExists(ROOT.$lowsrc);
    }
}
