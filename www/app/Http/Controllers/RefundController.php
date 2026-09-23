<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.refund-policy',
            'title' => 'Refund Policy Page',
            'Css' => StyleController::getCss(['common','contact']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
