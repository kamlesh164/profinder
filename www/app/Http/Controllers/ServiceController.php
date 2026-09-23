<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.service',
            'title' => 'Services Page',
            'Css' => StyleController::getCss(['common','service']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
