<?php

namespace App\Http\Controllers\admin;

use App\DefaultTrait;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use DefaultTrait;

    public function index()
    {
        $blogs = Blog::get();
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'title' => 'required|string|unique:blogs,title',
                'image' => ['required', 'max:1024', 'mimes:jpg,jpeg,png,webp'],
                'description' => 'required',
                'is_active' => 'required|in:0,1',
                'published_at' => 'required|date|after:now',
                'seo_title' => 'nullable|string',
                'seo_description' => 'nullable|string',
            ];
            $this->validate($request, $rules);
            $blog = new Blog;
            $blog->title = $request->title;
            $blog->description = $request->description;
            $blog->seo_title = $request->seo_title;
            $blog->seo_description = $request->seo_description;
            $blog->is_active = $request->is_active;
            $blog->published_at = $request->published_at;
            $blog->slug = str($request->title)->slug();
            if ($request->hasFile('image')) {
                $blog->image = $this->verifyAndUpload($request, 'image', 'uploads/blogs/') ?? null;
            }
            $blog->save();
            return redirect()->route('admin.blogs.index')->with('success', 'Blogs created successfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Blog::find($id);
        return view('admin.blogs.create', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            $rules = [
                'title' => 'required|string|unique:blogs,title,' . $id,
                'image' => ['nullable', 'max:1024', 'mimes:jpg,jpeg,png,webp'],
                'description' => 'required',
                'is_active' => 'required|in:0,1',
                'published_at' => 'required|date|after:now',
                'seo_title' => 'nullable|string',
                'seo_description' => 'nullable|string',
            ];

            $this->validate($request, $rules);
            $blog->title = $request->title;
            $blog->description = $request->description;
            $blog->seo_title = $request->seo_title;
            $blog->seo_description = $request->seo_description;
            $blog->is_active = $request->is_active;
            $blog->published_at = $request->published_at;

            if ($request->hasFile('image')) {
                if ($blog->image && file_exists(public_path($blog->image))) {
                    unlink(public_path($blog->image));
                }
                $blog->image = $this->verifyAndUpload($request, 'image', 'uploads/blogs/') ?? $blog->image;
            }

            $blog->save();

            return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Blog::findOrfail($id)->delete();
            return back()->with('success','Blog deleted successfully!');
        } catch (\Exception $th) {
           return back()->with('error',$th->getMessage());
        }
    }
}
