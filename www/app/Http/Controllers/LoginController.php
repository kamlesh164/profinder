<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.login',
            'title' => 'Login Page',
            'Css' => StyleController::getCss(['common']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
