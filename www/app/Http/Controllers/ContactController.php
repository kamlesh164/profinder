<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.contact',
            'title' => 'Contact Us Page',
            'Css' => StyleController::getCss(['common','contact']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
