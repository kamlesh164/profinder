<?php

namespace App\Http\Controllers\API;

use App\Helpers\StaticDataHelper;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\User;
use App\Notifications\BookingApprovedNotification;
use App\Notifications\BookingRejectedByCustomerNotification;
use App\Notifications\BookingRejectedNotification;
use Illuminate\Http\Request;

class ApiServiceProfessionalBookingController extends Controller
{
    public function getSpBookings(Request $request)
    {   
        $request->validate([
            'userId'=> 'required|exists:users,id',
        ]);
        $query = Booking::where('sp_id', $request->userId);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->with(['service', 'user'])
            ->orderBy('created_at', 'desc')
            ->get()->groupBy('status');

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }
    public function approveBooking(Request $request, $bookingId)
    {
        $request->validate([
            'userId'   => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->userId);
        $booking = Booking::findOrFail($bookingId);

        if ($booking->sp_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to approve this booking'
            ], 403);
        }
        // Check if can be approved
        if (!$booking->canBeRejected()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking is not in pending status'
            ], 422);
        }

        try {
            $oldStatus = $booking->getOriginal('status');

            $booking->update([
                'status' => config('static_data.status.SP_APPROVED') /* 'contractor_approved' */,
                'contractor_response_status' => 'approved',
            ]);

            // Log status change
            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => config('static_data.status.SP_APPROVED'),
                'changed_by_user_id' => $user->id,
                'change_reason' => 'Booking approved by contractor',
            ]);

            // Update status to payment_pending
            $booking->update(['status' => config('static_data.status.PMT_PENDING')]);

            // Send notification to customer
            $booking->user->notify(new BookingApprovedNotification($booking));

            return response()->json([
                'success' => true,
                'message' => 'Booking approved. Customer can now proceed with payment.',
                'booking' => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function rejectBooking($bookingId, Request $request)
    {
        $validated = $request->validate([
            'reason'  => 'required|string|max:500',
            'userId' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['userId']);
        $booking = Booking::findOrFail($bookingId);

        if (config('static_data.accountType.customer') == $user->type) {
            $bookingStatus = config('static_data.status.CM_REJECTED');
            $contractorStatus = null;
            if ($booking->user_id != $validated['userId']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to reject this booking'
                ], 403);
            }
        } else {
            $bookingStatus = config('static_data.status.SP_REJECTED');
            $contractorStatus = config('static_data.status.REJECTED');
            if ($booking->sp_id != $validated['userId']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to reject this booking'
                ], 403);
            }
        }
        // Check if can be rejected
        if (!$booking->canBeRejectedByContractor()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking cannot be rejected in current status'
            ], 422);
        }

        try {
            $old_status = $booking->getOriginal('status');
            $booking->update([
                'status' => $bookingStatus,
                'contractor_response_status' => $contractorStatus,
                'reject_reason' => $validated['reason'],
                'payment_status' => 'cancelled',
            ]);

            // Log status change
            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'old_status' => $old_status,
                'new_status' => $bookingStatus,
                'changed_by_user_id' => $validated['userId'],
                'change_reason' => 'Booking rejected by '.StaticDataHelper::getAccountTypeLabel($user->type).'. Reason: ' . $validated['reason'],
            ]);

            // Send notification to customer
            if (config('static_data.accountType.customer') == $user->type) {
                $booking->user->notify(new BookingRejectedByCustomerNotification($booking));
            }
            else{
                $booking->contractor->notify(new BookingRejectedNotification($booking));
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking rejected',
                'booking' => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
