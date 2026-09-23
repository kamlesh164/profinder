<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\DefaultTrait;
use Illuminate\Support\Facades\File;
use View;

class CategoryController extends Controller
{
    use DefaultTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $title = "Category Index";
            $categories = Category::with(['products'])->orderBy('created_at', 'desc')->get();
            return view('admin.categories.index', compact('title', 'categories'));
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
            $title = "Add New Category";
            return view('admin.categories.create', compact('title'));
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
            'name' => ['required', 'max:55', 'string', 'unique:categories,name'],
            'image' => ['nullable', 'max:1024', 'mimes:jpg,jpeg,png,webp'],
            'status' => ['required', 'in:1,0'],
            'commission' => 'required',
        ]);
        try {
            $data = $request->only('name', 'description', 'image', 'status', 'banner', 'icon', 'commission');
            $data['image'] = $this->ImageResizer($request, 'image', 'uploads/categories/', 600, 700) ?? null;
            $data['icon'] = $this->ImageResizer($request, 'icon', 'uploads/categories/icons/', 100, 100) ?? null;
            $data['banner'] = $this->ImageResizer($request, 'banner', 'uploads/categories/banners/', 1900, 400) ?? null;
            $data['slug'] = str($data['name'])->slug();
            Category::updateOrCreate(
                [
                    'name' => $data['name'],
                ],
                $data
            );
            return back()->with('success', 'Data added successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            return view('admin.categories.show', compact('category'));
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
            $title = "Edit Category";
            $data = Category::find($id);
            return view('admin.categories.create', compact('title', 'data'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'max:55', 'string', 'unique:categories,name,' . $category->id],
            'image' => ['nullable', 'max:1024', 'mimes:jpg,jpeg,png,webp'],
            'status' => ['required', 'in:1,0'],
            'commission' => ['required'],
        ]);
        $data = $request->only('name', 'description', 'image', 'status', 'banner', 'icon', 'commission');
        if ($request->hasFile('image')) {
            $oldFile = Category::whereId($category->id)->value('image');
            File::delete($oldFile);
            $data['image'] = $this->ImageResizer($request, 'image', 'uploads/categories/', 600, 700) ?? null;
        } else {
            $data['image'] = Category::whereId($category->id)->value('image');
        }

        if ($request->hasFile('icon')) {
            $iconFile = Category::whereId($category->id)->value('icon');
            File::delete($iconFile);
            $data['icon'] = $this->ImageResizer($request, 'icon', 'uploads/categories/icons/', 100, 100) ?? null;
        } else {
            $data['icon'] = Category::whereId($category->id)->value('icon');
        }

        if ($request->hasFile('banner')) {
            $bannerFile = Category::whereId($category->id)->value('banner');
            File::delete($bannerFile);
            $data['banner'] = $this->ImageResizer($request, 'banner', 'uploads/catalogue/categories/banners/', 1900, 400) ?? null;
        } else {
            $data['banner'] = Category::whereId($category->id)->value('banner');
        }

        $data['slug'] = str($data['name'])->slug();
        Category::whereId($category->id)->update($data);
        return to_route('admin.category.index')->with('success', 'Data updated successfully');
        try {
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Category = Category::findOrFail($id);
        $Category->delete();
        return back()->with('success', 'Deleted successfully!');
    }

    public function changeStatus($id)
    {
        $category = Category::find($id);
        if ($category->status == 1) {
            $category->status = 0;
            $category->save();
            $message = 'Inactive Successfully';
        } else {
            $category->status = 1;
            $category->save();
            $message = 'Active Successfully';
        }
        return response()->json($message);
    }
}
