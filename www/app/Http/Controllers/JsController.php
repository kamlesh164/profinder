<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsController extends Controller
{
    public static function getJs($keys) {
        $path = 'js/';
        $js = [
            'common' => [
                $path.'jquery-3.7.1.min.js',
                $path.'bootstrap.bundle.min.js',
                $path.'libs/dist/jquery.validate.min.js',
                $path.'libs/dist/additional-methods.min.js',
                $path.'common.js',
            ],
            'admin' => [
                'admin',
                'dashboard'
            ],
        ];

        $final = [];
        foreach ($keys as $key) {
            if (isset($js[$key])) {
                $final = array_merge($final, $js[$key]);
            } else {
                $final[] = $path . $key . '.js';
            }
        }
        return array_unique($final);
    }
}
