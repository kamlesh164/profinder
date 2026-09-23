<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.terms-and-conditions',
            'title' => 'Terms and Conditions Page',
            'Css' => StyleController::getCss(['common','contact']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
