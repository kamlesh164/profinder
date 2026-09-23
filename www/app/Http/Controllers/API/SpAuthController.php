<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BookingSlot;
use App\Models\City;
use App\Models\UserInfoCategory;
use Illuminate\Http\Request;
use App\Models\ServiceProfessional;
use App\Models\State;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SpAuthController extends BaseApiController
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required',
            'category_id' => 'required|integer|exists:info_categories,id',
            'email' => 'required|email',
            'gender' => 'required|in:Male,Female,Others',
            'address' => 'required',
            'phone' => 'required|digits:10',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
        $phone = '+91' . $request->phone;
        $existing = User::where('phone', $phone)
                ->where('type', $request->type)
                ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'This phone number is already registered!!',
            ], 422);
        }
        $existing = User::where('email', $request->email)
                ->where('type', $request->type)
                ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already registered with this Vendor Type!!',
            ], 422);
        }
        
        try {
            
            $otp = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $phone,
                'gender' => $request->gender,
                'type' => $request->type,
                'address' => $request->address,
                'city_id' => $request->city,
                'website' => $request->website,
                'otp' => $otp,
                'otp_valid_till' => Carbon::now()->addSeconds(30),
                'password' => Hash::make($request->password),
                'source' => 'web',
            ]);
            UserInfoCategory::create([
                'user_id'     => $user->id,
                'info_category_id' => $request->category_id,
            ]);

            $state = State::find($request->state);
            $city  = City::find($request->city);

            $stateName = $state?->name;
            $cityName  = $city?->name;

            $fullAddress = implode(', ', array_filter([
                $request->address,
                $cityName,
                $stateName,
                $request->pincode,
            ]));

            UserAddress::create([
                'user_id'      => $user->id,
                'city'         => $request->city,
                'state'        => $request->state,
                'pincode'      => $request->pincode,
                'address'      => $fullAddress,
                'latitude'     => null, 
                'longitude'    => null,
                'is_default'   => 1,    
                'address_type' => 'Home',
            ]);

            // Mail::to($request->email)->send(new RegisterMail($user, $otp));
            // return response()->json([
            //     'success' => true,
            //     'message' => 'OTP sent, Valid till 5 Minutes',
            //     'otp' => $otp,
            //     'data' => $vendor
            // ]);
            return response()->json([
                'success' => true,
                'message' => 'Registered Succesfully',
                'otp' => $otp,
                'data' => $user
            ]);
        } catch (\Exception $m) {
            return response()->json([
                'success' => false,
                'message' => $m->getMessage()
            ]);
        }
    }
    public function createSlot(Request $request){
        try {
            
            return $this->successResponse('');
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return $this->errorResponse('Validation failed',$ex->errors());
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

}
