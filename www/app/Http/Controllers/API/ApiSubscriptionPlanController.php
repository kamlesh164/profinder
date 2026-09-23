<?php

namespace App\Http\Controllers\API;

use App\Helpers\StaticDataHelper;
use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\PurchaseHistory;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class ApiSubscriptionPlanController extends BaseApiController
{
    public function getAllPlans(Request $request){
        $plans = SubscriptionPlan::orderBy('serial')->get();

        return response()->json([
            'success' => true,
            'data' => $plans,
        ]);
    }
    public function purchasePlan(Request $request)
    {
        $request->validate([
            'planId' => 'required|exists:subscription_plans,id',
            'userId' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->userId);
        $plan = SubscriptionPlan::findOrFail($request->planId);

        try {

            if ($user->type == config('static_data.accountType.customer')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only service providers can purchase plan'
                ], 422);
            }

            $orderId = 'PLAN_' . $plan->id . '_' . time();
            $razorResponse  = StaticDataHelper::createRazorpayOrder(
                amount: $plan->plan_price,
                receipt: $orderId
            );
            if (!$razorResponse['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create payment order',
                    'razorpay' => $razorResponse['error'] ?? null,
                ], 500);
            }
            $rzpOrderId = $razorResponse['order']['id'];
            PaymentTransaction::create([
                'user_id'        => $user->id,
                'reference_type' => 'subscription_plan',
                'reference_id'   => $plan->id,
                'amount'         => $plan->plan_price,
                'order_id'       => $rzpOrderId,
                'payment_method' => 'Razorpay',
                'status'         => config('static_data.status.PENDING')
            ]);

            PurchaseHistory::create([
                'user_id'    => $user->id,
                'plan_id'    => $plan->id,
                'amount'     => $plan->plan_price,
                'bids'       => $plan->bids,
                'order_id'   => $rzpOrderId,
                'status'     => config('static_data.status.PENDING'),
                'payment_gateway' => 'Razorpay',
            ]);

            $config = config('app.razorpay');
            $key_id = $config['app_id'];

            return response()->json([
                'success'        => true,
                'razorpay_key'   => $key_id,
                'order_id'       => $rzpOrderId,
                'amount_paise'   => $razorResponse['order']['amount'],
                'currency'       => $razorResponse['order']['currency'],
                'receipt'        => $razorResponse['order']['receipt'],
                'plan'           => $plan,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Plan purchase error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Payment initiation failed'
            ], 500);
        }
    }


}
