<?php

namespace App\Http\Controllers\admin;

use App\DefaultTrait;
use App\Http\Controllers\Controller;
use App\Models\PurchaseHistory;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as FacadesFile;

class SubscriptionController extends Controller
{
    use DefaultTrait;

    public function purchaseHistory(Request $request){
        $query = PurchaseHistory::with(['user', 'plan', 'paymentTransaction'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('paymentTransaction', function ($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        if ($request->filled('from_date')) {
            $from = $request->from_date;
            $query->whereDate('starts_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $to = $request->to_date;
            $query->whereDate('ends_at', '<=', $to);
        }

        $purchases = $query->paginate(20)->withQueryString();
        
        return view('admin.subscriptions.purchase-history', compact('purchases'));
    }
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('serial', 'asc')->get();

        return view('admin.subscriptions.index', compact('plans'));
    }


    public function create()
    {
        return view('admin.subscriptions.create');
    }


    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                        'plan_name' => 'required',
                        'plan_price' => 'required',
                        'expiration_date' => 'required',
                        'serial' => 'required',
                        'bids' => 'required',
                        'skills' => 'required',
                        'status' => 'required|in:0,1',
                    ]);

            $keys   = $request->input('features_keys', []);
            $values = $request->input('features_values', []);

            $features = [];

            foreach ($keys as $index => $key) {
                $key = trim($key);
                if ($key === '') {
                    continue;
                }

                $raw = $values[$index] ?? null;
                $value = trim((string) $raw);

                if (strtolower($value) === 'true') {
                    $value = true;
                } elseif (strtolower($value) === 'false') {
                    $value = false;
                } elseif (is_numeric($value)) {
                    $value = $value + 0;
                }

                $features[$key] = $value;
            }

            $data['features_json'] = $features;
            SubscriptionPlan::create($data);

            return redirect()
                ->route('admin.subscriptions.index')
                ->with('success', 'Plan created.');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show($id) {}

    public function edit($id)
    {

        $data = SubscriptionPlan::find($id);

        return view('admin.subscriptions.create', compact('data'));
    }

    public function update(Request $request, SubscriptionPlan $subscription)
    {
        try {
            $data = $request->validate([
                'plan_name'       => 'required',
                'plan_price'      => 'required',
                'expiration_date' => 'required',
                'serial'          => 'required',
                'bids'            => 'required',
                'skills'          => 'required',
                'status'          => 'required|in:0,1',
            ]);

            $features = $subscription->features_json ?? [];

            $keys   = $request->input('features_keys', []);
            $values = $request->input('features_values', []);

            foreach ($keys as $index => $key) {
                $key = trim($key);
                if ($key === '') {
                    continue;
                }

                $raw   = $values[$index] ?? null;
                $value = trim((string) $raw);

                if (strtolower($value) === 'true') {
                    $value = true;
                } elseif (strtolower($value) === 'false') {
                    $value = false;
                } elseif (is_numeric($value)) {
                    $value = $value + 0;
                }

                $features[$key] = $value;
            }

            $subscription->plan_name       = $data['plan_name'];
            $subscription->plan_price      = $data['plan_price'];
            $subscription->expiration_date = $data['expiration_date'];
            $subscription->serial          = $data['serial'];
            $subscription->bids            = $data['bids'];
            $subscription->skills          = $data['skills'];
            $subscription->status          = $data['status'];
            $subscription->features_json   = $features;
            $subscription->save();

            return redirect()
                ->route('admin.subscriptions.index')
                ->with('success', 'Plan updated successfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $plan = SubscriptionPlan::find($id);
        $plan->delete();
        return redirect()->route('admin.subscriptions.index')->with('success','Deleted successfully');
    }
    public function purchase(Request $request){
        dd('purchase history page');
    }
}
