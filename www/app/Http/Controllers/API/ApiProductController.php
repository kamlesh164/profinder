<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiProductController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $datas = Product::where('is_active',1)->where('approve_admin',1)->paginate();
            return $this->successResponse($datas, 'Product data fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function AllWishlist(Request $request)
    {
        try {
            $datas = Wishlist::with('user','product')->get();
            return $this->successResponse($datas, 'Wishlist data fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function wishlistStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id'     => 'required|numeric|exists:products,id'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation Error', $validator->errors());
            }
            $user = $request->user();
            $datas = Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
            ]);
            return $this->successResponse($datas, 'Wishlist store successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function wishlistRemove($id)
    {
        try {
            Wishlist::find($id)->delete();
            return $this->successResponse($id, 'Wishlist removed successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function getAllProductsByCity(Request $request){
        
    }
}
