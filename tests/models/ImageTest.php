<?php
use app\models\Image;

class ImageTest extends \PHPUnit_Framework_TestCase{
	private static $inputDir;
	private static $outputDir;

	public static function setUpBeforeClass()
    {
        $baseDir = dirname(dirname(dirname(__FILE__)));
		self::$inputDir = $baseDir.'/tests/input/';
		self::$outputDir = $baseDir.'/tests/output/';
		self::clearOutputFolder();
    }
	
	public function testSave(){
		$image = new Image(self::$inputDir.'example.jpg');
		$image->saveAs(self::$outputDir.'full.jpg', 1200, 1900);
		$this->assertTrue(file_exists(self::$outputDir.'full.jpg'));
	}

	public function testSaveThumb(){
		$image = new Image(self::$inputDir.'example.jpg');
		$image->saveAs(self::$outputDir.'thumb.png', 200, 200);
		$this->assertTrue(file_exists(self::$outputDir.'thumb.png'));
	}

	protected static function clearOutputFolder()
    {
        $files = glob(self::$outputDir.'*'); // get all file names
		foreach($files as $file){ // iterate files
  			if(is_file($file))
    			unlink($file); // delete file
		}
    }
}
