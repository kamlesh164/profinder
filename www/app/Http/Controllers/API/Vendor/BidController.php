<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Project;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function allprojects()
    {
        try {
            $projects = Project::get();
            return response()->json([
                'success' => true,
                'message' => 'Project data fetched successfully!',
                'data' => $projects
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function placeBid(Request $request)
    {
        try {
            $user = $request->user();
            $data = Bid::create([
                'user_id' => $user->id,
                'project_id' => $request->project_id,
                'amount' => $request->amount,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!',
                'data' => $data
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }


    public function allbids(Request $request)
    {
        try {
            $user = $request->user();
            $bids = Bid::with('project')->where('user_id', $user->id)->get();
            return response()->json([
                'success' => true,
                'message' => 'Bids data fetched successfully!',
                'data' => $bids
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
