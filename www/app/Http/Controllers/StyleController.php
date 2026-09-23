<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StyleController extends Controller
{
    public static function getCss($keys) {
        $path = 'css/';
        $styles = [
            'common' => [
                $path.'bootstrap.min.css',
                $path.'fontawesome-6.4.0/css/all.min.css',
                $path.'css2.css',
                $path.'jquery-ui.min.css',
                $path.'style.css',
            ]
        ];

        $final = [];
        foreach ($keys as $key) {
            if (isset($styles[$key])) {
                $final = array_merge($final, $styles[$key]);
            } else {
                $final[] = $path . $key . '.css';
            }
        }
        return array_unique($final);
    }
}
