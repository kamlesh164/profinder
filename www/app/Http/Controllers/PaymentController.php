<?php

namespace App\Http\Controllers;

use App\Models\BookingStatusLog;
use App\Models\PaymentTransaction;
use App\Models\PurchaseHistory;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\PaymentConfirmedNotification;
use App\Notifications\PaymentFailedNotification;
use App\Notifications\PlanPurchasedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    public function verifyRazorpayPayment(Request $request){

        $request->validate([
            'razorpay_payment_id'  => 'required|string',
            'razorpay_order_id'    => 'required|string',
            'razorpay_signature'   => 'required|string',
            'booking_id'           => 'required|exists:bookings,id',
        ]);

        $transaction = PaymentTransaction::where('order_id', $request->razorpay_order_id)
            ->where('status', config('static_data.status.PENDING'))
            ->whereNotNull('booking_id')
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        try {
            
            $config = config('app.razorpay');
            $key_id = $config['app_id'];
            $key_secret = $config['secret_key'];

            // 1) Razorpay signature verify
            $api = new Api($key_id, $key_secret);
            
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ];

            $api->utility->verifyPaymentSignature($attributes);

            $booking = $transaction->booking;

            // 3) Update status
            DB::transaction(function () use ($transaction, $booking, $request) {
                $oldStatus = $booking->getOriginal('status');

                $transaction->update([
                    'status'     => config('static_data.status.COMPLETED'),
                    'payment_id' => $request->razorpay_payment_id,
                    'verified_at'=> now(),
                ]);

                $booking->update([
                    'status'        => config('static_data.status.ONGOING'),
                    'payment_status'=> config('static_data.status.COMPLETED'),
                ]);

                BookingStatusLog::create([
                    'booking_id' => $booking->id,
                    'old_status' => $oldStatus,
                    'new_status' => config('static_data.status.ONGOING'),
                    'changed_by_user_id' => $booking->user_id,
                    'change_reason' => 'Payment completed',
                ]);

                // Notifications etc.
                $booking->user->notify(new PaymentConfirmedNotification($booking));
                $booking->contractor->notify(new BookingConfirmedNotification($booking));
            });

            return response()->json([
                'success' => true,
                'message' => 'Payment verified and booking confirmed',
                'booking' => $booking->load('service', 'contractor'),
            ]);

        } catch (SignatureVerificationError $e) {
            $transaction->update([
                'status'        => config('static_data.status.FAILED'),
                'error_message' => 'Signature verification failed',
            ]);
            $transaction->booking->update(['payment_status' => config('static_data.status.FAILED')]);
            $transaction->booking->user->notify(new PaymentFailedNotification($transaction->booking));
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 400);

        } catch (\Exception $e) {
            Log::error('Razorpay verify error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ], 500);
        }
    }
    public function verifyRazorpayPaymentForPlan(Request $request){

        $request->validate([
            'razorpay_payment_id'  => 'required|string',
            'razorpay_order_id'    => 'required|string',
            'razorpay_signature'   => 'required|string',
        ]);

        $transaction = PaymentTransaction::where('order_id', $request->razorpay_order_id)
            ->where('status', config('static_data.status.PENDING'))
            ->where('reference_type', 'subscription_plan')
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        try {
            
            $config = config('app.razorpay');
            $api = new Api($config['app_id'], $config['secret_key']);
            
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            $purchase = PurchaseHistory::where('order_id', $request->razorpay_order_id)->firstOrFail();
            // 3) Update status
            DB::transaction(function () use ($transaction, $purchase, $request) {
                $transaction->update([
                    'status'     => config('static_data.status.COMPLETED'),
                    'payment_id' => $request->razorpay_payment_id,
                    'verified_at'=> now(),
                ]);

                $endsAt = match($purchase->plan->expiration_date) { 
                    'monthly'   => now()->addMonth(),
                    'yearly'    => now()->addYear(),
                    default     => now()->addMonth(),
                };

                $purchase->update([
                    'status'            => config('static_data.status.ACTIVE'),
                    'starts_at'         => now(),
                    'ends_at'           => $endsAt ,
                    'payment_id'        => $request->razorpay_payment_id,
                ]);

                $user = $purchase->user; 

                $user->update([
                    'current_plan_id'      => $purchase->plan_id,
                    'current_plan_ends_at' => $endsAt,
                    'current_bids'         => $user->current_bids + $purchase->plan->bids
                ]);
                
                // Notifications etc.
                $transaction->user->notify(new PlanPurchasedNotification($purchase));
            });

            return response()->json([
                'success' => true,
                'message' => 'Plan purchased successfully',
                'purchase' => $purchase->load('plan'),
            ]);

        } catch (SignatureVerificationError $e) {
            $transaction->update([
                'status'        => config('static_data.status.FAILED'),
                'error_message' => 'Signature verification failed',
            ]);
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Plan verify error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Verification failed'], 500);
        }
    }
    public function cancelPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'booking_id'        => 'required|exists:bookings,id',
            'error_message'     => 'nullable|string|max:500',
        ]);

        $transaction = PaymentTransaction::where('order_id', $request->razorpay_order_id)
            ->where('status', config('static_data.status.PENDING'))
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $transaction->update([
            'status'        => config('static_data.status.CANCELLED'),
            'error_message' => $request->error_message ?? 'Payment cancelled',
        ]);

        $transaction->booking->update([
            'payment_status' => config('static_data.status.CANCELLED'),
        ]);

        return response()->json(['success' => true, 'message' => 'Payment cancelled']);
    }

}
