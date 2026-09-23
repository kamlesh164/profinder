<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Models\InfoCategory;


class AdminServiceProfessionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sp = User::where('source','web')->paginate(15);
        $categories = InfoCategory::orderBy('created_at', 'desc')->get();
        return view('admin.service-professional.index', compact('sp','categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $vendor = ServiceProfessional::with(['cityRelation','stateRelation'])->find($id);
        $vendor = User::with(['address.cityRelation', 'address.stateRelation'])->find($id);
        $address = $vendor->address->where('is_default', true)->first() ?? $vendor->address->first();
        return view('admin.service-professional.show', compact('vendor','address'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'gender' => 'required|in:Male,Female,Other',
            'type' => 'required',
            'address' => 'required|string'
            // add more rules as needed
        ]);

        $user = User::findOrFail($id);

        $user->update($request->only([
            'name', 'email', 'gender', 'type'
        ]));

        UserAddress::updateOrCreate(
            ['user_id' => $id, 'is_default' => true],
            [
                'address' => $request->address,
                'pincode' => $request->pincode,
                'city' => $request->city,
                'state' => $request->state,
                'address_type' => 'Home'
            ]
        );
        
        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vendor = User::findOrFail($id);

        $vendor->delete();

        return back()->with('success', 'Profile deleted successfully!');
    }
    public function changeStatus($id)
    {
        $customer = User::find($id);
        $customer->is_active = $customer->is_active == 1 ? 0 : 1;
        $customer->save();
        $message = 'Status update successfully';
        return response()->json($message);
    }
}
