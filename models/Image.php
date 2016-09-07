<?php
namespace app\models;

class Image
{
    private $image;

    public function __construct($path)
    {
        $this->image = new \PHPThumb\GD($path);
        $orientation = $this->getOrientation($path);
        $this->rotate($orientation);
    }

    public function saveAs($path, $maxWidth, $maxHeight)
    {
        $this->image->resize($maxWidth, $maxHeight);
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $this->image->save($path, $ext);
    }
    
    public function resize($maxWidth, $maxHeight) {
        $this->image->resize($maxWidth, $maxHeight);
    }
    
    public function getAsString(){
        return $this->image->getImageAsString();
    }

    private function getOrientation($path)
    {
        $exif = exif_read_data($path);
        if (isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
        } else {
            $orientation = NULL;
        }
        return $orientation;
    }

    private function rotate($orientation)
    {
        switch ($orientation) {
            case 3:
                $this->image->rotateImageNDegrees(180);
                break;
            case 6:
                $this->image->rotateImageNDegrees(270);
                break;
            case 8:
                $this->image->rotateImageNDegrees(90);
                break;
        }
    }
}
