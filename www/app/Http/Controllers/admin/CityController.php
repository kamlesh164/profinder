<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    public function index()
    {
        try {
            $cities = City::with(['user','services'])->orderBy('name', 'asc')->get();
            return view('admin.cities.index', compact('cities'));
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
            $states = State::get();
            return view('admin.cities.create', compact('states'));
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
                'state_id' => 'required',
                'name' => 'required|unique:cities,name',
                'pincode' => 'required|unique:cities,code',
            ];
            $this->validate($request, $rules);
            $data = $request->all();
            $data['code'] = $request->pincode;
            City::create($data);
            return redirect()->route('admin.cities.index')->with('success', 'Data store successfully!')->withInput();;
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
            $states = State::get();
            $data = City::find($id);
            return view('admin.cities.create', compact('data', 'states'));
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
                'state_id' => 'required',
                'name'   => [
                    'required',
                    Rule::unique('cities', 'name')->ignore($id),
                ],
                'pincode'   => [
                    'required',
                    Rule::unique('cities', 'code')->ignore($id),
                ],
            ]);
            $update = City::find($id);
            $update->state_id = $request->state_id;
            $update->name = $request->name;
            $update->code = $request->pincode;
            $update->save();

            return redirect()
                ->route('admin.cities.index')
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
        $city = City::findOrFail($id);
        $city->delete();
        return back()->with('success', 'Deleted successfully!');
    }
    public function getCities(Request $request){
        $cities = City::where('state_id', $request->state_id)->orderBy('name','asc')->get(['id', 'name']);
        return response()->json(['cities' => $cities]);
    }
}
