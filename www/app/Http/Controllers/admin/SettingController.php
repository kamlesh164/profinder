<?php

namespace App\Http\Controllers\admin;

use App\DefaultTrait;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use DefaultTrait;
    public function CityStateByPincode($code)
    {
        try {
            $data = City::where('code', $code)->first();
            if ($data) {
                $stateName = $data->state->name;
                $cityName = $data->name;
                return response()->json([
                    'success' => true,
                    'city' => $cityName,
                    'state' => $stateName
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Invalid pincode',
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function index()
    {
        $setting = Setting::first();
        // $cookieConsent = CookieConsent::first();
        // $googleRecaptcha = GoogleRecaptcha::first();
        // $tawkChat = TawkChat::first();
        // $googleAnalytic = GoogleAnalytic::first();
        // $customPaginations = CustomPagination::all();
        // $socialLogin = SocialLoginInformation::first();
        // $facebookPixel = FacebookPixel::first();
        // $pusher = PusherCredentail::first();
        // $currencies = Currency::orderBy('name','asc')->get();
        // $selected_theme = $setting->selected_theme;
        return view('admin.settings', compact('setting'));

        // return view('admin.setting',compact('setting','cookieConsent','googleRecaptcha','tawkChat','googleAnalytic','customPaginations','socialLogin','facebookPixel','currencies','pusher','selected_theme'));
    }
    public function updateLogoFavicon(Request $request)
    {
        try {
            $setting = Setting::first();
            $logoFields = [
                'logo' => 'logo',
                'footer_logo' => 'footer_logo',
                'favicon' => 'favicon',
            ];
            foreach ($logoFields as $requestField => $settingField) {
                if ($request->$requestField) {
                    $old_logo = $setting->$settingField;
                    $file_path = $this->verifyAndUpload($request, $requestField, 'backend/') ?? null;
                    $setting->$settingField = $file_path;
                    $setting->save();
                }
            }
            return redirect()->back()->with('success', 'Update successfully!');
        } catch (\Exception $th) {
            return back('error',$th->getMessage());
        }
    }
}
