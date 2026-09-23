<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ApiServiceController extends BaseApiController
{
    public function getServicesByCategory(Request $request){
        try {
            $request->validate([
                'categoryId' => 'required|string',
                'city' => 'required|string'
            ]);

            $services = Service::getServicesByCategoryIdAndCityName($request->categoryId, $request->city);
            return $this->successResponse($services);
                
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return $this->errorResponse('Validation failed');
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }
    public function serviceDetailsById(Request $request){
        try {
            $request->validate([
                'serviceId' => 'required|string'
            ]);
            $service = Service::getServiceDetailsById($request->serviceId);
            return $this->successResponse($service);
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return $this->errorResponse('Validation failed');
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }
    public function saveItem(Request $request)
    {
        try {
            $request->validate([
                'type' => ['required', 'in:Service,Product'],
            ]);

            if ($request->type === 'Service') {
                return $this->saveService($request);
            }

            if ($request->type === 'Product') {
                return $this->saveProduct($request);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid type',
            ], 422);

        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $ex->errors(),
            ], 422);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }
    private function saveService(Request $request)
    {
        $request->validate([
            'serviceId'       => ['nullable', 'string', 'exists:services,id'],
            'userId'          => ['required', 'string', 'exists:users,id'],
            'name'            => ['required', 'string', 'max:255'],
            'coverImage'      => ['nullable', 'string', 'max:255'],
            'city'            => ['required', 'string', 'exists:cities,id'],
            'price'           => ['required', 'numeric', 'min:0'],
            'category'        => ['nullable', 'string', 'exists:service_categories,id'],
            'description'     => ['nullable', 'string'],
            'workingDays'     => ['required', 'array', 'min:1'],
            'workingDays.*'   => ['integer', 'between:0,6'],
            'startTime'       => ['required', 'date_format:h:i a'],
            'endTime'         => ['required', 'date_format:h:i a'],
            'isPropertyBased'   => ['required', 'boolean'],
            'apartmentPrices'   => ['nullable', 'array'],
            'apartmentPrices.*' => ['numeric', 'min:0'],
            'villaPrices'       => ['nullable', 'array'],
            'villaPrices.*'     => ['numeric', 'min:0'],
            'includes'        => ['nullable', 'array'],
            'includes.*'      => ['string', 'max:255'],
            'excludes'        => ['nullable', 'array'],
            'excludes.*'      => ['string', 'max:255'],
        ]);

        $service = $request->filled('serviceId')
            ? Service::find($request->serviceId)
            : new Service();

        $service->sp_id          = $request->userId;
        $service->name           = $request->name;
        if ($request->filled('coverImage')) {
            $service->image = $request->coverImage;
        }
        $service->city_id        = $request->city;
        $service->base_price     = $request->price;
        $service->service_category_id    = $request->category;
        $service->description    = $request->description;
        $service->working_days   = json_encode($request->workingDays);
        $service->start_time = \Carbon\Carbon::createFromFormat('h:i a', $request->startTime)
            ->format('H:i:s');
        $service->end_time = \Carbon\Carbon::createFromFormat('h:i a', $request->endTime)
            ->format('H:i:s');
        $service->is_property_based = $request->isPropertyBased ? 1 : 0;

        if ($request->isPropertyBased) {
            $service->apartment_prices = $request->apartmentPrices
                ? json_encode($request->apartmentPrices)
                : null;
            $service->villa_prices = $request->villaPrices
                ? json_encode($request->villaPrices)
                : null;
        } else {
            $service->apartment_prices = null;
            $service->villa_prices     = null;
        }
        $service->includes = $request->includes ? json_encode($request->includes) : null;
        $service->excludes = $request->excludes ? json_encode($request->excludes) : null;
        $service->save();
        $service->image_url = $service->image ? asset($service->image) : null;


        return response()->json([
            'success' => true,
            'message' => $request->filled('serviceId')
                ? 'Service updated successfully'
                : 'Service created successfully',
            'data' => $service,
        ], 200);
    }
    public function deleteService($id)
    {
        try {
            $service = Service::findOrFail($id);

            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'Service deleted successfully',
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    private function saveProduct(Request $request)
    {
        $request->validate([
            'productId'      => ['nullable', 'string', 'exists:products,id'],
            'userId'         => ['required', 'string', 'exists:users,id'],
            'name'           => ['required', 'string', 'max:255'],
            'coverImage'     => ['nullable', 'string', 'max:255'],
            'city'           => ['required', 'string', 'exists:cities,id'],
            'price'          => ['required', 'numeric', 'min:0'],
            'category'       => ['nullable', 'string', 'exists:categories,id'],
            'description'    => ['nullable', 'string'],
            'qty'            => ['nullable', 'string'],
            'specs'          => ['nullable', 'array'],
            'specs.*'        => ['string', 'max:255']
        ]);

        $product = $request->filled('productId')
            ? Product::find($request->productId)
            : new Product();

        $product->sp_id       = $request->userId;
        $product->name        = $request->name;
        if ($request->filled('coverImage')) {
            $product->image = $request->coverImage;
        }
        $product->city_id     = $request->city;
        $product->price       = $request->price;
        $product->category_id = $request->category;
        $product->description = $request->description;
        $product->qty         = $request->qty;
        $product->specs       = $request->specs ? json_encode($request->specs) : null;
        $product->save();
        $product->image_url = $product->image ? asset($product->image) : null;

        return response()->json([
            'success' => true,
            'message' => $request->filled('productId')
                ? 'Product updated successfully'
                : 'Product created successfully',
            'data' => $product,
        ], 200);
    }
    public function getSpServicesByCity(Request $request)
    {
        $request->validate([
            'userId' => ['required', 'string', 'exists:users,id'],
            'cityId' => ['required', 'string', 'exists:cities,id'],
        ]);

        $services = Service::getAllServicesOfSpByParam($request->all());
        
        return response()->json([
            'success' => true,
            'data'    => $services,
        ], 200);
    }
    public function getAllServices(Request $request)
    {
        $services = Service::getAllServicesOfSpByParam($request->all());
        
        return response()->json([
            'success' => true,
            'data'    => $services,
        ], 200);
    }

}
