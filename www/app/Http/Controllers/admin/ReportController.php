<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $vendors = Vendor::where('is_active', 1)->get();
        return view('admin.report.vendor', compact('vendors'));
    }

    public function getReport(Request $request)
    {
        $vendors = Vendor::where('is_active', 1)->get();
        $startdate = $request->start_date;
        $enddate = $request->end_date;
        if ($request->report_type == 'service') {
            if ($request->vendor_id == 'all') {
                $datas = Booking::with('service')->whereBetween('created_at', [$startdate, $enddate])->get();
            } else {
                $datas = Booking::with('service')->where('vendor_id', $request->vendor_id)->whereBetween('created_at', [$startdate, $enddate])->get();
            }
        }
        return view('admin.report.vendor',compact('datas','vendors'));
    }
}
