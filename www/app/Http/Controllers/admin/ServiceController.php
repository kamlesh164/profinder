<?php

namespace App\Http\Controllers\admin;

use App\DefaultTrait;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceProfessional;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as FacadesFile;

class ServiceController extends Controller
{
    use DefaultTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $services = Service::orderBy('created_at', 'desc')->get();

            return view('admin.services.index', compact('services'));
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
            $categories = ServiceCategory::where('is_active',1)->get();
            $states = State::orderBy('name','asc')->get();
            $servicePro = User::getServiceProfessionalUsers();
            return view('admin.services.create',compact('categories','states','servicePro'));
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'service_category_id' => ['required', 'integer', 'exists:service_categories,id'],
            'name' => ['required', 'max:55', 'string', 'unique:services,name'],
            'image' => ['nullable', 'max:1024', 'mimes:jpg,jpeg,png,webp'],
            'is_active' => ['required', 'in:1,0'],
            'base_price' => 'required',
        ]);
        try {
            $data = $request->all();
            $data['image'] = $this->ImageResizer($request, 'image', 'uploads/services/', 600, 700) ?? null;
            $data['slug'] = str($data['name'])->slug();
            Service::updateOrCreate(
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
    public function show(Service $service)
    {
        try {
            return view('admin.services.show', compact('service'));
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
            $categories = ServiceCategory::where('is_active',1)->get();
            $data = Service::find($id);
            $states = State::orderBy('name','asc')->get();
            $servicePro = User::getServiceProfessionalUsers();
            return view('admin.services.create', compact('data','categories','states','servicePro'));
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
            $service = Service::find($id);
            $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'service_category_id' => ['required', 'integer', 'exists:service_categories,id'],
                'name' => ['required', 'max:55', 'string'],
                'image' => ['nullable', 'mimes:jpg,jpeg,png,webp'],
                'is_active' => ['required', 'in:1,0'],
                'base_price' => ['required'],
            ]);
            // $data = $request->only('service_category_id', 'name', 'description', 'image', 'is_active', 'base_price');
            $data = $request->all();
            if ($request->hasFile('image')) {
                if (FacadesFile::exists(public_path($service->image))) {
                    unlink(public_path($service->image));
                }
                $data['image'] = $this->ImageResizer($request, 'image', 'uploads/services/', 600, 700) ?? null;
                $service->image = $data['image'];
            }

            $data['slug'] = str($data['name'])->slug();
            $service->update($data);
            /* $service->service_category_id = $data['service_category_id'];
            $service->name = $data['name'];
            $service->slug = $data['slug'];
            $service->base_price = $data['base_price'];
            $service->description = $data['description'];
            $service->is_active = $data['is_active'];
            $service->save(); */
            return to_route('admin.service.index')->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        return back()->with('error', 'can not delete');
    }

    public function changeStatus($id)
    {
        $category = Service::find($id);
        $status = $category->is_active == 1 ? 0 : 1;
        $message = $status == 1 ? 'Active Successfully' : 'Inactive Successfully';
        $category->save();
        return response()->json($message);
    }
}
