<?php
namespace app\models;

use app\exceptions\GalleryException;

class Gallery
{
    private $items = [];
    private $path;

    public function fromJson($path)
    {
        $this->validateJson($path);
        $this->path = $path;
        $this->items = json_decode(file_get_contents($path), true);
    }

    private function validateJson($path)
    {
        if (!file_exists($path))
            throw new GalleryException("File $path doesn't exist");
        if (pathinfo($path, PATHINFO_EXTENSION) != 'json')
            throw new GalleryException("File $path is not json file");
        return true;
    }

    public function add(GalleryItem $image)
    {
        $this->items[] = $image->toArray();
        $this->save();
    }

    public function exists($name)
    {
        return $this->getIndex($name) != -1;
    }

    private function save()
    {
        return file_put_contents($this->path, json_encode($this->items));
    }

    public function toArray()
    {
        return $this->items;
    }

    public function deleteByName($name)
    {
        $index = $this->getIndex($name);
        if ($index == -1) return false;
        return $this->deleteByIndex($index);
    }


    public function deleteAll(){
        for($i = 0; $i < sizeof($this->items); $i++){
            $this->deleteByIndex(0);
        }
    }

    public function get($name){
        $index = $this->getIndex($name);
        if($index == -1) return null;
        $item = $this->items[$index];
        return new GalleryItem($item['description'],$item['category'],$item['fullsrc'],$item['lowsrc']);
    }

    /**
     * @param $name
     * @return int
     */
    private function getIndex($name)
    {
        for ($i = 0; $i < sizeof($this->items); $i++) {
            $current = $this->items[$i];
            if ($current['description'] . '_' . $current['category'] == $name)
                return $i;
        }
        return -1;
    }

    /**
     * @param $index
     * @return GalleryItem
     */
    private function buildItem($index)
    {
        $itemProps = $this->items[$index];
        $item = new GalleryItem($itemProps['description'], $itemProps['category'], $itemProps['fullsrc'], $itemProps['lowsrc']);
        return $item;
    }

    /**
     * @param $index
     * @return bool
     */
    private function deleteByIndex($index)
    {
        $item = $this->buildItem($index);
        $item->delete();
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->save();
        return true;
    }
}
