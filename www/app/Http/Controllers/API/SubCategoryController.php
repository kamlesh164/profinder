<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\SubCategory;


class SubCategoryController extends BaseApiController
{
    

    public function index(Request $request)
    {
        try{
           $categories = SubCategory::with('category')->where('status',1)->get();
           return $this->successResponse($categories, 'SubCategory fetched successfully');
            
        }catch(\Exception $m){
              return $this->errorResponse($m->getMessages());
        }
    }
}
