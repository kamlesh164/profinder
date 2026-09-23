<?php

namespace App\Http\Controllers\API\Vendor;

use App\DefaultTrait;
use App\Http\Controllers\API\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorKyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KYCController extends BaseApiController
{
    use DefaultTrait;
    public function storeKyc(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'adhar_front'  => 'required|mimes:jpeg,jpg,png,webp,pdf|max:1024',
                'adhar_back'   => 'required|mimes:jpeg,jpg,png,webp,pdf|max:1024',
                'pancard'      => 'required|mimes:jpeg,jpg,png,webp,pdf|max:1024',
                'gst_doc'      => 'required|mimes:jpeg,jpg,png,webp,pdf|max:1024',
                'gst_number'   => 'required',
                'pancard_no'   => 'required',
                'adhar_no'     => 'required',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation Error',$validator->errors());
            }
            $vendor = $request->user();
            if(VendorKyc::where('vendor_id',$vendor->id)->exists()){
                return $this->errorResponse('Error',"You can't resubmit kyc details!");
            }
            $data = $request->all();
            $data['vendor_id'] = $vendor->id;
            $data['adhar_front'] = $this->verifyAndUpload($request, 'adhar_front', 'uploads/vendor/kyc/')??null;
            $data['adhar_back'] = $this->verifyAndUpload($request, 'adhar_back', 'uploads/vendor/kyc/')??null;
            $data['gst_doc'] = $this->verifyAndUpload($request, 'gst_doc', 'uploads/vendor/kyc/')??null;
            $data['pancard'] = $this->verifyAndUpload($request, 'pancard', 'uploads/vendor/kyc/')??null;
            $alldata = VendorKyc::create($data);
            return $this->successResponse($alldata,'Kyc data store successfully! Wait for approval');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error',$th->getMessage());
        }

    }

    public function kycDetails(Request $request)
    {
        try {
            $vendor = $request->user();
            $data = VendorKyc::where('vendor_id',$vendor->id)->first();
            return $this->successResponse($data,'Kyc data fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage());
        }

    }
}
