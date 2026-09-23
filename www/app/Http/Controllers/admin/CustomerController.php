<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Str;

class CustomerController extends Controller
{

    public function index()
    {
        $title = 'User List';
        $users = User::paginate(10);
        return view('admin.users.index', compact('title','users'));
    }

    public function show($id)
    {
        $customer = User::find($id);
        $addresses = UserAddress::where('user_id',$customer->id)->get();
        return view('admin.users.show', compact('customer','addresses'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'max:55', 'string'],
            'gender' => ['required'],
        ]);
        $data = $request->only('name', 'gender');
        
        try {
            $user->update($data);
            return redirect('admin.customers.index')->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('customers.index')->with('success', 'User deleted successfully.');
    }
    public function changeStatus($id)
    {
        $customer = User::find($id);
        $customer->status = $customer->status == 1 ? 0 : 1;
        $customer->save();
        $message = 'Status update successfully';
        return response()->json($message);
    }
}
