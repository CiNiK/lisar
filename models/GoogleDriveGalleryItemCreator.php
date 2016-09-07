<?php
namespace app\models;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;

putenv('GOOGLE_APPLICATION_CREDENTIALS=Lisar-985cffd544b5.json');
class GoogleDriveGalleryItemCreator extends GalleryItemCreator
{
    private $service;

    /**
     * GoogleDriveImageItemCreator constructor.
     */
    public function __construct()
    {
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google_Service_Drive::DRIVE);
        $this->service = new Google_Service_Drive($client);
    }

    function create($path)
    {
        $imageToLoad = new Image($path);
        $name = pathinfo($path, PATHINFO_FILENAME);
        list($desc, $year) = explode('_', $name);
        $imageToLoad->resize($this->fullImageProps["height"], $this->fullImageProps["width"]);
        $fullID = $this->upload($name, $imageToLoad->getAsString());
        $fullSrc = $this->getPath($fullID);
        //thumb
        $imageToLoad->resize($this->lowImageProps["height"], $this->lowImageProps["width"]);
        $lowID = $this->upload($name, $imageToLoad->getAsString());
        $lowSrc = $this->getPath($lowID);
        return new GalleryItem($desc, $year, $fullSrc, $lowSrc);
    }

    function delete(GalleryItem $item)
    {
        $fullID = $this->getID($item->getFullSrc());
        $this->service->files->delete($fullID);
        $lowID = $this->getID($item->getLowSrc());
        $this->service->files->delete($lowID);
    }

    /**
     * @param $id
     * @return string
     */
    private function getPath($id)
    {
        return "http://docs.google.com/uc?id=".$id;
    }

    private function getID($url) {
        $parts = parse_url($url);
        parse_str($parts['query'], $query);
        return $query['id'];
    }

    /**
     * @param $data
     * @param $name
     * @return mixed uploaded file id
     */
    public function upload($name, $data)
    {
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($name);
        $file->setMimeType('image/jpeg');
        $file = $this->service->files->create($file, array(
            'data' => $data,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart'
        ));
        //Give everyone permission to read and write the file
        $permission = new Google_Service_Drive_Permission();
        $permission->setRole('reader');
        $permission->setType('anyone');
        $this->service->permissions->create($file->getId(), $permission);
        return $file->getId();
    }

}