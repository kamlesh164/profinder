<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.subscription',
            'title' => 'Subscription Page',
            'Css' => StyleController::getCss(['common','subscription']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
