<?php

namespace App\Controller;

class AppController
{

    public function index()
    {
        ob_start();
        $a = "aaaa";
        var_dump(get_included_files());
        view('hello.html', compact("a"));
        echo nl2br(ob_get_clean());

    }
}
