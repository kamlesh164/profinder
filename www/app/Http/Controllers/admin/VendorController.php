<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Imports\VendorImport;
use App\Models\VendorKyc;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendors = Vendor::paginate(15);
        return view('admin.vendors.index', compact('vendors','request'));
    }
    public function show($id)
    {
        $vendor = Vendor::find($id);
        if(!$vendor->kycDetails){
            VendorKyc::create([
                'vendor_id' => $vendor->id
                ]);
        }
        return view('admin.vendors.show', compact('vendor'));
    }
    public function pendingKyc()
    {
        $kycs = VendorKyc::where('status', 0)->paginate(15);
        return view('admin.vendors.pendingKyc', compact('kycs'));
    }
    public function kycDetails($id)
    {
        $kyc = VendorKyc::find($id);
        return view('admin.vendors.kycShow', compact('kyc'));
    }
    public function kycApproval(Request $request)
    {
        try {
            $kyc = VendorKyc::find($request->kyc_id);
            $kyc->status = $request->status;
            if ($request->reject_reason) {
                $kyc->reject_reason = $request->reject_reason;
            }
            $kyc->save();
            return back()->with('success', 'Kyc updated successfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function changeStatus($id)
    {
        $customer = Vendor::find($id);
        $customer->is_active = $customer->is_active == 1 ? 0 : 1;
        $customer->save();
        $message = 'Status update successfully';
        return response()->json($message);
    }
      public function importExcel(Request $request)
    {
        try {
               Excel::queueImport(new VendorImport, $request->file('excel_file'));
            return back()->with('success', 'Vendor Import successfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    
}
