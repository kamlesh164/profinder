<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Js;

class FrontHomeController extends Controller
{
    public function index()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.home',
            'title' => 'Home Page',
            'Css' => StyleController::getCss(['common']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
