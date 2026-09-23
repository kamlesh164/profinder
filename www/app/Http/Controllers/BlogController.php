<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.blog',
            'title' => 'Blogs Page',
            'Css' => StyleController::getCss(['common','blog']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
