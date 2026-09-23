<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\InfoCategory;
use Illuminate\Http\Request;

class AdminInfoCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        InfoCategory::create(['name' => $request->name]);

        if ($request->ajax()) {
            $categories = InfoCategory::orderBy('created_at', 'desc')->get();
            $html = view('admin.partials.info-category-list', compact('categories'))->render();

            return response()->json([
                'success' => true,
                'html'    => $html,
            ]);
        }

        return back()->with('success', 'Category added.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        InfoCategory::whereKey($id)->delete();

        if ($request->ajax()) {
            $categories = InfoCategory::orderBy('created_at', 'desc')->get();
            $html = view('admin.partials.info-category-list', compact('categories'))->render();

            return response()->json([
                'success' => true,
                'html'    => $html,
            ]);
        }

        return back()->with('success', 'Category deleted.');
    }
    public function checkName(Request $request)
    {
        $name = $request->get('name', '');

        $exists = InfoCategory::whereRaw('LOWER(name) = ?', [strtolower($name)])->exists();

        return response()->json([
            'exists' => $exists,
        ]);
    }
}
