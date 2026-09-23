<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Coupan;
use Illuminate\Http\Request;

class CoupanController extends Controller
{
    public function Index()
    {
        $coupans = Coupan::orderBy('id', 'desc')->get();
        return view('admin.coupans.index', compact('coupans'));
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'coupan_code' => 'required|unique:coupans,coupan_code',
                'value' => 'required',
                'type' => 'required|in:1,2',
                'coupan_for' => 'required|in:1,2',
                'is_active' => 'required|in:0,1',
            ];
            $this->validate($request, $rules);
            Coupan::create([
                'coupan_code' => $request->coupan_code,
                'value' => $request->value,
                'type' => $request->type,
                'coupan_for' => $request->coupan_for,
                'is_active' => $request->is_active,
                'expires_at' => $request->expires_at
            ]);
            return back()->with('success', 'Date store successfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'coupan_code' => 'required|exists:coupans,coupan_code',
                'value' => 'required',
                'type' => 'required|in:1,2',
                'coupan_for' => 'required|in:1,2',
                'is_active' => 'required|in:0,1',
            ];
            $this->validate($request, $rules);
            Coupan::whereid($id)->update([
                'coupan_code' => $request->coupan_code,
                'value' => $request->value,
                'type' => $request->type,
                'coupan_for' => $request->coupan_for,
                'is_active' => $request->is_active
            ]);
            return back()->with('success', 'Date updated successfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Coupan::find($id)->delete();
            return back()->with('success', 'Data deleted successfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function changeStatus($id)
    {
        $update = Coupan::find($id);
        if ($update->is_active == 1) {
            $update->is_active = 0;
            $update->save();
            $message = trans('Inactive Successfully');
        } else {
            $update->is_active = 1;
            $update->save();
            $message = trans('Active Successfully');
        }
        return response()->json($message);
    }
}
