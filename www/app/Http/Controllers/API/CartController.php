<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CartController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId' => 'required|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation Error', $validator->errors());
            }

            $userId = $request->userId;

            $cartItems = Cart::with('product')
                ->where('user_id', $userId)
                ->get();

            return $this->successResponse($cartItems, 'Cart data fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId'     => 'required|integer|exists:users,id',
                'product_id' => 'required|integer|exists:products,id',
                'quantity'   => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation Error', $validator->errors());
            }
            $userId  = $request->userId;
            $product = Product::findOrFail($request->product_id);

            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                $cartItem = Cart::create([
                    'user_id'    => $userId,
                    'product_id' => $product->id,
                    'quantity'   => $request->quantity,
                ]);
            }
            return $this->successResponse($cartItem, 'Added to cart successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function updateQuantity(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId'   => 'required|integer|exists:users,id',
                'quantity' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation Error', $validator->errors());
            }

            $userId   = $request->userId;
            $quantity = $request->quantity;

            $cartItem = Cart::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            if ($quantity === 0) {
                $cartItem->delete();

                return $this->successResponse([], 'Item removed from cart.');
            }

            $cartItem->quantity = $quantity;
            $cartItem->save();

            return $this->successResponse($cartItem, 'Cart updated successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function changeQuantity(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer|exists:users,id',
            'action' => 'required|in:increase,decrease',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', $validator->errors());
        }

        $cartItem = Cart::where('id', $id)
            ->where('user_id', $request->userId)
            ->firstOrFail();

        if ($request->action === 'increase') {
            $cartItem->quantity++;
        } else {
            $cartItem->quantity--;
            if ($cartItem->quantity <= 0) {
                $cartItem->delete();
                return $this->successResponse([], 'Item removed from cart.');
            }
        }

        $cartItem->save();

        return $this->successResponse($cartItem, 'Cart updated successfully!');
    }

    public function remove(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
            'userId' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', $validator->errors());
        }

        $userId = $request->userId;

        $cartItem = Cart::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $cartItem->delete();
            return $this->successResponse([], 'Remove cart successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function clear(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId' => 'required|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation Error', $validator->errors());
            }

            $userId = $request->userId;

            Cart::where('user_id', $userId)->delete();
            
            return $this->successResponse([], 'Remove cart successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
}
