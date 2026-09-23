<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StateController extends Controller
{
 
    public function index()
    {
        try {
            $states = State::with(['cities'])->orderBy('name', 'asc')->get();
            return view('admin.states.index', compact('states'));
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
            return view('admin.states.create');
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
                'name' => 'required|unique:states,name',
            ];
            $this->validate($request, $rules);
            $data = $request->all();
            State::create($data);
            return redirect()->route('admin.states.index')->with('success', 'Data store successfully!')->withInput();;
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
            $data = State::find($id);
            return view('admin.states.create', compact('data'));
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
                    Rule::unique('states', 'name')->ignore($id),
                ],
            ]);
            State::whereid($id)->update($validated);

            return redirect()
                ->route('admin.states.index')
                ->with('success', 'Data updated successfully!')
                ->withInput();
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $state = State::findOrFail($id);
        $state->delete();
        return back()->with('success', 'Deleted successfully!');
    }
}
