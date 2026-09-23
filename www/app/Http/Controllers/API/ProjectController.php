<?php

namespace App\Http\Controllers\API;

use App\DefaultTrait;
use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProjectController extends BaseApiController
{
    use DefaultTrait;

    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $datas = Project::with('service', 'bids')->where('user_id',$user->id)->get();
            return $this->successResponse($datas, 'Data fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error', $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {

            $rules = [
                'service_id' => 'required|exists:services,id',
                'project_name' => 'required|string',
                'customer_name' => 'required|string',
                'customer_mobile' => 'required|digits:10',
                'customer_email' => 'required|email',
                'budget' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
                'start_date' => 'required|date|after_or_equal:today',
                'gender' => 'required|in:Male,Female,Other',
                'house_no' => 'required|integer',
                'area' => 'required',
                'pincode' => 'required|digits:6',
                'city' => 'required|string',
                'state' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:800',

            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', $validator->errors());
            }

            $user = $request->user();
            $data = $request->all();
            $data['image'] = $this->verifyAndUpload($request, 'image', 'uploads/projectsImg/')??null;
            $data['user_id'] = $user->id;
            $data['name'] = $request->project_name;
            $project = Project::create($data);
            return $this->successResponse($project, 'Data store successfully!');
        } catch (\Exception $e) {
            return $this->errorResponse('Backend Error', $e->getMessage());
        }
    }

    public function allbids(Request $request) {
        try {
            $user = $request->user();
            $allbids = Bid::with('project')->where('project_id',$request->project_id)->get();
            return $this->successResponse($allbids, 'Data fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error', $th->getMessage());
        }

    }

    public function ApproveBid(Request $request) {
        try {
            $bid = Bid::find($request->bid_id);
            $bid->is_approve = 'Approved';
            $bid->save();
            Bid::whereNotin('id',[$bid->id])->where('project_id',$bid->project_id)->update(['status' => 'Closed','is_approve' => 'Disapproved']);
            $project = Project::find($bid->project_id);
            $project->bid_id = $bid->id;
            $project->status = 1;
            $project->save();
            return $this->successResponse($bid, 'Approved successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error', $th->getMessage());
        }

    }
}
