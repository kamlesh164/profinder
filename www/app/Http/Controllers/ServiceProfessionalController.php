<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use App\Models\InfoCategory;

class ServiceProfessionalController extends Controller
{
    public function index()
    {
        $states = State::orderBy('name','asc')->get();
        $categories = InfoCategory::orderBy('created_at', 'desc')->get();
        return view('layouts.default_layouts', [
            'T_Body' => 'front.service-professional',
            'title' => 'Service Professional Page',
            'states' => $states,
            'categories' => $categories,
            'Css' => StyleController::getCss(['common','service-professional']),
            'Js' => JsController::getJs(['common'])
        ]);
    }
}
