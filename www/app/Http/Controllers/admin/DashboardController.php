<?php

namespace App\Http\Controllers\admin;

use App\DefaultTrait;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PurchaseHistory;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    use DefaultTrait;
    public function dashboard(Request $request)
    {
        $data = [];
        $order = Order::where('payment_status', 'success');
        $data['totalOrder'] = $order->count();
        $data['pendingOrder'] = $order->where('order_status', 0)->count();
        $data['completeOrder'] = $order->where('order_status', 1)->count();

        // Customers
        $data['totalCustomer'] = User::count();
        $data['newCustomer'] = User::whereDate('created_at', '>=', now()->subDays(7))->count();

        // Vendors
        $data['totalVendor'] = Vendor::count();

        $data['purchaseHistory'] = PurchaseHistory::count();
        $data['totalBookingAmount'] = Order::where('payment_status', 'success')->sum('total_amount');

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY);

        $purchaseRaw = PurchaseHistory::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->select(DB::raw('DAYOFWEEK(created_at) as day'), DB::raw('COUNT(*) as total'))
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();
            
        $purchaseDayCount = [];
        for ($i = 1; $i <= 7; $i++) {
            $purchaseDayCount[] = $purchaseRaw[$i] ?? 0;
        }

        $data['purchaseDayCount'] = $purchaseDayCount;
        return view('admin.index', $data);
    }


    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        $setting = Setting::first();
        $default_avatar = array(
            'image' => $setting->default_avatar
        );
        $default_avatar = (object) $default_avatar;
        return view('admin.auth.profile', compact('admin', 'default_avatar'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            $rules = [
                'name' => 'required',
                'email' => 'required|unique:admins,email,' . $admin->id,
                'password' => 'confirmed',
            ];
            $customMessages = [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exist',
                'password.confirmed' => 'Confirm password does not match',
            ];
            $this->validate($request, $rules, $customMessages);

            $this->validate($request, $rules);
            $admin = Auth::guard('admin')->user();
            if ($request->hasFile('image')) {
                $file_path = $this->verifyAndUpload($request, 'image', 'backend/profile/') ?? null;
                $admin->image = $file_path;
            }
            if ($request->password) {
                $admin->password = Hash::make($request->password);
            }
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->save();

            $notification = 'Update Successfully';
            return back()->with('success', $notification);
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }
}
