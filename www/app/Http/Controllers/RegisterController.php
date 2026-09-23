<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('layouts.default_layouts', [
            'T_Body' => 'front.register',
            'title' => 'Register Page',
            'Css' => StyleController::getCss(['common']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
