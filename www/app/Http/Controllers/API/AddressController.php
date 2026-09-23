<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AddressController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $datas = UserAddress::where('user_id', $user->id)->get();
            return $this->successResponse($datas, 'Address data fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|string',
            'mobile' => 'required|digits:10|unique:user_addresses,mobile',
            'address_type' => 'required|in:Home,Office,Other',
            'house_no' => 'required|integer',
            'area' => 'required',
            'pincode' => 'required|digits:6',
            'city' => 'required|string',
            'state' => 'required|string',
            'is_default' => 'required|in:1,0',

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            if ($request->is_default == 1) {
                if (UserAddress::where('user_id', $user->id)->where('is_default', $request->is_default)->exists()) {
                    return $this->errorResponse('Only one default address is allowed per user.');
                }
            }
            $data = $request->all();
            $data['user_id'] = $user->id;
            $alldata = UserAddress::create($data);
            return $this->successResponse($alldata, 'Address store successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function update(Request $request)
    {

        $rules = [
            'name'          => 'required|string',
            'mobile'        => [
                'required',
                'digits:10',
                Rule::unique('user_addresses', 'mobile')->ignore($request->address_id),
            ],
            'address_type'  => 'required|in:Home,Office,Other',
            'house_no'      => 'required|integer',
            'area'          => 'required|string',
            'pincode'       => 'required|digits:6',
            'city'          => 'required|string',
            'state'         => 'required|string',
            'is_default'    => 'required|in:1,0',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = $request->user();
            $address = UserAddress::where('user_id', $user->id)->findOrFail($request->address_id);
            if ($request->is_default == 1) {
                UserAddress::where('user_id', $user->id)
                    ->where('is_default', 1)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => 0]);
            }
            $address->update($request->only([
                'name',
                'mobile',
                'address_type',
                'house_no',
                'area',
                'pincode',
                'city',
                'state',
                'is_default',
            ]));
            return $this->successResponse($address, 'Address data fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            UserAddress::find($id)->delete();
            return $this->successResponse($id, 'Address deleted successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function addAddress(Request $request){
        try {
            $request->validate([
                'address_type' => 'required|in:Home,Office,Other',
                'house_no'     => 'required|integer',
                'area'         => 'required',
                'pincode'      => 'required|digits:6',
                'city'         => 'required|string',
                'state'        => 'required|string',
                'is_default'   => 'required|in:1,0',
            ]);

            $user = $request->user();

            if ($request->is_default == 1) {
                UserAddress::where('user_id', $user->id)
                ->where('is_default', 1)
                ->update(['is_default' => 0]);
            }

            $data = $request->all();
            $fullAddress =  $request->house_no . ' ' .
                            $request->area . ', ' .
                            $request->city . ', ' .
                            $request->state . ' - ' .
                            $request->pincode;
            $data['user_id'] = $user->id;
            $data['address'] = $fullAddress;

            $address = UserAddress::updateOrCreate(
                [
                    'user_id'      => $user->id,
                    'address_type' => $request->address_type,
                ],
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Address stored successfully!',
                'data'    => $address
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $ex->errors(),
            ], 422);
        }
        catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
    public function allAddressById(Request $request){
        try {
            $request->validate([
                'userId' => 'required|string'
            ]);

            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $addresses = UserAddress::where('user_id', $user->id)->get();

            return response()->json([
                'success' => true,
                'message' => 'All addresses fetched!',
                'data'    => $addresses
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $ex->errors(),
            ], 422);
        }
        catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

}
