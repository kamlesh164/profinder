<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PurchaseHistory;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function buySubscription(Request $request)
    {
        try {
            $user = $request->user();
            $subscription_plan = SubscriptionPlan::find($request->plan_id);
            $payment_gateway = 'Cashfree';
            $transaction = 'fhreffhbbrfjvrbhr';
            $payment_status = 'success';
            $data = $this->store_subscription($user, $subscription_plan, $payment_gateway, $transaction, $payment_status);
            return response()->json([
                'success' => true,
                'message' => 'Subcription purchased successfully!',
                'data' => $data
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
    private function store_subscription($user, $subscription_plan, $payment_gateway, $transaction, $payment_status)
    {
        $purchase = new PurchaseHistory();

        if ($subscription_plan->expiration_date == 'monthly') {
            $expiration_date = date('Y-m-d', strtotime('30 days'));
        } elseif ($subscription_plan->expiration_date == 'yearly') {
            $expiration_date = date('Y-m-d', strtotime('365 days'));
        } elseif ($subscription_plan->expiration_date == 'lifetime') {
            $expiration_date = 'lifetime';
        }

        if ($payment_status == 'success') {
            PurchaseHistory::where('vendor_id', $user->id)->update(['status' => 'expired']);
        }


        $purchase->vendor_id = $user->id;
        $purchase->plan_id = $subscription_plan->id;
        $purchase->plan_name = $subscription_plan->plan_name;
        $purchase->plan_price = $subscription_plan->plan_price;
        $purchase->expiration = $subscription_plan->expiration_date;
        $purchase->expiration_date = $expiration_date;
        $purchase->maximum_service = $subscription_plan->maximum_service;
        if ($payment_status == 'success') {
            $purchase->status = 'active';
        } else {
            $purchase->status = 'pending';
        }

        $purchase->payment_method = $payment_gateway;
        $purchase->payment_status = $payment_status;
        $purchase->transaction = $transaction;
        $purchase->save();
        $user->plan_id = $subscription_plan->id;
        $user->save();
        return $purchase;
    }

    public function subscriptionDetail(Request $request)
    {
        try {
            $user = $request->user();
            $subscription_plan = SubscriptionPlan::find($user->plan_id);
            return response()->json([
                'success' => true,
                'message' => 'Subcription data fetched successfully!',
                'data' => $subscription_plan
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function SubscriptionPurchaseHistory(Request $request)
    {
        try {
            $user = $request->user();
            $purchaseHistory = PurchaseHistory::with('plan')->where('vendor_id',$user->id)->get();
            return response()->json([
                'success' => true,
                'message' => 'Subcription purchase history fetched successfully!',
                'data' => $purchaseHistory
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
