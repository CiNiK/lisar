<?php

require __DIR__ . '/vendor/autoload.php';
define('ROOT', __DIR__);
//F3::set('CACHE',TRUE);
F3::set('DEBUG', 3);
F3::set('UI', 'ui/');


F3::route('GET /',
    function () {
        $view = new View;
        echo $view->render('gallery.htm');
    });

F3::route('GET /admin',
    function () {
        F3::set('html_title', 'My Blog Administration');

        $db = new \DB\Jig('data/');
        $user = new \DB\Jig\Mapper($db, 'users.json');
        $auth = new \Auth($user, array('id' => 'username', 'pw' => 'password'));
        $auth->basic();
        if ($auth) {
            //set the session so user stays logged in
            F3::set('SESSION.user', $auth->name);
            F3::reroute('/admin/upload');
        } else {
            F3::reroute('/admin');
        }
        $view = new View;
        echo $view->render('layout.htm');
    }
);

F3::route('GET /admin/list',
    function () {
        (new app\controllers\GalleryController())->viewAll();
    }
);

F3::route('GET /admin/upload',
    function () {
        F3::set('html_title', 'My Blog Create');
        F3::set('content', 'admin_edit.html');
        if (!F3::get('SESSION.user')) F3::set('content', 'security.html');
        $view = new View;
        echo $view->render('upload.html');
    }
);

F3::route('POST /admin/loadToServer',
    function () {
       echo (new app\controllers\UploadController())->load();
    }
);

F3::route('DELETE /admin/deleteFromServer/@id',
    function () {
        echo (new app\controllers\UploadController())->delete();
    }
);

F3::route('GET /admin/delete/@name',
    function () {
        (new app\controllers\GalleryController())->delete(F3::get('PARAMS.name'));
    }
);

F3::route('GET /admin/addToGallery',
    function () {
        (new app\controllers\GalleryController())->loadAll();
    }
);

F3::run();

?>
