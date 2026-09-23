<?php

namespace App\Http\Controllers\admin;

use App\DefaultTrait;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as FacadesFile;

class ServiceCategoryController extends Controller
{
    use DefaultTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = ServiceCategory::with(['service'])->orderBy('created_at', 'desc')->get();
            
            return view('admin.servicecategories.index', compact('categories'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin.servicecategories.create');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:55', 'string', 'unique:service_categories,name'],
            'image' => ['nullable', 'max:1024', 'mimes:jpg,jpeg,png,webp'],
            'is_active' => ['required', 'in:1,0'],
            'commission' => 'required',
        ]);
        try {
            $data = $request->only('name', 'description', 'image', 'is_active', 'commission');
            $data['image'] = $this->ImageResizer($request, 'image', 'uploads/servicecategories/', 600, 700) ?? null;
            $data['slug'] = str($data['name'])->slug();
            ServiceCategory::updateOrCreate(
                [
                    'name' => $data['name'],
                ],
                $data
            );
            return redirect()->route('admin.servicecategory.index')->with('success', 'Data added successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceCategory $category)
    {
        try {
            return view('admin.servicecategories.show', compact('category'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $data = ServiceCategory::find($id);
            return view('admin.servicecategories.create', compact('data'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $category = ServiceCategory::find($id);
            $request->validate([
                'name' => ['required', 'max:55'],
                'image' => ['nullable', 'mimes:jpg,jpeg,png,webp'],
                'is_active' => ['required', 'in:1,0'],
                'commission' => ['required'],
            ]);
            $data = $request->only('name', 'description', 'image', 'is_active', 'commission');
            if ($request->hasFile('image')) {

                if (FacadesFile::exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }
                $data['image'] = $this->ImageResizer($request, 'image', 'uploads/categories/', 600, 700) ?? null;
                $category->image = $data['image'];
            }

            $data['slug'] = str($data['name'])->slug();
            $category->name = $data['name'];
            $category->slug = $data['slug'];
            $category->description = $data['description'];
            $category->is_active = $data['is_active'];
            $category->commission = $data['commission'];
            $category->save();
            return to_route('admin.servicecategory.index')->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceCategory $category)
    {
        return back()->with('error', 'can not delete');
    }

    public function changeStatus($id)
    {
        $category = ServiceCategory::find($id);
        $status = $category->is_active == 1 ? 0 : 1;
        $message = $status == 1 ? 'Active Successfully' : 'Inactive Successfully';
        $category->is_active = $status;
        $category->save();
        if(Service::where('service_category_id',$category->id)->exists()){
            Service::where('service_category_id',$category->id)->update(['is_active' => $status]);
        }
        return response()->json($message);
    }
    
}
