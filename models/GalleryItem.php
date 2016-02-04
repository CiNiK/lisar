<?php
namespace app\models;

class GalleryItem
{
    private $description;
    private $category;
    private $fullsrc;
    private $lowsrc;

    public function __construct($description, $category, $fullsrc, $lowsrc)
    {
        $this->description = $description;
        $this->category = $category;
        $this->fullsrc = $fullsrc;
        $this->lowsrc = $lowsrc;
    }

    public function toArray()
    {
        return [
            'description' => $this->description,
            'category' => $this->category,
            'fullsrc' => $this->fullsrc,
            'lowsrc' => $this->lowsrc
        ];
    }

    public function getName()
    {
        return $this->description . '_' . $this->category;
    }

    public function delete(){
        if(file_exists(ROOT.$this->fullsrc))
            unlink(ROOT.$this->fullsrc);
        if(file_exists(ROOT.$this->lowsrc))
            unlink(ROOT.$this->lowsrc);
    }

}
