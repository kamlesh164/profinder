<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;

class ApiServiceCategoryController extends BaseApiController
{
    public function allCategory(Request $request){
        try {
            $categories = ServiceCategory::Obj()->getAll(['id', 'name', 'image']);
            return $this->successResponse($categories);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
