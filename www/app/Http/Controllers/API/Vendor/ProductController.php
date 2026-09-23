<?php

namespace App\Http\Controllers\API\Vendor;

use App\DefaultTrait;
use App\Http\Controllers\API\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends BaseApiController
{
    use DefaultTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $products = Product::getAllProductsByParam($request->all());
            return $this->successResponse($products, 'Products fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error', $th->getMessage());
        }
    }

    public function indexUser(Request $request)
    {
        try {
            $products = Product::getAllProductsByParam($request->all());
            return $this->successResponse($products, 'Products fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error', $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId'          => 'required|string',
                'product_image'   => 'required|string',
                'category_id'     => 'required|numeric|exists:categories,id',
                'name'            => 'required|string|unique:products,name',
                'mrp'             => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
                'selling_price'   => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
                'description'     => 'required|string',
                'is_active'     => 'in:0,1'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation Error', $validator->errors());
            }

            $sp = User::findOrFail($request->userId);
            $reqdata = $request->all();
            $reqdata['sp_id'] = $sp->id;
            $reqdata['image'] = $this->verifyAndUpload($request, 'product_image', 'uploads/product/') ?? null;
            $alldata = Product::create($reqdata);
            return $this->successResponse($alldata, 'Products data store successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            $vendor = $request->user();
            $product = Product::with('category', 'vendor')->where('sp_id', $vendor->id)->find($id);
            return $this->successResponse($product, 'Product fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId' => 'required',
                'product_id' => 'required|exists:products,id',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse('Invalid product_id', $validator->errors());
            }
            $product = Product::find($request->product_id);
            $sp = User::findOrFail($request->userId);
            if ($product->sp_id !== $sp->id) {
                return $this->errorResponse('Unauthorized', 'You do not own this product.');
            }

            $validator = Validator::make($request->all(), [
                'category_id'     => 'required|numeric|exists:categories,id',
                'name'            => [
                    'required',
                    'string',
                    Rule::unique('products', 'name')->ignore($product->id)
                ],
                'mrp'             => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
                'selling_price'   => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
                'description'     => 'required|string',
                'is_active'       => 'in:0,1'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation Error', $validator->errors());
            }

            $data = $request->all();
            $data['approve_admin'] = 0;
            $product->update($data);

            return $this->successResponse($product->fresh(), 'Product updated successfully!');
        } catch (\Exception $e) {
            return $this->errorResponse('Backend Error', $e->getMessage());
        }
    }

    public function changeStatus($id)
    {
        try {
            $product = Product::find($id);
            if($product->is_active==1){
                $product->is_active=0;
            }else{
                $product->is_active=1;
            }
            $product->save();
            return $this->successResponse($product->fresh(), 'Product status updated successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error', $th->getMessage());
        }
    }

    public function deleteProduct($id)
    {
        try {
            $Product = Product::findOrFail($id);

            $Product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Product not found', $e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong', $e->getMessage());
        }
    }
    public function productDetailsById(Request $request){
        try {
            $request->validate([
                'productId' => 'required'
            ]);
            $product = Product::getProductDetailsById($request->productId);
            return $this->successResponse($product);
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return $this->errorResponse('Validation failed');
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }
}
