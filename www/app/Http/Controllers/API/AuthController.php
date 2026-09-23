<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Slider;
use App\Models\ServiceCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\DefaultTrait;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Helpers\StaticDataHelper;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseApiController
{

    use DefaultTrait;

    public function register(Request $request)
    {
        User::where('otp_status', 0)->where('phone', $request->phone)->delete();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|',
            'phone' => 'required|digits:10|unique:users,phone',
            'city' => 'required|string',
            'state' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'user_type' => 'required|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        try {

            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);


            $gstdoc = $this->verifyAndUpload($request, 'gst_doc', 'uploads/user/') ?? null;
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_type' => $request->user_type,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'city' => $request->city,
                'state' => $request->state,
                'website' => $request->website,
                'gst_number' => $request->gst_number,
                'gst_doc' => $gstdoc,
                'otp' => $otp,
                'otp_valid_till' => Carbon::now()->addMinutes(5),
                'password' => Hash::make($request->password),
            ]);


            Mail::to($request->email)->send(new RegisterMail($user, $otp));
            return response()->json([
                'success' => true,
                'message' => 'OTP sent, Valid till 5 Minutes',
                'otp' => $otp,
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'user_type' => $user->type == 1 ? 'Customer' : ($user->type == 2 ? 'Contractor' : 'Builder'),
                    'address' => $user->address ?? '',
                    'latitude' => $user->latitude ?? '',
                    'longitude' => $user->longitude ?? '',
                    'city' => $user->city ?? '',
                    'state' => $user->state ?? '',
                    'company_name' => $user->company_name ?? '',
                    'website' => $user->website ?? '',
                    'gst_number' => $user->gst_number ?? '',
                    'gst_doc' => $user->gst_doc ?? '',
                    'created_at' => $user->created_at,
                ],
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

            $user = User::where('phone', $request->phone)->first();
            if ($user->otp_valid_till < Carbon::now()) {

                return response()->json([
                    'success' => false,
                    'message' => 'OTP Expired'
                ]);
            }
            if ($request->otp != $user->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect OTP'
                ]);
            }
            $user->otp_status = 1;
            $user->save();
            $token = $user->createToken('RegisterToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'OTP Verified',
                'token' => $token,
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'user_type' => $user->type == 1 ? 'Customer' : ($user->type == 2 ? 'Contractor' : 'Builder'),
                    'address' => $user->address ?? '',
                    'latitude' => $user->latitude ?? '',
                    'longitude' => $user->longitude ?? '',
                    'city' => $user->city ?? '',
                    'state' => $user->state ?? '',
                    'company_name' => $user->company_name ?? '',
                    'website' => $user->website ?? '',
                    'gst_number' => $user->gst_number ?? '',
                    'gst_doc' => $user->gst_doc ?? '',
                    'created_at' => $user->created_at,
                ],
            ]);
        } catch (\Exception $m) {
            return response()->json([
                'success' => false,
                'message' => $m->getMessage()
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $checkVerify = User::where('phone', $request->phone)->where('otp_status', 0)->exists();
            if ($checkVerify) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify phone!'
                ]);
            }
            $user = User::where('phone', $request->phone)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            if ($user->status == 0) {

                return response()->json([
                    'success' => false,
                    'message' => 'Your account inactive, please contact to administrator!'
                ], 401);
            }

            $token = $user->createToken('CustomerLoginToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successfull.',
                'token' => $token,
                'data' => [
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'user_type' => $user->type == 1 ? 'Customer' : ($user->type == 2 ? 'Contractor' : 'Builder'),
                    'created_at' => $user->created_at,
                ],
            ]);
        } catch (\Exception $m) {
            return response()->json([
                'success' => false,
                'message' => $m->getMessage()
            ]);
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'user_type' => array_search($user->type, config('static_data.accountType')),
                    'address' => $user->address ?? '',
                    'city' => $user->city ?? '',
                    'state' => $user->state ?? '',
                    'created_at' => date('Y-m-d H:i:s',strtotime($user->created_at)),
                ],
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
            $request->validate([
                'userId' => 'required|string'
            ]);
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);

            return response()->json([
                'success' => true
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
    public function generateOTP(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string',
                'accountType' => 'required|string'
            ]);

            $accountType = config('static_data.accountType.'.strtolower($request->accountType));
            $otp = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

            $user = User::where('phone', $request->phone)
                    ->where('type', $accountType)
                    ->first();
            
            if ($user) {
                $user->otp = $otp;
                $user->otp_status = 0;
                $user->otp_valid_till = now()->addSeconds(30);
                $user->save();
            } else {
                User::create([
                    'phone' => $request->phone,
                    'type' => $accountType,
                    'otp' => $otp,
                    'otp_status' => 0,
                    'otp_valid_till' => now()->addSeconds(30),
                    'is_new_user' => 1
                ]);
            }

            return response()->json([
                'success'   => true,
                'message'   => 'OTP sent successfully',
                'otp'       => $otp,
                'otpLength' => strlen($otp)
            ]);
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $ex->errors(),
            ], 422);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
    public function otpVerify(Request $request) {
        try {
            $request->validate([
                'phone' => 'required|string',
                'otp' => 'required|string|max:6',
                'accountType' => 'required|string'
            ]);

            $accountType = config('static_data.accountType.'.strtolower($request->accountType));
            $user = User::where('phone', $request->phone)
                    ->where('type', $accountType)
                    ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            $isExist = !$user->is_new_user ? true:false;

            $token = null;
            $userData = null;

            if ($request->otp === $user->otp) {
                if(now()->lessThanOrEqualTo($user->otp_valid_till)){
                    $user->otp_status = 1;
                    $user->is_new_user = 0;
                    $user->save();

                    $token = JWTAuth::fromUser($user);
                    $refresh_token = $token;

                    $userData = [
                        'id' => $user->id,
                        'phone' => $user->phone,
                        'accountType' => array_search($user->type, config('static_data.accountType')),
                        'isExist' => $isExist
                    ];

                     return response()->json([
                        'success' => true,
                        'message' => 'OTP verified successfully',
                        'token' => $token,
                        'refresh_token' => $refresh_token,
                        'user' => $userData
                    ]);
                }
                else{
                    return response()->json([
                        'success' => false,
                        'message' => 'OTP expired',
                    ], 401);
                }
            }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP',
                ], 401);
            }
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ex->errors(),
            ], 422);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
    public function refresh(Request $request)
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();

            return response()->json([
                'success' => true,
                'token'   => $newToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        }
    }
    public function homeData(Request $request){
        try {
            $request->validate([
                'city' => 'required|string'
            ]);
            
            $topbanners = Slider::Obj()->getAll(['type' =>1]);
            $secondbanners = Slider::Obj()->getAll(['type' =>2]);
            $serviceCategories = ServiceCategory::Obj()->getAll(['id','name','slug','image'],5);
            $featuredServices = Service::getFeaturedServicesByCityName($request->city, 10);
            $popularSps = User::getPopularServiceProfessionalsByCityname($request->city,10);

            return response()->json([
                'success' => true,
                'topBanners' => $topbanners,
                'secondBanners' => $secondbanners,
                'category' => $serviceCategories,
                'featuredServices' => $featuredServices,
                'popularServiceProfessionals' => $popularSps,
            ]);
                
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ex->errors(),
            ], 422);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
    public function getUser(Request $request){
        try {
            $request->validate([
                'userId' => 'required|string'
            ]);

            $user = User::with(['address'])->find($request->userId);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            $data = [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'phone'             => $user->phone,
                'gender'            => $user->gender,
                'dob'               => $user->dob,
                'image_url'         => $user->image_url, 
                'type'              => array_search($user->type, config('static_data.accountType')),
                'is_verified'       => $user->is_verified,
                'profile_completed' => $user->profile_completed,
                'created_at'        => $user->created_at,
                'updated_at'        => $user->updated_at,
                'address'           => $user->address, 
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
                
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ex->errors(),
            ], 422);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
    public function saveProfile(Request $request)
    {
        try {
            $request->validate([
                'userId' => ['required', 'integer', 'exists:users,id'],
                'name'   => ['required', 'string', 'max:100'],
                'city'   => ['nullable', 'string', 'exits:cities,id'],
                'email'  => ['nullable', 'email', 'max:150'],
                'gender' => ['nullable', 'in:male,female,other'],
                'dob'    => ['nullable', 'date']
            ]);

            $user = User::find($request->userId);

            $user->name   = $request->name;
            $user->city_id   = $request->city;
            $user->email  = $request->email ?? $user->email;
            $user->gender = $request->gender;
            $user->dob    = $request->dob;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data'    => $user,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $ex->errors(),
            ], 422);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
    public function uploadImage(Request $request){
        try{
            $request->validate([
                'userId' => 'required|exists:users,id',
                'image'   => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);
            $user = User::findOrFail($request->userId);
            if ($user->image && file_exists(public_path($user->image))) {
                @unlink(public_path($user->image));
            }
            $file = $request->file('image');
            $uploadPath = public_path('uploads/media');

            if (! is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
                chmod($uploadPath, 0775); // Fix permission
            }

            $filename = 'media-'.$user->id.'-'.time().'.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);

            $user->image = 'uploads/media/'.$filename;
            $user->save();
            return response()->json([
                'success'   => true,
                'message'   => 'Image uploaded successfully',
                'image'     => $user->image,
                'image_url' => asset($user->image),
            ]);
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $ex->errors(),
            ], 422);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }


}
