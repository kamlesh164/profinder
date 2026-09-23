<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DefaultTrait;
use Illuminate\Support\Facades\File;
use App\Models\{
    Category,
    SubCategory
};

class SubCategoryController extends Controller
{
    use DefaultTrait;
    public function index()
    {
        try {
            $title = "Subcategory Index";
            $subcategories = SubCategory::orderBy('created_at', 'desc')->get();
            return view('admin.subcategories.index', compact('title', 'subcategories'));
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
            $title = "Add New SubCategory";
            $categories = Category::where(['status' => 1])->get();
            return view('admin.subcategories.create', compact('title', 'categories'));
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
            'name' => ['required', 'max:55', 'string', 'unique:subcategories,name'],
            'image' => ['nullable', 'max:1024', 'mimes:jpg,jpeg,png,webp'],
            'banner' => ['nullable', 'max:1024', 'mimes:jpg,jpeg,png,webp'],
            'icon' => ['nullable', 'max:500', 'mimes:jpg,jpeg,png,webp'],
            'status' => ['required', 'in:1,0'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);
        try {
            $data = $request->only('name', 'description', 'image', 'status', 'banner', 'icon', 'category_id');
            $data['image'] = $this->ImageResizer($request, 'image', 'uploads/subcategories/', 600, 700) ?? null;
            $data['icon'] = $this->ImageResizer($request, 'icon', 'uploads/subcategories/icons/', 100, 100) ?? null;
            $data['banner'] = $this->ImageResizer($request, 'banner', 'uploads/subcategories/banners/', 1900, 400) ?? null;
            $data['slug'] = str($data['name'])->slug();
            $subcategory = SubCategory::updateOrCreate(
                [
                    'name' => $data['name'],
                ],
                $data
            );
            return redirect()->route('admin.subcategories.create')->with('success', 'Data added successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subcategory)
    {
        try {
            return view('admin.subcategories.show', compact('subcategory'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subcategory)
    {
        try {
            $title = "Edit SubCategory";
            $categories = Category::where(['status' => 1])->get();
            return view('admin.subcategories.create', compact('title', 'categories', 'subcategory'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $subcategory)
    {
        $request->validate([
            'name' => ['required', 'max:55', 'string', 'unique:subcategories,name,' . $subcategory->id],
            'image' => ['nullable', 'max:1024', 'mimes:jpg,jpeg,png,webp'],
            'banner' => ['nullable', 'max:1024', 'mimes:jpg,jpeg,png,webp'],
            'icon' => ['nullable', 'max:500', 'mimes:jpg,jpeg,png,webp'],
            'status' => ['required', 'in:1,0'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);
        try {
            $data = $request->only('name', 'description', 'image', 'status', 'banner', 'icon', 'category_id');
            if ($request->hasFile('image')) {
                $oldFile = SubCategory::whereId($subcategory->id)->value('image');
                File::delete($oldFile);
                $data['image'] = $this->ImageResizer($request, 'image', 'uploads/subcategories/', 600, 700) ?? null;
            } else {
                $data['image'] = SubCategory::whereId($subcategory->id)->value('image');
            }

            if ($request->hasFile('icon')) {
                $iconFile = SubCategory::whereId($subcategory->id)->value('icon');
                File::delete($iconFile);
                $data['icon'] = $this->ImageResizer($request, 'icon', 'uploads/subcategories/icons/', 100, 100) ?? null;
            } else {
                $data['icon'] = SubCategory::whereId($subcategory->id)->value('icon');
            }

            if ($request->hasFile('banner')) {
                $bannerFile = SubCategory::whereId($subcategory->id)->value('banner');
                File::delete($bannerFile);
                $data['banner'] = $this->ImageResizer($request, 'banner', 'uploads/subcategories/banners/', 1920, 286) ?? null;
            } else {
                $data['banner'] = SubCategory::whereId($subcategory->id)->value('banner');
            }

            $data['slug'] = str($data['name'])->slug();
            SubCategory::whereId($subcategory->id)->update($data);
            return redirect()->route('admin.subcategories.edit', ['subcategory' => $subcategory])->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subcategory)
    {
        return back()->with('error', 'can not delete');
    }
}
