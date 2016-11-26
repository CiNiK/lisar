<?php
namespace app\models;

use app\exceptions\GalleryException;
use app\exceptions\ImageNotFoundException;


class GalleryLoader
{
    private $itemList;
    private $itemHandler;
    private $loaded = [];
    private $failed = [];

    public function __construct()
    {
        $this->itemList = new Gallery();
        $this->itemHandler = new GoogleDriveGalleryItemCreator();
    }

    public function setItemList(Gallery $itemList)
    {
        $this->itemList = $itemList;
    }

    public function setItemHandler(GalleryItemCreator $itemHandler)
    {
        $this->itemHandler = $itemHandler;
        
    }

    public function load($path)
    {
        try {
            if (!file_exists($path)) throw new ImageNotFoundException("Image $path doesn't exist");
            $item = $this->itemHandler->create($path);
            $this->itemList->add($item);
            $this->loaded[] =$path;
        }catch (GalleryException $e){
            $failed['path'] = $path;
            $failed['cause'] = $e->getMessage();
            $this->failed[] = $failed;
        }
    }

    public function getLoaded(){
        return $this->loaded;
    }

    public function getFailed(){
        return $this->failed;
    }

    public function hasErrors(){
        return !empty($this->failed);
    }

    public function loadFromDir($dir)
    {
        $paths = $this->getImagePaths($dir);
        foreach ($paths as $path) {
            $this->load($path);
        }
    }

    public static function build(array $cfg){
        $loader = new GalleryLoader();
        $itemList = new Gallery();
        $itemList->fromJson(ROOT.$cfg['json']);
        $loader->setItemList($itemList);
        $itemHandler = new GoogleDriveGalleryItemCreator();
        $itemHandler->setFullImageProps($cfg['full']);
        $itemHandler->setLowImageProps($cfg['low']);
        $loader->setItemHandler($itemHandler);
        return $loader;
    }

    private function getImagePaths($dir)
    {
        $paths = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir)) as $filename) {
            if ($filename->isDir()) continue;
            if (!in_array($filename->getExtension(), ['jpg', 'jpeg', 'png'])) ;
            $paths[] = $filename->getPathname();
        }
        return $paths;
    }
}
