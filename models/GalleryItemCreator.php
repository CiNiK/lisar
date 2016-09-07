<?php
namespace app\models;
defined('ROOT') or die("Root directory didn't defined");

use app\exceptions\ImagePropertyException;

abstract class GalleryItemCreator
{
    protected $fullImageProps;
    protected $lowImageProps;

    public function setFullImageProps(array $props)
    {
        $this->validateImageProps($props);
        $this->fullImageProps = $props;
    }

    public function setLowImageProps(array $props)
    {
        $this->validateImageProps($props);
        $this->lowImageProps = $props;
    }

    private function validateImageProps(array $props)
    {
        if (!self::isValidImageSize($props['width']))
            throw new ImagePropertyException(" Invalid image width: " . $props['width']);
        if (!self::isValidImageSize($props['height']))
            throw new ImagePropertyException("Invalid image height: " . $props['height']);
        if (!in_array($props['ext'], ['jpg', 'jpeg', 'png']))
            throw new ImagePropertyException("Unsupported image extension : " . $props['height']);
        if (!is_dir(ROOT . $props['path']))
            throw new ImagePropertyException(ROOT . $props['path'] . " is not a directory");
        if (!is_writable(ROOT . $props['path']))
            throw new ImagePropertyException(" Directory " . $props['path'] . " is not writable");
        return true;
    }

    private function isValidImageSize($size)
    {
        if (!(is_numeric($size)) OR $size == 0) return false;
        return true;
    }

    abstract function create($path);

    abstract function delete(GalleryItem $item);
}