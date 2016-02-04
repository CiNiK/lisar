<?php
namespace app\models;
defined('ROOT') or die("Root directory didn't defined");
use app\exceptions\GalleryException;
use app\exceptions\ImageAlreadyExistsException;
use app\exceptions\ImageNotFoundException;
use app\exceptions\ImagePropertyException;

class GalleryItemCreator{
	private $fullImageProps;
	private $lowImageProps;

	public function setFullImageProps(array $props){
		$this->validateImageProps($props);
		$this->fullImageProps = $props;
	}

	public function setLowImageProps(array $props){
		$this->validateImageProps($props);
		$this->lowImageProps = $props;
	}

	public function create($path){
		$imageToLoad = new Image($path);
		$name = pathinfo($path, PATHINFO_FILENAME);
		list($desc, $year) = explode('_', $name);
		$fullsrc = $this->getPath($this->fullImageProps, $name);
		$this->saveImage($this->fullImageProps, $fullsrc, $imageToLoad);
		$lowsrc = $this->getPath($this->lowImageProps, $name);
        try{
		    $this->saveImage($this->lowImageProps, $lowsrc, $imageToLoad);
        }catch (GalleryException $e){
            unlink($this->getPath($this->fullImageProps,$name));
            throw $e;
        }
		$item = new GalleryItem($desc, $year, $fullsrc, $lowsrc);
		return $item;
	}

    public function delete(GalleryItem $item){
        $props = $item->toArray();
        if(file_exists(ROOT.$props['fullsrc']))
            unlink(ROOT.$props['fullsrc']);
        if(file_exists(ROOT.$props['lowsrc']))
            unlink(ROOT.$props['lowsrc']);
    }

	/**
	 * @param $imgProps
	 * @param $name
	 * @return string
	 */
	private function getPath(array $imgProps, $name)
	{
		return $imgProps['path'] . $name . '.' . $imgProps['ext'];
	}

	/**
	 * @param $imgProps
	 * @param $src
	 * @param $imageToLoad
	 * @throws ImageAlreadyExistsException
	 * @throws ImageNotFoundException
	 */
	private function saveImage(array $imgProps, $src, Image $imageToLoad)
	{
		if (file_exists(ROOT . $src)) throw new ImageAlreadyExistsException("Image " .pathinfo($src,PATHINFO_BASENAME) . " already exists");
		$imageToLoad->saveAs(ROOT . $src, $imgProps['height'], $imgProps['width']);
		if (!file_exists(ROOT . $src)) throw new ImageNotFoundException("Unable to save image " . ROOT . $src);
	}

	private function validateImageProps(array $props){
		if(!self::isValidImageSize($props['width']))
			throw new ImagePropertyException(" Invalid image width: ".$props['width']);
		if(!self::isValidImageSize($props['height']))
			throw new ImagePropertyException("Invalid image height: ".$props['height']);
		if(!in_array($props['ext'],['jpg','jpeg','png']))
			throw new ImagePropertyException("Unsupported image extension : ".$props['height']);
		if(!is_dir(ROOT.$props['path']))
			throw new ImagePropertyException(ROOT.$props['path']." is not a directory");
		if(!is_writable(ROOT.$props['path']))
			throw new ImagePropertyException(" Directory ".$props['path']." is not writable");
		return true;
	}

	private function isValidImageSize($size){
		if(!(is_numeric($size)) OR $size == 0) return false;
		return true;
	}
}
