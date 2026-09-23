<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Designation;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $designations = Designation::orderBy('id', 'desc')->get();
            return view('admin.designations.index', compact('designations'));
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.designations.create');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|unique:designations,name',
                'status' => 'required|in:1,0'
            ];
            $this->validate($request, $rules);
            $data = $request->all();
            Designation::create($data);
            return redirect()->route('admin.designations.index')->with('success', 'Data store successfully!')->withInput();
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data = Designation::find($id);
            return view('admin.designations.create', compact('data'));
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name'   => [
                    'required',
                    Rule::unique('designations', 'name')->ignore($id),
                ],
                'status' => ['required', Rule::in([1, 0])],
            ]);
            Designation::whereid($id)->update($validated);

            return redirect()
                ->route('admin.designations.index')
                ->with('success', 'Designation updated successfully!')
                ->withInput();
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function UpdateStatus($id)
    {
        $update = Designation::find($id);
        if ($update->status == 1) {
            $update->status = 0;
            $update->save();
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $update->status = 1;
            $update->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
