<?php

namespace App\Http\Controllers\API\Vendor;

use App\DefaultTrait;
use App\Http\Controllers\Controller;
use App\Mail\RegisterMail;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use DefaultTrait;

    public function signup(Request $request)
    {
        Vendor::where('otp_status', 0)->where('phone', $request->phone)->delete();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'vendor_type' => 'required|in:1,2,3',
            'email' => 'required|email',
            'gender' => 'required|in:Male,Female,Others',
            'address' => 'required',
            'phone' => 'required|digits:10|unique:vendors,phone',
            'city' => 'required',
            'state' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        try {

            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $vendor = Vendor::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'vendor_type' => $request->vendor_type,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'website' => $request->website,
                'otp' => $otp,
                'otp_valid_till' => Carbon::now()->addMinutes(5),
                'password' => Hash::make($request->password),
            ]);


            // Mail::to($request->email)->send(new RegisterMail($user, $otp));
            return response()->json([
                'success' => true,
                'message' => 'OTP sent, Valid till 5 Minutes',
                'otp' => $otp,
                'data' => $vendor
            ]);
        } catch (\Exception $m) {
            return response()->json([
                'success' => false,
                'message' => $m->getMessage()
            ]);
        }
    }

    public function VerifyOtp(Request $request)
    {

        try {

            $vendor = Vendor::where('phone', $request->phone)->first();
            if ($vendor->otp_valid_till < Carbon::now()) {

                return response()->json([
                    'success' => false,
                    'message' => 'OTP Expired'
                ]);
            }
            if ($request->otp != $vendor->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect OTP'
                ]);
            }
            $vendor->otp_status = 1;
            $vendor->save();
            event(new Registered($vendor));
            $token = $vendor->createToken('VendorRegisterToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'OTP Verified',
                'token' => $token,
                'data' => $vendor
            ]);
        } catch (\Exception $m) {
            return response()->json([
                'success' => false,
                'message' => $m->getMessage()
            ]);
        }
    }

    public function signin(Request $request)
    {
        try {
            $checkVerify = Vendor::where('phone', $request->phone)
                ->where('otp_status', 0)
                ->exists();

            if ($checkVerify) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify phone!',
                ]);
            }

            $vendor = Vendor::where('phone', $request->phone)->first();

            if (!$vendor || !Hash::check($request->password, $vendor->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            $token = $vendor->createToken('vendor-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'token' => $token,
                'data' => [
                    'name' => $vendor->name,
                    'email' => $vendor->email,
                    'vendor_type' => $vendor->vendor_type,
                    'created_at' => $vendor->created_at,
                ],
            ]);
        } catch (\Exception $m) {
            return response()->json([
                'success' => false,
                'message' => $m->getMessage(),
            ]);
        }
    }


    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $m) {
            return response()->json([
                'success' => false,
                'message' => $m->getMessage()
            ]);
        }
    }


    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful.',
            ]);
        } catch (\Exception $m) {
            return response()->json([
                'success' => false,
                'message' => $m->getMessage()
            ]);
        }
    }

    public function profileUpdate(Request $request)
    {
        try {
            $user = $request->user();
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->save();
            return $this->successResponse($user, 'Profile updated successfully');
        } catch (\Exception $m) {
            return $this->errorResponse($m->getMessage());
        }
    }
}
