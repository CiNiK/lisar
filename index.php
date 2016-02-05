<?php

require __DIR__ . '/vendor/autoload.php';
define('ROOT', __DIR__);
//F3::set('CACHE',TRUE);
F3::set('DEBUG', 3);
F3::set('UI', 'ui/');
ini_set("upload_max_filesize","10M");
ini_set("post_max_size","10M");

F3::route('GET /',
    function () {
        $view = new View;
        echo $view->render('gallery.htm');
    });

F3::route('POST /admin/login',
    function(){
        (new app\controllers\UserController())->login();
    }
);
F3::route('GET /admin/list',
    function () {
        if(F3::exists('SESSION.user')) {
            (new app\controllers\GalleryController())->viewAll();
        }else{
            (new app\controllers\UserController())->auth();
        }
    }
);

F3::route('GET /admin/upload',
    function () {
        if(!F3::exists('SESSION.user')) {
            (new app\controllers\UserController())->auth();
        }else {
            F3::set('html_title', 'Uploading...');
            F3::set('content', 'admin_edit.html');
            $view = new View;
            echo $view->render('upload.html');
        }
    }
);

F3::route('POST /admin/loadToServer',
    function () {
        if(!F3::exists('SESSION.user')) {
            (new app\controllers\UserController())->auth();
        }
        echo (new app\controllers\UploadController())->load();
    }
);

F3::route('DELETE /admin/deleteFromServer/@id',
    function () {
        if(!F3::exists('SESSION.user')) {
            (new app\controllers\UserController())->auth();
        }else {
            echo (new app\controllers\UploadController())->delete();
        }
    }
);

F3::route('GET /admin/delete/@name',
    function () {
        if(!F3::exists('SESSION.user')) {
            (new app\controllers\UserController())->auth();
        }
        (new app\controllers\GalleryController())->delete(F3::get('PARAMS.name'));
    }
);

F3::route('GET /admin/addToGallery',
    function () {
        if(!F3::exists('SESSION.user')) {
            (new app\controllers\UserController())->auth();
        }
        (new app\controllers\GalleryController())->loadAll();
    }
);

F3::run();

?>
