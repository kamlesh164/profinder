<?php

namespace App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->ajax()) {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => trans('admin_validation.Email is required'),
                'password.required' => trans('admin_validation.Password is required'),
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $credential = [
                'email' => $request->email,
                'password' => $request->password
            ];

            $isAdmin = Admin::where('email', $request->email)->first();
            if ($isAdmin) {
                if ($isAdmin->status == 1) {
                    if (Hash::check($request->password, $isAdmin->password)) {
                        if (Auth::guard('admin')->attempt($credential, $request->remember)) {
                            $notification = 'Login Successfully';
                            return response()->json(['success' => $notification]);
                        }
                    } else {
                        $notification = 'Invalid Password';
                        return response()->json(['error' => $notification]);
                    }
                } else {
                    $notification = 'Inactive account';
                    return response()->json(['error' => $notification]);
                }
            } else {
                $notification = 'Invalid Email';
                return response()->json(['error' => $notification]);
            }
        }
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if ($request->isMethod('post')) {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'password' => 'required',
            ], [
                'email.required' => 'Email Address is required',
                'email.email' => 'Valid Email is required',
                'password.required' => 'Password is required',
            ]);
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid Email or Password');
            }
        }
        return view('admin.auth.login');
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    
}
