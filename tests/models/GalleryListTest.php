<?php

use app\models\GalleryItem;
use app\models\Gallery;

class GalleryListTest extends \PHPUnit_Framework_TestCase
{
    private static $jsonName = 'images.json';
    private static $inputDir;
    private static $outputDir;

    public static function setUpBeforeClass()
    {
        $baseDir = dirname(dirname(dirname(__FILE__)));
        self::$inputDir = $baseDir . '/tests/input/';
        self::$outputDir = $baseDir . '/tests/output/';
    }

    public function setUp()
    {
        if (is_file(self::$outputDir . self::$jsonName))
            unlink(self::$outputDir . self::$jsonName); // delete file
        copy(self::$inputDir . self::$jsonName, self::$outputDir . self::$jsonName);
    }

    public function testOpen()
    {
        $list = new Gallery();
        $list->fromJson(self::$outputDir . self::$jsonName);
        $this->assertTrue(file_exists(self::$outputDir . self::$jsonName));
    }

    public function testAdd()
    {
        $list = new Gallery();
        $list->fromJson(self::$outputDir . self::$jsonName);
        $item = new GalleryItem("desc", "year", "pathToFull", "pathToThumb");
        $list->add($item);
        $this->assertEquals($item->toArray(), json_decode(file_get_contents(self::$outputDir . self::$jsonName), true)[0]);
    }

    public function testExists()
    {
        $list = new Gallery();
        $list->fromJson(self::$outputDir . self::$jsonName);
        $item = new GalleryItem("desc", "year", "pathToFull", "pathToThumb");
        $list->add($item);
        $this->assertTrue($list->exists($item->getName()));
        $this->assertFalse($list->exists("Some other name"));
    }

    public function testExistsFromFile()
    {
        $list = new Gallery();
        $list->fromJson(self::$inputDir . self::$jsonName);
        $item = new GalleryItem("desc", "year", "pathToFull", "pathToThumb");
        $this->assertTrue($list->exists($item->getName()));
        $this->assertFalse($list->exists("Some other name"));
    }

    public function testDelete()
    {
        $list = new Gallery();
        $list->fromJson(self::$outputDir . self::$jsonName);
        $item = new GalleryItem("other_desc", "year", "pathToFull", "pathToThumb");
        $list->add($item);
        $this->assertTrue($list->deleteByName($item->getName()));
        $this->assertFalse($list->exists($item->getName()));
    }

    public function testDeleteFromFile()
    {
        $list = new Gallery();
        $list->fromJson(self::$outputDir . self::$jsonName);
        $item = new GalleryItem("desc", "year", "pathToFull", "pathToThumb");
        $this->assertTrue($list->exists($item->getName()));
        $this->assertTrue($list->deleteByName($item->getName()));
        $this->assertFalse($list->exists($item->getName()));
        $list = new Gallery();
        $list->fromJson(self::$outputDir . self::$jsonName);
        $this->assertFalse($list->exists($item->getName()));
    }

    public function testGet()
    {
        $list = new Gallery();
        $list->fromJson(self::$outputDir . self::$jsonName);
        $item = new GalleryItem("desc", "year", "pathToFull", "pathToThumb");
        $this->assertEquals($list->get($item->getName()), $item);
    }
}
