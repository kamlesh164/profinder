<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Designation;
use App\Models\Timeslot;
use App\Models\BhkApartment;
use App\Models\City;
use App\Models\Product;
use App\Models\Service;
use App\Models\Slider;
use App\Models\SquareFoot;
use App\Models\State;
use DB;
use Symfony\Component\CssSelector\Node\FunctionNode;

class MasterController extends BaseApiController
{

    public function category(Request $request)
    {
        try {
            // $categories = Category::where('status', 1)->get();
            $categories = Category::Obj()->getAll();
            return $this->successResponse($categories, 'Category fetched successfully');
        } catch (\Exception $m) {
            return $this->errorResponse($m->getMessage());
        }
    }
    public function subcategory(Request $request)
    {
        try {
            $categories = SubCategory::with('category')->where('status', 1)->get();
            return $this->successResponse($categories, 'SubCategory fetched successfully');
        } catch (\Exception $m) {
            return $this->errorResponse($m->getMessage());
        }
    }
    public function states()
    {
        try {
            // $states = DB::table('states')->get();
            $states = State::all();
            return $this->successResponse($states, 'States fetched successfully');
        } catch (\Exception $m) {
            return $this->errorResponse($m->getMessage());
        }
    }
    public function citiesByState(Request $request, int $stateId){
        try {
            $cities = City::where('state_id', $stateId)
                        ->select('id', 'name', 'state_id')
                        ->get();
            
            return $this->successResponse($cities, 'Cities fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
    public function searchCity(Request $request){
        try {
            $request->validate([
                'q'    => 'required|string|min:1',
                'limit'=> 'nullable|integer|min:1|max:20',
            ]);
            $q = $request->q;
            $limit = $request->limit ?? 10;
            $cities = City::query()
                ->where('name', 'LIKE', $q.'%') 
                ->orderByRaw('CASE WHEN name = ? THEN 0 WHEN name LIKE ? THEN 1 ELSE 2 END', [
                    $q,
                    $q.'%',
                ])           
                ->limit($limit)
                ->get(['id','name']);
            
            return $this->successResponse($cities, 'Cities found successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function designation()
    {
        try {
            $states = Designation::get();
            return $this->successResponse($states, 'Designation fetched successfully');
        } catch (\Exception $m) {
            return $this->errorResponse($m->getMessage());
        }
    }
    public function timeslots()
    {
        try {
            $datas = Timeslot::get();
            $datas->transform(function ($item) {
                $item->slots = json_decode($item->slots);
                return $item;
            });
            return $this->successResponse($datas, 'Timeslots fetched successfully');
        } catch (\Exception $m) {
            return $this->errorResponse($m->getMessage());
        }
    }
    public function apartments()
    {
        try {
            $datas = [
                'square_feet' => SquareFoot::get(),
                'bhk' => BhkApartment::get(),
            ];
            return $this->successResponse($datas, 'BhkApartment fetched successfully');
        } catch (\Exception $m) {
            return $this->errorResponse($m->getMessage());
        }
    }
    public function citiesWithServices(Request $request){
        try {
            
            $cities = City::whereHas('services')
            ->select('id', 'name', 'state_id')
            ->orderBy('name')
            ->get();
            
            return $this->successResponse($cities, 'Cities found successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
    public function getBanners(Request $request){
        try {
            
            $topbanners = Slider::Obj()->getAll(['type' =>1]);
            $secondbanners = Slider::Obj()->getAll(['type' =>2]);

            return response()->json([
                'success' => true,
                'topBanners' => $topbanners,
                'secondBanners' => $secondbanners
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
    public function search(Request $request)
    {
        $request->validate([
            'q'      => ['required', 'string', 'max:255'],
            'cityId' => ['required']
        ]);

        $term = $request->q;

        $products = Product::query()
            ->where('name', 'LIKE', "%{$term}%")
            ->where('city_id', $request->cityId)
            ->select('id', 'name')
            ->get();

        $services = Service::query()
            ->where('name', 'LIKE', "%{$term}%")
            ->where('city_id', $request->cityId)
            ->select('id', 'name')
            ->get();

        $results = [];

        foreach ($products as $p) {
            $results[] = [
                'type' => 'product',
                'id'   => $p->id,
                'name' => $p->name,
            ];
        }

        foreach ($services as $s) {
            $results[] = [
                'type' => 'service',
                'id'   => $s->id,
                'name' => $s->name,
            ];
        }

        return response()->json([
            'success' => true,
            'query'   => $term,
            'results' => $results,
        ]);
    }

}
