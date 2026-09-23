<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    public function index()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.privacy-policy',
            'title' => 'Pricavy Policy Page',
            'Css' => StyleController::getCss(['common','contact']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
