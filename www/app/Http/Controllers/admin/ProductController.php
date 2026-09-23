<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $products = Product::with(['serviceProfessional'])->paginate(15);
            return view('admin.products.index',compact('products'));
        } catch (\Exception $th) {
            return back('error',$th->getMessage());
        }
    }
    public function details(Product $product)
    {
        try {
            return view('admin.products.show',compact('product'));
        } catch (\Exception $th) {
            return back('error',$th->getMessage());
        }
    }
    public function approval(Request $request)
    {
        try {
            Product::where('id',$request->product_id)->update([
                 'approve_admin' => $request->status
            ]);
            return back()->with('success','Updated successfully!');
        } catch (\Exception $th) {
            return back('error',$th->getMessage());
        }
    }
    public function changeStatus($id){
        $product = Product::find($id);
        if($product->is_active==1){
            $product->is_active=0;
            $product->save();
            $message = 'Inactive Successfully';
        }else{
            $product->is_active=1;
            $product->save();
            $message= 'Active Successfully';
        }
        return response()->json($message);
    }
}
