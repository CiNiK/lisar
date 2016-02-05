<?php

namespace app\controllers;


use F3;

class UserController
{
    public function auth()
    {
        F3::clear('SESSION');
        F3::set('page_head','Authentication');
        $view = new \View;
        echo $view->render('auth.htm','text/html',['message' => "Please, type your name and password"]);
    }

    public function login()
    {
        $db = new \DB\Jig('data/');
        $user = new \DB\Jig\Mapper($db, 'users.json');
        $auth = new \Auth($user, array('id' => 'username', 'pw' => 'password'));
        if ($auth->login(F3::get('POST.login'),F3::get('POST.password')) == true) {
            F3::set('SESSION.user', F3::get('POST.login'));
            F3::reroute('upload');
        }
        else{
            $view = new \View;
            echo $view->render('auth.htm','text/html',['message' => "Incorrect name or password"]);
        }
    }
}