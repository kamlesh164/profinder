<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FAQsController extends Controller
{
    public function index()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.faq',
            'title' => 'FAQs Page',
            'Css' => StyleController::getCss(['common','faq']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
