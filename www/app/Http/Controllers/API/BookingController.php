<?php

namespace App\Http\Controllers\API;

use App\Helpers\StaticDataHelper;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\PaymentTransaction;
use App\Models\Service;
use App\Models\ServiceProfessionalRatings;
use App\Models\ServiceRating;
use App\Models\User;
use App\Notifications\BookingCompletedNotification;
use App\Notifications\NewBookingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookingController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $datas = Booking::where('user_id', $user->id)->get();
            return $this->successResponse($datas, 'Data fetched successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error', $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_id'     => 'required|numeric|exists:vendors,id',
                'service_id'    => 'required|numeric|exists:services,id',
                'property_type' => ['required', Rule::in(['apartment', 'villa'])],
                'slot_date'     => 'required|date',
                'slot_time'     => 'required|date_format:H:i',
                'address'       => 'required|string',
                'city'          => 'required|string',
                'state'         => 'required|string',
                'total_amount'  => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation Error', $validator->errors());
            }

            $user = $request->user();
            $service = Service::find($request->service_id);
            $commissionAmount = $request->total_amount * $service->serviceCategory->commission / 100;
            $chkbooking = Booking::where(['user_id' => $user->id,'slot_date' => $request->slot_date, 'slot_time' => $request->slot_time])->exists();
            if($chkbooking == true){
                return $this->errorResponse('Error', 'Already booked on this slot');
            }
            $data = Booking::create([
                'user_id' => $user->id,
                'vendor_id' => $request->vendor_id,
                'service_id' => $request->service_id,
                'property_type' => $request->property_type,
                'slot_date' => $request->slot_date,
                'slot_time' => $request->slot_time,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'total_amount' => $request->total_amount,
                'admin_commission_percent' => $service->serviceCategory->commission,
                'admin_commission' => $commissionAmount,
            ]);
            return $this->successResponse($data, 'Data store successfully!');
        } catch (\Exception $th) {
            return $this->errorResponse('Backend Error', $th->getMessage());
        }
    }
    public function booking(Request $request){
        try {
            $validated = $request->validate([
                'userId' => 'required|exists:users,id',
                'serviceId' => 'required|exists:services,id',
                'scheduleDate' => 'required|date|after:today',
                'scheduleTime' => 'required|date_format:H:i:s',
                'property_type' => 'nullable|in:apartment,villa',
                'propertySize' => 'nullable',
                'basePrice' => 'required',
                'totalAmount' => 'required',
                'qty' => 'nullable',
                'notes' => 'nullable|string|max:500',
                'userAddress' => 'required|string|max:500',
            ]);

            $service = Service::findOrFail($validated['serviceId']);

            /* $service_amount = $service->base_price;
            if ($service->is_property_based) {
                if (empty($validated['basePrice'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'basePrice is required for this service',
                    ], 422);
                }
                $service_amount = (float) $validated['basePrice'];
            }
            else {
                $service_amount = (float) $validated['totalAmount'];
            } */
            $service_amount = (float) $validated['totalAmount'];

            $tax = 0;
            $total_amount = $service_amount + $tax;

            $booking = Booking::create([
                'user_id' => $validated['userId'],
                'sp_id' => $service->sp_id,
                'service_id' => $validated['serviceId'],
                'slot_date' => $validated['scheduleDate'],
                'slot_time' => $validated['scheduleTime'],
                'property_type' => $validated['property_type'] ?? null,
                'property_size' => $validated['propertySize'] ?? null,
                'qty' => $validated['qty'] ?? null,
                'base_price' => $validated['basePrice'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'address' => $validated['userAddress'],
                'tax' => $tax,
                'total_amount' => $total_amount,
                'status' => config('static_data.status.PENDING'),
                'payment_status' => config('static_data.status.PENDING'),
            ]);

            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'old_status' => null,
                'new_status' => config('static_data.status.PENDING'),
                'changed_by_user_id' => $validated['userId'],
                'change_reason' => 'Booking created',
            ]);

            $contractor = User::findOrFail($service->sp_id);
            $contractor->notify(new NewBookingNotification($booking));

            return response()->json([
                'success' => true,
                'booking' => $booking->load('service', 'user'),
                'message' => 'Booking created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function show($bookingId)
    {
        $booking = Booking::with(['service', 'contractor', 'statusLogs', 'paymentTransactions'])
            ->findOrFail($bookingId);

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }
    public function getBookingSummary($bookingId)
    {
        $booking = Booking::with(['service', 'contractor'])
            ->findOrFail($bookingId);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $booking->id,
                'service' => $booking->service->only(['id', 'name', 'description']),
                'contractor' => $booking->contractor->only(['id', 'name']),
                'date' => $booking->slot_date,
                'time' => $booking->slot_time,
                'address' => $booking->address,
                'service_amount' => $booking->service_amount,
                'total_amount' => $booking->total_amount,
                'status' => $booking->status,
            ]
        ]);
    }
    public function getCustomerBookings(Request $request)
    {   
        $request->validate([
            'userId'=> 'required|exists:users,id',
        ]);
        $query = Booking::where('user_id', $request->userId);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->with(['service', 'contractor'])
            ->orderBy('created_at', 'desc')
            ->get()->groupBy('status');

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'bookingId'=> 'required|exists:bookings,id',
            'userId'   => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->userId);
        $booking = Booking::findOrFail($request->bookingId);

        if ($booking->user_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Booking does not belong to this user'
            ], 403);
        }
        // Check booking status
        if (!$booking->canPayment()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking is not in payment_pending status'
            ], 422);
        }

        try {
            $orderId = 'ORDER_' . $booking->id . '_' . time();
            
            $razorResponse = StaticDataHelper::createRazorpayOrder(
                amount: $booking->total_amount,
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
                'booking_id' => $booking->id,
                'user_id'    => $user->id,
                'amount'     => $booking->total_amount,
                'order_id'   => $rzpOrderId, 
                'payment_method'   => 'Razorpay', 
                'status'     => config('static_data.status.PENDING')
            ]);

            $booking->update(['payment_id' => $rzpOrderId]);

            $config = config('app.razorpay');
            $key_id = $config['app_id'];

            return response()->json([
                'success'        => true,
                'razorpay_key'   => $key_id,
                'order_id'       => $rzpOrderId,
                'amount_paise'   => $razorResponse['order']['amount'],
                'currency'       => $razorResponse['order']['currency'],
                'receipt'        => $razorResponse['order']['receipt'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function completeService(Request $request,$bookingId){

        try {
            $validate = $request->validate([
                'userId'        => 'required|exists:users,id',
                'star'          => 'nullable|integer|max:5',
                'review'        => 'nullable|string|max:1000',
            ]);

            $booking = Booking::findOrFail($bookingId);

            if ($booking->user_id !== $validate['userId']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - This booking does not belong to you'
                ], 403);
            }
            
            if ($booking->payment_status != config('static_data.status.COMPLETED')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment is not completed yet',
                ], 400);
            }
            
            if ($booking->status == config('static_data.status.COMPLETED')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking already completed'
                ], 400);
            }

            DB::transaction(function () use ($booking,$validate) {

                $oldStatus = $booking->getOriginal('status');
                $booking->update([
                    'status'        => config('static_data.status.COMPLETED'),
                ]);

                BookingStatusLog::create([
                    'booking_id' => $booking->id,
                    'old_status' => $oldStatus,
                    'new_status' => config('static_data.status.COMPLETED'),
                    'changed_by_user_id' => $booking->user_id,
                    'change_reason' => 'Booking completed',
                ]);
                
                $hasRatingData = array_key_exists('star', $validate) || array_key_exists('review', $validate);

                if ($hasRatingData) {
                    // Service rating
                    ServiceRating::create([
                        'booking_id' => $booking->id,
                        'service_id' => $booking->service_id,
                        'user_id'    => $validate['userId'],
                        'rating'     => $validate['star']   ?? null,
                        'review'     => $validate['review'] ?? null,
                    ]);
                
                    // Professional rating (yahan sirf star store kar rahe ho)
                    ServiceProfessionalRatings::create([
                        'booking_id' => $booking->id,
                        'sp_id'      => $booking->sp_id,
                        'user_id'    => $validate['userId'],
                        'rating'     => $validate['star'] ?? null,
                    ]);
                }

                // Notifications etc.
                $booking->contractor->notify(new BookingCompletedNotification($booking));
            });

            return response()->json([
                'success' => true,
                'message' => 'Service completed',
                'booking' => $booking->load('service', 'contractor'),
                'star' => $validate['star'] ?? null,
                'review' => $validate['review'] ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
