<?php

namespace App\Http\Controllers\API;

use App\Helpers\StaticDataHelper;
use App\Models\JobBid;
use App\Models\JobPost;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiJobPostController extends BaseApiController
{
    public function saveItem(Request $request)
    {
        try {

            $request->validate([
                'jobPostId'       => ['nullable', 'string', 'exists:job_posts,id'],
                'userId'          => ['required', 'string', 'exists:users,id'],
                'category_id'     => ['nullable', 'string', 'exists:service_categories,id'],
                'city_id'         => ['required', 'string', 'exists:cities,id'],
                'title'           => ['required', 'string', 'max:255'],
                'image'           => ['nullable', 'string', 'max:255'],
                'budget'          => ['required', 'numeric', 'min:0'],
                'duration'        => ['required', 'string', 'max:100'],
                'description'     => ['nullable', 'string'],
                'address'         => ['required', 'string', 'max:255'],
            ]);

            $job = $request->filled('jobPostId')
                ? JobPost::findOrFail($request->jobPostId)
                : new JobPost();

            $job->user_id       = $request->userId;
            $job->title         = $request->title;
            if ($request->filled('image')) {
                $job->image = $request->image;
            }
            $job->city_id       = $request->city_id;
            $job->budget        = $request->budget;
            $job->service_category_id    = $request->category_id;
            $job->duration      = $request->duration;
            $job->address       = $request->address;
            $job->description   = $request->description ?? $job->description;
            $job->status        = $job->status ?? config('static_data.status.OPEN');
            $job->save();
            $job->image_url = $job->image ? asset($job->image) : null;


            return response()->json([
                'success' => true,
                'message' => $request->filled('jobPostId')
                    ? 'Job updated successfully'
                    : 'Job created successfully',
                'data' => $job,
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
    public function deleteItem(Request $request, $id = null)
    {
        try {
            $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
            ]);
            $jobId = $id ?? $request->jobPostId;

            $job = JobPost::where('id', $jobId)
                ->where('user_id', $request->user_id) // optional
                ->firstOrFail();

            $job->bids()->delete(); 

            $job->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job and related bids deleted successfully',
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found',
            ], 404);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
    public function getAllJobPost(Request $request){
        $request->validate([
            'cityId' => ['nullable', 'integer', 'exists:cities,id'],
            'userId' => ['nullable', 'integer', 'exists:users,id'],
        ]);
        $cityId = $request->cityId;
        $userId = $request->userId;

        $query = JobPost::query()->with(['user:id,name,email'])
        ->orderByDesc('created_at');

        if ($cityId) {
            $query->where('city_id', $cityId);
        }
        if ($userId) {
            $user = User::find($userId);

            if ($user && $user->type != config('static_data.accountType.customer')) {
                $query->where('status', config('static_data.status.OPEN'));
                $categoryIds = Service::where('sp_id', $userId)
                    ->pluck('service_category_id')
                    ->unique()
                    ->toArray();

                if (!empty($categoryIds)) {
                    $query->whereIn('service_category_id', $categoryIds);
                }
                else{
                    $query = JobPost::where('status', config('static_data.status.OPEN'))
                    ->orderByDesc('created_at');
                }
            }
        }

        $jobs = $query->get();

        $jobs->each(function ($job) {
            $job->image_url = $job->image ? asset($job->image) : null;
        });

        return response()->json([
            'success' => true,
            'data'    => $jobs,
        ]);
    }
    public function jobDetailsById(Request $request)
    {
        $request->validate([
            'job_post_id' => ['required', 'integer', 'exists:job_posts,id'],
        ]);

        $job = JobPost::with(['serviceCategory', 'city', 'user'])
            ->findOrFail($request->job_post_id);

        $job->image_url = $job->image ? asset($job->image) : null;

        return response()->json([
            'success' => true,
            'data'    => $job,
        ]);
    }
    public function placeBid(Request $request, JobPost $jobPost)
    {
        $request->validate([
            'sp_id'   => ['required', 'exists:users,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $sp = User::findOrFail($request->sp_id);

        // 1) SP type check
        if ($sp->type == config('static_data.accountType.customer')) {
            return response()->json([
                'success' => false,
                'message' => 'Only service providers can bid'
            ], 422);
        }

        // 2) Plan purchased + active check
        if (!$sp->current_plan_id || !$sp->current_plan_ends_at || $sp->current_plan_ends_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'No active plan. Please purchase a plan to bid.'
            ], 403);
        }

        // 3) Bids available check
        if (($sp->current_bids ?? 0) <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No bids available. Please purchase more bids.'
            ], 403);
        }

        // 4) Prevent duplicate bid on same job
        $existing = JobBid::where('job_post_id', $jobPost->id)
            ->where('sp_id', $sp->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already placed a bid on this job.'
            ], 409);
        }

        // 5) Transaction: create bid + decrease bids count
        DB::transaction(function () use ($jobPost, $sp, $request, &$bid) {
            $bid = JobBid::create([
                'job_post_id' => $jobPost->id,
                'sp_id'       => $sp->id,
                'message'     => $request->message,
                'status'      => config('static_data.status.PENDING'),
            ]);

            // Decrease available bids by 1
            $sp->decrement('current_bids', 1);
        });

        return response()->json([
            'success' => true,
            'message' => 'Bid placed successfully.',
            'data'    => $bid,
        ]);
    }
    public function getJobBids(Request $request, JobPost $jobPost)
    {
        try{
            $request->validate([
                'user_id'   => ['required', 'exists:users,id'],
            ]);
            // Optional: ensure requesting user is job owner
            if ($request->has('user_id')) {
                $user = User::find($request->user_id);
                if (!$user || $user->id !== $jobPost->user_id) {
                    return response()->json(['success' => false, 'message' => 'Not authorized'], 403);
                }
            }

            $bids = $jobPost->bids()
                ->with(['user:id,name,phone,email'])
                ->orderByDesc('created_at')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $bids,
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
    public function acceptBid(Request $request, JobBid $bid)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $job = $bid->jobPost;

        // Ensure this customer owns the job
        if ($job->user_id != $request->user_id) {
            return response()->json(['success' => false, 'message' => 'Not authorized'], 403);
        }

        // 1) Mark this bid as accepted
        $bid->update(['status' => config('static_data.status.ACCEPTED')]);

        // 2) Other bids as rejected
        JobBid::where('job_post_id', $job->id)
            ->where('id', '!=', $bid->id)
            ->update(['status' => config('static_data.status.REJECTED')]);

        // 3) Job post update: assigned
        $job->update([
            'status'         => config('static_data.status.ASSIGNED'),
            'assigned_sp_id' => $bid->sp_id,
            'accepted_bid_id'=> $bid->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bid accepted and job assigned',
            'data'    => [
                'job' => $job,
                'accepted_bid' => $bid,
            ],
        ]);
    }
    public function rejectBid(Request $request, JobBid $bid)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $job = $bid->jobPost;

        // Ensure this customer owns the job
        if ($job->user_id != $request->user_id) {
            return response()->json(['success' => false, 'message' => 'Not authorized'], 403);
        }

        // 1) Mark this bid as accepted
        $bid->update(['status' => config('static_data.status.REJECTED')]);

        return response()->json([
            'success' => true,
            'message' => 'Bid rejected',
            'data'    => [
                'job' => $job,
                'rejected_bid' => $bid,
            ],
        ]);
    }


}
