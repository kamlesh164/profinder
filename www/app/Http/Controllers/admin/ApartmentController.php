<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BhkApartment;
use App\Models\SquareFoot;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\BackupGlobals;

class ApartmentController extends Controller
{
    public function apartments()
    {
        $bhks = BhkApartment::get();
        $squarefeets = SquareFoot::get();
        return view('admin.apartments.index', compact('bhks', 'squarefeets'));
    }
    public function apartmentStore(Request $request)
    {
        try {
            if ($request->form_type == 1) {
                $rules = [
                    'bhk' => 'required|numeric|exists:bhk_apartments,bhk',
                ];
                $this->validate($request, $rules);
                BhkApartment::create([
                    'title' => $request->bhk . ' BHK',
                    'bhk' => $request->bhk,
                    'price_multiply' => $request->price_multiply,
                ]);
            }
            if ($request->form_type == 2) {
                $rules = [
                    'from_area' => 'required|numeric|exists:square_feet,from_area',
                    'to_area' => 'required|numeric|exists:square_feet,to_area',
                ];
                $this->validate($request, $rules);
                SquareFoot::create([
                    'title' => $request->from_area . '-' . $request->to_area . ' sq.ft',
                    'from_area' => $request->from_area,
                    'to_area' => $request->to_area,
                    'price_multiply' => $request->price_multiply,
                ]);
            }
            return back()->with('success', 'Data store succcessfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function apartmentUpdate(Request $request)
    {
        try {
            if ($request->form_type == 1) {
                // $rules = [
                //     'bhk' => 'required|numeric|exists:bhk_apartments,bhk,'.$request->id,
                // ];
                // $this->validate($request, $rules);
                BhkApartment::where('id',$request->id)->update([
                    'title' => $request->bhk . ' BHK',
                    'bhk' => $request->bhk,
                    'price_multiply' => $request->price_multiply,
                ]);
            }
            if ($request->form_type == 2) {
                // $rules = [
                //     'from_area' => 'required|numeric|exists:square_feet,from_area,'.$request->id,
                //     'to_area' => 'required|numeric|exists:square_feet,to_area,'.$request->id,
                // ];
                // $this->validate($request, $rules);
                SquareFoot::where('id',$request->id)->update([
                    'title' => $request->from_area . '-' . $request->to_area . ' sq.ft',
                    'from_area' => $request->from_area,
                    'to_area' => $request->to_area,
                    'price_multiply' => $request->price_multiply,
                ]);
            }
            return back()->with('success', 'Data updated succcessfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function apartmentDelete(Request $request)
    {
        try {
            if ($request->form_type == 1) {
                BhkApartment::where('id',$request->id)->delete();
            }
            if ($request->form_type == 2) {
                SquareFoot::where('id',$request->id)->delete();
            }
            return back()->with('success', 'Data deleted succcessfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
