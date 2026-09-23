<?php

namespace App\Http\Controllers\admin;

use App\DefaultTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Slider;

class SliderController extends Controller
{
    use DefaultTrait;

    public function index()
    {
        try {
            $sliders = Slider::orderBy('id', 'desc')->get();
            return view('admin.sliders.index', compact('sliders'));
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
            return view('admin.sliders.create');
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
            $request->validate([
                'name' => 'required|unique:designations,name',
                'image' => 'required',
                'is_active' => 'required|in:1,0',
                'type' => 'required|in:1,2'
            ]);
            $data = $request->all();
            $data['image'] = $this->ImageResizer($request, 'image', 'uploads/sliders/')??null;
            Slider::create($data);
            return redirect()->route('admin.sliders.index')->with('success', 'Data store successfully!')->withInput();
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
            $data = Slider::find($id);
            return view('admin.sliders.create', compact('data'));
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
                    Rule::unique('sliders', 'name')->ignore($id),
                ],
                'is_active' => ['required', Rule::in([1, 0])],
            ]);
            $slider = Slider::find($id);
            $slider->name = $request->name;
            if ($request->hasFile('image')) {
                if ($slider->image && file_exists(public_path($slider->image))) {
                    unlink(public_path($slider->image));
                }
                $file_path = $this->ImageResizer($request, 'image', 'uploads/sliders/')??null;
                $slider->image = $file_path;
            }
            $slider->is_active = $request->is_active;
            $slider->type = $request->type;
            $slider->save();
            return redirect()
                ->route('admin.sliders.index')
                ->with('success', 'Data updated successfully!')
                ->withInput();
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Slider::find($id)->delete();
            return back()->with('success', 'Data deleted successfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function changeStatus($id)
    {
        $update = Slider::find($id);
        if ($update->is_active == 1) {
            $update->is_active = 0;
            $update->save();
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $update->is_active = 1;
            $update->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
