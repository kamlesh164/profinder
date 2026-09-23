<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\DefaultTrait;
use File;
use View;

class PackageController extends Controller
{
     use DefaultTrait;
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        try{
            $title = "Package Index";
            $packages = Package::orderBy('created_at','desc')->get();
            return view('admin.packages.index', compact('title','packages'));
            
        }catch(\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            $title = "Add New Package";
            return view('admin.packages.create', compact('title'));
        }catch(\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required','max:55','string','unique:packages,name'],
            'price' => ['required'],
            'duration' => ['required'],
            'icon' => ['nullable','max:1024','mimes:jpg,jpeg,png,webp'],
            'status' => ['required','in:1,0'],
        ]);
        try{
            $data = $request->only('name','price','duration','description','icon','status');     
            $data['icon'] = $this->ImageResizer($request, 'icon', 'uploads/packages/',200,200)??null;
            Package::updateOrCreate(
                [
                    'name' => $data['name'],
                ],
                $data
            );
            return back()->with('success', 'Data added successfully');
        }catch(\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        try{
            return view('admin.packages.show', compact('package'));
        }catch(\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        try{
            $title = "Edit Package";
            return view('admin.packages.create', compact('title','package'));
        }catch(\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => ['required','max:55','string','unique:packages,name,'.$package->id],
            'price' => ['required'],
            'duration' => ['required'],
            'icon' => ['nullable','max:1024','mimes:jpg,jpeg,png,webp'],
            'status' => ['required','in:1,0'],
        ]);
           $data = $request->only('name','price','duration','description','icon','status'); 
            
            if($request->hasFile('icon')){
                $iconFile = Package::whereId($package->id)->value('icon');
                File::delete($iconFile);
                $data['icon'] = $this->ImageResizer($request, 'icon', 'uploads/packages/',200,200)??null;
            }else{
                $data['icon'] = Package::whereId($package->id)->value('icon');
            }
            
            Package::whereId($package->id)->update($data);
            return to_route('admin.packages.index')->with('success', 'Data updated successfully');
        try{
        }catch(\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        return back()->with('error', 'can not delete');
    }
}
