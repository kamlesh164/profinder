<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.about-us',
            'title' => 'About Us Page',
            'Css' => StyleController::getCss(['common','aboutus']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
